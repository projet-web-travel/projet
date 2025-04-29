<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Events</title>
  <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@600&family=Poppins:wght@500&display=swap"
    rel="stylesheet">
  <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" rel="stylesheet">
  <link rel="stylesheet" href="event-front.css">
  <style>
    .modal {
      display: none;
      position: fixed;
      z-index: 9999;
      inset: 0;
      background: rgba(0, 0, 0, 0.4);
      overflow: hidden;
    }

    .modal.open {
      display: flex;
      align-items: center;
      justify-content: center;
    }

    .modal-content {
      margin: 0;
      width: 90%;
      max-width: 500px;
      padding: 30px;
      border-radius: 10px;
      background: #fff;
      position: relative;
      box-sizing: border-box;
    }

    .modal-content input,
    .modal-content select {
      width: 100%;
      box-sizing: border-box;
      margin: 8px 0;
    }

    .modal-content label {
      display: block;
      margin-top: 10px;
      margin-bottom: 5px;
      color: #333;
      font-weight: 600;
    }


    .modal-content h2 {
      margin-bottom: 20px;
      color: #333;
    }

    .modal-content label {
      display: block;
      margin-top: 10px;
      margin-bottom: 5px;
      color: #333;
      font-weight: 600;
    }

    .modal-content input,
    .modal-content select {
      width: 100%;
      padding: 8px 12px;
      border: 1px solid #ccc;
      border-radius: 6px;
      font-size: 14px;
    }

    .submit-reservation {
      margin-top: 20px;
      background-color: #00A2E8;
      color: white;
      border: none;
      padding: 10px 15px;
      border-radius: 5px;
      font-size: 15px;
      cursor: pointer;
      transition: background-color 0.3s ease;
    }

    .submit-reservation:hover {
      background-color: #0086C1;
    }

    .close-btn {
      position: absolute;
      top: 15px;
      right: 20px;
      font-size: 24px;
      cursor: pointer;
      color: #aaa;
    }

    .close-btn:hover {
      color: #000;
    }

    .error-msg {
      color: red;
      font-size: 12px;
      margin-top: 5px;
      display: block;
    }
  </style>
</head>

<body>

  <nav class="navbar">
    <div class="logo">
      <a href="#"><span class="trip">TRIP</span><span class="ped">PED</span></a>
    </div>
    <div class="nav-actions">
      <a href="dashboard.php"><i class="fas fa-user"></i> Login</a>
      <a href="#"><i class="fas fa-globe"></i> EN</a>
    </div>
    <div class="nav-links">
      <a href="../../travel/view/main">Home</a>
      <a href="#">Transport</a>
      <a href="#">Events</a>
      <a href="#">Accomodation</a>
      <a href="#">Blog</a>
      <a href="#">Subscription</a>
      <!--<a href="#" class="book-now">Book Now</a>-->
    </div>
  </nav>

  <div class="background-section">
    <div class="offers-section">
      Offers
      <button class="filter-button"><i class="fas fa-filter"></i> Filter</button>
    </div>

    <div class="card-container" id="eventCards"></div>
  </div>

  <div class="image-popup" id="imagePopup">
    <img id="popupImage" src="" alt="Popup Image">
    <span class="close-btn" id="closePopup">&times;</span>
  </div>

  <div id="reservation-modal" class="modal">
    <div class="modal-content">
      <span class="close-btn">&times;</span>
      <h2>Add a new reservation</h2>
      <form id="reservation-form" action="../controller/AjouterReservation.php" method="POST">
        <label for="reservationName">Name</label>
        <input type="text" id="reservationName" name="clientName" placeholder="Client Name" onkeyup="validateEditReservationName()"
        required>
        <small id="reservationNameError" class="error-msg"></small>

        <label for="reservationEmail">Email</label>
        <input type="email" id="reservationEmail" name="clientEmail" placeholder="Email Address" required>
        <small id="reservationEmailError" class="error-msg"></small>

        <label for="reservationPhone">Telephone</label>
        <input type="text" id="reservationPhone" name="clientPhone" placeholder="Telephone Number">
        <small id="reservationPhoneError" class="error-msg"></small>

        <label for="reservationSeats">Number of Seats</label>
        <input type="number" id="reservationSeats" name="numSeats" placeholder="Seats" required>
        <small id="reservationSeatsError" class="error-msg"></small>

        <label for="reservationDate">Reservation Date</label>
        <input type="date" id="reservationDate" name="reservationDate" required readonly>
        <small id="reservationDateError" class="error-msg"></small>

        <input type="hidden" id="reservationEventId" name="eventId">

        <button type="submit" class="submit-reservation">
          Add Reservation
        </button>
      </form>
    </div>
  </div>


  <script>
    document.addEventListener("DOMContentLoaded", function () {
      fetch("../controller/front-event.php")
        .then((response) => response.json())
        .then((data) => {
          const container = document.getElementById("eventCards");
          container.innerHTML = "";

          data.forEach(event => {
            const card = document.createElement("div");
            card.className = "offer-card";
            card.style.backgroundImage = `url('../uploads/${event.Apercu}')`;

            card.innerHTML = `
              <div class="view-icon" data-img="../uploads/${event.Apercu}"><i class="fas fa-image"></i></div>
              <div class="card-info">
                <h3>${event.Nom}</h3>
                <p>${event.Date} • $${event.Prix} • ${event.Duree} Days</p>
                <p><i class="fas fa-location-dot"></i> ${event.Localisation}</p>
                <p><i class="fas fa-star"></i> 4.7</p>
                <a href="javascript:void(0)"
                  class="book-now"
                    data-id="${event.Id}">
                      Book Now
                </a>
              </div>
            `;

            container.appendChild(card);
          });

          document.querySelectorAll(".view-icon").forEach(icon => {
            icon.addEventListener("click", function () {
              const imgSrc = this.getAttribute("data-img");
              document.getElementById("popupImage").src = imgSrc;
              document.getElementById("imagePopup").style.display = "flex";
            });
          });

          document.getElementById("closePopup").onclick = function () {
            document.getElementById("imagePopup").style.display = "none";
          };
        })
        .catch((err) => {
          console.error("Erreur chargement des events:", err);
        });
    });

    function initReservationModal() {
      const modal = document.getElementById('reservation-modal');
      const closeBtn = modal.querySelector('.close-btn');
      const dateInput = document.getElementById('reservationDate');
      const eventIdInput = document.getElementById('reservationEventId');

      function openModal(id) {
        dateInput.value = new Date().toISOString().split('T')[0];
        eventIdInput.value = id;
        modal.classList.add('open');
      }

      function closeModal() {
        modal.classList.remove('open');
      }

      closeBtn.onclick = closeModal;
      window.addEventListener('click', e => {
        if (e.target === modal) closeModal();
      });

      document.getElementById('eventCards')
        .addEventListener('click', e => {
          const btn = e.target.closest('.book-now');
          if (!btn) return;
          openModal(btn.dataset.id);
        });
    }

    document.addEventListener('DOMContentLoaded', initReservationModal);

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
  </script>
</body>

</html>