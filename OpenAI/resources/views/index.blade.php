@extends('OpenAI::layouts.master')

@section('content')
    <h1>Hello World</h1>

    <p>
        This view is loaded from plugin: {!! config('open-ai.name') !!}
    </p>
@endsection
