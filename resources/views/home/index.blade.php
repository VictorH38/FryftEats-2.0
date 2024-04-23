@extends('layouts/main')

@section('description', 'Discover your favorite dining spots with FryftEats! Exclusively for USC students, our home page showcases your preferred restaurants within the USC Fryft Zone, tailored to your tastes. Log in to see your personalized list.')
@section('title', 'FryftEats')

@section('style')
    <style>
        .restaurant-grid {
            margin-top: 20px;
        }
    </style>
@endsection

@section('main')
    <div id="content">
        <h1 id="home-text">Find restaurants within USC Fryft Zone!</h1>

        <div id="background"></div>

        <div id="user-content">
            @if (session('username'))
                <h1>Hi, {{ session('username') }}!</h1>

                @if ($favorites->count() > 0)
                    <h3>Here are your favorite restaurants</h3>
                @else
                    <h3>You have no favorite restaurants to display</h3>
                @endif
            @else
                <h3>Login or Sign Up to add restaurants to your favorites list</h3>
            @endif
        </div>

        <div class="restaurant-grid">
            @if (session('username') && $favorites->count() > 0)
                @foreach ($favorites as $restaurant)
                    <div id="{{ str_slug($restaurant->name) }}" class="card">
                        <img src="{{ $restaurant->image_url ?: asset('images/no-image.jpeg') }}" alt="{{ $restaurant->name }}" class="restaurant-photo"/>
                        <h3 class="restaurant-name">{{ $restaurant->name }}</h3>
                        <p class="restaurant-address">{{ $restaurant->address }}</p>
                        <p class="restaurant-phone">{{ $restaurant->phone_number }}</p>
                        <p class="restaurant-rating">
                            @for ($i = 0; $i < floor($restaurant->rating); $i++)
                                <span class="fa fa-star star"></span>
                            @endfor
                            @if ($restaurant->rating - floor($restaurant->rating) >= 0.5)
                                <span class="fa fa-star-half star"></span>
                            @endif
                        </p>
                        <button class="favorites-button" onclick="removeFromFavorites('{{ $restaurant->name }}')">
                            <span class="fa fa-star"></span> Remove from Favorites
                        </button>
                    </div>
                @endforeach
            @endif
        </div>
    </div>
@endsection

@section('script')
    <script>
        function removeFromFavorites(restaurantName) {
            var favUrl = new URL(window.location.href);
            favUrl.pathname = '/FryftEats/removeFromFavorites.php';
            favUrl.search = 'name=' + encodeURIComponent(restaurantName);

            fetch(favUrl, { method: 'GET' })
                .then(response => {
                    if (response.ok) {
                        restauarant_id = restaurantName.replace(/ /g, "-");
                        let restaurant_card = document.getElementById(restauarant_id);

                        if (restaurant_card) {
                            let grid = document.querySelector('.restaurant-grid');
                            grid.removeChild(restaurant_card);
                        }

                    }
                })
                .catch(err => console.error('Error removing from favorites:', err));
        }
    </script>
@endsection
