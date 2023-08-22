@extends('GithubAuth::layouts.master')

@section('content')
    <div class="container">
        <h1>Plugin: {{ config('github-auth.name') }}</h1>

        <p>
            This view is loaded from plugin: {{ config('github-auth.name') }}
        </p>

        <a href="{{ route('github-auth.setting') }}">Go to the {{ config('github-auth.name') }} plugin settings page.</a>
    </div>
@endsection
