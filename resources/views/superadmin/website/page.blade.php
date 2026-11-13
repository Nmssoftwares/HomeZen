@extends('superadmin.layouts.app')

@section('styles')
<style>
    .wb-card {
        border-radius: 16px;
        border: 1px solid #e2e8f0;
        background: #fff;
        padding: 1.5rem;
        box-shadow: 0 4px 15px rgba(0, 0, 0, .03);
        margin-bottom: 1.5rem;
    }

    .wb-card-head {
        display: flex;
        align-items: flex-start;
        justify-content: space-between;
        gap: 12px;
        margin-bottom: 1rem;
    }

    .wb-card-head h5 {
        margin: 0;
        font-weight: 700;
    }

    .wb-card-head p {
        margin: 2px 0 0;
        font-size: 13px;
        color: #64748b;
    }

    .wb-row {
        display: flex;
        align-items: center;
        gap: 12px;
        padding: 10px 14px;
        border: 1px solid #e2e8f0;
        border-radius: 12px;
        background: #fff;
        margin-bottom: 8px;
        transition: opacity .2s;
    }

    .wb-row.is-off {
        opacity: .55;
        background: #f8fafc;
    }

    .wb-ico {
        width: 38px;
        height: 38px;
        border-radius: 10px;
        display: grid;
        place-items: center;
        flex: 0 0 auto;
        background: #eef2ff;
        color: #4A6BF0;
        font-size: 18px;
    }

    .wb-main {
        flex: 1 1 auto;
        min-width: 0;
    }

    .wb-main b {
        display: block;
        font-size: 14px;
        color: #0f172a;
    }

    .wb-main span {
        display: block;
        font-size: 12.5px;
        color: #64748b;
        white-space: nowrap;
        overflow: hidden;
        text-overflow: ellipsis;
    }

    .wb-order {
        font-size: 11px;
        background: #f1f5f9;
        color: #475569;
        border-radius: 999px;
        padding: 2px 9px;
        flex: 0 0 auto;
    }

    .wb-img-box {
        width: 220px;
        height: 120px;
        border: 2px dashed #cbd5e1;
        border-radius: 12px;
        overflow: hidden;
        background: #f8fafc;
        display: flex;
        align-items: center;
        justify-content: center;
    }

    .wb-img-box img {
        width: 100%;
        height: 100%;
        object-fit: cover;
    }

    .wb-toast {
        position: fixed;
        right: 20px;
        top: 80px;
        z-index: 2000;
        min-width: 240px;
        max-width: 360px;
        padding: 12px 16px;
        border-radius: 12px;
        color: #fff;
        font-size: 14px;
        box-shadow: 0 10px 30px rgba(0, 0, 0, .2);
        display: none;
    }

    .wb-toast.ok {
        background: #16a34a;
    }

    .wb-toast.bad {
        background: #dc2626;
    }
</style>
@endsection

@section('content')
@php
$required = ['site_title', 'hero_title_1', 'hero_title_highlight', 'hero_btn_primary', 'demo_email'];
$sectionMeta = collect($sections)->map(fn ($s) => $s['meta'])->all();
$iconList = ['bi-people','bi-person','bi-person-plus','bi-person-check','bi-person-badge','bi-wallet2','bi-box-seam','bi-door-open','bi-headset','bi-shield-lock','bi-building','bi-graph-up-arrow','bi-check-circle','bi-calendar2-check','bi-gear','bi-globe2','bi-envelope','bi-file-earmark-text','bi-cash-stack','bi-clipboard-check','bi-bar-chart','bi-chat-dots','bi-lightning','bi-lock','bi-star','bi-truck','bi-cart','bi-qr-code','bi-bell','bi-camera'];
@endphp

