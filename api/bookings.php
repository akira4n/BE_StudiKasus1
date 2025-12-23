<?php

if ($method === 'GET') {
    // index
    $query = "SELECT 
                bookings.id, 
                users.name as user_name, 
                events.title as event_title, 
                bookings.status, 
                bookings.created_at 
              FROM bookings
              JOIN users ON bookings.user_id = users.id
              JOIN events ON bookings.event_id = events.id
              ORDER BY bookings.created_at DESC";

    $stmt = $pdo->query($query);
    echo json_encode($stmt->fetchAll());
} elseif ($method === 'POST') {
    // store
    $user_id = $input['user_id'] ?? null;
    $event_id = $input['event_id'] ?? null;

    if (!$user_id || !$event_id) {
        http_response_code(400);
        echo json_encode(["message" => "Data tidak lengkap"]);
        exit;
    }

    try {
        $pdo->beginTransaction();

        $stmt = $pdo->prepare("SELECT remaining_stock FROM events WHERE id = ? FOR UPDATE");
        $stmt->execute([$event_id]);
        $event = $stmt->fetch();

        if (!$event || $event['remaining_stock'] <= 0) {
            throw new Exception("Tiket habis");
        }

        $pdo->prepare("UPDATE events SET remaining_stock = remaining_stock - 1 WHERE id = ?")->execute([$event_id]);
        $pdo->prepare("INSERT INTO bookings (user_id, event_id, status) VALUES (?, ?, 'paid')")->execute([$user_id, $event_id]);

        $pdo->commit();

        http_response_code(201);
        echo json_encode(["message" => "Tiket berhasil di pesan"]);
    } catch (Exception $e) {
        $pdo->rollBack();
        http_response_code(400);
        echo json_encode(["error" => $e->getMessage()]);
    }
} elseif ($method === 'PATCH') {
    // patch ubah status
    $booking_id = $input['id'] ?? null;
    $new_status = $input['status'] ?? null;

    if (!$booking_id || !$new_status) {
        http_response_code(400);
        echo json_encode(["message" => "id booking dan status baru harus diisi"]);
        exit;
    }

    try {
        $pdo->beginTransaction();

        $stmt = $pdo->prepare("SELECT event_id, status FROM bookings WHERE id = ? FOR UPDATE");
        $stmt->execute([$booking_id]);
        $old_booking = $stmt->fetch();

        if (!$old_booking) {
            throw new Exception("data booking tidak ditemukan");
        }

        // stok nya +1 kalo status berubah ke cancel/exp
        if (($old_booking['status'] !== 'cancelled' && $old_booking['status'] !== 'expired') &&
            ($new_status === 'cancelled' || $new_status === 'expired')
        ) {
            $updateStock = $pdo->prepare("UPDATE events SET remaining_stock = remaining_stock + 1 WHERE id = ?");
            $updateStock->execute([$old_booking['event_id']]);
        }

        $updateStatus = $pdo->prepare("UPDATE bookings SET status = ? WHERE id = ?");
        $updateStatus->execute([$new_status, $booking_id]);

        $pdo->commit();
        echo json_encode(["message" => "Status booking berhasil diperbarui!"]);
    } catch (Exception $e) {
        $pdo->rollBack();
        http_response_code(400);
        echo json_encode(["error" => $e->getMessage()]);
    }
}
