@extends('html')
@section('title', $entity['contents']['title'])
@section('main')
    <h1>{{ $entity['contents']['title'] }}</h1>
    <p>{{ $entity['contents']['description'] }}</p>
    <ul>
        @forelse ($children as $child)
            <li><a href="{{ $child['contents']['url'] }}">{{ $child['contents']['title'] }}</a></li>
        @empty
            <li>No children</li>
        @endforelse
    </ul>
@endsection