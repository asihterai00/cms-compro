    </div>

</main>

<!-- Bootstrap 5.3.3 Bundle JS (100% Free & Open Source) -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
<!-- SweetAlert2 (100% Free & Open Source) -->
<script src="../assets/vendor/sweetalert2/sweetalert2.all.min.js"></script>

<script>
document.addEventListener('DOMContentLoaded', function () {
    // 1. Mobile Sidebar Toggle
    const sidebar = document.getElementById('adminSidebar');
    const backdrop = document.getElementById('adminSidebarBackdrop');
    const toggleBtn = document.getElementById('adminSidebarToggle');

    if (toggleBtn && sidebar && backdrop) {
        toggleBtn.addEventListener('click', function () {
            sidebar.classList.toggle('is-open');
            backdrop.classList.toggle('d-none');
            backdrop.classList.toggle('show');
        });

        backdrop.addEventListener('click', function () {
            sidebar.classList.remove('is-open');
            backdrop.classList.add('d-none');
            backdrop.classList.remove('show');
        });
    }

    // 2. Global Quick Search (Ctrl+K or Cmd+K)
    const searchInput = document.getElementById('adminQuickSearchInput');
    if (searchInput) {
        document.addEventListener('keydown', function (e) {
            if ((e.ctrlKey || e.metaKey) && e.key.toLowerCase() === 'k') {
                e.preventDefault();
                searchInput.focus();
                searchInput.select();
            }
        });

        // Instant filter on sidebar menu items
        searchInput.addEventListener('input', function () {
            const query = this.value.trim().toLowerCase();
            const menuLinks = document.querySelectorAll('.admin-menu-link');
            const categories = document.querySelectorAll('.admin-menu-category');

            if (query === '') {
                menuLinks.forEach(l => l.style.display = '');
                categories.forEach(c => c.style.display = '');
                return;
            }

            menuLinks.forEach(link => {
                const text = link.textContent.toLowerCase();
                const title = (link.getAttribute('title') || '').toLowerCase();
                if (text.includes(query) || title.includes(query)) {
                    link.style.display = '';
                } else {
                    link.style.display = 'none';
                }
            });

            // Hide categories if all their children are hidden
            categories.forEach(cat => {
                let next = cat.nextElementSibling;
                let hasVisible = false;
                while (next && !next.classList.contains('admin-menu-category')) {
                    if (next.classList.contains('admin-menu-link') && next.style.display !== 'none') {
                        hasVisible = true;
                        break;
                    }
                    next = next.nextElementSibling;
                }
                cat.style.display = hasVisible ? '' : 'none';
            });
        });
    }

    // 3. Global SweetAlert2 Confirmation on Delete Links
    document.querySelectorAll('a[href*="action=delete"], a[href*="delete="]').forEach(function(el) {
        // Prevent default native alert override
        el.removeAttribute('onclick');
        el.addEventListener('click', function(e) {
            e.preventDefault();
            const href = this.getAttribute('href');
            if (!href) return;
            
            if (typeof Swal !== 'undefined') {
                Swal.fire({
                    title: 'Konfirmasi Penghapusan',
                    text: 'Apakah Anda yakin ingin menghapus data ini? Tindakan ini tidak dapat dibatalkan.',
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#DC2626',
                    cancelButtonColor: '#64748B',
                    confirmButtonText: '<i class="bi bi-trash me-1"></i> Ya, Hapus Data',
                    cancelButtonText: 'Batal',
                    reverseButtons: true,
                    customClass: {
                        popup: 'rounded-4 shadow-lg border-0'
                    }
                }).then((result) => {
                    if (result.isConfirmed) {
                        window.location.href = href;
                    }
                });
            } else {
                if (confirm('Apakah Anda yakin ingin menghapus data ini?')) {
                    window.location.href = href;
                }
            }
        });
    });

    // 4. Dashboard V14 Theme Mode Toggle
    const themeToggleBtn = document.getElementById('themeToggleBtn');
    if (themeToggleBtn) {
        themeToggleBtn.addEventListener('click', function() {
            const currentTheme = document.documentElement.getAttribute('data-theme') || 'light';
            const nextTheme = currentTheme === 'light' ? 'dark' : 'light';
            document.documentElement.setAttribute('data-theme', nextTheme);
            localStorage.setItem('v14_theme', nextTheme);
            const icon = themeToggleBtn.querySelector('i');
            if (icon) {
                icon.className = nextTheme === 'dark' ? 'bi bi-sun' : 'bi bi-moon-stars';
            }
        });
        // Restore saved theme
        const savedTheme = localStorage.getItem('v14_theme');
        if (savedTheme) {
            document.documentElement.setAttribute('data-theme', savedTheme);
            const icon = themeToggleBtn.querySelector('i');
            if (icon && savedTheme === 'dark') {
                icon.className = 'bi bi-sun';
            }
        }
    }

    // 5. Global Toast Notification Helper
    window.showAdminToast = function(title, icon = 'success') {
        if (typeof Swal !== 'undefined') {
            const Toast = Swal.mixin({
                toast: true,
                position: 'top-end',
                showConfirmButton: false,
                timer: 3000,
                timerProgressBar: true,
                didOpen: (toast) => {
                    toast.addEventListener('mouseenter', Swal.stopTimer);
                    toast.addEventListener('mouseleave', Swal.resumeTimer);
                }
            });
            Toast.fire({
                icon: icon,
                title: title
            });
        }
    };
});
</script>
