<?php

require_once __DIR__ . '/config/database.php';

$totalTasks = $pdo->query("SELECT COUNT(*) FROM tasks")->fetchColumn();

$todoTasks = $pdo->query("
    SELECT COUNT(*)
    FROM tasks
    WHERE status = 'À faire'
")->fetchColumn();

$inProgressTasks = $pdo->query("
    SELECT COUNT(*)
    FROM tasks
    WHERE status = 'En cours'
")->fetchColumn();

$completedTasks = $pdo->query("
    SELECT COUNT(*)
    FROM tasks
    WHERE status = 'Terminée'
")->fetchColumn();

$recentTasks = $pdo->query("
    SELECT
        tasks.id,
        tasks.title,
        tasks.status,
        tasks.priority,
        tasks.due_date,
        categories.name AS category_name
    FROM tasks
    LEFT JOIN categories
        ON tasks.category_id = categories.id
    ORDER BY tasks.created_at DESC
    LIMIT 5
")->fetchAll();

?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard - Gestionnaire de tâches</title>

    <link rel="stylesheet" href="assets/style.css?v=2">
</head>

<body>

<div class="layout">

    <aside class="sidebar">

        <h2>Gestionnaire</h2>

        <nav>
            <a href="index.php" class="active">Dashboard</a>
            <a href="tasks/index.php">Tâches</a>
            <a href="categories/index.php">Catégories</a>
        </nav>

    </aside>

    <main class="main-content">

        <div class="page-header">

            <div>
                <h1>Dashboard</h1>
                <p>Vue d'ensemble de vos tâches</p>
            </div>

        </div>

        <section class="stats">

            <div class="stat-card">
                <h3>Total des tâches</h3>
                <div class="number"><?= (int) $totalTasks ?></div>
            </div>

            <div class="stat-card">
                <h3>À faire</h3>
                <div class="number"><?= (int) $todoTasks ?></div>
            </div>

            <div class="stat-card">
                <h3>En cours</h3>
                <div class="number"><?= (int) $inProgressTasks ?></div>
            </div>

            <div class="stat-card">
                <h3>Terminées</h3>
                <div class="number"><?= (int) $completedTasks ?></div>
            </div>

        </section>

        <div class="card">

            <div class="page-header">

                <div>
                    <h2>Tâches récentes</h2>
                    <p>Les dernières tâches ajoutées</p>
                </div>

                <a href="tasks/create.php" class="btn">
                    + Nouvelle tâche
                </a>

            </div>

            <?php if (empty($recentTasks)): ?>

                <p>Aucune tâche disponible.</p>

            <?php else: ?>

                <table>

                    <thead>
                        <tr>
                            <th>Titre</th>
                            <th>Catégorie</th>
                            <th>Statut</th>
                            <th>Priorité</th>
                            <th>Date limite</th>
                        </tr>
                    </thead>

                    <tbody>

                    <?php foreach ($recentTasks as $task): ?>

                        <tr>

                            <td>
                                <?= htmlspecialchars($task['title'], ENT_QUOTES, 'UTF-8') ?>
                            </td>

                            <td>
                                <?= htmlspecialchars(
                                    $task['category_name'] ?? 'Sans catégorie',
                                    ENT_QUOTES,
                                    'UTF-8'
                                ) ?>
                            </td>

                            <td>
                                <?= htmlspecialchars(
                                    $task['status'],
                                    ENT_QUOTES,
                                    'UTF-8'
                                ) ?>
                            </td>

                            <td>
                                <?= htmlspecialchars(
                                    $task['priority'],
                                    ENT_QUOTES,
                                    'UTF-8'
                                ) ?>
                            </td>

                            <td>
                                <?= $task['due_date']
                                    ? htmlspecialchars($task['due_date'], ENT_QUOTES, 'UTF-8')
                                    : '-' ?>
                            </td>

                        </tr>

                    <?php endforeach; ?>

                    </tbody>

                </table>

            <?php endif; ?>

        </div>

    </main>

</div>

</body>
</html>