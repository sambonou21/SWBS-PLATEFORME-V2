@extends('layouts.admin')

@section('title', __('admin.menu.services'))

@section('content')
    <h1>{{ __('admin.menu.services') }}</h1>

    <div class="swbs-section" style="padding-top: 0;">
        <a href="{{ route('admin.services.create') }}" class="swbs-btn swbs-btn-primary">{{ __('generic.add') ?? 'Ajouter' }} un service</a>
    </div>

    <table class="swbs-table">
        <thead>
        <tr>
            <th>#</th>
            <th>Titre</th>
            <th>Slug</th>
            <th>Actif</th>
            <th>{{ __('generic.actions') }}</th>
        </tr>
        </thead>
        <tbody>
        @foreach($services as $service)
            <tr>
                <td>{{ $service->id }}</td>
                <td>{{ $service->title }}</td>
                <td>{{ $service->slug }}</td>
                <td>{{ $service->is_active ? 'Oui' : 'Non' }}</td>
                <td>
                    <a href="{{ route('admin.services.edit', $service) }}" class="swbs-link">{{ __('generic.edit') }}</a>
                    <form method="POST" action="{{ route('admin.services.destroy', $service) }}" style="display:inline">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="swbs-btn swbs-btn-text" onclick="return confirm('Supprimer ce service ?')">
                            {{ __('generic.delete') }}
                        </button>
                    </form>
                </td>
            </tr>
        @endforeach
        </tbody>
    </table>
@endsection