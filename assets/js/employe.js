document.addEventListener('DOMContentLoaded', function() {

    const tabButtons = document.querySelectorAll('button[data-bs-toggle="pill"]');
    tabButtons.forEach(button => {
        button.addEventListener('click', function(e) {
            e.preventDefault(); 
            tabButtons.forEach(btn => btn.classList.remove('active'));
            this.classList.add('active');
            document.querySelectorAll('.tab-pane').forEach(pane => pane.classList.remove('show', 'active'));
            const targetId = this.getAttribute('data-bs-target');
            if(document.querySelector(targetId)) document.querySelector(targetId).classList.add('show', 'active');
        });
    });

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

    document.querySelectorAll('.js-update-status').forEach(select => {
        select.addEventListener('change', function() {
            const formData = new FormData();
            formData.append('id', this.getAttribute('data-id'));
            formData.append('statut', this.value);
            formData.append('ajax', '1');

            if(this.value === 'attente_retour_materiel') {
                if(!confirm("⚠️ ATTENTION : Cela va envoyer un email de pénalité (600€) au client. Confirmer ?")) {
                    this.value = this.getAttribute('data-original'); return;
                }
            }

            fetch('index.php?page=employe_update_status', { method: 'POST', body: formData })
            .then(res => res.json())
            .then(data => {
                if(data.status === 'success') {
                    this.style.borderColor = '#198754';
                    this.className = `form-select form-select-sm status-select js-update-status ${this.value}`;
                    this.closest('tr').setAttribute('data-status', this.value);
                    this.setAttribute('data-original', this.value);
                    setTimeout(() => { this.style.borderColor = ''; }, 1000);
                } else {
                    alert("Erreur : " + data.message);
                }
            });
        });
    });

    document.querySelectorAll('.js-open-cancel').forEach(btn => {
        btn.addEventListener('click', function() {
            document.getElementById('cancel-id').value = this.getAttribute('data-id');
            document.getElementById('form-cancel').reset();
        });
    });

    const formCancel = document.getElementById('form-cancel');
    if(formCancel) {
        formCancel.addEventListener('submit', function(e) {
            e.preventDefault();
            const formData = new FormData(this);
            fetch('index.php?page=employe_cancel_order', { method: 'POST', body: formData })
            .then(res => res.json())
            .then(data => {
                if(data.status === 'success') {
                    alert("✅ Commande annulée.");
                    location.reload();
                } else {
                    alert("Erreur technique.");
                }
            });
        });
    }

    document.querySelectorAll('.js-validate-avis').forEach(btn => {
        btn.addEventListener('click', function(e) {
            e.preventDefault();
            const row = this.closest('tr');
            const badge = row.querySelector('.js-badge');
            fetch(`index.php?page=admin_avis_validate&id=${this.getAttribute('data-id')}&ajax=1`)
            .then(res => res.json())
            .then(data => {
                if(data.status === 'success') {
                    badge.className = 'badge bg-success js-badge'; badge.textContent = 'En ligne'; this.remove();
                }
            });
        });
    });

    document.querySelectorAll('.js-delete-avis').forEach(btn => {
        btn.addEventListener('click', function(e) {
            e.preventDefault();
            if(!confirm('Supprimer cet avis ?')) return;
            const row = this.closest('tr');
            fetch(`index.php?page=admin_avis_delete&id=${this.getAttribute('data-id')}&ajax=1`)
            .then(res => res.json())
            .then(data => { if(data.status === 'success') row.remove(); });
        });
    });

    document.querySelectorAll('.js-save-horaire').forEach(btn => {
        btn.addEventListener('click', function() {
            const id = this.getAttribute('data-id');
            const creneau = document.getElementById(`horaire-${id}`).value;
            const originalHTML = this.innerHTML;
            this.innerHTML = '...';
            this.disabled = true;

            const formData = new FormData();
            formData.append('id', id); formData.append('creneau', creneau); formData.append('ajax', '1');

            fetch('index.php?page=admin_horaire_update', { method: 'POST', body: formData })
            .then(res => res.json())
            .then(data => {
                if(data.status === 'success') {
                    this.innerHTML = 'OK';
                    setTimeout(() => { this.innerHTML = originalHTML; this.disabled = false; }, 1000);
                }
            });
        });
    });

});