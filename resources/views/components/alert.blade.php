@props(['type' => 'success', 'message'])
@php
$classes = $type === 'success' ? 'bg-green-50 text-green-700 border-green-200' : 'bg-red-50 text-red-700 border-red-200';
@endphp
<div class="border {{ $classes }} rounded-sm px-4 py-3 mb-6 text-sm">{{ $message }}</div>