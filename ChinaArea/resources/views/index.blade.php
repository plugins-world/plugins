@extends('ChinaArea::layouts.master')

@section('content')
    <div class="container">
        <h1>Plugin: {{ config('china-area.name') }}</h1>

        <p>
            This view is loaded from plugin: {{ config('china-area.name') }}
        </p>

        <a href="{{ route('china-area.setting') }}">Go to the {{ config('china-area.name') }} plugin settings page.</a>
    </div>
@endsection
