@extends('html')
@section('title', $entity['contents']['title'] ?? '')
@section('main')
    <h1>{{ $entity['contents']['title'] ?? '' }}</h1>
    <img src="/img/logo.svg" class="logo">
    <p>{{ $entity['contents']['summary'] ?? '' }}</p>
    <ul>
        @forelse ($children as $child)
            <li><a href="{{ $child['contents']['url'] ?? '' }}">{{ $child['contents']['title'] ?? '' }}</a></li>
        @empty
            <li>No children</li>
        @endforelse
    </ul>
@endsection