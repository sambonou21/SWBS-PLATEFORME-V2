@extends('layouts.app')

@section('title', __('dashboard.section.quotes'))

@section('content')
<section class="swbs-section">
    <div class="swbs-container">
        <h1>{{ __('dashboard.section.quotes') }}</h1>

        @if($quotes->isEmpty())
            <p>Aucune demande de devis pour le moment.</p>
        @else
            <table class="swbs-table">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>{{ __('quotes.form.service') }}</th>
                        <th>{{ __('generic.status') }}</th>
                        <th>Date</th>
                    </tr>
                </thead>
                <tbody>
                @foreach($quotes as $quote)
                    <tr>
                        <td>#{{ $quote->id }}</td>
                        <td>{{ $quote->service?->title ?? $quote->project_type ?? '-' }}</td>
                        <td>{{ __('quotes.status.'.$quote->status) }}</td>
                        <td>{{ $quote->created_at?->format('d/m/Y H:i') }}</td>
                    </tr>
                @endforeach
                </tbody>
            </table>

            <div class="swbs-pagination">
                {{ $quotes->links() }}
            </div>
        @endif
    </div>
</section>
@endsection