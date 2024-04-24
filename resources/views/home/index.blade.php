@extends('layouts/main')

@section('description', "Discover your favorite dining spots with FryftEats! Exclusively for USC students, our home page showcases your preferred restaurants within the USC Fryft Zone, tailored to your tastes. Log in to see your personalized list.")
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
            @auth
                <h1>Hi, {{ $user->first_name }}!</h1>
        
                @if ($favorites->count() > 0)
                    <h3>Here are your favorite restaurants</h3>
                @else
                    <h3>You have no favorite restaurants</h3>
                @endif
            @else
                <h3>Login or Sign Up to add restaurants to your favorites list</h3>
            @endauth
        </div>

        <div class="restaurant-grid">
            @auth
                @if ($favorites->count() > 0)
                    @foreach ($favorites as $restaurant)
                        <div id="home-restaurant-{{ $restaurant->id }}" class="card">
                            <img src="{{ $restaurant->image_url }}" alt="{{ $restaurant->name }}" class="restaurant-photo">
                            <h3 class="restaurant-name">{{ $restaurant->name }}</h3>
                            <p class="restaurant-address">{{ $restaurant->address }}</p>
                            <p class="restaurant-phone">{{ $restaurant->phone_number }}</p>
                            <p class="restaurant-rating">
                                @for($i = 0; $i < floor($restaurant->rating); $i++)
                                    <span class="fa fa-star star"></span>
                                @endfor
                                @if($restaurant->rating - floor($restaurant->rating) >= 0.5)
                                    <span class="fa fa-star-half-o"></span>
                                @endif
                            </p>
        
                            @auth
                                @php
                                    $isFavorite = $user->favorites()->where('restaurant_id', $restaurant->id)->exists();
                                @endphp
        
                                <button class="favorites-button" data-restaurant-id="{{ $restaurant->id }}">
                                    <span class="fa fa-star"></span> Remove from Favorites
                                </button>
                            @endauth
                        </div>
                    @endforeach
                @endif
            @endauth
        </div>
    </div>
@endsection

@section('script')
    <script>
        document.querySelectorAll('.favorites-button').forEach(button => {
            button.addEventListener('click', function() {
                removeFromFavorites(this);
            });
        });

        function removeFromFavorites(button) {
            const restaurantId = button.getAttribute('data-restaurant-id');
            const card = document.getElementById(`home-restaurant-${restaurantId}`);

            card.style.display = 'none';

            fetch(`/removeFromFavorites/${restaurantId}`, {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                    'Content-Type': 'application/json'
                }
            })
            .catch(error => console.error('Error:', error));
        }
    </script>
@endsection
