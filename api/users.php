<?php

if ($method === 'GET') {
    // index
    $stmt = $pdo->query("SELECT * FROM users ORDER BY created_at DESC");
    echo json_encode($stmt->fetchAll());
} elseif ($method === 'POST') {
    // store
    $name = $input['name'] ?? null;
    $email = $input['email'] ?? null;

    if (!$name || !$email) {
        http_response_code(400);
        echo json_encode(["message" => "Nama dan Email wajib diisi"]);
        exit;
    }

    try {
        $stmt = $pdo->prepare("INSERT INTO users (name, email) VALUES (?, ?)");
        $stmt->execute([$name, $email]);

        http_response_code(201);
        echo json_encode(["message" => "User berhasil didaftarkan"]);
    } catch (PDOException $e) {
        http_response_code(400);
        echo json_encode(["message" => "Email sudah terdaftar atau error"]);
    }
}
