<?php
session_start();
require_once 'db_connection.php';

$data = json_decode(file_get_contents("php://input"));

if (isset($data->message) && !empty($data->message)) {
    $message = $data->message;

    $user_id = $_SESSION['user_id'];

    $sql = "INSERT INTO messages (user_id, message) VALUES (?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("is", $user_id, $message);

    if ($stmt->execute()) {
        echo json_encode(["success" => true, "message" => "Messaggio inviato con successo"]);
    } else {
        echo json_encode(["success" => false, "message" => "Errore nell'invio del messaggio"]);
    }

    $stmt->close();
} else {
    echo json_encode(["success" => false, "message" => "Messaggio vuoto"]);
}

$conn->close();
?>
