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
            background-color: #851515;
            color: white;
            box-shadow: 0 2px 15px rgba(0, 0, 0, 0.1);
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
        <div id="login">
            <p id="login-title">Login</p>

            @if(session('loginError'))
                <p id="login-error-message">{{ session('loginError') }}</p>
            @endif

            <form id="login-form" action="{{ route('auth.login') }}" method="post">
                @csrf
                <div class="form-row">
                    <label for="login-username">Username</label>
                    <input type="text" id="login-username" name="username" value="{{ old('username') }}" required>
                </div>

                <div class="form-row">
                    <label for="login-password">Password</label>
                    <input type="password" id="login-password" name="password" required>
                </div>

                <button type="submit" class="sign-in-button"><span class="fa-sign-in"></span> Sign In</button>
            </form>

            <p id="signup-message">
                Don't have an account? <a href="{{ route('auth.showSignupForm') }}">Sign Up</a>
            </p>
        </div>
    </div>
@endsection
