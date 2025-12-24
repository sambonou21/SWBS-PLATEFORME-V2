@props(['type' => 'info', 'message' => ''])

@php
    $classes = match($type) {
        'success' => 'swbs-alert swbs-alert-success',
        'error' => 'swbs-alert swbs-alert-error',
        default => 'swbs-alert',
    };
@endphp

@if($message)
    <div class="{{ $classes }}">
        {{ $message }}
    </div>
@endif