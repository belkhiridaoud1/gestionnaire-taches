<?php

require_once __DIR__ . '/../config/database.php';

$stmt = $pdo->query("
    SELECT c.id, c.name, COUNT(t.id) AS task_count
    FROM categories c
    LEFT JOIN tasks t ON t.category_id = c.id
    GROUP BY c.id, c.name
    ORDER BY c.name ASC
");

$categories = $stmt->fetchAll();
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Catégories - Gestionnaire de tâches</title>
    <link rel="stylesheet" href="../assets/style.css?v=2">
</head>
<body>

<div class="layout">

    <aside class="sidebar">
        <h2>Gestionnaire</h2>

        <nav>
            <a href="../index.php">Dashboard</a>
            <a href="../tasks/index.php">Tâches</a>
            <a href="index.php" class="active">Catégories</a>
        </nav>
    </aside>

    <main class="main-content">

        <div class="page-header">
            <div>
                <h1>Catégories</h1>
                <p>Gérez les catégories de vos tâches.</p>
            </div>

            <a href="create.php" class="btn">+ Nouvelle catégorie</a>
        </div>

        <div class="card">

            <?php if (empty($categories)): ?>
                <p>Aucune catégorie trouvée.</p>
            <?php else: ?>

                <table>
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Nom</th>
                            <th>Nombre de tâches</th>
                            <th>Actions</th>
                        </tr>
                    </thead>

                    <tbody>
                        <?php foreach ($categories as $category): ?>
                            <tr>
                                <td><?= (int) $category['id'] ?></td>

                                <td>
                                    <?= htmlspecialchars($category['name']) ?>
                                </td>

                                <td>
                                    <?= (int) $category['task_count'] ?>
                                </td>

                                <td>
                                    <a href="edit.php?id=<?= (int) $category['id'] ?>">
                                        Modifier
                                    </a>

                                    |

                                    <a href="delete.php?id=<?= (int) $category['id'] ?>"
                                       onclick="return confirm('Supprimer cette catégorie ?')">
                                        Supprimer
                                    </a>
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