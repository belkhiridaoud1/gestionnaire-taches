<?php

require_once __DIR__ . '/../config/database.php';

$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $name = trim($_POST['name'] ?? '');

    if ($name === '') {
        $error = 'Le nom de la catégorie est obligatoire.';
    } else {

        try {
            $stmt = $pdo->prepare("
                INSERT INTO categories (name)
                VALUES (:name)
            ");

            $stmt->execute([
                'name' => $name
            ]);

            header('Location: index.php');
            exit;

        } catch (PDOException $e) {
            $error = 'Cette catégorie existe déjà.';
        }
    }
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Nouvelle catégorie</title>
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
                <h1>Nouvelle catégorie</h1>
                <p>Ajouter une nouvelle catégorie.</p>
            </div>
        </div>

        <div class="card">

            <?php if ($error): ?>
                <p><?= htmlspecialchars($error) ?></p>
            <?php endif; ?>

            <form method="POST">

                <div class="form-group">
                    <label for="name">Nom de la catégorie</label>

                    <input
                        type="text"
                        id="name"
                        name="name"
                        required
                    >
                </div>

                <button type="submit" class="btn">
                    Ajouter
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