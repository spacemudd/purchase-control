@extends('layouts.public', ['title' => '404'])

@section('content')
    <section class="section">
        <div class="columns">
            <div class="column">
                <p>Page not found. 404.</p>
                <p><a href="{{ url('/') }}">Go back home.</a></p>
            </div>
        </div>
    </section>
@endsection
