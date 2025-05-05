<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard - Events</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600&display=swap" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" rel="stylesheet">
    <link rel="stylesheet" href="dashboard-event.css">
    <style>
        .close-edit {
            position: absolute;
            top: 15px;
            right: 20px;
            font-size: 24px;
            cursor: pointer;
            color: #aaa;
        }

        .close-edit:hover {
            color: #000;
        }

        .content {
            margin-left: 250px;
            padding: 20px;
            width: calc(100% - 250px);
            height: 100vh;
            background-image: url('images/pexels-scottwebb-3255761.jpg');
            background-size: cover;
            background-position: center;
            background-repeat: no-repeat;
        }

        .action-buttons {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin: 15px 0;
        }

        .btn-action {
            display: inline-flex;
            align-items: center;
            background-color: #00A2E8;
            color: #fff;
            border: none;
            padding: 10px 15px;
            border-radius: 5px;
            cursor: pointer;
            font-size: 15px;
            transition: all 0.3s ease-in-out;
        }

        .btn-action i {
            margin-right: 8px;
        }

        .btn-action:hover {
            background-color: #0086C1;
            transform: scale(1.05);
        }

        .top-content {
            display: flex;
            align-items: center;
            gap: 20px;
        }

        .content h1 {
            font-size: 38px;
            color: #333;
            margin-right: 80px;
        }

        .search {
            border: 1px solid #ddd;
            padding: 5px 15px;
            border-radius: 20px;
            width: 400px;
            height: 35px;
        }

        .user-info {
            display: flex;
            align-items: center;
            margin-left: auto;
        }

        .user-info i {
            font-size: 16px;
            color: #333;
            margin-right: 10px;
        }

        .user-info span {
            font-size: 16px;
            color: #333;
        }
    </style>
</head>

