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
    </div>
@endsection
