@extends('LaravelLocalStorage::layouts.master')

@section('content')
    <h1>Hello World</h1>

    <p>
        This view is loaded from plugin: {!! config('laravel-local-storage.name') !!}
    </p>
@endsection
