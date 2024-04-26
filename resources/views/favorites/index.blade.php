@extends('layouts/main')

@section('description', "Explore your curated list of favorite restaurants with FryftEats! Designed exclusively for USC students, this page highlights your top dining choices within the USC Fryft Zone. Log in to access your personalized list and manage your favorite spots, ensuring your next meal is just what you crave.")
@section('title', 'Favorites')

@section('style')
    <style>
        .restaurant-grid {
            margin: 20px 0px 100px;
        }
    </style>
@endsection

@section('main')
    <div id="favorites-content">
        <div id="user-content">
            @auth
                <h1>Hi, {{ $user->first_name }}!</h1>
        
                @if ($favorites->count() > 0)
                    <h3 class="favorites-message">Here are your favorite restaurants</h3>
                @else
                    <h3 class="favorites-message">You have no favorite restaurants</h3>
                @endif
            @else
                <h3>Login or Sign Up to add restaurants to your favorites list</h3>
            @endauth
        </div>

        <div id="favorite-alert-container"></div>

        <div class="restaurant-grid">
            @auth
                @if ($favorites->count() > 0)
                    @foreach ($favorites as $restaurant)
                        <div id="favorite-restaurant-{{ $restaurant->id }}" class="card" onclick="redirectToRestaurant({{ $restaurant->id }})">
                            <img src="{{ $restaurant->image_url ?: asset('images/no-image.jpeg') }}" alt="{{ $restaurant->name }}" class="restaurant-photo">
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
                            <p class="time-added">Added {{ $restaurant->pivot->created_at->diffForHumans() }}</p>
        
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
        function showAlert(message, type = 'success') {
            const alertContainer = document.getElementById('favorite-alert-container');

            const alertDiv = document.createElement('div');
            alertDiv.className = `alert alert-${type}`;
            alertDiv.textContent = message;

            alertContainer.appendChild(alertDiv);

            setTimeout(() => {
                alertDiv.remove();
            }, 10000);
        }

        document.querySelectorAll('.favorites-button').forEach(button => {
            button.addEventListener('click', function() {
                event.stopPropagation();
                event.preventDefault();
                removeFromFavorites(this);
            });
        });

        function removeFromFavorites(button) {
            const restaurantId = button.getAttribute('data-restaurant-id');

            fetch(`/removeFromFavorites/${restaurantId}`, {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                    'Content-Type': 'application/json'
                }
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    const card = document.getElementById(`favorite-restaurant-${restaurantId}`);
                    if (card) {
                        card.remove();

                        const grid = document.querySelector('.restaurant-grid');
                        grid.style.gridTemplateColumns = `repeat(auto-fill, minmax(20%, 1fr))`;

                        if (!grid.children.length) {
                            const favoritesMessage = document.querySelector('.favorites-message');
                            favoritesMessage.innerHTML = 'You have no favorite restaurants';
                        }
                    }

                    showAlert(data.message, 'success');
                }
            })
            .catch(error => console.error('Error:', error));
        }

        function redirectToRestaurant(restaurantId) {
            window.location.href = `/restaurants/${restaurantId}`;
        }
    </script>
@endsection
