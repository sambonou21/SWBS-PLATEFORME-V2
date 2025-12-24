@extends('layouts.admin')

@section('title', __('admin.menu.categories'))

@section('content')
    <h1>{{ __('admin.menu.categories') }}</h1>

    <div class="swbs-section" style="padding-top: 0;">
        <a href="{{ route('admin.categories.create') }}" class="swbs-btn swbs-btn-primary">{{ __('generic.add') }} une catégorie</a>
    </div>

    <table class="swbs-table">
        <thead>
        <tr>
            <th>#</th>
            <th>Nom</th>
            <th>Slug</th>
            <th>Parent</th>
            <th>Active</th>
            <th>{{ __('generic.actions') }}</th>
        </tr>
        </thead>
        <tbody>
        @foreach($categories as $category)
            <tr>
                <td>{{ $category->id }}</td>
                <td>{{ $category->name }}</td>
                <td>{{ $category->slug }}</td>
                <td>{{ $category->parent?->name }}</td>
                <td>{{ $category->is_active ? 'Oui' : 'Non' }}</td>
                <td>
                    <a href="{{ route('admin.categories.edit', $category) }}" class="swbs-link">{{ __('generic.edit') }}</a>
                    <form method="POST" action="{{ route('admin.categories.destroy', $category) }}" style="display:inline">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="swbs-btn swbs-btn-text" onclick="return confirm('Supprimer cette catégorie ?')">
                            {{ __('generic.delete') }}
                        </button>
                    </form>
                </td>
            </tr>
        @endforeach
        </tbody>
    </table>
@endsection