<div class="pagetitle mb-4 d-flex justify-content-between align-items-start flex-wrap gap-2">
    <div>
        <h1 class="fw-bold mb-1"><i class="bi {{ $def['icon'] }} me-2"></i>{{ $def['title'] }}</h1>
        <p class="text-muted small mb-0">{{ $def['help'] }}</p>
    </div>
    <a href="{{ route('website') }}" target="_blank" class="btn btn-outline-primary btn-sm"><i class="bi bi-box-arrow-up-right me-1"></i> View website</a>
</div>

<div id="wbToast" class="wb-toast"></div>

<section class="section">

    {{-- ============ TEXT FIELDS ============ --}}
    <div class="wb-card">
        <div class="wb-card-head">
            <div>
                <h5>Text and settings</h5>
                <p>Change the headings, paragraphs and buttons of this page, then press Save text.</p>
            </div>
        </div>

        <form id="settingsForm" action="{{ route('superadmin.website.settings', $page) }}" enctype="multipart/form-data" novalidate>
            <div class="row g-3">
                @foreach ($def['fields'] as $key => $f)
                @php [$label, $type] = $f; $val = $values[$key] ?? ''; @endphp

                @if ($type === 'image')
                <div class="col-12">
                    <label class="form-label fw-semibold fs-13">{{ $label }}</label>
                    <div class="d-flex align-items-center gap-3 flex-wrap">
                        <div class="wb-img-box"><img id="img_{{ $key }}" src="{{ \App\Support\WebsiteCms::heroImage($values) }}" alt=""></div>
                        <div>
                            <input type="file" class="form-control form-control-sm" id="file_{{ $key }}" name="{{ $key }}" accept="image/*">
                            <input type="hidden" name="remove_{{ $key }}" id="remove_{{ $key }}" value="0">
                            <small class="text-muted d-block mt-1">JPG, PNG or WEBP, up to 4 MB.</small>
                            <button type="button" class="btn btn-outline-danger btn-sm mt-2" id="btnRemove_{{ $key }}"><i class="bi bi-trash me-1"></i>Use original image</button>
                            <small class="text-danger d-block" data-err="{{ $key }}"></small>
                        </div>
                    </div>
                </div>
                @elseif ($type === 'textarea')
                <div class="col-12">
                    <label class="form-label fw-semibold fs-13">{{ $label }} @if(in_array($key, $required))<span class="text-danger">*</span>@endif</label>
                    <textarea class="form-control" name="{{ $key }}" rows="3" maxlength="2000">{{ $val }}</textarea>
                    <small class="text-danger" data-err="{{ $key }}"></small>
                </div>
                @else
                <div class="col-md-6">
                    <label class="form-label fw-semibold fs-13">{{ $label }} @if(in_array($key, $required))<span class="text-danger">*</span>@endif</label>
                    @if ($type === 'number')
                    <input type="number" class="form-control" name="{{ $key }}" value="{{ $val }}" min="0" max="30">
                    @elseif ($type === 'email')
                    <input type="email" class="form-control" name="{{ $key }}" value="{{ $val }}" maxlength="150">
                    @else
                    <input type="text" class="form-control" name="{{ $key }}" value="{{ $val }}" maxlength="255">
                    @endif
                    <small class="text-danger" data-err="{{ $key }}"></small>
                </div>
                @endif
                @endforeach
            </div>

            <div class="mt-4">
                <button type="submit" class="btn btn-primary" id="btnSaveText"><i class="bi bi-check2-circle me-1"></i> Save text</button>
            </div>
        </form>
    </div>

    {{-- ============ REPEATING SECTIONS ============ --}}
    @foreach ($sections as $name => $s)
    @php $meta = $s['meta']; $rows = $s['rows']; @endphp
    <div class="wb-card">
        <div class="wb-card-head">
            <div>
                <h5>{{ $meta['title'] }}</h5>
                <p>{{ $meta['help'] }} Lower order number shows first.</p>
            </div>
            <button type="button" class="btn btn-primary btn-sm js-add" data-section="{{ $name }}"><i class="bi bi-plus-lg me-1"></i> Add</button>
        </div>

        @forelse ($rows as $row)
        @php
        $payload = [
        'id' => $row->id,
        'title' => $row->title,
        'description' => (string) $row->description,
        'icon' => (string) $row->icon,
        'sort_order' => $row->sort_order,
        'is_active' => (bool) $row->is_active,
        ];
        @endphp
        <div class="wb-row {{ $row->is_active ? '' : 'is-off' }}" data-item="{{ json_encode($payload) }}" data-section="{{ $name }}">
            @if ($meta['icon'])
            <div class="wb-ico"><i class="bi {{ $row->icon ?: 'bi-circle' }}"></i></div>
            @endif
            <div class="wb-main">
                <b>{{ $row->title }}</b>
                @if ($meta['desc'] && $row->description)
                <span title="{{ $row->description }}">{{ $row->description }}</span>
                @endif
            </div>
            <span class="wb-order">#{{ $row->sort_order }}</span>
            <div class="form-check form-switch m-0" title="Show on website">
                <input class="form-check-input js-toggle" type="checkbox" {{ $row->is_active ? 'checked' : '' }}>
            </div>
            <button type="button" class="btn btn-outline-primary btn-sm js-edit" title="Edit"><i class="bi bi-pencil"></i></button>
            <button type="button" class="btn btn-outline-danger btn-sm js-del" title="Delete"><i class="bi bi-trash"></i></button>
        </div>
        @empty
        <p class="text-muted small mb-0">Nothing here yet. Press Add to create the first one.</p>
        @endforelse
    </div>
    @endforeach
