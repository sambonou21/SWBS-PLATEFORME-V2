@extends('layouts.admin')

@section('title', __('admin.menu.admins'))

@section('content')
    <h1>{{ __('admin.menu.admins') }}</h1>

    <div class="swbs-section" style="padding-top: 0;">
        <a href="{{ route('admin.admins.create') }}" class="swbs-btn swbs-btn-primary">{{ __('generic.add') }} un compte admin</a>
    </div>

    <table class="swbs-table">
        <thead>
        <tr>
            <th>#</th>
            <th>Nom</th>
            <th>Email</th>
            <th>{{ __('generic.actions') }}</th>
        </tr>
        </thead>
        <tbody>
        @foreach($admins as $admin)
            <tr>
                <td>{{ $admin->id }}</td>
                <td>{{ $admin->name }}</td>
                <td>{{ $admin->email }}</td>
                <td>
                    <a href="{{ route('admin.admins.edit', $admin) }}" class="swbs-link">{{ __('generic.edit') }}</a>
                    <form method="POST" action="{{ route('admin.admins.destroy', $admin) }}" style="display:inline">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="swbs-btn swbs-btn-text" onclick="return confirm('Supprimer ce compte administrateur ?')">
                            {{ __('generic.delete') }}
                        </button>
                    </form>
                </td>
            </tr>
        @endforeach
        </tbody>
    </table>
@endsection