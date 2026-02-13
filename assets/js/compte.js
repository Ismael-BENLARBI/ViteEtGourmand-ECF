document.addEventListener('DOMContentLoaded', function() {
    const tabButtons = document.querySelectorAll('button[data-bs-toggle="pill"], button[data-bs-toggle="tab"]');

    tabButtons.forEach(button => {
        button.addEventListener('click', function(e) {
            e.preventDefault();

            const parentNav = this.closest('.nav');
            if (parentNav) {
                parentNav.querySelectorAll('.nav-link').forEach(btn => btn.classList.remove('active'));
            } else {
                tabButtons.forEach(btn => btn.classList.remove('active'));
            }

            this.classList.add('active');

            const tabContentId = parentNav ? parentNav.getAttribute('id').replace('-tab', '-tabContent') : null;
            let panes;

            if (tabContentId && document.getElementById(tabContentId)) {
                panes = document.getElementById(tabContentId).querySelectorAll('.tab-pane');
            } else {
                panes = document.querySelectorAll('.tab-pane');
            }

            panes.forEach(pane => {
                pane.classList.remove('show', 'active');
            });

            const targetId = this.getAttribute('data-bs-target');
            const targetPane = document.querySelector(targetId);
            if (targetPane) {
                targetPane.classList.add('show', 'active');
            }
        });
    });
});