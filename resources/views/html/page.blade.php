@extends('html')
@section('title', $entity['contents']['title'])
@section('main')
    <h1>{{ $entity['contents']['title'] }}</h1>
    <p>{{ $entity['contents']['summary']}}</p>
    <div>
        @forelse ($media as $mediumEntity)
            <img src="{{ $mediumEntity->medium->url('thumb') }}" alt="" />
        @empty
            <em>No children</em>
        @endforelse
    </div>
@endsection