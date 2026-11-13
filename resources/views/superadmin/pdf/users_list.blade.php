@extends('pdf.master_layout')

@section('title', 'Registered Users List')

@section('content')
<h3 style="text-align: center; margin-bottom: 15px; color: #333;">Team Members & Users List</h3>

<table>
    <thead>
        <tr>
            <th style="width: 5%;">Sr.</th>
            <th style="width: 30%;">Employee Details</th>
            <th style="width: 20%;">Role & Dept</th>
            <th style="width: 30%;">Contact Info</th>
            <th style="width: 15%;">Status</th>
        </tr>
    </thead>
    <tbody>
        @foreach($users as $index => $u)
        <tr>
            <td>{{ $index + 1 }}</td>
            <td>
                <strong>{{ $u->first_name }} {{ $u->last_name }}</strong>
                @if($u->employee_code)
                <br><small style="color: #666;">Code: {{ $u->employee_code }}</small>
                @endif
            </td>
            <td>
                <strong>
                    @if($u->role == 2) Admin
                    @elseif($u->role == 4) Team Lead / Manager
                    @else Member
                    @endif
                </strong>
                <br><small style="color: #666;">{{ $u->department ?: 'N/A' }}</small>
            </td>
            <td>
                <div style="font-size: 11px;">
                    <strong>E:</strong> {{ $u->email }}<br>
                    <strong>P:</strong> {{ $u->phone ?: 'N/A' }}
                </div>
            </td>
            <td style="color: {{ $u->status == 1 ? 'green' : 'red' }}; font-weight: bold;">
                {{ $u->status == 1 ? 'Active' : 'Inactive' }}
            </td>
        </tr>
        @endforeach
    </tbody>
</table>
@endsection