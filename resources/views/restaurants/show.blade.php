@extends('layouts/main')

@section('description', "Learn more about " . $restaurant->name . " and why it's popular among USC students.")
@section('title', $restaurant->name)

@section('main')
    <div id="restaurant-details-content">
        <div class="restaurant-details">
            <div id="restaurant-details-column-1">
                <h2>{{ $restaurant->name }}</h2>

                <img src="{{ $restaurant->image_url ?: asset('images/no-image.jpeg') }}" alt="{{ $restaurant->name }}" class="restaurant-image">
            </div>

            <div id="restaurant-details-column-2">
                <div id="restaurant-info">
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

                <div id="restaurant-buttons">
                    <button id="lyft-button" class="restaurant-button">Download our app to request a Lyft</button>

                    <a id="restaurant-report-button" class="restaurant-button" href="{{ route('reports.create', ['restaurant_id' => $restaurant->id]) }}">Report</a>
                </div>
            </div>
        </div>

        <div class="comments-section">
            <h5>Comments</h5>

            @if(session('success'))
                <div class="alert alert-success">
                    {{ session('success') }}
                </div>
            @endif

            @auth
                <form id="comment-form" action="{{ route('comments.store', $restaurant->id) }}" method="POST">
                    @csrf
                    <textarea id="comment-field" name="body" placeholder="Write a comment..." required></textarea>
                    <button id="comment-button" type="submit">Post</button>
                </form>
            @endauth

            <div id="comment-alert-container"></div>
        
            <div class="comment-list">
                @foreach($restaurant->comments as $comment)
                    <div class="comment">
                        <div class="comment-row-1">
                            <p class="comment-username">{{ $comment->user->username }}</p>
                            <p>{{ $comment->created_at->diffForHumans() }}</p>
                        </div>

                        <div class="comment-row-2">
                            <p id="comment-text-{{ $comment->id }}" class="comment-text">{{ $comment->body }}</p>

                            @if (Auth::id() == $comment->user_id)
                                <div class="modify-comment-buttons">
                                    <button id="edit-comment-button-{{ $comment->id }}" class="edit-comment-button" onclick="editComment({{ $comment->id }})">Edit</button>

                                    <button id="delete-comment-button-{{ $comment->id }}" class="delete-comment-button" data-comment-id="{{ $comment->id }}" onclick="deleteComment(this)">Delete</button>
                                </div>
                            @endif
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </div>
@endsection

@section('script')
    <script>
        function showAlert(message, type = 'success') {
            const alertContainer = document.getElementById('comment-alert-container');

            const alertDiv = document.createElement('div');
            alertDiv.className = `alert alert-${type}`;
            alertDiv.textContent = message;

            alertContainer.appendChild(alertDiv);

            setTimeout(() => {
                alertDiv.remove();
            }, 10000);
        }

        function editComment(commentId) {
            let commentTextElement = document.getElementById(`comment-text-${commentId}`);
            let currentText = commentTextElement.innerText;
            commentTextElement.innerHTML = `<textarea id="edit-field-${commentId}" class="edit-field">${currentText}</textarea>`;

            let editCommentButton = document.getElementById(`edit-comment-button-${commentId}`);
            editCommentButton.onclick = () => saveComment(commentId);
            editCommentButton.innerHTML = "Save";
        }

        function saveComment(commentId) {
            let editedTextElement = document.getElementById(`edit-field-${commentId}`);
            let editedText = editedTextElement.value;

            fetch(`/comments/${commentId}`, {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                    'Content-Type': 'application/json'
                },
                body: JSON.stringify({ body: editedText, _method: 'PATCH' })
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    let commentTextElement = document.getElementById(`comment-text-${commentId}`);
                    commentTextElement.innerHTML = `${editedText}`;

                    let editCommentButton = document.getElementById(`edit-comment-button-${commentId}`);
                    editCommentButton.onclick = () => editComment(commentId);
                    editCommentButton.innerHTML = "Edit";

                    showAlert(data.message, 'success');
                } else {
                    showAlert(data.message, 'danger');
                }
            })
            .catch(error => console.error('Error:', error));
        }

        function deleteComment(button) {
            const commentId = button.getAttribute('data-comment-id');

            fetch(`/comments/${commentId}`, {
                method: 'DELETE',
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                    'Content-Type': 'application/json'
                },
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    button.closest('.comment').style.display = 'none';
                    showAlert(data.message, 'success');
                } else {
                    showAlert(data.message, 'danger');
                }
            })
            .catch(error => console.error('Error:', error));
        }
    </script>
@endsection
