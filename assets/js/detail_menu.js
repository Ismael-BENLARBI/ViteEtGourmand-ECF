function openModal(element) {
    var modal = document.getElementById("imageModal");
    var modalImg = document.getElementById("fullImage");

    if (modal && modalImg) {
        modal.style.display = "flex";
        modalImg.src = element.src;
    }
}

function closeModal() {
    var modal = document.getElementById("imageModal");
    if (modal) {
        modal.style.display = "none";
    }
}

function changeMainImage(element) {
    var mainImage = document.querySelector('.main-image');
    if (mainImage) {
        mainImage.src = element.src;
    }
}

function updateQty(change) {
    let input = document.getElementById('qtyInput');
    let formInput = document.getElementById('formQty');

    if (!input) return;

    let min = parseInt(input.min);
    let max = parseInt(input.max);
    let currentVal = parseInt(input.value);

    if (isNaN(currentVal)) currentVal = min;

    let newVal = currentVal + change;

    if (newVal >= min && newVal <= max) {
        input.value = newVal;
        formInput.value = newVal;
    } else if (newVal > max) {
        alert("Désolé, nous n'avons pas plus de stock disponible pour ce menu.");
    }
}

window.onclick = function(event) {
    var modal = document.getElementById("imageModal");
    if (event.target == modal) {
        closeModal();
    }
}