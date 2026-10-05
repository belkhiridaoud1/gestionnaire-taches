<?php

require_once __DIR__ . '/../config/database.php';

$id = filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT);

if ($id) {
    $stmt = $pdo->prepare("
        UPDATE tasks
        SET status = 'Terminée'
        WHERE id = :id
    ");

    $stmt->execute([
        'id' => $id
    ]);
}

header('Location: index.php');
exit;