</section>

{{-- ============ ADD / EDIT MODAL ============ --}}
<div class="modal fade" id="itemModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content" style="border-radius:16px;">
            <div class="modal-header">
                <h5 class="modal-title" id="itemModalTitle">Edit</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <div class="alert alert-danger py-2 small d-none" id="itemErr"></div>

                <div class="mb-3">
                    <label class="form-label fw-semibold fs-13" id="lblTitle">Title</label>
                    <input type="text" class="form-control" id="fTitle" maxlength="255">
                </div>
                <div class="mb-3" id="wrapDesc">
                    <label class="form-label fw-semibold fs-13" id="lblDesc">Description</label>
                    <textarea class="form-control" id="fDesc" rows="3" maxlength="2000"></textarea>
                </div>
                <div class="mb-3" id="wrapIcon">
                    <label class="form-label fw-semibold fs-13">Icon</label>
                    <div class="d-flex align-items-center gap-2">
                        <div class="wb-ico"><i class="bi bi-circle" id="iconPreview"></i></div>
                        <input type="text" class="form-control" id="fIcon" list="iconList" maxlength="60" placeholder="e.g. bi-people">
                    </div>
                    <datalist id="iconList">
                        @foreach ($iconList as $ic)<option value="{{ $ic }}">@endforeach
                    </datalist>
                    <small class="text-muted">Type any Bootstrap icon name. Browse them at
                        <a href="https://icons.getbootstrap.com" target="_blank" rel="noopener">icons.getbootstrap.com</a>.</small>
                </div>
                <div class="row g-3">
                    <div class="col-6">
                        <label class="form-label fw-semibold fs-13">Order</label>
                        <input type="number" class="form-control" id="fOrder" min="0" max="9999" placeholder="auto">
                    </div>
                    <div class="col-6 d-flex align-items-end">
                        <div class="form-check form-switch mb-2">
                            <input class="form-check-input" type="checkbox" id="fActive" checked>
                            <label class="form-check-label" for="fActive">Show on website</label>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-light" data-bs-dismiss="modal">Cancel</button>
                <button type="button" class="btn btn-primary" id="btnItemSave">Save</button>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
    (function() {
        const CSRF = document.querySelector('meta[name="csrf-token"]').content;
        const SECTIONS = @json($sectionMeta);
        const URLS = {
            store: @json(route('superadmin.website.item.store', '__S__')),
            update: @json(route('superadmin.website.item.update', '__ID__')),
            toggle: @json(route('superadmin.website.item.toggle', '__ID__')),
            destroy: @json(route('superadmin.website.item.destroy', '__ID__')),
        };
        const ORIGINAL_IMG = @json(asset('assets/img/login-bg.jpg'));

        /* ---------- helpers ---------- */
        const $ = (id) => document.getElementById(id);

        function toast(msg, ok) {
            const t = $('wbToast');
            t.textContent = msg;
            t.className = 'wb-toast ' + (ok === false ? 'bad' : 'ok');
            t.style.display = 'block';
            clearTimeout(t._h);
            t._h = setTimeout(() => {
                t.style.display = 'none';
            }, 3200);
        }

        async function api(url, method, body) {
            const opt = {
                method: method,
                headers: {
                    'X-CSRF-TOKEN': CSRF,
                    'Accept': 'application/json',
                    'X-Requested-With': 'XMLHttpRequest'
                }
            };
            if (body instanceof FormData) {
                opt.body = body;
            } else if (body) {
                opt.headers['Content-Type'] = 'application/json';
                opt.body = JSON.stringify(body);
            }
            const res = await fetch(url, opt);
            let data = {};
            try {
                data = await res.json();
            } catch (e) {}
            return {
                ok: res.ok,
                status: res.status,
                data: data
            };
        }

        function firstError(data) {
            if (data && data.errors) {
                return Object.values(data.errors).map((a) => a[0]).join(' ');
            }
            return (data && data.message) ? data.message : 'Something went wrong. Please try again.';
        }

        function reloadSoon() {
            sessionStorage.setItem('wbScroll', window.scrollY);
            setTimeout(() => location.reload(), 450);
        }
        const savedY = sessionStorage.getItem('wbScroll');
        if (savedY) {
            window.scrollTo(0, +savedY);
            sessionStorage.removeItem('wbScroll');
        }

        /* ---------- text settings form ---------- */
        const form = $('settingsForm');
        form.addEventListener('submit', async function(e) {
            e.preventDefault();
            form.querySelectorAll('[data-err]').forEach((el) => {
                el.textContent = '';
            });
            const btn = $('btnSaveText');
            btn.disabled = true;

            const r = await api(form.action, 'POST', new FormData(form));
            btn.disabled = false;

            if (r.ok) {
                toast(r.data.message || 'Saved.');
                if (r.data.image_url) {
                    document.querySelectorAll('[id^="img_"]').forEach((img) => {
                        img.src = r.data.image_url + '?t=' + Date.now();
                    });
                }
                form.querySelectorAll('input[type=file]').forEach((f) => {
                    f.value = '';
                });
                form.querySelectorAll('input[id^="remove_"]').forEach((h) => {
                    h.value = '0';
                });
            } else if (r.status === 422 && r.data.errors) {
                Object.keys(r.data.errors).forEach((k) => {
                    const el = form.querySelector('[data-err="' + k + '"]');
                    if (el) el.textContent = r.data.errors[k][0];
                });
                toast('Please fix the highlighted fields.', false);
            } else {
                toast(firstError(r.data), false);
            }
        });

        // image preview + remove
        form.querySelectorAll('input[type=file]').forEach((inp) => {
            inp.addEventListener('change', function() {
                const key = inp.name;
                if (inp.files && inp.files[0]) {
                    $('img_' + key).src = URL.createObjectURL(inp.files[0]);
                    $('remove_' + key).value = '0';
                }
            });
        });
        form.querySelectorAll('[id^="btnRemove_"]').forEach((b) => {
            b.addEventListener('click', function() {
                const key = b.id.replace('btnRemove_', '');
                $('file_' + key).value = '';
                $('remove_' + key).value = '1';
                $('img_' + key).src = ORIGINAL_IMG;
                toast('Press Save text to apply.');
            });
        });

        /* ---------- add / edit modal ---------- */
        const modal = new bootstrap.Modal($('itemModal'));
        let cur = {
            section: null,
            id: null
        };

        function iconClass(v) {
            v = (v || '').trim().replace(/^bi\s+/, '');
            if (v && !v.startsWith('bi-')) v = 'bi-' + v;
            return v || 'bi-circle';
        }
        $('fIcon').addEventListener('input', function() {
            $('iconPreview').className = 'bi ' + iconClass(this.value);
        });

        function openModal(section, item) {
            const m = SECTIONS[section];
            cur = {
                section: section,
                id: item ? item.id : null
            };
            $('itemModalTitle').textContent = (item ? 'Edit: ' : 'Add to: ') + m.title;
            $('lblTitle').textContent = m.title_label || 'Title';
            $('lblDesc').textContent = m.desc_label || 'Description';
            $('wrapDesc').style.display = m.desc ? '' : 'none';
            $('wrapIcon').style.display = m.icon ? '' : 'none';
            $('fTitle').value = item ? item.title : '';
            $('fDesc').value = item ? item.description : '';
            $('fIcon').value = item ? item.icon : '';
            $('fOrder').value = item ? item.sort_order : '';
            $('fActive').checked = item ? item.is_active : true;
            $('iconPreview').className = 'bi ' + iconClass($('fIcon').value);
            $('itemErr').classList.add('d-none');
            modal.show();
            setTimeout(() => $('fTitle').focus(), 300);
        }

        $('btnItemSave').addEventListener('click', async function() {
            const btn = this;
            const order = $('fOrder').value.trim();
            const payload = {
                title: $('fTitle').value.trim(),
                description: $('fDesc').value,
                icon: $('fIcon').value.trim(),
                sort_order: order === '' ? null : Number(order),
                is_active: $('fActive').checked,
            };
            if (!payload.title) {
                $('itemErr').textContent = 'Please enter the ' + ($('lblTitle').textContent || 'title').toLowerCase() + '.';
                $('itemErr').classList.remove('d-none');
                return;
            }

            btn.disabled = true;
            const r = cur.id ?
                await api(URLS.update.replace('__ID__', cur.id), 'PUT', payload) :
                await api(URLS.store.replace('__S__', cur.section), 'POST', payload);
            btn.disabled = false;

            if (r.ok) {
                modal.hide();
                toast(r.data.message || 'Saved.');
                reloadSoon();
            } else {
                $('itemErr').textContent = firstError(r.data);
                $('itemErr').classList.remove('d-none');
            }
        });

        /* ---------- row buttons (add / edit / delete / show-hide) ---------- */
        document.addEventListener('click', async function(e) {
            const add = e.target.closest('.js-add');
            if (add) {
                openModal(add.dataset.section, null);
                return;
            }

            const row = e.target.closest('.wb-row');
            if (!row) return;
            const item = JSON.parse(row.dataset.item);

            if (e.target.closest('.js-edit')) {
                openModal(row.dataset.section, item);
            } else if (e.target.closest('.js-del')) {
                if (!confirm('Delete "' + item.title + '" from the website?')) return;
                const r = await api(URLS.destroy.replace('__ID__', item.id), 'DELETE');
                if (r.ok) {
                    toast(r.data.message || 'Deleted.');
                    reloadSoon();
                } else toast(firstError(r.data), false);
            }
        });

        document.addEventListener('change', async function(e) {
            const sw = e.target.closest('.js-toggle');
            if (!sw) return;
            const row = sw.closest('.wb-row');
            const item = JSON.parse(row.dataset.item);
            const r = await api(URLS.toggle.replace('__ID__', item.id), 'POST');
            if (r.ok) {
                row.classList.toggle('is-off', !sw.checked);
                item.is_active = sw.checked;
                row.dataset.item = JSON.stringify(item);
                toast(r.data.message);
            } else {
                sw.checked = !sw.checked;
                toast(firstError(r.data), false);
            }
        });
    })();
</script>
@endsection