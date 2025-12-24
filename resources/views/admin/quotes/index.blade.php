@extends('layouts.admin')

@section('title', __('admin.menu.quotes'))

@section('content')
    <h1>{{ __('admin.menu.quotes') }}</h1>

    <table class="swbs-table">
        <thead>
        <tr>
            <th>#</th>
            <th>Nom</th>
            <th>Service</th>
            <th>Statut</th>
            <th>Date</th>
            <th>{{ __('generic.actions') }}</th>
        </tr>
        </thead>
        <tbody>
        @foreach($quotes as $quote)
            <tr>
                <td>#{{ $quote->id }}</td>
                <td>{{ $quote->name }}</td>
                <td>{{ $quote->service?->title ?? $quote->project_type }}</td>
                <td>{{ __('quotes.status.'.$quote->status) }}</td>
                <td>{{ $quote->created_at?->format('d/m/Y H:i') }}</td>
                <td>
                    <a href="{{ route('admin.quotes.show', $quote) }}" class="swbs-link">{{ __('generic.view') }}</a>
                    <form method="POST" action="{{ route('admin.quotes.destroy', $quote) }}" style="display:inline">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="swbs-btn swbs-btn-text" onclick="return confirm('Supprimer ce devis ?')">
                            {{ __('generic.delete') }}
                        </button>
                    </form>
                </td>
            </tr>
        @endforeach
        </tbody>
    </table>

    <div class="swbs-pagination">
        {{ $quotes->links() }}
    </div>
@endsection