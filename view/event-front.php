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

    .book-now {
      background-color: #3487FF;
      color: #FFFFFF !important;
      padding: 10px 30px;
      border-radius: 50px;
      text-decoration: none;
      font-weight: normal;
      transition: 0.3s;
      display: inline-block;
    }

    .book-now:hover {
      background-color: #2665CC;
    }

    .toast {
      position: fixed;
      left: 50%;
      top: 20%;
      transform: translateX(-50%);
      padding: 15px 25px;
      border-radius: 8px;
      color: #fff;
      font-size: 16px;
      font-weight: 500;
      z-index: 10000;
      opacity: 1;
      transition: opacity 0.5s ease, top 0.5s ease;
    }

    .toast-success {
      background-color: #28a745;
    }

    .toast-error {
      background-color: #dc3545;
    }

    .toast.fade-out {
      opacity: 0;
      top: 10%;
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
      Events
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
      <form id="reservation-form" method="POST">
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
    document.addEventListener('DOMContentLoaded', () => {
      initEventCards();
      initReservationModal();
      initAddReservationAjax();
      initAlphabeticalFilter();
    });

    /** 1) Load & render event cards **/
    function initEventCards() {
      fetch('../controller/front-event.php')
        .then(res => res.json())
        .then(data => {
          const container = document.getElementById('eventCards');
          container.innerHTML = '';
          data.forEach(evt => {
            const card = document.createElement('div');
            card.className = 'offer-card';
            card.style.backgroundImage = `url('../uploads/${evt.Apercu}')`;
            card.innerHTML = `
            <div class="view-icon" data-img="../uploads/${evt.Apercu}">
              <i class="fas fa-image"></i>
            </div>
            <div class="card-info">
              <h3>${evt.Nom}</h3>
              <p>${evt.Date} • $${evt.Prix} • ${evt.Duree} Days</p>
              <p>${evt.NombrePlaces} Seats</p>
              <p><i class="fas fa-location-dot"></i> ${evt.Localisation}</p>
              <a href="javascript:void(0)"
                 class="book-now"
                 data-id="${evt.Id}">
                Book Now
              </a>
            </div>`;
            container.appendChild(card);
          });

          // Image popup
          document.querySelectorAll('.view-icon').forEach(icon => {
            icon.addEventListener('click', () => {
              document.getElementById('popupImage').src = icon.dataset.img;
              document.getElementById('imagePopup').style.display = 'flex';
            });
          });
          document.getElementById('closePopup').onclick = () => {
            document.getElementById('imagePopup').style.display = 'none';
          };
        })
        .catch(err => console.error('Error loading events:', err));
    }

    /** 2) Reservation modal open/close **/
    function initReservationModal() {
      const modal = document.getElementById('reservation-modal');
      const closeBtn = modal.querySelector('.close-btn');
      const dateInput = document.getElementById('reservationDate');
      const eventIdInput = document.getElementById('reservationEventId');

      // Open on Book Now click
      document.getElementById('eventCards').addEventListener('click', e => {
        const btn = e.target.closest('.book-now');
        if (!btn) return;
        dateInput.value = new Date().toISOString().split('T')[0];
        eventIdInput.value = btn.dataset.id;
        modal.classList.add('open');
      });

      // Close handlers
      closeBtn.onclick = () => modal.classList.remove('open');
      window.addEventListener('click', e => {
        if (e.target === modal) modal.classList.remove('open');
      });
    }

    /** 3) AJAX submission + styled toast **/
    function initAddReservationAjax() {
      const form = document.getElementById('reservation-form');
      const modal = document.getElementById('reservation-modal');

      form.addEventListener('submit', e => {
        e.preventDefault();
        const data = new FormData(form);

        fetch('../controller/AjouterReservation.php', {
          method: 'POST',
          body: data
        })
          .then(res => res.text().then(txt => ({ status: res.status, text: txt })))
          .then(({ status, text }) => {
            // Try JSON.parse
            let json;
            try {
              json = JSON.parse(text);
            } catch (err) {
              console.error('Invalid JSON:', text);
              throw new Error('Server returned invalid response.');
            }
            if (!json.success) throw new Error(json.error || 'Unknown error');
            // Success!
            modal.classList.remove('open');
            form.reset();
            showToast('✅ Reservation added!', false);
          })
          .catch(err => {
            console.error(err);
            showToast('❌ ' + err.message, true);
          });
      });
    }

    /** Toast helper **/
    function showToast(msg, isError) {
      const toast = document.createElement('div');
      toast.className = 'toast ' + (isError ? 'toast-error' : 'toast-success');
      toast.innerText = msg;
      document.body.appendChild(toast);
      setTimeout(() => toast.classList.add('fade-out'), 2500);
      setTimeout(() => toast.remove(), 3000);
    }

    /** 4) Alphabetical filter toggle **/
    function initAlphabeticalFilter() {
      const btn = document.querySelector('.filter-button');
      const container = document.getElementById('eventCards');
      let isSorted = false, cache = [];

      btn.addEventListener('click', () => {
        if (!isSorted) {
          if (!cache.length) cache = Array.from(container.children).map(c => c.outerHTML);
          const cards = Array.from(container.children);
          cards.sort((a, b) => {
            const nA = a.querySelector('h3').textContent.toLowerCase();
            const nB = b.querySelector('h3').textContent.toLowerCase();
            return nA.localeCompare(nB);
          });
          container.innerHTML = '';
          cards.forEach(c => container.appendChild(c));
        } else {
          container.innerHTML = cache.join('');
        }
        isSorted = !isSorted;
      });
    }

    /** 5) Validation stubs (called via onkeyup/onchange) **/
    function validateReservationName() {
      const val = document.getElementById('reservationName').value.trim();
      const err = document.getElementById('reservationNameError');
      err.textContent = val.length < 2 ? 'Name must be at least 2 characters.' : '';
    }
    function validateReservationEmail() {
      const val = document.getElementById('reservationEmail').value;
      const err = document.getElementById('reservationEmailError');
      err.textContent = /^[^@\s]+@[^@\s]+\.[^@\s]+$/.test(val) ? '' : 'Invalid email address.';
    }
    function validateReservationPhone() {
      const val = document.getElementById('reservationPhone').value;
      const err = document.getElementById('reservationPhoneError');
      err.textContent = val && !/^\+?[0-9\s\-]{7,}$/.test(val) ? 'Invalid phone number.' : '';
    }
  </script>
</body>
</html>