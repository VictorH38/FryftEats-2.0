@extends('layouts/main')

@section('description', "Explore the best eateries around USC with FryftEats! Search and find restaurants by price, rating, and more. Ideal for USC students seeking convenient and delightful dining experiences within the Fryft Zone.")
@section('title', 'Search')

@section('main')
    <div class="search-container">
        <form action="{{ route('search.index') }}" method="get">
            <input type="text" id="search-bar" name="restaurant" placeholder="Search for restaurants..." required>
            <select name="price" id="price">
                <option value="0">All Prices</option>
                <option value="1">$</option>
                <option value="2">$$</option>
                <option value="3">$$$</option>
                <option value="4">$$$$</option>
            </select>
            <select name="sort_by" id="sort-by">
                <option value="best_match">Best Match</option>
                <option value="rating">Rating</option>
                <option value="review_count">Review Count</option>
                <option value="distance">Distance</option>
            </select>
            <button type="submit" id="search-button">Search</button>
        </form>
    </div>

    <div id="search-text">
        @if(request('restaurant'))
            <h2>Search results for "{{ request('restaurant') }}"</h2>
        @else
            <h2>Search to find restaurants</h2>
        @endif
    </div>

    @if($results)
        <div class="restaurant-grid">
            @foreach($results as $restaurant)
                <div class="card">
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

                        <button class="favorites-button" data-restaurant-id="{{ $restaurant->id }}" data-favorite="{{ $isFavorite ? 'true' : 'false' }}">
                            <span class="fa fa-star"></span> {{ $isFavorite ? 'Remove from Favorites' : 'Add to Favorites' }}
                        </button>
                    @endauth
                </div>
            @endforeach
        </div>
    @endif
@endsection

@section('script')
    <script>
        document.querySelectorAll('.favorites-button').forEach(button => {
            button.addEventListener('click', function() {
                toggleFavorite(this);
            });
        });

        function toggleFavorite(button) {
            const restaurantId = button.getAttribute('data-restaurant-id');
            const isFavorite = button.getAttribute('data-favorite') === 'true';

            if (!isFavorite) {
                    button.innerHTML = '<span class="fa fa-star"></span> Remove from Favorites';
                    button.setAttribute('data-favorite', 'true');
            } else {
                button.innerHTML = '<span class="fa fa-star"></span> Add to Favorites';
                button.setAttribute('data-favorite', 'false');
            }

            fetch(`/toggleFavorite/${restaurantId}`, {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                    'Content-Type': 'application/json'
                },
                body: JSON.stringify({ isFavorite: isFavorite })
            })
            .catch(error => console.error('Error:', error));
        }
    </script>
@endsection
