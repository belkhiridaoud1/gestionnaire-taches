<?php

require_once __DIR__ . '/../config/database.php';

$search = trim($_GET['search'] ?? '');
$status = $_GET['status'] ?? '';

$sql = "
    SELECT
        tasks.*,
        categories.name AS category_name
    FROM tasks
    LEFT JOIN categories
        ON tasks.category_id = categories.id
    WHERE 1=1
";

$params = [];

if ($search !== '') {
    $sql .= " AND (tasks.title LIKE :search OR tasks.description LIKE :search)";
    $params['search'] = '%' . $search . '%';
}

if ($status !== '') {
    $sql .= " AND tasks.status = :status";
    $params['status'] = $status;
}

$sql .= " ORDER BY tasks.created_at DESC";

$stmt = $pdo->prepare($sql);
$stmt->execute($params);

$tasks = $stmt->fetchAll();

?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Tâches - Gestionnaire</title>

    <link rel="stylesheet" href="../assets/style.css?v=2">
</head>

<body>

<div class="layout">

    <aside class="sidebar">

        <h2>Gestionnaire</h2>

        <nav>
            <a href="../index.php">Dashboard</a>
            <a href="index.php" class="active">Tâches</a>
            <a href="../categories/index.php">Catégories</a>
        </nav>

    </aside>

    <main class="main-content">

        <div class="page-header">

            <div>
                <h1>Tâches</h1>
                <p>Gérer toutes vos tâches</p>
            </div>

            <a href="create.php" class="btn">
                + Nouvelle tâche
            </a>

        </div>

        <div class="card">

            <form method="GET" class="filters">

                <input
                    type="text"
                    name="search"
                    placeholder="Rechercher une tâche..."
                    value="<?= htmlspecialchars($search) ?>"
                >

                <select name="status">

                    <option value="">Tous les statuts</option>

                    <option value="À faire"
                        <?= $status === 'À faire' ? 'selected' : '' ?>>
                        À faire
                    </option>

                    <option value="En cours"
                        <?= $status === 'En cours' ? 'selected' : '' ?>>
                        En cours
                    </option>

                    <option value="Terminée"
                        <?= $status === 'Terminée' ? 'selected' : '' ?>>
                        Terminée
                    </option>

                </select>

                <button type="submit" class="btn">
                    Rechercher
                </button>

                <a href="index.php" class="btn">
                    Réinitialiser
                </a>

            </form>

        </div>

        <div class="card">

            <h2>Liste des tâches</h2>

            <p style="margin: 8px 0 20px;">
                <?= count($tasks) ?> tâche(s)
            </p>

            <?php if (empty($tasks)): ?>

                <p>Aucune tâche trouvée.</p>

            <?php else: ?>

                <table>

                    <thead>
                        <tr>
                            <th>Titre</th>
                            <th>Catégorie</th>
                            <th>Statut</th>
                            <th>Priorité</th>
                            <th>Date limite</th>
                            <th>Actions</th>
                        </tr>
                    </thead>

                    <tbody>

                    <?php foreach ($tasks as $task): ?>

                        <tr>

                            <td>
                                <strong>
                                    <?= htmlspecialchars($task['title']) ?>
                                </strong>

                                <?php if (!empty($task['description'])): ?>
                                    <br>
                                    <small>
                                        <?= htmlspecialchars($task['description']) ?>
                                    </small>
                                <?php endif; ?>
                            </td>

                            <td>
                                <?= htmlspecialchars(
                                    $task['category_name'] ?? 'Sans catégorie'
                                ) ?>
                            </td>

                            <td>
                                <?= htmlspecialchars($task['status']) ?>
                            </td>

                            <td>
                                <?= htmlspecialchars($task['priority']) ?>
                            </td>

                            <td>
                                <?= $task['due_date']
                                    ? htmlspecialchars($task['due_date'])
                                    : '-' ?>
                            </td>

                            <td>

                                <a href="edit.php?id=<?= (int) $task['id'] ?>">
                                    Modifier
                                </a>

                                |

                                <?php if ($task['status'] !== 'Terminée'): ?>

                                    <a href="complete.php?id=<?= (int) $task['id'] ?>">
                                        Terminer
                                    </a>

                                    |

                                <?php endif; ?>

                                <a
                                    href="delete.php?id=<?= (int) $task['id'] ?>"
                                    onclick="return confirm('Supprimer cette tâche ?')"
                                >
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