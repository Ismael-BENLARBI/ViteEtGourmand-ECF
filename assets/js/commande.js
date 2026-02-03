document.addEventListener("DOMContentLoaded", function() {
    console.log("✅ Fichier commande.js (Version API Corrigée) chargé !");

    // --- 1. RÉCUPÉRATION DES DONNÉES ---
    const form = document.getElementById('commandeForm');
    if (!form) return;

    // Sécurisation des valeurs
    const subTotalInitial = parseFloat(form.dataset.subtotal) || 0;
    const totalQty = parseInt(form.dataset.qty) || 0;

    // Coordonnées Bordeaux (Place de la Bourse)
    const bordeauxLat = 44.8404;
    const bordeauxLon = -0.5805;

    // --- 2. CIBLAGE DOM ---
    const zipInput = document.getElementById('zipCode');
    const cityInput = document.getElementById('cityInput');
    
    const shippingText = document.getElementById('shippingText');
    const shippingInfo = document.getElementById('shippingInfo');
    const grandTotalEl = document.getElementById('grandTotal');
    const discountRow = document.getElementById('discountRow');
    const discountAmountEl = document.getElementById('discountAmount');
    
    const distanceInfoBox = document.getElementById('distanceInfoBox');
    const distDisplay = document.getElementById('distDisplay');

    const inputTotalFinal = document.getElementById('inputTotalFinal');
    const inputFrais = document.getElementById('inputFrais');
    const inputReduction = document.getElementById('inputReduction');

    // Timer pour éviter de spammer l'API
    let typingTimer;                
    const doneTypingInterval = 600; 

    // --- 3. FONCTIONS ---

    function getDistanceFromLatLonInKm(lat1, lon1, lat2, lon2) {
        var R = 6371; 
        var dLat = deg2rad(lat2-lat1);  
        var dLon = deg2rad(lon2-lon1); 
        var a = Math.sin(dLat/2) * Math.sin(dLat/2) +
                Math.cos(deg2rad(lat1)) * Math.cos(deg2rad(lat2)) * Math.sin(dLon/2) * Math.sin(dLon/2); 
        var c = 2 * Math.atan2(Math.sqrt(a), Math.sqrt(1-a)); 
        return R * c; 
    }

    function deg2rad(deg) { return deg * (Math.PI/180); }

    function updatePrices(km) {
        let currentTotal = subTotalInitial;
        let discount = 0;
        let shipping = 0;

        // A. Réduction
        if (totalQty > 13) {
            discount = currentTotal * 0.10;
            if(discountRow) discountRow.style.display = 'flex';
            if(discountAmountEl) discountAmountEl.innerText = "- " + discount.toFixed(2) + " €";
            if(inputReduction) inputReduction.value = discount.toFixed(2);
        } else {
            if(discountRow) discountRow.style.display = 'none';
            if(inputReduction) inputReduction.value = 0;
        }

        currentTotal -= discount;

        // B. Livraison
        if (km === -1) {
            if(shippingText) shippingText.innerText = "...";
            if(shippingInfo) shippingInfo.innerText = "Calcul en cours...";
            return;
        }

        if (km > 0) {
            // Hors Bordeaux
            shipping = 5.00 + (km * 0.59);
            if(shippingText) shippingText.innerText = shipping.toFixed(2) + " €";
            if(shippingInfo) shippingInfo.innerText = "Forfait 5€ + 0,59€/km";
        } else {
            // Bordeaux (0 km)
            shipping = 0;
            if(shippingText) shippingText.innerHTML = '<span class="text-success">GRATUIT</span>';
            if(shippingInfo) shippingInfo.innerText = "Livraison offerte sur Bordeaux !";
        }

        // C. Total
        const finalPrice = currentTotal + shipping;
        if(grandTotalEl) grandTotalEl.innerText = finalPrice.toFixed(2) + " €";
        
        if(inputFrais) inputFrais.value = shipping.toFixed(2);
        if(inputTotalFinal) inputTotalFinal.value = finalPrice.toFixed(2);
    }

    // --- 4. LOGIQUE PRINCIPALE ---

    if (zipInput) {
        zipInput.addEventListener('input', function() {
            clearTimeout(typingTimer);
            if(shippingInfo) shippingInfo.innerText = "Saisie en cours...";
            
            // On attend que l'utilisateur finisse de taper
            typingTimer = setTimeout(doneTyping, doneTypingInterval);
        });

        function doneTyping () {
            let cp = zipInput.value.trim();

            if (cp.length === 5) {
                
                // 1. CAS BORDEAUX
                if (cp === '33000') {
                    cityInput.value = "Bordeaux";
                    distanceInfoBox.style.display = 'none';
                    updatePrices(0);
                    return;
                }

                // 2. APPEL API (Version corrigée)
                if(shippingInfo) shippingInfo.innerText = "Recherche de la ville...";
                
                // 👇 ICI LE CHANGEMENT : On ajoute '&type=municipality' pour forcer la recherche de ville
                fetch('https://api-adresse.data.gouv.fr/search/?q=' + cp + '&type=municipality&limit=1')
                    .then(response => {
                        if (!response.ok) { throw new Error("Erreur serveur"); }
                        return response.json();
                    })
                    .then(data => {
                        if(data.features && data.features.length > 0) {
                            const props = data.features[0].properties;
                            const coords = data.features[0].geometry.coordinates;

                            cityInput.value = props.city;

                            // Calcul Distance x 1.3
                            let dist = getDistanceFromLatLonInKm(bordeauxLat, bordeauxLon, coords[1], coords[0]);
                            dist = dist * 1.3;

                            distanceInfoBox.style.display = 'block';
                            distDisplay.innerText = dist.toFixed(1);

                            updatePrices(dist);
                        } else {
                            if(shippingInfo) shippingInfo.innerText = "Code postal non trouvé.";
                            distanceInfoBox.style.display = 'none';
                        }
                    })
                    .catch(err => {
                        console.error("Erreur API:", err);
                        // En cas d'erreur API, on essaie une recherche plus large sans 'municipality'
                        // C'est une sécurité "Fail-over"
                        searchBackup(cp);
                    });
            }
        }
        
        // Fonction de secours si la première recherche échoue
        function searchBackup(cp) {
             fetch('https://api-adresse.data.gouv.fr/search/?q=' + cp + '&limit=1')
                .then(response => response.json())
                .then(data => {
                    if(data.features && data.features.length > 0) {
                        const props = data.features[0].properties;
                        const coords = data.features[0].geometry.coordinates;
                        cityInput.value = props.city;
                        let dist = getDistanceFromLatLonInKm(bordeauxLat, bordeauxLon, coords[1], coords[0]);
                        dist = dist * 1.3;
                        distanceInfoBox.style.display = 'block';
                        distDisplay.innerText = dist.toFixed(1);
                        updatePrices(dist);
                    } else {
                         if(shippingInfo) shippingInfo.innerText = "Erreur de connexion (Ville introuvable).";
                    }
                })
                .catch(e => {
                    if(shippingInfo) shippingInfo.innerText = "Erreur réseau. Vérifiez votre connexion.";
                });
        }
    }

    // Initialisation
    updatePrices(0);
});