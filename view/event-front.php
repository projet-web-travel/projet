<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>Events</title>
  <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@600&family=Poppins:wght@500&display=swap" rel="stylesheet">
  <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" rel="stylesheet">
  <link rel="stylesheet" href="event-front.css">
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
      <a href="#" class="book-now">Book Now</a>
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

  <script>
    document.addEventListener("DOMContentLoaded", function () {
      fetch("front-event.php")
        .then((response) => response.json())
        .then((data) => {
          const container = document.getElementById("eventCards");
          container.innerHTML = ""; // Clear placeholders

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
                <a href="#" class="book-now">Book Now</a>
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
  </script>
</body>
</html>
