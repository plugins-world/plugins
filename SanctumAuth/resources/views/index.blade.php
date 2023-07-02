@extends('SanctumAuth::layouts.master')

@section('content')
    <div class="container">
        <h1>Plugin: SanctumAuth</h1>

        <p>
            This view is loaded from plugin: {!! config('sanctum-auth.name') !!}
        </p>

        <a href="{{ route('sanctum-auth.setting') }}">Go to the SanctumAuth plugin settings page.</a>
    </div>
@endsection
