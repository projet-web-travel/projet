// validation.js - Contrôle de saisie des formulaires
document.addEventListener('DOMContentLoaded', function() {
  console.log("Script de validation chargé");

  // Validation du formulaire d'ajout
  const addForm = document.getElementById('addForm');
  if (addForm) {
      addForm.addEventListener('submit', function(e) {
          if (!validateAddForm()) {
              e.preventDefault();
              return false;
          }
      });
  }

  // Validation du formulaire d'édition
  const editForm = document.getElementById('editForm');
  if (editForm) {
      editForm.addEventListener('submit', function(e) {
          if (!validateEditForm()) {
              e.preventDefault();
              return false;
          }
      });
  }
});

function validateAddForm() {
  console.log("Validation du formulaire d'ajout");
  let isValid = true;
  
  // Réinitialiser les erreurs
  clearErrors();

  // Validation du nom
  const nom = document.getElementById('nom');
  if (nom.value.trim() === '') {
      showError(nom, 'Le nom est obligatoire');
      isValid = false;
  }

  // Validation du prénom
  const prenom = document.getElementById('prenom');
  if (prenom.value.trim() === '') {
      showError(prenom, 'Le prénom est obligatoire');
      isValid = false;
  }

  // Validation de l'email
  const email = document.getElementById('email');
  const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
  if (email.value.trim() === '') {
      showError(email, 'L\'email est obligatoire');
      isValid = false;
  } else if (!emailRegex.test(email.value)) {
      showError(email, 'Veuillez entrer un email valide');
      isValid = false;
  }

  // Validation du mot de passe (seulement pour l'ajout)
  const password = document.getElementById('mot_de_passe');
  if (password && password.value.trim() === '') {
      showError(password, 'Le mot de passe est obligatoire');
      isValid = false;
  } else if (password && password.value.length < 6) {
      showError(password, 'Le mot de passe doit contenir au moins 6 caractères');
      isValid = false;
  }

  // Validation du téléphone
  const telephone = document.getElementById('telephone');
  const phoneRegex = /^[0-9]{8}$/;
  if (telephone.value.trim() === '') {
      showError(telephone, 'Le téléphone est obligatoire');
      isValid = false;
  } else if (!phoneRegex.test(telephone.value)) {
      showError(telephone, 'Le téléphone doit contenir 10 chiffres');
      isValid = false;
  }

  // Validation de la date de naissance
  const dateNaissance = document.getElementById('date_naissance');
  if (dateNaissance.value.trim() === '') {
      showError(dateNaissance, 'La date de naissance est obligatoire');
      isValid = false;
  } else {
      const birthDate = new Date(dateNaissance.value);
      const today = new Date();
      let age = today.getFullYear() - birthDate.getFullYear();
      const monthDiff = today.getMonth() - birthDate.getMonth();
      
      if (monthDiff < 0 || (monthDiff === 0 && today.getDate() < birthDate.getDate())) {
          age--;
      }
      
      if (age < 18) {
          showError(dateNaissance, 'Vous devez avoir au moins 18 ans');
          isValid = false;
      }
  }

  return isValid;
}

function validateEditForm() {
  console.log("Validation du formulaire d'édition");
  let isValid = true;
  
  // Réinitialiser les erreurs
  clearErrors();

  // Validation du nom
  const nom = document.getElementById('nom');
  if (nom.value.trim() === '') {
      showError(nom, 'Le nom est obligatoire');
      isValid = false;
  }

  // Validation du prénom
  const prenom = document.getElementById('prenom');
  if (prenom.value.trim() === '') {
      showError(prenom, 'Le prénom est obligatoire');
      isValid = false;
  }

  // Validation de l'email
  const email = document.getElementById('email');
  const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
  if (email.value.trim() === '') {
      showError(email, 'L\'email est obligatoire');
      isValid = false;
  } else if (!emailRegex.test(email.value)) {
      showError(email, 'Veuillez entrer un email valide');
      isValid = false;
  }

  // Validation du mot de passe (optionnel pour l'édition)
  const password = document.getElementById('mot_de_passe');
  if (password && password.value.trim() !== '' && password.value.length < 6) {
      showError(password, 'Le mot de passe doit contenir au moins 6 caractères');
      isValid = false;
  }

  // Validation du téléphone
  const telephone = document.getElementById('telephone');
  const phoneRegex = /^[0-9]{8}$/;
  if (telephone.value.trim() === '') {
      showError(telephone, 'Le téléphone est obligatoire');
      isValid = false;
  } else if (!phoneRegex.test(telephone.value)) {
      showError(telephone, 'Le téléphone doit contenir 10 chiffres');
      isValid = false;
  }

  // Validation de la date de naissance
  const dateNaissance = document.getElementById('date_naissance');
  if (dateNaissance.value.trim() === '') {
      showError(dateNaissance, 'La date de naissance est obligatoire');
      isValid = false;
  }

  return isValid;
}

// Fonction pour afficher les erreurs
function showError(input, message) {
  const formGroup = input.closest('.form-group');
  if (!formGroup) return;
  
  // Supprimer l'erreur existante
  const existingError = formGroup.querySelector('.error-message');
  if (existingError) {
      existingError.remove();
  }
  
  // Ajouter le message d'erreur
  const errorElement = document.createElement('div');
  errorElement.className = 'error-message';
  errorElement.style.color = '#ef4444';
  errorElement.style.fontSize = '0.875rem';
  errorElement.style.marginTop = '0.25rem';
  errorElement.textContent = message;
  
  formGroup.appendChild(errorElement);
  
  // Ajouter une classe d'erreur au champ
  input.style.borderColor = '#ef4444';
}

// Fonction pour effacer les erreurs
function clearErrors() {
  // Supprimer tous les messages d'erreur
  document.querySelectorAll('.error-message').forEach(el => el.remove());
  
  // Réinitialiser les bordures
  document.querySelectorAll('.form-control').forEach(input => {
      input.style.borderColor = '#d1d5db';
  });
}