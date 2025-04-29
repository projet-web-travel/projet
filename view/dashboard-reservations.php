<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard - Reservations</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600&display=swap" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" rel="stylesheet">
    <!-- Reuse the same CSS as events -->
    <link rel="stylesheet" href="dashboard-reservation.css">
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
        <a href="dashboard-event.php"><i class="fas fa-route"></i> Events</a>
        <a href="#"><i class="fa-solid fa-gear"></i> Settings</a>
        <a href="event-front.php" class="logout"><i class="fas fa-sign-out-alt"></i> Logout</a>
    </div>

    <!-- Main Content -->
    <div class="content">
        <div class="top-content">
            <h1>Reservations</h1>
            <input type="text" class="search" placeholder="Search...">
            <div class="user-info">
                <i class="fas fa-user"></i>
                <span>My account</span>
            </div>
        </div>

        <div class="action-buttons">
            <a href="dashboard-event.php">
                <button class="btn-action">
                    <i class="fa-solid fa-arrow-left"></i> Return
                </button>
            </a>
            <button class="btn-action new-reservation">
                <i class="fas fa-plus"></i> New Reservation
            </button>
        </div>

        <!-- Reservation Table -->
        <table>
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Event ID</th>
                    <th>Event Name</th>
                    <th>Client Name</th>
                    <th>Email</th>
                    <th>Telephone</th>
                    <th>Seats</th>
                    <th>Reservation Date</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody id="reservation-table-body">
                <?php include '../controller/AfficherReservation.php'; ?>
            </tbody>
        </table>
    </div>

    <!-- Add Reservation Modal -->
    <div id="reservation-modal" class="modal">
        <div class="modal-content">
            <span class="close-btn">&times;</span>
            <h2>Add a new reservation</h2>
            <form id="reservation-form" action="../controller/AjouterReservation.php" method="POST">
                <label for="reservationName">Name</label>
                <input type="text" id="reservationName" name="clientName" placeholder="Client Name"
                    onkeyup="validateReservationName()" required>
                <small id="reservationNameError" class="error-msg"></small>

                <label for="reservationEmail">Email</label>
                <input type="email" id="reservationEmail" name="clientEmail" placeholder="Email Address"
                    onkeyup="validateReservationEmail()" required>
                <small id="reservationEmailError" class="error-msg"></small>

                <label for="reservationPhone">Telephone</label>
                <input type="text" id="reservationPhone" name="clientPhone" placeholder="Telephone Number"
                    onkeyup="validateReservationPhone()">
                <small id="reservationPhoneError" class="error-msg"></small>

                <label for="reservationSeats">Number of Seats</label>
                <input type="number" id="reservationSeats" name="numSeats" placeholder="Seats"
                    onkeyup="validateReservationSeats()" required>
                <small id="reservationSeatsError" class="error-msg"></small>

                <label for="reservationDate">Reservation Date</label>
                <input type="date" id="reservationDate" name="reservationDate" onchange="validateReservationDate()"
                    required>
                <small id="reservationDateError" class="error-msg"></small>

                <label for="reservationEventId">Event ID</label>
                <input type="number" id="reservationEventId" name="eventId" placeholder="Event ID"
                    onkeyup="validateReservationEventId()" required>
                <small id="reservationEventIdError" class="error-msg"></small>

                <button type="submit" class="submit-reservation">Add Reservation</button>
            </form>
        </div>
    </div>

    <!-- Edit Reservation Modal -->
    <div id="edit-reservation-modal" class="modal">
        <div class="modal-content">
            <span class="close-edit">&times;</span>
            <h2>Edit Reservation</h2>
            <form id="edit-reservation-form" action="../controller/ModifierReservation.php" method="POST">
                <input type="hidden" id="editReservationId" name="id">

                <label for="editReservationName">Client Name</label>
                <input type="text" id="editReservationName" name="clientName" onkeyup="validateEditReservationName()"
                    required>
                <small id="editReservationNameError" class="error-msg"></small>

                <label for="editReservationEmail">Email</label>
                <input type="email" id="editReservationEmail" name="clientEmail"
                    onkeyup="validateEditReservationEmail()" required>
                <small id="editReservationEmailError" class="error-msg"></small>

                <label for="editReservationPhone">Telephone</label>
                <input type="text" id="editReservationPhone" name="clientPhone"
                    onkeyup="validateEditReservationPhone()">
                <small id="editReservationPhoneError" class="error-msg"></small>

                <label for="editReservationSeats">Number of Seats</label>
                <input type="number" id="editReservationSeats" name="numSeats" onkeyup="validateEditReservationSeats()"
                    required>
                <small id="editReservationSeatsError" class="error-msg"></small>

                <label for="editReservationDate">Reservation Date</label>
                <input type="date" id="editReservationDate" name="reservationDate"
                    onchange="validateEditReservationDate()" required>
                <small id="editReservationDateError" class="error-msg"></small>

                <label for="editReservationEventId">Event ID</label>
                <input type="number" id="editReservationEventId" name="eventId" placeholder="Event ID"
                    onkeyup="validateEditReservationEventId()" required>
                <small id="editReservationEventIdError" class="error-msg"></small>

                <button type="submit" class="submit-reservation">Update Reservation</button>
            </form>
        </div>
    </div>

    <!-- Delete Confirmation Modal -->
    <div id="confirmReservationModal" class="modal">
        <div class="modal-content">
            <span class="close-btn" id="closeConfirmReservationModal">&times;</span>
            <h2>Confirmation</h2>
            <p>Are you sure you want to delete this reservation?</p>
            <div style="margin-top: 20px; display: flex; justify-content: flex-end;">
                <button id="confirmDeleteReservationBtn" class="submit-reservation" style="margin-right: 10px;">Yes,
                    Delete</button>
                <button id="cancelDeleteReservationBtn" class="submit-reservation"
                    style="background-color: #aaa;">Cancel</button>
            </div>
        </div>
    </div>

    <!-- Script -->
    <script>
        // Open/close Add Reservation Modal
        const reservationModal = document.getElementById("reservation-modal");
        const newReservationBtn = document.querySelector(".new-reservation");
        const closeReservationBtn = document.querySelector("#reservation-modal .close-btn");

        newReservationBtn.onclick = () => {
            // compute YYYY-MM-DD
            const today = new Date().toISOString().split('T')[0];
            // set it on the add-form date input
            document.getElementById('reservationDate').value = today;
            // show modal
            reservationModal.style.display = 'block';
        };

        closeReservationBtn.onclick = () => reservationModal.style.display = "none";
        window.onclick = (e) => { if (e.target == reservationModal) reservationModal.style.display = "none"; };

        // Validation functions
        function validateReservationName() {
            const value = document.getElementById("reservationName").value.trim();
            const error = document.getElementById("reservationNameError");
            error.textContent = value.length < 2 ? "Name must be at least 2 characters." : "";
        }
        function validateReservationEmail() {
            const value = document.getElementById("reservationEmail").value;
            const error = document.getElementById("reservationEmailError");
            error.textContent = /^[^@\s]+@[^@\s]+\.[^@\s]+$/.test(value) ? "" : "Invalid email address.";
        }
        function validateReservationPhone() {
            const value = document.getElementById("reservationPhone").value;
            const error = document.getElementById("reservationPhoneError");
            error.textContent = value && !/^\+?[0-9\s\-]{7,}$/.test(value) ? "Invalid phone number." : "";
        }
        function validateReservationSeats() {
            const value = document.getElementById("reservationSeats").value;
            const error = document.getElementById("reservationSeatsError");
            error.textContent = value < 1 ? "Must reserve at least 1 seat." : "";
        }
        function validateReservationDate() {
            const value = document.getElementById("reservationDate").value;
            const error = document.getElementById("reservationDateError");
            error.textContent = value ? "" : "Please select a date.";
        }
        async function validateReservationEventId() {
            const input = document.getElementById("reservationEventId");
            const error = document.getElementById("reservationEventIdError");
            const val = input.value.trim();

            // basic numeric sanity
            if (!/^\d+$/.test(val) || +val < 1) {
                error.textContent = "Invalid event ID.";
                return;
            }

            // check existence on the server
            try {
                const res = await fetch(`../controller/VerifierEventExiste.php?id=${encodeURIComponent(val)}`);
                if (!res.ok) throw new Error("Network error");
                const { exists } = await res.json();
                error.textContent = exists
                    ? ""
                    : "No event found with that ID.";
            } catch (e) {
                console.error(e);
                error.textContent = "Unable to validate event ID right now.";
            }
        }

        // Edit‐form validators
        function validateEditReservationName() {
            const value = document.getElementById("editReservationName").value.trim();
            const error = document.getElementById("editReservationNameError");
            error.textContent = value.length < 2
                ? "Name must be at least 2 characters."
                : "";
        }

        function validateEditReservationEmail() {
            const value = document.getElementById("editReservationEmail").value;
            const error = document.getElementById("editReservationEmailError");
            error.textContent = /^[^@\s]+@[^@\s]+\.[^@\s]+$/.test(value)
                ? ""
                : "Invalid email address.";
        }

        function validateEditReservationPhone() {
            const value = document.getElementById("editReservationPhone").value;
            const error = document.getElementById("editReservationPhoneError");
            error.textContent = value && !/^\+?[0-9\s\-]{7,}$/.test(value)
                ? "Invalid phone number."
                : "";
        }

        function validateEditReservationSeats() {
            const value = document.getElementById("editReservationSeats").value;
            const error = document.getElementById("editReservationSeatsError");
            error.textContent = value < 1
                ? "Must reserve at least 1 seat."
                : "";
        }

        function validateEditReservationDate() {
            const value = document.getElementById("editReservationDate").value;
            const error = document.getElementById("editReservationDateError");
            error.textContent = value
                ? ""
                : "Please select a date.";
        }

        async function validateEditReservationEventId() {
            const input = document.getElementById("editReservationEventId");
            const error = document.getElementById("editReservationEventIdError");
            const val = input.value.trim();

            // basic numeric sanity
            if (!/^\d+$/.test(val) || +val < 1) {
                error.textContent = "Invalid event ID.";
                return;
            }

            // check existence on the server
            try {
                const res = await fetch(
                    `../controller/VerifierEventExiste.php?id=${encodeURIComponent(val)}`
                );
                if (!res.ok) throw new Error("Network error");
                const { exists } = await res.json();
                error.textContent = exists
                    ? ""
                    : "No event found with that ID.";
            } catch (e) {
                console.error(e);
                error.textContent = "Unable to validate event ID right now.";
            }
        }

        // Load reservations dynamically
        function loadReservations() {
            fetch('../controller/AfficherReservation.php')
                .then(res => res.text())
                .then(html => document.getElementById("reservation-table-body").innerHTML = html)
                .catch(err => console.error('Failed to load reservations:', err));
        }
        document.addEventListener("DOMContentLoaded", loadReservations);

        // Delete reservation
        function initDeleteReservation() {
            const table = document.querySelector('table');
            const confirmModal = document.getElementById('confirmReservationModal');
            const confirmBtn = document.getElementById('confirmDeleteReservationBtn');
            const cancelBtn = document.getElementById('cancelDeleteReservationBtn');
            const closeConfirm = document.getElementById('closeConfirmReservationModal');
            let currentRow, currentId;

            table.addEventListener('click', e => {
                if (e.target.closest('.delete-btn')) {
                    const btn = e.target.closest('.delete-btn');
                    currentRow = btn.closest('tr');
                    currentId = btn.dataset.id;
                    confirmModal.style.display = 'block';
                }
            });

            confirmBtn.onclick = () => {
                fetch('../controller/SupprimerReservation.php', {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
                    body: 'id=' + encodeURIComponent(currentId)
                })
                    .then(res => res.json())
                    .then(data => {
                        if (data.success) {
                            currentRow.remove();
                        } else alert('Error: ' + data.error);
                    })
                    .catch(err => alert('Deletion error'));
                confirmModal.style.display = 'none';
            };
            cancelBtn.onclick = () => confirmModal.style.display = 'none';
            closeConfirm.onclick = () => confirmModal.style.display = 'none';
            window.onclick = e => { if (e.target == confirmModal) confirmModal.style.display = 'none'; };
        }
        document.addEventListener('DOMContentLoaded', initDeleteReservation);

        // Edit reservation
        document.addEventListener("DOMContentLoaded", () => {
            const editModal = document.getElementById("edit-reservation-modal");
            const closeEdit = document.querySelector("#edit-reservation-modal .close-edit");

            document.querySelector('table').addEventListener('click', e => {
                if (e.target.closest('.edit-btn')) {
                    const row = e.target.closest('tr');
                    const cells = row.getElementsByTagName('td');
                    document.getElementById('editReservationId').value = cells[0].textContent;
                    document.getElementById('editReservationName').value = cells[3].textContent;
                    document.getElementById('editReservationEmail').value = cells[4].textContent;
                    document.getElementById('editReservationPhone').value = cells[5].textContent;
                    document.getElementById('editReservationSeats').value = cells[6].textContent;
                    document.getElementById('editReservationDate').value = cells[7].textContent;
                    document.getElementById('editReservationEventId').value = cells[1].textContent;
                    editModal.style.display = 'block';
                }
            });

            closeEdit.onclick = () => editModal.style.display = 'none';
            window.onclick = e => { if (e.target == editModal) editModal.style.display = 'none'; };
        });

        // Table search
        function filterRows(tbody, term) {
            const text = term.toLowerCase();
            Array.from(tbody.rows).forEach(r => r.style.display = r.textContent.toLowerCase().includes(text) ? '' : 'none');
        }
        function setupSearch() {
            const input = document.querySelector('.search');
            const tbody = document.getElementById('reservation-table-body');
            input.addEventListener('input', () => filterRows(tbody, input.value));
        }
        document.addEventListener('DOMContentLoaded', setupSearch);
    </script>
</body>

</html>