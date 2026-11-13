@extends('superadmin.layouts.app')

@section('styles')
<style>
   .dr-tabs { display: flex; flex-wrap: wrap; gap: 8px; margin-bottom: 1rem; }
   .dr-tabs a { padding: 6px 16px; border-radius: 999px; font-size: 13px; font-weight: 600; border: 1px solid #e2e8f0; color: #475569; background: #fff; text-decoration: none; }
   .dr-tabs a.active { background: #4A6BF0; border-color: #4A6BF0; color: #fff; }
   .dr-tabs small { opacity: .8; margin-left: 4px; }
   .dr-card { border-radius: 16px; border: 1px solid #e2e8f0; background: #fff; padding: 1.25rem; box-shadow: 0 4px 15px rgba(0, 0, 0, .03); }
   .dr-msg { max-width: 260px; white-space: normal; font-size: 13px; color: #475569; }
   .dr-status { min-width: 118px; font-size: 13px; font-weight: 600; border-radius: 999px; }
   .dr-toast { position: fixed; right: 20px; top: 80px; z-index: 2000; min-width: 240px; padding: 12px 16px; border-radius: 12px; color: #fff; font-size: 14px; box-shadow: 0 10px 30px rgba(0, 0, 0, .2); display: none; }
   .dr-toast.ok { background: #16a34a; } .dr-toast.bad { background: #dc2626; }
</style>
@endsection

@section('content')
<div class="pagetitle d-flex justify-content-between align-items-center mb-3 flex-wrap gap-2">
   <div>
      <h1 class="mb-0">Demo Requests</h1>
      <div class="text-muted small mt-1">People who used “Book a demo” on the website.</div>
   </div>
</div>

<section class="section">
   <div class="dr-tabs">
      <a href="{{ route('superadmin.demo-requests.index') }}" class="{{ !$status ? 'active' : '' }}">All <small>{{ array_sum($counts) }}</small></a>
      @foreach (\App\Models\DemoRequest::STATUSES as $key => $label)
         <a href="{{ route('superadmin.demo-requests.index', ['status' => $key]) }}" class="{{ $status === $key ? 'active' : '' }}">{{ $label }} <small>{{ $counts[$key] ?? 0 }}</small></a>
      @endforeach
   </div>

   <div class="dr-card">
      @if ($requests->isEmpty())
         <p class="text-muted mb-0 py-4 text-center"><i class="bi bi-inbox fs-3 d-block mb-2"></i>No demo requests yet.</p>
      @else
         <div class="table-responsive">
            <table class="table table-bordered align-middle mb-0" id="demoTable" width="100%">
               <thead class="table-light">
                  <tr>
                     <th>#</th>
                     <th>Received</th>
                     <th>Name / Company</th>
                     <th>Email</th>
                     <th>Phone</th>
                     <th>Preferred Date &amp; Slot</th>
                     <th>Message</th>
                     <th>Status</th>
                     <th class="text-center">Action</th>
                  </tr>
               </thead>
               <tbody>
                  @foreach ($requests as $r)
                     <tr data-id="{{ $r->id }}" data-name="{{ $r->name }}">
                        <td>{{ $loop->iteration }}</td>
                        <td class="text-nowrap" data-order="{{ $r->created_at->timestamp }}">{{ $r->created_at->format('d M Y') }}<br><small class="text-muted">{{ $r->created_at->format('h:i A') }}</small></td>
                        <td><b>{{ $r->name }}</b><br><small class="text-muted">{{ $r->company }}</small></td>
                        <td><a href="mailto:{{ $r->email }}">{{ $r->email }}</a></td>
                        <td class="text-nowrap"><a href="tel:{{ preg_replace('/[^0-9+]/', '', $r->phone) }}">{{ $r->phone }}</a></td>
                        <td class="text-nowrap">{{ $r->preferred_date->format('d M Y') }}<br><small class="text-muted">{{ $r->preferred_slot }}</small></td>
                        <td><div class="dr-msg">{{ $r->message ?: '-' }}</div></td>
                        <td>
                           <select class="form-select form-select-sm dr-status js-status">
                              @foreach (\App\Models\DemoRequest::STATUSES as $key => $label)
                                 <option value="{{ $key }}" @selected($r->status === $key)>{{ $label }}</option>
                              @endforeach
                           </select>
                        </td>
                        <td class="text-center">
                           <button type="button" class="btn btn-outline-danger btn-sm js-del" title="Delete"><i class="bi bi-trash"></i></button>
                        </td>
                     </tr>
                  @endforeach
               </tbody>
            </table>
         </div>
      @endif
   </div>
</section>

<div class="dr-toast" id="drToast"></div>
@endsection

@section('scripts')
<script>
   (function () {
      const CSRF = document.querySelector('meta[name="csrf-token"]').content;
      const URL_STATUS  = "{{ route('superadmin.demo-requests.status', '__ID__') }}";
      const URL_DESTROY = "{{ route('superadmin.demo-requests.destroy', '__ID__') }}";

      function toast(msg, ok = true) {
         const t = document.getElementById('drToast');
         t.textContent = msg; t.className = 'dr-toast ' + (ok ? 'ok' : 'bad'); t.style.display = 'block';
         clearTimeout(t._h); t._h = setTimeout(() => t.style.display = 'none', 3000);
      }

      async function call(url, method, body) {
         const res = await fetch(url, {
            method,
            headers: { 'X-CSRF-TOKEN': CSRF, 'Accept': 'application/json', 'Content-Type': 'application/json' },
            body: body ? JSON.stringify(body) : undefined
         });
         let data = {}; try { data = await res.json(); } catch (e) {}
         return { ok: res.ok, data };
      }

      if (window.jQuery && document.getElementById('demoTable')) {
         $('#demoTable').DataTable({ order: [[1, 'desc']], pageLength: 25, columnDefs: [{ orderable: false, targets: [6, 8] }] });
      }

      document.addEventListener('change', async function (e) {
         const sel = e.target.closest('.js-status'); if (!sel) return;
         const id = sel.closest('tr').dataset.id;
         const r = await call(URL_STATUS.replace('__ID__', id), 'POST', { status: sel.value });
         toast(r.ok ? r.data.message : 'Could not update status.', r.ok);
      });

      document.addEventListener('click', function (e) {
         const btn = e.target.closest('.js-del'); if (!btn) return;
         const tr = btn.closest('tr');
         nmsConfirm('Are you sure you want to delete the demo request from "' + tr.dataset.name + '"?', async function () {
            const r = await call(URL_DESTROY.replace('__ID__', tr.dataset.id), 'DELETE');
            if (r.ok) { toast(r.data.message); setTimeout(() => location.reload(), 600); }
            else toast('Could not delete.', false);
         });
      });
   })();
</script>
@endsection