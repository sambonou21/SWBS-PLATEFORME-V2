@extends('layouts.admin')

@section('title', __('admin.menu.products'))

@section('content')
    <h1>{{ __('admin.menu.products') }}</h1>

    <div class="swbs-section" style="padding-top: 0;">
        <a href="{{ route('admin.products.create') }}" class="swbs-btn swbs-btn-primary">{{ __('generic.add') }} un produit</a>
    </div>

    <table class="swbs-table">
        <thead>
        <tr>
            <th>#</th>
            <th>Nom</th>
            <th>Catégorie</th>
            <th>Prix FCFA</th>
            <th>Actif</th>
            <th>{{ __('generic.actions') }}</th>
        </tr>
        </thead>
        <tbody>
        @foreach($products as $product)
            <tr>
                <td>{{ $product->id }}</td>
                <td>{{ $product->name }}</td>
                <td>{{ $product->category?->name }}</td>
                <td>{{ number_format($product->price_fcfa, 0, ',', ' ') }}</td>
                <td>{{ $product->is_active ? 'Oui' : 'Non' }}</td>
                <td>
                    <a href="{{ route('admin.products.edit', $product) }}" class="swbs-link">{{ __('generic.edit') }}</a>
                    <form method="POST" action="{{ route('admin.products.destroy', $product) }}" style="display:inline">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="swbs-btn swbs-btn-text" onclick="return confirm('Supprimer ce produit ?')">
                            {{ __('generic.delete') }}
                        </button>
                    </form>
                </td>
            </tr>
        @endforeach
        </tbody>
    </table>
@endsection