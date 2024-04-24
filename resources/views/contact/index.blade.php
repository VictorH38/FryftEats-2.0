@extends('layouts/main')

@section('description', "Have questions or feedback for FryftEats? Visit our contact page to get in touch! We're here to help USC students enhance their dining experiences and answer any inquiries about our restaurant finder service.")
@section('title', 'Contact')

@section('main')
    <div id="contact">
        <h2 id="contact-us">Contact Us</h2>

        @if(session('message'))
            <div class="alert alert-success">
                {{ session('message') }}
            </div>
        @endif

        @if(session('error'))
            <div class="alert alert-danger">
                {{ session('error') }}
            </div>
        @endif

        <form id="contact-form" action="{{ route('contact.send') }}" method="post">
            @csrf
            <div class="form-section">
                <label for="name">Name:</label>
                <input type="text" id="name" name="name" required>
            </div>
            
            <div class="form-section">
                <label for="email">Email:</label>
                <input type="email" id="email" name="email" required>
            </div>
            
            <div class="form-section">
                <label for="message">Message:</label>
                <textarea id="message" name="message" rows="4" required></textarea>
            </div>
            
            <button id="send-button" type="submit">Send</button>
        </form>
    </div>
@endsection

@section('script')
    <script>
        let send_button = document.getElementById('send-button');
        send_button.addEventListener('click', function() {
            var name = document.getElementById('name').value;
            var email = document.getElementById('email').value;
            var message = document.getElementById('message').value;

            var mailtoLink = 'mailto:victor.h8.2003@gmail.com?subject=' + encodeURIComponent('FryftEats Contact Form') + '&body=' + encodeURIComponent('Name: ' + name + '\nEmail: ' + email + '\nMessage: ' + message);

            window.location.href = mailtoLink;
        });
    </script>
@endsection
