<!-- resources/views/movies/index.blade.php -->
@extends('layouts.app')

@section('content')
    @if (session('message'))
        <div class="alert alert-{{ session('type') }} alert-dismissible" role="alert">
            <div>{{ session('message') }}</div>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif
    <h1 class="text-center text-white my-4">Top 100 Popular Movies</h1>
    <div class="container search mb-4">
        <form action="{{ route('movies.search') }}" method="GET" class="d-flex align-items-center gap-2">
            <input id="movie-search" class="w-100 rounded" type="text" name="query" placeholder="Search movies...">
            {{-- <button class="btn btn-primary" type="submit">Search</button> --}}
        </form>
        <!-- Error message for search input -->
        <div id="search-error-message" class="alert alert-danger mt-2" style="display: none;">
            Please enter at least 3 characters to search.
        </div>
    </div>
    <div class="container">
        <!-- Message when no results are found -->
        <div id="no-results-message" class="alert alert-warning" style="display: none;">
            No results found for your search.
        </div>
        <table id="movies-table" class="table table-primary table-striped text-center align-middle">
            <thead>
                <th>ID</th>
                <th>Title</th>
                <th>Image</th>
                <th>Genres</th>
                <th>Add to Favorites</th>
            </thead>
            <tbody>
                @foreach ($movies as $i => $movie)
                    <tr>
                        <td>{{ (int) $i + 1 }}</td>
                        <td>{{ $movie->title }}</td>
                        <td>
                            <div class="d-flex justify-content-center align-items-center">
                                <img style="max-height: 100px" src="{{ $movie->backdrop_path }}" alt="Movie Image">
                            </div>
                        </td>
                        <td>
                            @foreach (json_decode($movie->genres) as $index => $item)
                                {{ $item }}
                                @if ($index + 1 < count(json_decode($movie->genres)))
                                    ,
                                @endif
                            @endforeach
                        </td>
                        <td>
                            <form action="{{ route('favorites.add', $movie->id) }}" method="POST">
                                @csrf
                                <button class="btn btn-sm btn-primary" type="submit">Add to Favorites</button>
                            </form>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script>
        $(document).ready(function() {
            // Handle the search input event
            $('#movie-search').on('input', function() {
                $('#movies-table').show();
                var query = $(this).val(); // Get the value from the input field

                // If query is less than 3 characters, show main data table
                if (query.length < 3 || query === "") {
                    // Show the main movie table with all data
                    $('#movies-table tbody').empty(); // Clear any search results
                    $('#no-results-message').hide(); // Hide "No results" message

                    // Append the original main data to the table
                    @foreach ($movies as $i => $movie)
                        var genres = JSON.parse(@json($movie->genres)).join(', '); // Parse genres

                        var row = `
                            <tr>
                                <td>{{ $i + 1 }}</td>
                                <td>{{ $movie->title }}</td>
                                <td>
                                    <div class="d-flex justify-content-center align-items-center">
                                        <img style="max-height: 100px" src="{{ $movie->backdrop_path }}" alt="Movie Image">
                                    </div>
                                </td>
                                <td>${genres}</td>
                                <td>
                                    <!-- Add to Favorites Button -->
                                </td>
                            </tr>
                        `;
                        $('#movies-table tbody').append(row);
                    @endforeach
                    if (query === "") {
                        $('#search-error-message').hide(); // Hide error message

                    } else {
                        $('#search-error-message').show(); // Hide error message

                    }
                } else {
                    // Show the error message if fewer than 3 characters
                    $('#search-error-message').hide();

                    // Send an AJAX request to the search route only if query length is 3 or more
                    $.ajax({
                        url: '{{ route('movies.search') }}', // The route to fetch movies
                        method: 'GET',
                        data: {
                            query: query
                        }, // Send the query parameter
                        success: function(response) {
                            // Clear the current table and message
                            $('#movies-table tbody').empty();
                            $('#no-results-message').hide();

                            // Check if the response has any movies
                            if (response.length > 0) {
                                // Loop through the response and append each movie to the table
                                $.each(response, function(index, movie) {
                                    // Ensure valid JSON parsing for genres
                                    var genres = JSON.parse(movie.genres.replace(
                                        /&quot;/g, '"')).join(', '); // Parse genres

                                    var row = `
                                        <tr>
                                            <td>${index + 1}</td>
                                            <td>${movie.title}</td>
                                            <td>
                                                <div class="d-flex justify-content-center align-items-center">
                                                    <img style="max-height: 100px" src="${movie.backdrop_path}" alt="Movie Image">
                                                </div>
                                            </td>
                                            <td>${genres}</td>
                                            <td>
                                                <!-- Add to Favorites Button -->
                                            </td>
                                        </tr>
                                    `;

                                    // Append the row to the table body
                                    $('#movies-table tbody').append(row);
                                });
                            } else {
                                // If no results, show the "No results" message
                                $('#no-results-message').show();
                                $('#movies-table').hide();
                            }
                        },
                        error: function(xhr, status, error) {
                            console.error('Error:', error);
                        }
                    });
                }
            });
        });
    </script>
@endsection
