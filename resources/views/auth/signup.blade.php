@extends('layouts/main')

@section('description', "Log in to FryftEats to personalize your restaurant finding experience! Access your favorite restaurants, save new ones, and explore the best of USC's Fryft Zone dining with just a few clicks.")
@section('title', 'Login')

@section('style')
    <style>
        #content {
            margin: 100px 70px;
            padding: 50px 70px;
            display: flex;
            justify-content: center;
            background-color: lightgray;
            border-radius: 30px;
        }

        .form-row {
            margin: 0px 0px 30px;
            width: 100%;
            display: flex;
            flex-direction: column;
        }

        .form-row label {
            margin: 7px 0px;
            color: black;
        }

        .form-row input {
            height: 30px;
        }

        button {
            cursor: pointer;
        }
    </style>
@endsection

@section('main')
    <div id="content">
        <div id="signup">
            <p id="signup-title">Sign Up</p>

            @if(session('signupError'))
                <p id="signup-error-message">{{ session('signupError') }}</p>
            @endif

            <form id="signup-form" action="{{ route('auth.signup') }}" method="post">
                @csrf
                <div class="form-row">
                    <label for="first-name">First Name</label>
                    <input type="text" id="first-name" name="firstName" value="{{ old('firstName') }}" required>
                </div>

                <div class="form-row">
                    <label for="last-name">Last Name</label>
                    <input type="text" id="last-name" name="lastName" value="{{ old('lastName') }}" required>
                </div>

                <div class="form-row">
                    <label for="email">Email</label>
                    <input type="email" id="email" name="email" value="{{ old('email') }}" required>

                    @error('email')
                        <p class="error-message">{{ $message }}</p>
                    @enderror
                </div>

                <div class="form-row">
                    <label for="signup-username">Username</label>
                    <input type="text" id="signup-username" name="username" value="{{ old('username') }}" required>

                    @error('username')
                        <p class="error-message">{{ $message }}</p>
                    @enderror
                </div>

                <div class="form-row">
                    <label for="signup-password">Password</label>
                    <input type="password" id="signup-password" name="password" required>

                    @error('password')
                        <p class="error-message">{{ $message }}</p>
                    @enderror
                </div>

                <div class="form-row">
                    <label for="confirm-password">Confirm Password</label>
                    <input type="password" id="confirm-password" name="password_confirmation" required>
                </div>

                <button type="submit" class="create-account-button"><span class="fa-user-plus"></span> Create Account</button>
            </form>

            <p id="login-message">
                Already have an account? <a href="{{ route('auth.showLoginForm') }}">Login</a>
            </p>
        </div>
    </div>
@endsection
