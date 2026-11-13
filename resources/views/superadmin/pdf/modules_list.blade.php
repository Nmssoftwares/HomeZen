@extends('pdf.master_layout')

@section('title', 'System Modules List')

@section('content')
<h3 style="text-align: center; margin-bottom: 15px;">System Modules List</h3>

<table>
    <thead>
        <tr>
            <th>Sr.</th>
            <th>Module Name & Info</th>
            <th>Code</th>
            <th>Functional Suite</th>
            <th>Dashboard URL</th>
            <th>Status</th>
        </tr>
    </thead>
    <tbody>
        @foreach($modules as $index => $mod)
        <tr>
            <td>{{ $index + 1 }}</td>
            <td>
                <strong>{{ $mod->name }}</strong><br>
                <small style="color: #666;">{{ $mod->description ?: 'No description provided' }}</small>
            </td>
            <td>{{ $mod->short_name }}</td>
            <td>{{ $mod->group_name ?: 'General' }}</td>
            <td>{{ $mod->dashboard_url ? '/' . ltrim($mod->dashboard_url, '/') : '-' }}</td>
            <td style="color: {{ $mod->status == 1 ? 'green' : 'red' }};">
                {{ $mod->status == 1 ? 'Active' : 'Inactive' }}
            </td>
        </tr>
        @endforeach
    </tbody>
</table>
@endsection