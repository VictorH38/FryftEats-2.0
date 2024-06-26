@extends('layouts/main')

@section('description', "Report any issues with restaurant listings directly to FryftEats. Help us maintain accurate and relevant information for the best dining choices around USC.")
@section('title', 'Report a Restaurant')

@section('main')
    <div id="report-content">
        <div id="report-form">
            <h3 id="make-report-text">Make a Report</h3>

            @if(session('success'))
                <div class="alert alert-success">
                    {{ session('success') }}
                </div>
            @endif

            <form action="{{ route('reports.store') }}" method="POST">
                @csrf
                <div class="report-row">
                    <label for="restaurant-search">Restaurant:</label>
                    <input type="text" id="restaurant-search" autocomplete="off" placeholder="Start typing a restaurant..." value="{{ $selectedRestaurant ? $selectedRestaurant->name : '' }}" required>
                    <div id="restaurant-list" class="restaurant-dropdown"></div>
                    <input type="hidden" name="restaurant_id" id="selected-restaurant-id" value="{{ $selectedRestaurant ? $selectedRestaurant->id : '' }}">
                </div>

                <div class="report-row">
                    <label for="reason">Reason for report:</label>
                    <select id="reason" id="reason-dropdown" name="reason" required>
                        <option value="" disabled selected>-- Select Reason --</option>
                        <option value="Outside of Fryft zone">Outside of Fryft zone</option>
                        <option value="Inaccurate information">Inaccurate information</option>
                    </select>
                </div>

                <div class="report-row">
                    <label for="notes">Additional notes:</label>
                    <textarea id="notes" name="notes"></textarea>
                </div>
                
                <button id="report-button" type="submit">Submit Report</button>
            </form>
        </div>

        @auth
            <div id="user-reports">
                <h3 id="user-reports-text">Your Reports</h3>

                <div id="report-alert-container"></div>

                <div class="report-list">
                    @foreach($reports as $report)
                        <div class="report">
                            <div class="report-row-1">
                                <p class="report-restaurant">
                                    <a id="restaurant-link-report" href="{{ route('restaurants.show', $report->restaurant->id) }}">{{ $report->restaurant->name }}</a>
                                </p>
                                <p class="report-submitted">Submitted {{ $report->created_at->diffForHumans() }}</p>
                            </div>
    
                            <div class="report-row-2">
                                <p id="report-reason-{{ $report->id }}">Reason: {{ $report->reason }}</p>
                                <p id="report-status-{{ $report->id }}">Status: <span class="status-{{ strtolower($report->status) }}">{{ $report->status }}</span></p>
                            </div>

                            <div class="report-row-3">
                                <p id="report-notes-{{ $report->id }}" class="report-notes">{{ $report->notes }}</p>
    
                                <div class="modify-report-buttons">
                                    <button id="edit-report-button-{{ $report->id }}" class="edit-report-button" onclick="editReport({{ $report->id }})">Edit</button>

                                    <button id="delete-report-button-{{ $report->id }}" class="delete-report-button" data-report-id="{{ $report->id }}" onclick="deleteReport(this)">Delete</button>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        @endauth
    </div>
@endsection

@section('script')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const restaurantSearch = document.getElementById('restaurant-search');
            const restaurantList = document.getElementById('restaurant-list');
            const selectedRestaurantId = document.getElementById('selected-restaurant-id');
            const restaurants = @json($restaurants);

            function updateRestaurantList(filter = '') {
                restaurantList.innerHTML = '';
                const filteredRestaurants = restaurants.filter(restaurant =>
                    restaurant.name.toLowerCase().includes(filter.toLowerCase())
                );

                filteredRestaurants.forEach(restaurant => {
                    const div = document.createElement('div');
                    div.textContent = restaurant.name;
                    div.className = 'restaurant-item';
                    div.dataset.id = restaurant.id;
                    restaurantList.appendChild(div);
                });
            }

            restaurantSearch.addEventListener('focus', function() {
                updateRestaurantList(this.value);
                restaurantList.style.display = 'block';
            });

            restaurantSearch.addEventListener('input', function() {
                updateRestaurantList(this.value);
            });

            document.addEventListener('click', function(event) {
                if (!restaurantList.contains(event.target) && !restaurantSearch.contains(event.target)) {
                    restaurantList.style.display = 'none';
                }
            });

            restaurantList.addEventListener('click', function(event) {
                if (event.target.className === 'restaurant-item') {
                    restaurantSearch.value = event.target.textContent;
                    selectedRestaurantId.value = event.target.dataset.id;
                    restaurantList.style.display = 'none';
                }
            });
        });

        function showAlert(message, type = 'success') {
            const alertContainer = document.getElementById('report-alert-container');

            const alertDiv = document.createElement('div');
            alertDiv.className = `alert alert-${type}`;
            alertDiv.textContent = message;

            alertContainer.appendChild(alertDiv);

            setTimeout(() => {
                alertDiv.remove();
            }, 10000);
        }

        function editReport(reportId) {
            let reportNotesElement = document.getElementById(`report-notes-${reportId}`);
            let currentNotes = reportNotesElement.innerText;
            reportNotesElement.innerHTML = `<textarea id="edit-report-notes-field-${reportId}" class="edit-report-notes-field">${currentNotes}</textarea>`;

            let editReportButton = document.getElementById(`edit-report-button-${reportId}`);
            editReportButton.onclick = () => saveReport(reportId);
            editReportButton.innerHTML = "Save";
        }

        function saveReport(reportId) {
            let editedNotesElement = document.getElementById(`edit-report-notes-field-${reportId}`);
            let editedNotes = editedNotesElement.value;

            fetch(`/reports/${reportId}`, {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                    'Content-Type': 'application/json'
                },
                body: JSON.stringify({ notes: editedNotes, _method: 'PATCH' })
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    let reportNotesElement = document.getElementById(`report-notes-${reportId}`);
                    reportNotesElement.innerHTML = `${editedNotes}`;

                    let editReportButton = document.getElementById(`edit-report-button-${reportId}`);
                    editReportButton.onclick = () => editReport(reportId);
                    editReportButton.innerHTML = "Edit";

                    showAlert(data.message, 'success');
                } else {
                    showAlert(data.message, 'danger');
                }
            })
            .catch(error => console.error('Error:', error));
        }

        function deleteReport(button) {
            const reportId = button.getAttribute('data-report-id');

            fetch(`/reports/${reportId}`, {
                method: 'DELETE',
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                    'Content-Type': 'application/json'
                },
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    button.closest('.report').style.display = 'none';
                    showAlert(data.message, 'success');
                } else {
                    showAlert(data.message, 'danger');
                }
            })
            .catch(error => console.error('Error:', error));
        }
    </script>
@endsection
