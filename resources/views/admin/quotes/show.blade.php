@extends('layouts.admin')

@section('title', 'Devis #'.$quote->id)

@section('content')
    <h1>Devis #{{ $quote->id }}</h1>

    <div class="swbs-two-columns">
        <section class="swbs-side-box">
            <h2>Informations client</h2>
            <p><strong>Nom :</strong> {{ $quote->name }}</p>
            <p><strong>Email :</strong> {{ $quote->email }}</p>
            <p><strong>Téléphone :</strong> {{ $quote->phone }}</p>
            <p><strong>Entreprise :</strong> {{ $quote->company }}</p>
            <p><strong>Service :</strong> {{ $quote->service?->title ?? $quote->project_type }}</p>
            <p><strong>Budget :</strong>
                {{ $quote->budget_min ? number_format($quote->budget_min, 0, ',', ' ') : '-' }}
                –
                {{ $quote->budget_max ? number_format($quote->budget_max, 0, ',', ' ') : '-' }}
                {{ $quote->currency }}
            </p>
        </section>

        <section>
            <h2>Détails du besoin</h2>
            <p>{!! nl2br(e($quote->message)) !!}</p>

            <h3>Suivi interne</h3>
            <form method="POST" action="{{ route('admin.quotes.update', $quote) }}" class="swbs-form">
                @csrf
                @method('PUT')
                <div class="swbs-form-group">
                    <label for="status">{{ __('generic.status') }}</label>
                    <select id="status" name="status">
                        @foreach(['recu','en_cours','valide','refuse'] as $status)
                            <option value="{{ $status }}" @selected($quote->status === $status)>{{ __('quotes.status.'.$status) }}</option>
                        @endforeach
                    </select>
                    @error('status')<p class="swbs-form-error">{{ $message }}</p>@enderror
                </div>
                <div class="swbs-form-group">
                    <label for="admin_notes">Notes internes</label>
                    <textarea id="admin_notes" name="admin_notes" rows="4">{{ old('admin_notes', $quote->admin_notes) }}</textarea>
                    @error('admin_notes')<p class="swbs-form-error">{{ $message }}</p>@enderror
                </div>
                <button type="submit" class="swbs-btn swbs-btn-primary">{{ __('generic.save') }}</button>
            </form>
        </section>
    </div>
@endsection