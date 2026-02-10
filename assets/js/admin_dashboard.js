document.addEventListener('DOMContentLoaded', function() {

    // ============================================================
    // 0. FILTRES COMMANDES (AJOUTÉ POUR L'ADMIN)
    // ============================================================
    const filterClient = document.getElementById('filter-client');
    const filterStatus = document.getElementById('filter-status');
    const rows = document.querySelectorAll('.cmd-row');

    function filterTable() {
        if(!filterClient || !filterStatus) return;

        const text = filterClient.value.toLowerCase();
        const stat = filterStatus.value;
        let count = 0;
        
        rows.forEach(row => {
            const rName = row.getAttribute('data-client');
            const rStat = row.getAttribute('data-status');
            let show = true;
            
            if(text && !rName.includes(text)) show = false;
            if(stat && rStat !== stat) show = false;
            
            row.style.display = show ? '' : 'none';
            if(show) count++;
        });
        
        const countDisplay = document.getElementById('count-display');
        if(countDisplay) countDisplay.textContent = count;
    }

    if(filterClient) filterClient.addEventListener('input', filterTable);
    if(filterStatus) filterStatus.addEventListener('change', filterTable);


    // ============================================================
    // 1. ONGLETS (SI BOOTSTRAP NE RÉPOND PAS)
    // ============================================================
    const tabButtons = document.querySelectorAll('button[data-bs-toggle="pill"]');
    
    tabButtons.forEach(button => {
        button.addEventListener('click', function(e) {
            e.preventDefault(); 
            tabButtons.forEach(btn => btn.classList.remove('active'));
            this.classList.add('active');
            document.querySelectorAll('.tab-pane').forEach(pane => {
                pane.classList.remove('show', 'active');
            });
            const targetId = this.getAttribute('data-bs-target');
            const targetPane = document.querySelector(targetId);
            if(targetPane) {
                targetPane.classList.add('show', 'active');
            }
        });
    });

    // ============================================================
    // 2. GESTION DES COMMANDES (Changement de statut)
    // ============================================================
    const statusSelects = document.querySelectorAll('.js-status-select');
    statusSelects.forEach(select => {
        select.addEventListener('change', function() {
            const formData = new FormData();
            formData.append('id', this.getAttribute('data-id'));
            formData.append('statut', this.value);
            formData.append('ajax', '1');

            fetch('index.php?page=admin_commande_status', { method: 'POST', body: formData })
            .then(res => res.json())
            .then(data => {
                if(data.status === 'success') {
                    // Feedback visuel
                    this.style.borderColor = '#198754';
                    this.style.boxShadow = '0 0 0 0.25rem rgba(25, 135, 84, 0.25)';
                    
                    // Mise à jour de la classe CSS pour la couleur
                    this.className = `form-select form-select-sm status-select js-status-select ${this.value}`;
                    
                    // IMPORTANT : Mettre à jour l'attribut data-status pour que le filtre marche
                    this.closest('tr').setAttribute('data-status', this.value);

                    setTimeout(() => { 
                        this.style.borderColor = ''; 
                        this.style.boxShadow = '';
                    }, 1000);
                }
            })
            .catch(error => console.error('Erreur:', error));
        });
    });

    // ============================================================
    // 3. MODÉRATION AVIS : VALIDATION
    // ============================================================
    document.querySelectorAll('.js-validate-avis').forEach(btn => {
        btn.addEventListener('click', function(e) {
            e.preventDefault();
            const row = this.closest('tr');
            const badge = row.querySelector('.js-badge');
            
            fetch(`index.php?page=admin_avis_validate&id=${this.getAttribute('data-id')}&ajax=1`)
            .then(res => res.json())
            .then(data => {
                if(data.status === 'success') {
                    badge.className = 'badge bg-success js-badge';
                    badge.textContent = 'En ligne';
                    this.remove();
                }
            });
        });
    });

    // ============================================================
    // 4. MODÉRATION AVIS : SUPPRESSION
    // ============================================================
    document.querySelectorAll('.js-delete-avis').forEach(btn => {
        btn.addEventListener('click', function(e) {
            e.preventDefault();
            if(!confirm('Supprimer cet avis ?')) return;
            const row = this.closest('tr');

            fetch(`index.php?page=admin_avis_delete&id=${this.getAttribute('data-id')}&ajax=1`)
            .then(res => res.json())
            .then(data => {
                if(data.status === 'success') {
                    row.style.transition = 'all 0.5s ease';
                    row.style.opacity = '0';
                    setTimeout(() => row.remove(), 500);
                }
            });
        });
    });

    // ============================================================
    // 5. UTILISATEURS : CHANGEMENT DE RÔLE
    // ============================================================
    document.querySelectorAll('.js-role-select').forEach(select => {
        select.addEventListener('change', function() {
            const formData = new FormData();
            formData.append('id', this.getAttribute('data-id'));
            formData.append('role_id', this.value);
            formData.append('ajax', '1');

            fetch('index.php?page=admin_user_role', { method: 'POST', body: formData })
            .then(res => res.json())
            .then(data => {
                if(data.status === 'success') {
                    this.style.borderColor = '#198754';
                    setTimeout(() => { this.style.borderColor = ''; }, 1000);
                    
                    if(this.value == '1') this.style.color = '#d63384';
                    else if(this.value == '2') this.style.color = '#0d6efd';
                    else this.style.color = '#333';
                } else {
                    alert(data.message);
                    location.reload();
                }
            });
        });
    });

    // ============================================================
    // 6. UTILISATEURS : SUPPRESSION
    // ============================================================
    document.querySelectorAll('.js-delete-user').forEach(btn => {
        btn.addEventListener('click', function(e) {
            e.preventDefault();
            if(!confirm('⚠️ ATTENTION : Supprimer cet utilisateur effacera aussi ses commandes et avis. Continuer ?')) return;
            const row = this.closest('tr');

            fetch(`index.php?page=admin_user_delete&id=${this.getAttribute('data-id')}&ajax=1`)
            .then(res => res.json())
            .then(data => {
                if(data.status === 'success') {
                    row.style.transition = 'all 0.5s ease';
                    row.style.opacity = '0';
                    setTimeout(() => row.remove(), 500);
                } else {
                    alert(data.message);
                }
            });
        });
    });

    // ============================================================
    // 7. STATISTIQUES (Filtre AJAX)
    // ============================================================
    const btnFilter = document.getElementById('js-filter-stats');
    if(btnFilter) {
        function loadStats() {
            const start = document.getElementById('stats-start').value;
            const end = document.getElementById('stats-end').value;
            const formData = new FormData();
            formData.append('start', start);
            formData.append('end', end);
            formData.append('ajax', '1');

            const originalText = btnFilter.innerHTML;
            btnFilter.innerHTML = '<i class="fa-solid fa-spinner fa-spin"></i> Chargement...';
            btnFilter.disabled = true;

            fetch('index.php?page=admin_stats_filter', { method: 'POST', body: formData })
            .then(res => res.json())
            .then(data => {
                if(data.status === 'success') {
                    document.getElementById('stats-ca').textContent = data.ca;
                    document.getElementById('stats-best-titre').textContent = data.best_seller_titre;
                    document.getElementById('stats-best-count').textContent = data.best_seller_count;

                    const tbody = document.getElementById('stats-history-body');
                    tbody.innerHTML = '';
                    
                    let maxOrders = 0;
                    if(data.history.length > 0) {
                        data.history.forEach(item => { if(parseInt(item.total) > maxOrders) maxOrders = parseInt(item.total); });
                        data.history.forEach(item => {
                            const percent = maxOrders > 0 ? (item.total / maxOrders) * 100 : 0;
                            const row = `<tr>
                                <td class="fw-bold text-muted">${item.mois}</td>
                                <td class="fw-bold text-dark fs-5">${item.total}</td>
                                <td class="text-start align-middle" style="width:50%;">
                                    <div style="background-color: #8B2635; height: 10px; border-radius: 5px; width: ${percent}%;"></div>
                                </td>
                            </tr>`;
                            tbody.innerHTML += row;
                        });
                    } else {
                        tbody.innerHTML = '<tr><td colspan="3" class="text-muted">Aucune donnée historique.</td></tr>';
                    }
                }
                btnFilter.innerHTML = originalText;
                btnFilter.disabled = false;
            })
            .catch(err => { console.error(err); btnFilter.disabled = false; btnFilter.innerHTML = originalText; });
        }
        btnFilter.addEventListener('click', loadStats);
        loadStats();
    }

    // ============================================================
    // 8. MESSAGERIE : SUPPRESSION
    // ============================================================
    document.querySelectorAll('.js-delete-message').forEach(btn => {
        btn.addEventListener('click', function(e) {
            e.preventDefault();
            if(!confirm('Voulez-vous vraiment supprimer ce message ?')) return;
            
            const row = this.closest('tr');
            const id = this.getAttribute('data-id');

            fetch(`index.php?page=admin_message_delete&id=${id}&ajax=1`)
            .then(res => res.json())
            .then(data => {
                if(data.status === 'success') {
                    row.style.transition = 'all 0.5s ease';
                    row.style.opacity = '0';
                    row.style.transform = 'translateX(20px)';
                    setTimeout(() => row.remove(), 500);
                } else {
                    alert("Erreur lors de la suppression : " + (data.message || "Inconnue"));
                }
            })
            .catch(error => {
                console.error("Erreur JS :", error);
                alert("Erreur technique. Vérifiez la console.");
            });
        });
    });

    // ============================================================
    // 9. MESSAGERIE : MODALE & RÉPONSE
    // ============================================================
    const replyModalEl = document.getElementById('replyModal');
    let replyModal;
    if(replyModalEl && typeof bootstrap !== 'undefined') {
        replyModal = new bootstrap.Modal(replyModalEl);
    }

    // A. OUVRIR LA MODALE
    document.querySelectorAll('.js-open-reply-modal').forEach(btn => {
        btn.addEventListener('click', function() {
            const id = this.getAttribute('data-id');
            const nom = this.getAttribute('data-nom');
            const email = this.getAttribute('data-email');
            const sujet = this.getAttribute('data-sujet');
            const message = this.getAttribute('data-message');
            const reponse = this.getAttribute('data-reponse');

            document.getElementById('modal-nom').textContent = nom;
            document.getElementById('modal-email').textContent = email;
            document.getElementById('modal-sujet').textContent = sujet;
            document.getElementById('modal-message').textContent = message;
            
            document.getElementById('reply-id').value = id;
            document.getElementById('reply-email').value = email;

            if(reponse && reponse !== '') {
                document.getElementById('reply-section').classList.add('d-none');
                document.getElementById('already-replied').classList.remove('d-none');
                document.getElementById('modal-reponse-content').textContent = reponse;
            } else {
                document.getElementById('reply-section').classList.remove('d-none');
                document.getElementById('already-replied').classList.add('d-none');
                document.getElementById('reply-content').value = ''; 
            }

            if(replyModal) replyModal.show();
        });
    });

    // B. ENVOYER LA RÉPONSE
    const replyForm = document.getElementById('form-reply');
    if(replyForm) {
        replyForm.addEventListener('submit', function(e) {
            e.preventDefault();
            
            const btn = this.querySelector('button');
            const originalText = btn.innerHTML;
            const messageReponse = document.getElementById('reply-content').value;
            
            if(messageReponse.trim() === "") {
                alert("Veuillez écrire une réponse.");
                return;
            }

            const formData = new FormData();
            formData.append('id', document.getElementById('reply-id').value);
            formData.append('email', document.getElementById('reply-email').value);
            formData.append('message', messageReponse);
            formData.append('ajax', '1');

            btn.innerHTML = '<i class="fa-solid fa-spinner fa-spin"></i> Envoi...';
            btn.disabled = true;

            fetch('index.php?page=admin_message_reply', { method: 'POST', body: formData })
            .then(res => res.json())
            .then(data => {
                if(data.status === 'success') {
                    if(replyModal) replyModal.hide();
                    alert("✅ Réponse envoyée !");
                    location.reload(); 
                } else {
                    alert("Erreur : " + data.message);
                }
                btn.innerHTML = originalText;
                btn.disabled = false;
            })
            .catch(err => {
                console.error(err);
                btn.innerHTML = originalText;
                btn.disabled = false;
            });
        });
    }

    // SAUVEGARDE HORAIRES
    document.querySelectorAll('.js-save-horaire').forEach(btn => {
        btn.addEventListener('click', function() {
            const id = this.getAttribute('data-id');
            const creneau = document.getElementById(`horaire-${id}`).value;
            const originalHTML = this.innerHTML;

            this.innerHTML = '<i class="fa-solid fa-spinner fa-spin"></i>';
            this.disabled = true;

            const formData = new FormData();
            formData.append('id', id);
            formData.append('creneau', creneau);
            formData.append('ajax', '1');

            fetch('index.php?page=admin_horaire_update', { method: 'POST', body: formData })
            .then(res => res.json())
            .then(data => {
                if(data.status === 'success') {
                    this.innerHTML = '<i class="fa-solid fa-check"></i> OK';
                    this.classList.replace('btn-success', 'btn-dark');
                    setTimeout(() => {
                        this.innerHTML = originalHTML;
                        this.classList.replace('btn-dark', 'btn-success');
                        this.disabled = false;
                    }, 2000);
                }
            });
        });
    });

    // ============================================================
    // 10. CRÉATION UTILISATEUR (C'EST ICI LA NOUVELLE FONCTIONNALITÉ)
    // ============================================================
    const formCreateUser = document.getElementById('form-create-user');
    if (formCreateUser) {
        formCreateUser.addEventListener('submit', function(e) {
            e.preventDefault();
            const btn = this.querySelector('button');
            const originalText = btn.innerHTML;
            
            btn.disabled = true;
            btn.innerHTML = '<i class="fa-solid fa-spinner fa-spin"></i> Création...';

            const formData = new FormData(this);

            fetch('index.php?page=admin_user_create', { method: 'POST', body: formData })
            .then(res => res.json())
            .then(data => {
                if (data.status === 'success') {
                    alert("✅ Utilisateur créé avec succès !");
                    location.reload();
                } else {
                    alert("❌ Erreur : " + data.message);
                    btn.disabled = false;
                    btn.innerHTML = originalText;
                }
            })
            .catch(err => {
                console.error(err);
                alert("Erreur technique (Voir console)");
                btn.disabled = false;
                btn.innerHTML = originalText;
            });
        });
    }

});