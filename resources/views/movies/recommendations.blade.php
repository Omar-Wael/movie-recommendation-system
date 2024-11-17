@extends('layouts.app')

@section('content')
    <div class="container">
        <h1 class="text-center text-white my-4">Recommended Movies</h1>
        <ul class="list-group">
            @foreach ($recommendedMovies as $movie)
                <li class="list-group-item">{{ $movie['title'] }} - {{ $movie['rating'] }}</li>
            @endforeach
        </ul>
    </div>
@endsection
