<?php

namespace App\Services;

use App\Models\Movie;
use App\Models\SearchHistory;
use App\Models\Favorite;

class RecommendationService
{
    public function getRecommendations($userId)
    {
        // Fetch the user's top search keywords and popular favorites
        $searchKeywords = $this->getUserTopSearchKeywords($userId);
        $popularFavorites = $this->getPopularFavorites($userId);

        // If popular favorites are empty, only search based on keywords
        if (empty($popularFavorites)) {
            // If there are no popular favorites, recommend movies based on the search history
            $recommendedMovies = Movie::where(function ($query) use ($searchKeywords) {
                foreach ($searchKeywords as $keyword) {
                    $query->orWhere('title', 'LIKE', "%$keyword%");
                }
            })
                ->distinct() // Ensure unique movies
                ->get();
        } else {
            // Query movies that match the popular items and user's search interests
            $recommendedMovies = Movie::where(function ($query) use ($searchKeywords, $popularFavorites) {
                $query->whereIn('id', $popularFavorites)
                    ->where(function ($q) use ($searchKeywords) {
                        foreach ($searchKeywords as $keyword) {
                            $q->orWhere('title', 'LIKE', "%$keyword%");
                        }
                    });
            })
                ->distinct() // Ensure unique movies
                ->get();
        }

        // Return the recommended movies
        return $recommendedMovies;
    }


    protected function getUserTopSearchKeywords($userId)
    {
        // Get the most frequently searched keywords by the user
        return SearchHistory::where('user_id', $userId)
            ->selectRaw('query, COUNT(*) as count')
            ->groupBy('query')
            ->orderByDesc('count')
            ->limit(5) // Adjust as needed
            ->pluck('query')
            ->toArray();
    }

    protected function getPopularFavorites($userId)
    {
        // Get the top favorite movies of other users
        return Favorite::where('user_id', '!=', $userId)
            ->selectRaw('movie_id, COUNT(*) as count')
            ->groupBy('movie_id')
            ->orderByDesc('count')
            ->limit(10) // Adjust as needed
            ->pluck('movie_id')
            ->toArray();
    }
}