        </div> <!-- .admin-content-area -->

        <footer class="admin-footer">
            <p>&copy; <?= date('Y') ?> <strong>FaunaID Administration System</strong>. Hak Cipta Dilindungi.</p>
        </footer>
    </div> <!-- .admin-main -->
</div> <!-- .admin-layout -->

<script src="<?= asset('js/main.js') ?>"></script>
<script>
    // Toggle mobile sidebar in admin
    const sidebar = document.getElementById('adminSidebar');
    const toggleBtn = document.getElementById('sidebarToggleBtn');
    const closeBtn = document.getElementById('sidebarCloseBtn');

    if (toggleBtn && sidebar) {
        toggleBtn.addEventListener('click', () => {
            sidebar.classList.toggle('active');
        });
    }

    if (closeBtn && sidebar) {
        closeBtn.addEventListener('click', () => {
            sidebar.classList.remove('active');
        });
    }
</script>

</body>
</html>

