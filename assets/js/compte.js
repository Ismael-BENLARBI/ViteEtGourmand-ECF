document.addEventListener('DOMContentLoaded', function() {

    // ============================================================
    // CORRECTIF FORCE POUR LES ONGLETS (Mon Compte)
    // ============================================================
    // On sélectionne tous les boutons d'onglets (Pills ou Tabs)
    const tabButtons = document.querySelectorAll('button[data-bs-toggle="pill"], button[data-bs-toggle="tab"]');
    
    tabButtons.forEach(button => {
        button.addEventListener('click', function(e) {
            // Empêche le comportement par défaut si Bootstrap bloque
            e.preventDefault(); 

            // 1. On retire la classe 'active' de tous les boutons de ce groupe
            // On cherche le parent (ul) pour ne pas désactiver les onglets d'autres parties du site
            const parentNav = this.closest('.nav');
            if(parentNav) {
                parentNav.querySelectorAll('.nav-link').forEach(btn => btn.classList.remove('active'));
            } else {
                // Fallback si pas de parent .nav trouvé
                tabButtons.forEach(btn => btn.classList.remove('active'));
            }

            // 2. On l'ajoute au bouton cliqué
            this.classList.add('active');

            // 3. On cache tous les contenus (tab-pane)
            // On cherche le conteneur global des contenus (tab-content)
            const tabContentId = parentNav ? parentNav.getAttribute('id').replace('-tab', '-tabContent') : null;
            let panes;
            
            // Essaie de trouver les contenus liés spécifiquement, sinon prend tous les tab-pane de la page
            if (tabContentId && document.getElementById(tabContentId)) {
                panes = document.getElementById(tabContentId).querySelectorAll('.tab-pane');
            } else {
                panes = document.querySelectorAll('.tab-pane');
            }

            panes.forEach(pane => {
                pane.classList.remove('show', 'active');
            });

            // 4. On affiche le contenu correspondant
            const targetId = this.getAttribute('data-bs-target'); // Ex: #pills-infos
            const targetPane = document.querySelector(targetId);
            if(targetPane) {
                targetPane.classList.add('show', 'active');
            }
        });
    });

});