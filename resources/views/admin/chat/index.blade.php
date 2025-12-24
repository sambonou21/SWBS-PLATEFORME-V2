@extends('layouts.admin')

@section('title', __('admin.menu.chat'))

@section('content')
    <h1>{{ __('admin.menu.chat') }}</h1>

    <table class="swbs-table">
        <thead>
        <tr>
            <th>#</th>
            <th>Client / prospect</th>
            <th>Statut</th>
            <th>Dernier message</th>
            <th>{{ __('generic.actions') }}</th>
        </tr>
        </thead>
        <tbody>
        @foreach($conversations as $conversation)
            <tr>
                <td>{{ $conversation->id }}</td>
                <td>
                    {{ $conversation->user?->name ?? $conversation->prospect_name ?? 'Invité' }}<br>
                    <small>{{ $conversation->user?->email ?? $conversation->prospect_email }}</small>
                </td>
                <td>{{ $conversation->status }}</td>
                <td>{{ $conversation->last_message_at?->format('d/m/Y H:i') }}</td>
                <td>
                    <a href="{{ route('admin.chat.show', $conversation) }}" class="swbs-link">{{ __('generic.view') }}</a>
                </td>
            </tr>
        @endforeach
        </tbody>
    </table>

    <div class="swbs-pagination">
        {{ $conversations->links() }}
    </div>
@endsection