@extends('pdf.master_layout')

@section('title', 'Registered Companies List')

@section('content')
<h3 style="text-align: center; margin-bottom: 15px; color: #333;">Registered Companies List</h3>

<table>
    <thead>
        <tr>
            <th style="width: 5%;">Sr.</th>
            <th style="width: 35%;">Company Details</th>
            <th style="width: 20%;">Admin Name</th>
            <th style="width: 20%;">Contact Info</th>
            <th style="width: 10%;">Status</th>
        </tr>
    </thead>
    <tbody>
        @foreach($companies as $index => $comp)
        @php
        $admin = $comp->users->first();
        @endphp
        <tr>
            <td>{{ $index + 1 }}</td>
            <td>
                <strong>{{ $comp->company_name }}</strong>
                @if($comp->company_code)
                <span style="color: #666; font-size: 10px;">({{ $comp->company_code }})</span>
                @endif
                <br>
                <small style="color: #666;">{{ $comp->company_address }}</small>
            </td>
            <td>
                {{ $admin ? $admin->first_name . ' ' . $admin->last_name : 'No Admin' }}
            </td>
            <td>
                <div style="font-size: 11px;">
                    <strong>E:</strong> {{ $admin->email ?? 'N/A' }}<br>
                    <strong>P:</strong> {{ $admin->phone ?? 'N/A' }}
                </div>
            </td>
            <td style="color: {{ $comp->status == 1 ? 'green' : 'red' }}; font-weight: bold;">
                {{ $comp->status == 1 ? 'Active' : 'Inactive' }}
            </td>
        </tr>
        @endforeach
    </tbody>
</table>
@endsection