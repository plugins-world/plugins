@extends('Tenant::layouts.master')

@section('content')
<div class="container">
    <h1>Plugin: Tenant</h1>

    <p>
        This view is loaded from plugin: {!! config('tenant.name') !!}
    </p>

    <a href="{{ route('tenant.setting') }}">Go to the Tenant plugin settings page.</a>
</div>
@endsection
