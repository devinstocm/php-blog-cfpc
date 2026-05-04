<?php

declare(strict_types=1);

session_start();
require_once 'database/database.php';
require_once 'flash.php';

if (!isset($_SESSION['auth']) || $_SESSION['role'] !== 'admin') {
    flash_set('error', "Accès refusé ! Vous devez être administrateur.");
    header("Location: login.php");
    exit();
}

$pageTitle = 'Administration';
ob_start();
?>
<div class="admin-dashboard">
    <h1>Tableau de Bord Administrateur</h1>
    <p>Bienvenue, <?= htmlspecialchars($_SESSION['username']) ?> !</p>
    <div class="stats-grid">
        <div class="stat-card">
            <h3>Articles</h3>
            <p>Gérer les publications</p>
            <a href="#" class="btn">Voir tout</a>
        </div>
        <div class="stat-card">
            <h3>Utilisateurs</h3>
            <p>Gérer les membres</p>
            <a href="#" class="btn">Voir tout</a>
        </div>
    </div>
</div>
<?php
$pageContent = ob_get_clean();
require_once 'resources/views/layouts/blog-layout/blog-layout_html.php';


