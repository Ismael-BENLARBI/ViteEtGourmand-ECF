// Fonction pour ouvrir la modale (Zoom)
function openModal(element) {
    var modal = document.getElementById("imageModal");
    var modalImg = document.getElementById("fullImage");
    
    if(modal && modalImg) {
        modal.style.display = "flex"; 
        modalImg.src = element.src;   
    }
}

// Fonction pour fermer la modale
function closeModal() {
    var modal = document.getElementById("imageModal");
    if(modal) {
        modal.style.display = "none";
    }
}

// Changer l'image principale au clic sur une miniature
function changeMainImage(element) {
    var mainImage = document.querySelector('.main-image');
    if(mainImage) {
        mainImage.src = element.src;
    }
}

// Gestion de la quantité (+ / -) avec LIMITES MIN et MAX
function updateQty(change) {
    let input = document.getElementById('qtyInput');   
    let formInput = document.getElementById('formQty'); 
    
    if(!input) return; // Sécurité si le formulaire est caché

    let min = parseInt(input.min);
    let max = parseInt(input.max); // On récupère le stock max défini par PHP dans le HTML
    let currentVal = parseInt(input.value);
    
    // Si la valeur est vide ou NaN (cas rare), on initialise
    if (isNaN(currentVal)) currentVal = min;

    let newVal = currentVal + change;
    
    // On vérifie qu'on reste entre Min et Max (Stock)
    if(newVal >= min && newVal <= max) {
        input.value = newVal;
        formInput.value = newVal; // Mise à jour de l'input caché
    } else if (newVal > max) {
        alert("Désolé, nous n'avons pas plus de stock disponible pour ce menu.");
    }
}

// Fermeture de la modale si on clique en dehors de l'image (Bonus UX)
window.onclick = function(event) {
    var modal = document.getElementById("imageModal");
    if (event.target == modal) {
        closeModal();
    }
}