<?php

require_once __DIR__ . '/../config/database.php';

$id = filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT);

if (!$id) {
    header('Location: index.php');
    exit;
}

$categoriesStmt = $pdo->query("
    SELECT id, name
    FROM categories
    ORDER BY name ASC
");

$categories = $categoriesStmt->fetchAll();

$stmt = $pdo->prepare("
    SELECT *
    FROM tasks
    WHERE id = :id
");

$stmt->execute(['id' => $id]);

$task = $stmt->fetch();

if (!$task) {
    header('Location: index.php');
    exit;
}

$errors = [];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $title = trim($_POST['title'] ?? '');
    $description = trim($_POST['description'] ?? '');
    $status = $_POST['status'] ?? 'À faire';
    $priority = $_POST['priority'] ?? 'Moyenne';
    $dueDate = $_POST['due_date'] ?? '';
    $categoryId = $_POST['category_id'] ?? '';

    if ($title === '') {
        $errors[] = 'Le titre est obligatoire.';
    }

    if (!in_array($status, ['À faire', 'En cours', 'Terminée'], true)) {
        $errors[] = 'Le statut sélectionné est invalide.';
    }

    if (!in_array($priority, ['Basse', 'Moyenne', 'Haute'], true)) {
        $errors[] = 'La priorité sélectionnée est invalide.';
    }

    if ($categoryId !== '' && !ctype_digit($categoryId)) {
        $errors[] = 'La catégorie sélectionnée est invalide.';
    }

    if (empty($errors)) {

        $categoryId = $categoryId === ''
            ? null
            : (int) $categoryId;

        $dueDate = $dueDate === ''
            ? null
            : $dueDate;

        $updateStmt = $pdo->prepare("
            UPDATE tasks
            SET
                title = :title,
                description = :description,
                status = :status,
                priority = :priority,
                due_date = :due_date,
                category_id = :category_id
            WHERE id = :id
        ");

        $updateStmt->execute([
            'title' => $title,
            'description' => $description,
            'status' => $status,
            'priority' => $priority,
            'due_date' => $dueDate,
            'category_id' => $categoryId,
            'id' => $id
        ]);

        header('Location: index.php');
        exit;
    }

    $task['title'] = $title;
    $task['description'] = $description;
    $task['status'] = $status;
    $task['priority'] = $priority;
    $task['due_date'] = $dueDate;
    $task['category_id'] = $categoryId;
}

?>

<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">

    <meta
        name="viewport"
        content="width=device-width, initial-scale=1.0"
    >

    <title>Modifier une tâche - Gestionnaire</title>

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
                <h1>Modifier une tâche</h1>
                <p>Modifier les informations de la tâche</p>
            </div>

        </div>

        <div class="card">

            <?php if (!empty($errors)): ?>

                <div class="card">

                    <?php foreach ($errors as $error): ?>

                        <p>
                            <?= htmlspecialchars(
                                $error,
                                ENT_QUOTES,
                                'UTF-8'
                            ) ?>
                        </p>

                    <?php endforeach; ?>

                </div>

            <?php endif; ?>

            <form method="POST">

                <div class="form-group">

                    <label for="title">
                        Titre *
                    </label>

                    <input
                        type="text"
                        id="title"
                        name="title"
                        value="<?= htmlspecialchars(
                            $task['title'],
                            ENT_QUOTES,
                            'UTF-8'
                        ) ?>"
                        required
                    >

                </div>

                <div class="form-group">

                    <label for="description">
                        Description
                    </label>

                    <textarea
                        id="description"
                        name="description"
                        rows="5"
                    ><?= htmlspecialchars(
                        $task['description'] ?? '',
                        ENT_QUOTES,
                        'UTF-8'
                    ) ?></textarea>

                </div>

                <div class="form-group">

                    <label for="status">
                        Statut
                    </label>

                    <select id="status" name="status">

                        <option value="À faire"
                            <?= $task['status'] === 'À faire'
                                ? 'selected'
                                : '' ?>>
                            À faire
                        </option>

                        <option value="En cours"
                            <?= $task['status'] === 'En cours'
                                ? 'selected'
                                : '' ?>>
                            En cours
                        </option>

                        <option value="Terminée"
                            <?= $task['status'] === 'Terminée'
                                ? 'selected'
                                : '' ?>>
                            Terminée
                        </option>

                    </select>

                </div>

                <div class="form-group">

                    <label for="priority">
                        Priorité
                    </label>

                    <select id="priority" name="priority">

                        <option value="Basse"
                            <?= $task['priority'] === 'Basse'
                                ? 'selected'
                                : '' ?>>
                            Basse
                        </option>

                        <option value="Moyenne"
                            <?= $task['priority'] === 'Moyenne'
                                ? 'selected'
                                : '' ?>>
                            Moyenne
                        </option>

                        <option value="Haute"
                            <?= $task['priority'] === 'Haute'
                                ? 'selected'
                                : '' ?>>
                            Haute
                        </option>

                    </select>

                </div>

                <div class="form-group">

                    <label for="due_date">
                        Date limite
                    </label>

                    <input
                        type="date"
                        id="due_date"
                        name="due_date"
                        value="<?= htmlspecialchars(
                            $task['due_date'] ?? '',
                            ENT_QUOTES,
                            'UTF-8'
                        ) ?>"
                    >

                </div>

                <div class="form-group">

                    <label for="category_id">
                        Catégorie
                    </label>

                    <select
                        id="category_id"
                        name="category_id"
                    >

                        <option value="">
                            Sans catégorie
                        </option>

                        <?php foreach ($categories as $category): ?>

                            <option
                                value="<?= (int) $category['id'] ?>"
                                <?= (string)$task['category_id'] === (string)$category['id']
                                    ? 'selected'
                                    : '' ?>
                            >
                                <?= htmlspecialchars(
                                    $category['name'],
                                    ENT_QUOTES,
                                    'UTF-8'
                                ) ?>
                            </option>

                        <?php endforeach; ?>

                    </select>

                </div>

                <button type="submit" class="btn">
                    Enregistrer les modifications
                </button>

                <a href="index.php" class="btn">
                    Annuler
                </a>

            </form>

        </div>

    </main>

</div>

</body>
</html>