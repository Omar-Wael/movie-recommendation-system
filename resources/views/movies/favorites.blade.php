@extends('layouts.app')

@section('content')
    @if (session('message'))
        <div class="alert alert-{{ session('type') }} alert-dismissible" role="alert">
            <div>{{ session('message') }}</div>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif
    <h1 class="text-center text-white my-4">Your Favorites</h1>
    @if ($favorites->isEmpty())
        <div class="container">
            <div id="no-results-message" class="alert alert-warning d-flex align-items-center justify-content-between">
                No Favorites found. Please add some!
                <a href="{{ route('movies') }}" class="btn btn-primary">Go to Movies</a>
            </div>
        </div>
    @endif
    @if ($favorites->isNotEmpty())
        <div class="container">
            <table id="movies-table" class="table table-primary table-striped text-center align-middle">
                <thead>
                    <th>ID</th>
                    <th>Title</th>
                    <th>Image</th>
                    <th>Genres</th>
                    <th>Remove from Favorites</th>
                </thead>
                <tbody>
                    @foreach ($favorites as $i => $favorite)
                        <tr>
                            <td>{{ (int) $i + 1 }}</td>
                            <td>{{ $favorite->movie->title }}</td>
                            <td>
                                <div class="d-flex justify-content-center align-items-center">
                                    <img style="max-height: 100px" src="{{ $favorite->movie->backdrop_path }}"
                                        alt="Movie Image">
                                </div>
                            </td>
                            <td>
                                @foreach (json_decode($favorite->movie->genres) as $index => $item)
                                    {{ $item }}
                                    @if ($index + 1 < count(json_decode($favorite->movie->genres)))
                                        ,
                                    @endif
                                @endforeach
                            </td>
                            <td>
                                <form action="{{ route('favorites.remove', $favorite->movie_id) }}" method="POST">
                                    @csrf
                                    @method('DELETE')
                                    <button class="btn btn-primary" type="submit">Remove</button>
                                </form>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
                </thead>
            </table>
        </div>
    @endif
@endsection
