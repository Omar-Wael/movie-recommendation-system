<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Http;
use App\Models\Movie; // Assuming you have a Movie model
use Carbon\Carbon;
use Illuminate\Support\Facades\Log;

class FetchMovies extends Command
{
    protected $signature = 'fetch:movies';
    protected $description = 'Fetch movies from the API and save them to the database';

    public function __construct()
    {
        parent::__construct();
    }

    public function handle()
    {
        // Define the API endpoint
        $apiUrl = 'https://movies-api14.p.rapidapi.com/movies';
        $apiHost = "movies-api14.p.rapidapi.com";
        $apiKey = env('RAPIDAPI_KEY');
        // Make the API call
        $response = Http::withHeaders([
            'x-rapidapi-host' => $apiHost,
            'x-rapidapi-key' => $apiKey,
        ])->get($apiUrl);

        if ($response->successful()) {
            $movies = $response->json(); // Assume the response is an array of movie data
            foreach ($movies['movies'] as $movie) {
                // dd($movie);
                // Save each movie to the database
                Movie::updateOrCreate(
                    [
                        'title' => $movie['original_title'],
                        'backdrop_path' => $movie['backdrop_path'],
                        'genres' => json_encode($movie['genres'] ?? []),
                        'overview' => $movie['overview'],
                        'poster_path' => $movie['poster_path'],
                        'release_date' => Carbon::parse($movie['release_date'])->format('Y-m-d'),
                        'created_at' => now(),
                        'updated_at' => now(),
                    ]
                );
            }

            $this->info('Movies fetched and saved successfully.');
        } else {
            Log::error('Failed to fetch movies: ' . $response->body());
            $this->error('Failed to fetch movies.');
        }
    }
}