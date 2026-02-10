/**
 * Prévisualise une image lors de l'upload
 * @param {HTMLInputElement} input - L'élément input file
 * @param {string} previewId - L'ID de l'image <img> de prévisualisation
 * @param {string} textId - L'ID du texte à cacher
 */
function previewImage(input, previewId, textId) {
    if (input.files && input.files[0]) {
        var reader = new FileReader();
        
        reader.onload = function(e) {
            var img = document.getElementById(previewId);
            var text = document.getElementById(textId);
            
            if (img) {
                img.src = e.target.result;
                img.style.display = 'block';
            }
            
            if (text) {
                text.style.display = 'none';
            }
        }
        
        reader.readAsDataURL(input.files[0]);
    }
}