@extends('layouts.admin')

@section('title', __('admin.menu.clients'))

@section('content')
    <h1>{{ __('admin.menu.clients') }}</h1>

    <table class="swbs-table">
        <thead>
        <tr>
            <th>#</th>
            <th>Nom</th>
            <th>Email</th>
            <th>Entreprise</th>
            <th>{{ __('generic.actions') }}</th>
        </tr>
        </thead>
        <tbody>
        @foreach($clients as $client)
            <tr>
                <td>{{ $client->id }}</td>
                <td>{{ $client->name }}</td>
                <td>{{ $client->email }}</td>
                <td>{{ $client->company }}</td>
                <td>
                    <a href="{{ route('admin.clients.show', $client) }}" class="swbs-link">{{ __('generic.view') }}</a>
                </td>
            </tr>
        @endforeach
        </tbody>
    </table>

    <div class="swbs-pagination">
        {{ $clients->links() }}
    </div>
@endsection