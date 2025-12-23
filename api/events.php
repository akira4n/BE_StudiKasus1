<?php

if ($method === 'GET') {
    // index
    $stmt = $pdo->query("SELECT * FROM events");
    echo json_encode($stmt->fetchAll());
} elseif ($method === 'POST') {
    // store
    if (!isset($input['title'], $input['capacity'])) {
        http_response_code(400);
        echo json_encode(["message" => "Data tidak lengkap"]);
        exit;
    }

    $stmt = $pdo->prepare("INSERT INTO events (title, total_capacity, remaining_stock) VALUES (?, ?, ?)");
    $stmt->execute([$input['title'], $input['capacity'], $input['capacity']]);

    echo json_encode(["message" => "Event berhasil dibuat"]);
}
