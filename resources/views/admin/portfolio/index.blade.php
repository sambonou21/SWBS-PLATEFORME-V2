@extends('layouts.admin')

@section('title', __('admin.menu.portfolio'))

@section('content')
    <h1>{{ __('admin.menu.portfolio') }}</h1>

    <div class="swbs-section" style="padding-top: 0;">
        <a href="{{ route('admin.portfolio.create') }}" class="swbs-btn swbs-btn-primary">{{ __('generic.add') }} un projet</a>
    </div>

    <table class="swbs-table">
        <thead>
        <tr>
            <th>#</th>
            <th>Titre</th>
            <th>Client</th>
            <th>Type</th>
            <th>Mis en avant</th>
            <th>{{ __('generic.actions') }}</th>
        </tr>
        </thead>
        <tbody>
        @foreach($items as $item)
            <tr>
                <td>{{ $item->id }}</td>
                <td>{{ $item->title }}</td>
                <td>{{ $item->client_name }}</td>
                <td>{{ $item->service_type }}</td>
                <td>{{ $item->is_featured ? 'Oui' : 'Non' }}</td>
                <td>
                    <a href="{{ route('admin.portfolio.edit', $item) }}" class="swbs-link">{{ __('generic.edit') }}</a>
                    <form method="POST" action="{{ route('admin.portfolio.destroy', $item) }}" style="display:inline">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="swbs-btn swbs-btn-text" onclick="return confirm('Supprimer ce projet ?')">
                            {{ __('generic.delete') }}
                        </button>
                    </form>
                </td>
            </tr>
        @endforeach
        </tbody>
    </table>
@endsection