<body>
    <!-- Sidebar -->
    <div class="sidebar">
        <div class="logo">TRIPPED</div>
        <a href="dashboard.php"><i class="fas fa-house-user"></i> Dashboard</a>
        <a href="#"><i class="fas fa-calendar-day"></i> Bookings</a>
        <a href="#"><i class="fas fa-bus"></i> Transports</a>
        <a href="#"><i class="fas fa-users"></i> Customers</a>
        <a href="#"><i class="fas fa-star-half-alt"></i> Reviews</a>
        <a href="#"><i class="fas fa-route"></i> Events</a>
        <a href="#"><i class="fa-solid fa-gear"></i> Settings</a>
        <a href="event-front.php" class="logout"><i class="fas fa-sign-out-alt"></i> Logout</a>
    </div>

    <!-- Main Content -->
    <div class="content">
        <div class="top-content">
            <h1>Events</h1>
            <input type="text" class="search" placeholder="Search...">
            <div class="user-info">
                <i class="fas fa-user"></i>
                <span>My account</span>
            </div>
        </div>

        <div class="action-buttons">
            <a href="dashboard-reservations.php">
                <button class="btn-action">
                    <i class="fas fa-calendar-alt"></i> Reservations
                </button>
            </a>
            <button class="btn-action new-event">
                <i class="fas fa-plus"></i> New Event
            </button>
        </div>

        <!-- Event Table -->
        <table>
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Preview</th>
                    <th>Tour Name</th>
                    <th>Date</th>
                    <th>Price</th>
                    <th>Duration</th>
                    <th>Location</th>
                    <th>Seats</th>
                    <th>Status</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody id="event-table-body">
                <?php include '../controller/Afficher.php'; ?>
            </tbody>
        </table>
    </div>

    <!-- Add Event Modal -->
    <div id="event-modal" class="modal">
        <div class="modal-content">
            <span class="close-btn">&times;</span>
            <h2>Add a new event</h2>
            <form id="event-form" action="../controller/Ajouter.php" method="POST" enctype="multipart/form-data">
                <label for="event-name">Name</label>
                <input type="text" id="eventName" name="eventName" placeholder="Event Name" onkeyup="validateName()"
                    required>
                <small id="nameError" class="error-msg"></small>

                <label for="event-date">Date</label>
                <input type="date" id="eventDate" name="eventDate" onchange="validateDate()" required>
                <small id="dateError" class="error-msg"></small>

                <label for="event-price">Price</label>
                <input type="text" id="eventPrice" name="eventPrice" placeholder="Price in TND"
                    onkeyup="validatePrice()" required>
                <small id="priceError" class="error-msg"></small>

                <label for="event-duration">Duration</label>
                <input type="text" id="eventDuration" name="eventDuration" placeholder="Duration (e.g. 5 Days)"
                    onkeyup="validateDuration()" required>
                <small id="durationError" class="error-msg"></small>

                <label for="event-location">Location</label>
                <input type="text" id="eventLocation" name="eventLocation" placeholder="Location"
                    onkeyup="validateLocation()" required>
                <small id="locationError" class="error-msg"></small>

                <label for="event-status">Status</label>
                <select id="event-status" name="event-status" required>
                    <option value="active">Active</option>
                    <option value="paused">Paused</option>
                </select>

                <label for="event-capacity">Number of Seats</label>
                <input type="number" id="eventCapacity" name="eventCapacity" placeholder="Seats"
                    onkeyup="validateSeats()" required>
                <small id="seatsError" class="error-msg"></small>

                <label for="event-preview">Preview</label>
                <input type="file" id="event-preview" name="event-preview" accept="image/*" required>

                <button type="submit" class="submit-event">Add Event</button>
            </form>
        </div>
    </div>

    <!-- Edit Event Modal -->
    <div id="edit-modal" class="modal">
        <div class="modal-content">
            <span class="close-edit">&times;</span>
            <h2>Edit Event</h2>
            <form id="edit-form" action="../controller/Modifier.php" method="POST" enctype="multipart/form-data">
                <input type="hidden" id="editId" name="id">

                <label for="editName">Name</label>
                <input type="text" id="editName" name="eventName" onkeyup="validateEditName()" required>
                <small id="editNameError" class="error-msg"></small>

                <label for="editDate">Date</label>
                <input type="date" id="editDate" name="eventDate" onchange="validateEditDate()" required>
                <small id="editDateError" class="error-msg"></small>

                <label for="editPrice">Price</label>
                <input type="text" id="editPrice" name="eventPrice" onkeyup="validateEditPrice()" required>
                <small id="editPriceError" class="error-msg"></small>

                <label for="editDuration">Duration</label>
                <input type="text" id="editDuration" name="eventDuration" onkeyup="validateEditDuration()" required>
                <small id="editDurationError" class="error-msg"></small>

                <label for="editLocation">Location</label>
                <input type="text" id="editLocation" name="eventLocation" onkeyup="validateEditLocation()" required>
                <small id="editLocationError" class="error-msg"></small>

                <label for="editStatus">Status</label>
                <select id="editStatus" name="eventStatus" required>
                    <option value="active">Active</option>
                    <option value="paused">Paused</option>
                </select>

                <label for="editSeats">Number of Seats</label>
                <input type="number" id="editEventCapacity" name="eventCapacity" onkeyup="validateEditSeats()"
                    required>
                <small id="editSeatsError" class="error-msg"></small>

                <label for="editPreview">Change Preview (optional)</label>
                <input type="file" id="editPreview" name="eventPreview" accept="image/*">

                <button type="submit" class="submit-event">Update Event</button>
            </form>
        </div>
    </div>

    <div id="confirmModal" class="modal">
        <div class="modal-content">
            <span class="close-btn" id="closeConfirmModal">&times;</span>
            <h2>Confirmation</h2>
            <p>Are you sure you want to delete this event?</p>
            <div style="margin-top: 20px; display: flex; justify-content: flex-end;">
                <button id="confirmDeleteBtn" class="submit-event" style="margin-right: 10px;">Yes, Delete</button>
                <button id="cancelDeleteBtn" class="submit-event" style="background-color: #aaa;">Cancel</button>
            </div>
        </div>
    </div>

    <!-- Script -->
    <script>
        // Open/close Add Event Modal
        const modal = document.getElementById("event-modal");
        const newEventBtn = document.querySelector(".new-event");
        const closeBtn = document.querySelector(".close-btn");

        newEventBtn.onclick = () => modal.style.display = "block";
        closeBtn.onclick = () => modal.style.display = "none";
        window.onclick = (e) => { if (e.target == modal) modal.style.display = "none"; };

        // Form validation
        function validateName() {
            const name = document.getElementById("eventName").value;
            const error = document.getElementById("nameError");
            if (name.length < 5) {
                error.textContent = "The name must be at least 5 characters long.";
            } else {
                error.textContent = "";
            }
        }

        function validateDate() {
            const date = document.getElementById("eventDate").value;
            const error = document.getElementById("dateError");
            if (!date) {
                error.textContent = "Please select a date.";
            } else {
                error.textContent = "";
            }
        }

        function validatePrice() {
            const price = document.getElementById("eventPrice").value;
            const error = document.getElementById("priceError");
            if (!/^\d+(\.\d{1,2})?$/.test(price)) {
                error.textContent = "Invalid price. Expected format: a number (e.g., 100 or 100.50).";
            } else {
                error.textContent = "";
            }
        }

        function validateDuration() {
            const duration = document.getElementById("eventDuration").value;
            const error = document.getElementById("durationError");
            if (!/^\d+\s+(Days|days)$/.test(duration)) {
                error.textContent = "Invalid duration. Expected format: e.g., 5 Days.";
            } else {
                error.textContent = "";
            }
        }

        function validateLocation() {
            const location = document.getElementById("eventLocation").value.trim();
            const error = document.getElementById("locationError");
            if (location.length < 7) {
                error.textContent = "The location must be at least 7 characters long.";
            } else if (!location.includes(",")) {
                error.textContent = "The location must contain a comma (e.g., City, Country).";
            } else {
                error.textContent = "";
            }
        }

        function validateSeats() {
            const value = document.getElementById("eventCapacity").value;
            const error = document.getElementById("seatsError");
            error.textContent = value < 1 ? "Must reserve at least 1 seat." : "";
        }

        function validateEditName() {
            const name = document.getElementById("editName").value;
            const error = document.getElementById("editNameError");
            if (name.length < 5) {
                error.textContent = "The name must be at least 5 characters long.";
            } else {
                error.textContent = "";
            }
        }

        function validateEditDate() {
            const date = document.getElementById("editDate").value;
            const error = document.getElementById("editDateError");
            if (!date) {
                error.textContent = "Please select a date.";
            } else {
                error.textContent = "";
            }
        }

        function validateEditPrice() {
            const price = document.getElementById("editPrice").value;
            const error = document.getElementById("editPriceError");
            if (!/^\d+(\.\d{1,2})?$/.test(price)) {
                error.textContent = "Invalid price. Expected format: a number (e.g., 100 or 100.50).";
            } else {
                error.textContent = "";
            }
        }

        function validateEditDuration() {
            const duration = document.getElementById("editDuration").value;
            const error = document.getElementById("editDurationError");
            if (!/^\d+\s+(Days|days)$/.test(duration)) {
                error.textContent = "Invalid duration. Expected format: e.g., 5 Days.";
            } else {
                error.textContent = "";
            }
        }

        function validateEditSeats() {
            const value = document.getElementById("editEventCapacity").value;
            const error = document.getElementById("editSeatsError");
            error.textContent = value < 1
                ? "Must reserve at least 1 seat."
                : "";
        }

        function validateEditLocation() {
            const location = document.getElementById("editLocation").value.trim();
            const error = document.getElementById("editLocationError");
            if (location.length < 7) {
                error.textContent = "The location must be at least 7 characters long.";
            } else if (!location.includes(",")) {
                error.textContent = "The location must contain a comma (e.g., City, Country).";
            } else {
                error.textContent = "";
            }
        }
        // Load events dynamically
        function loadEvents() {
            fetch('../controller/Afficher.php')
                .then(response => response.text())
                .then(data => {
                    document.getElementById("event-table-body").innerHTML = data;
                })
                .catch(error => console.error('Failed to load events:', error));
        }

        document.addEventListener("DOMContentLoaded", loadEvents);

        // Delete event
        function initialiserSuppression() {
            const table = document.querySelector('table');
            const confirmModal = document.getElementById('confirmModal');
            const confirmDeleteBtn = document.getElementById('confirmDeleteBtn');
            const cancelDeleteBtn = document.getElementById('cancelDeleteBtn');
            const closeConfirmModal = document.getElementById('closeConfirmModal');

            let currentRow;
            let currentId;

            table.addEventListener('click', function (e) {
                if (e.target.closest('.delete-btn')) {
                    const button = e.target.closest('.delete-btn');
                    currentRow = button.closest('tr');
                    currentId = button.dataset.id;

                    // Ouvre le pop-up de confirmation
                    confirmModal.style.display = 'block';
                }
            });

            // Si l'utilisateur confirme
            confirmDeleteBtn.addEventListener('click', function () {
                fetch('../controller/Supprimer.php', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/x-www-form-urlencoded',
                    },
                    body: 'id=' + encodeURIComponent(currentId)
                })
                    .then(response => response.json())
                    .then(data => {
                        if (data.success) {
                            currentRow.style.transition = 'opacity 0.3s ease';
                            currentRow.style.opacity = '0';
                            setTimeout(() => currentRow.remove(), 300);
                        } else {
                            alert("Error : " + data.error);
                        }
                    })
                    .catch(error => {
                        console.error('AJAX Error :', error);
                        alert("An error occurred while deleting the event.");
                    });

                confirmModal.style.display = 'none';
            });

            // Si l'utilisateur annule
            cancelDeleteBtn.addEventListener('click', function () {
                confirmModal.style.display = 'none';
            });

            // Si l'utilisateur clique sur la croix
            closeConfirmModal.addEventListener('click', function () {
                confirmModal.style.display = 'none';
            });

            // Fermer aussi si on clique en dehors de la modal
            window.addEventListener('click', function (e) {
                if (e.target == confirmModal) {
                    confirmModal.style.display = 'none';
                }
            });
        }

        document.addEventListener('DOMContentLoaded', initialiserSuppression);

        // Open and fill the edit modal
        document.addEventListener("DOMContentLoaded", function () {
            const editModal = document.getElementById("edit-modal");
            const closeEditBtn = document.querySelector(".close-edit");

            document.querySelector("table").addEventListener("click", function (e) {
                if (e.target.closest('.edit-btn')) {
                    const row = e.target.closest("tr");
                    const cells = row.getElementsByTagName("td");

                    document.getElementById("editId").value = cells[0].textContent;
                    document.getElementById("editName").value = cells[2].textContent;
                    document.getElementById("editDate").value = cells[3].textContent;
                    document.getElementById("editPrice").value = cells[4].textContent;
                    document.getElementById("editDuration").value = cells[5].textContent;
                    document.getElementById("editLocation").value = cells[6].textContent;
                    document.getElementById("editEventCapacity").value = cells[7].textContent;
                    document.getElementById("editStatus").value = cells[8].textContent;

                    editModal.style.display = "block";
                }
            });

            closeEditBtn.onclick = () => editModal.style.display = "none";
            window.onclick = (e) => {
                if (e.target == editModal) {
                    editModal.style.display = "none";
                }
            };
        });

        // Filtre les lignes du <tbody> en fonction du terme
        function filterRows(tbody, searchTerm) {
            const term = searchTerm.toLowerCase().trim();
            Array.from(tbody.rows).forEach(row => {
                // row.textContent récupère le texte de toutes les <td> de la ligne
                const text = row.textContent.toLowerCase();
                row.style.display = text.includes(term) ? '' : 'none';
            });
        }

        // Initialise la recherche : on passe le sélecteur de l'input et du tbody
        function setupTableSearch(inputSelector, tbodySelector) {
            const input = document.querySelector(inputSelector);
            const tbody = document.querySelector(tbodySelector);

            input.addEventListener('input', () => {
                filterRows(tbody, input.value);
            });
        }

        // On lance quand tout le DOM est chargé
        document.addEventListener('DOMContentLoaded', () => {
            setupTableSearch('.search', '#event-table-body');
        });

        function initAddEventAjax() {
            const addForm = document.getElementById('event-form');
            const addModal = document.getElementById('event-modal');

            // utility toast
            function showToast(message) {
                const toast = document.createElement('div');
                toast.textContent = message;
                Object.assign(toast.style, {
                    position: 'fixed',
                    bottom: '20px',
                    left: '50%',
                    transform: 'translateX(-50%)',
                    background: 'rgba(0,0,0,0.8)',
                    color: 'white',
                    padding: '10px 20px',
                    borderRadius: '5px',
                    zIndex: 10000
                });
                document.body.appendChild(toast);
                setTimeout(() => toast.remove(), 3000);
            }

            addForm.addEventListener('submit', function (e) {
                e.preventDefault();                // stop classique
                const fd = new FormData(addForm);

                fetch('../controller/Ajouter.php', {
                    method: 'POST',
                    body: fd
                })
                    .then(res => res.json())
                    .then(json => {
                        if (json.success) {
                            showToast('✅ Event added successfully');
                            addModal.classList.remove('open');  // ou style.display='none'
                            addForm.reset();
                            loadEvents();                       // ta fonction AJAX existante
                        } else {
                            showToast('❌ ' + (json.error || 'Unknown error'));
                        }
                    })
                    .catch(err => {
                        console.error(err);
                        showToast('❌ Server error');
                    });
            });
        }

        // appelle la fonction au chargement
        document.addEventListener('DOMContentLoaded', () => {
            initAddEventAjax();
        });
    </script>
</body>

</html>