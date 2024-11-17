<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\SearchHistory;
use App\Models\Favorite;
use App\Models\Movie;
use App\Services\RecommendationService;


class MovieController extends Controller
{
    protected $recommendationService;

    public function __construct(RecommendationService $recommendationService)
    {
        $this->recommendationService = $recommendationService;
    }

    public function index()
    {
        $movies = Movie::all();
        return view('movies.index', compact('movies'));
    }

    public function addToFavorites($movieId)
    {
        $favoriteExists = Favorite::where([
            'user_id' => auth()->id(),
            'movie_id' => $movieId,
        ])->exists();

        $movie = Movie::findOrFail($movieId);

        if ($favoriteExists) {
            return redirect()->back()->with([
                'message' => "{$movie->title} Movie is already in your favorites!",
                'type' => 'info'
            ]);
        }

        // If the favorite doesn't exist, create it
        Favorite::create([
            'user_id' => auth()->id(),
            'movie_id' => $movieId,
        ]);

        return redirect()->back()->with([
            'message' => "{$movie->title} Movie added to favorites!",
            'type' => 'success'
        ]);
    }


    public function viewFavorites()
    {
        $favorites = auth()->user()->favorites()->with('movie')->get();
        if ($favorites->isEmpty()) {
            // Pass an empty collection if there are no favorites
            $favorites = collect();
        }
        return view('movies.favorites', compact('favorites'));
    }

    public function removeFromFavorites($movieId)
    {
        $movie = Movie::findOrFail($movieId);
        Favorite::where('user_id', auth()->id())
            ->where('movie_id', $movieId)
            ->delete();

        return redirect()->back()->with([
            'message' => "{$movie->title} Movie removed from favorites!",
            'type' => 'success'
        ]);
    }


    public function search(Request $request)
    {
        $query = $request->input('query');


        if ($query) {
            // Assuming your movies table has a 'title' column to search
            $movies = Movie::where('title', 'like', '%' . $query . '%')->get();

            SearchHistory::create([
                'user_id' => auth()->id(),
                'query' => $query,
            ]);
        } else {
            $movies = Movie::all();
        }
        return response()->json($movies); // Return as JSON
    }




    public function recommendations()
    {
        $userId = auth()->id();
        $recommendedMovies = $this->recommendationService->getRecommendations($userId);

        return view('movies.recommendations', compact('recommendedMovies'));
    }
}