@extends('layouts/main')

@section('description', "Learn more about " . $restaurant->name . " and why it's popular among USC students.")
@section('title', $restaurant->name)

@section('style')
    <style>
        
    </style>
@endsection

@section('main')
    <div class="restaurant-details">
        <div id="restaurant-details-column-1">
            <h2>{{ $restaurant->name }}</h2>

            <img src="{{ $restaurant->image_url ?: asset('images/no-image.jpeg') }}" alt="{{ $restaurant->name }}" class="restaurant-image">
        </div>

        <div id="restaurant-details-column-2">
            <p class="detail"><strong>Address:</strong> {{ $restaurant->address }}</p>
            <p class="detail"><strong>Phone:</strong> {{ $restaurant->phone_number }}</p>
            <p class="detail"><strong>Cuisine:</strong> {{ $restaurant->cuisine }}</p>
            <p class="detail">
                <strong>Rating:</strong>
                <span class="rating">
                    @for($i = 0; $i < floor($restaurant->rating); $i++)
                        <span class="fa fa-star star"></span>
                    @endfor
                    @if($restaurant->rating - floor($restaurant->rating) >= 0.5)
                        <span class="fa fa-star-half-o"></span>
                    @endif
                </span>
            </p>
            <p class="detail"><strong>Price:</strong> <span class="price">{{ $restaurant->price }}</span></p>
            <p class="detail"><strong>More Info:</strong> <a href="{{ $restaurant->url }}" target="_blank">Visit Website</a></p>
        </div>
    </div>
@endsection

@section('script')
    <script>
        
    </script>
@endsection
