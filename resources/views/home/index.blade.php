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

        <h2 id="about-title">About Us</h2>
        <h5 id="welcome-message">
            Welcome to FryftEats, the premier dining guide designed exclusively for USC students! Whether you’re craving a quick bite between classes or planning a group dinner near campus, FryftEats is your go-to resource for finding the best local eats. Explore a wide range of dining options, comment on your experiences, and save your favorite spots for future visits. Sign in to personalize your food journey and discover new flavors right at your doorstep.
        </h5>

        <div id="fryft-zone-content">
            <h5 id="fryft-zone-message">
                While we strive to keep our listings within the designated Fryft Zone, it's possible that some restaurants may extend beyond these boundaries. If you encounter a restaurant listed outside of the Fryft Zone or notice any inaccurate information, please help us maintain the accuracy of our service by <a href="{{ route('reports.create') }}">reporting it here</a>. Your feedback is invaluable in helping us ensure that FryftEats remains the most reliable and student-friendly dining guide around.
            </h5>
            
            <img id="fryft-zone-image" src="{{ asset("/images/fryft-zone.jpeg") }}" alt="Fryft Zone">
        </div>
    </div>
@endsection
