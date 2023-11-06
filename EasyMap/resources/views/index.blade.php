@extends('EasyMap::layouts.master')

@section('content')
    <div class="container">
        <h1>Plugin: {{ config('easy-map.name') }}</h1>

        <p>
            This view is loaded from plugin: {{ config('easy-map.name') }}
        </p>

        <a href="{{ route('easy-map.setting') }}">Go to the {{ config('easy-map.name') }} plugin settings page.</a>
    </div>
@endsection
