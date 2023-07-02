@extends('FileStorage::layouts.master')

@section('content')
    <div class="container">
        <h1>Plugin: FileStorage</h1>

        <p>
            This view is loaded from plugin: {!! config('file-storage.name') !!}
        </p>

        <a href="{{ route('file-storage.setting') }}">Go to the FileStorage plugin settings page.</a>
    </div>
@endsection
