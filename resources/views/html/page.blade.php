@extends('html')
@section('title', $entity['contents']['title'])
@section('main')
    <h1>{{ $entity['contents']['title'] }}</h1>
    <p>{{ $entity['contents']['description'] or '' }}</p>
    <p>{{ $entity['contents']['content'] or '' }}</p>
@endsection