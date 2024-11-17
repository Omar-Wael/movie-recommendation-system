# Movie Recommendation System

A Laravel-based web application that recommends movies to users based on their search history and popular favorites among other users. The application provides an efficient way to discover movies while ensuring a personalized experience.

---

## Features

- **User Authentication**: Secure login and registration functionality.
- **Movie Recommendations**: Personalized movie suggestions based on user behavior.
- **Favorites Management**: Users can add and manage their favorite movies.
- **Search History Integration**: Recommendations are influenced by user search patterns.
- **Responsive UI**: Optimized for a seamless user experience.

---

## Installation

### Prerequisites

Before starting, ensure you have the following installed:

- PHP 8.1 or higher
- Composer
- Laravel 10.x
- MySQL or any supported database
- Node.js (for front-end assets)

### Steps

1. **Clone the Repository**

   ```bash
   git clone https://github.com/Omar-Wael/movie-recommendation-system.git
   cd <project_directory>
   ```

2. **Install Backend Dependencies**

   ```bash
   composer install
   ```

3. **Install Frontend Dependencies**

   ```bash
   npm install
   ```
4. **Set Up Environment Variables**

    Copy .env.example to .env and update the configuration:

    ```bash
    cp .env.example .env
    ```

5. **Generate Application Key**

   ```bash
   php artisan key:generate
   ```

6. **Run Database Migrations**

   ```bash
   php artisan migrate
   ```

7. **Build Frontend Assets**

   ```bash
   npm run dev
   ```

8. **Run the Development Server**

   ```bash
   php artisan serve
   ```
   The application will be accessible at: http://127.0.0.1:8000

---

## Project Structure

### Key Services

1. **Recommendation Service**:

   - Generates movie recommendations based on search history and favorite movies.
   - Avoids duplication in the recommendation list.
  
    ### Key Methods:

    - **getRecommendations($userId):** Returns recommended movies for a user.
    - **getUserTopSearchKeywords($userId):** Fetches top search keywords for personalized recommendations.
    - **getTopSearchKeywords($userId):** Fetches top search keywords for personalized recommendations.
    - **getPopularFavorites($userId):** Fetches popular favorite movies from other users.
  
2. **SearchHistory**:

   - Tracks user search queries for generating personalized recommendations.

3. **Favorite**:

   - Manages user favorite movies.

### Models

  - **Movie**: Represents movie information.
  - **SearchHistory**: Logs user search queries.
  - **Favorite**: Tracks movies marked as favorites by users.

---

## Usage

### Movie Recommendations
To fetch recommendations for a user:

```php
use App\Services\RecommendationService;

$recommendationService = new RecommendationService();
$recommendedMovies = $recommendationService->getRecommendations($userId);
```

---

## Contribution

We welcome contributions! To contribute:

1. Fork the repository.
2. Create a new branch (`feature/your-feature`).
3. Commit your changes and push them.
4. Submit a pull request.

---

## License

This project is licensed under the MIT License. See the `LICENSE.md` file for details.
 