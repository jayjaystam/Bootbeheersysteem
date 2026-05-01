<aside class="sidebar">
    <h2>Bootbeheer</h2>

    <nav>
        <a href="dashboard.php" class="<?= $currentPage === 'dashboard' ? 'active' : ''; ?>">
            Dashboard
        </a>

        <a href="boten.php" class="<?= $currentPage === 'boten' ? 'active' : ''; ?>">
            Boten
        </a>

        <a href="onderhoud.php" class="<?= $currentPage === 'onderhoud' ? 'active' : ''; ?>">
            Onderhoud
        </a>

        <?php if ($rol === 'beheerder'): ?>
            <a href="planning.php" class="<?= $currentPage === 'planning' ? 'active' : ''; ?>">
                Planning
            </a>
        <?php endif; ?>
    </nav>

    <a href="logout.php" class="logout-link">Uitloggen</a>
</aside>