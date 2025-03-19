<?php
session_start();
header("Content-Type: application/json");
require 'db_connection.php'; // Assicurati che questo file gestisca la connessione al database

// Controllo accessi: l'utente deve essere loggato e avere ruolo admin o superadmin
if (!isset($_SESSION['user_id']) || !isset($_SESSION['user_role'])) {
    echo json_encode(["success" => false, "message" => "Accesso negato."]);
    exit;
}

if ($_SESSION['user_role'] !== 'admin' && $_SESSION['user_role'] !== 'superadmin') {
    echo json_encode(["success" => false, "message" => "Permessi insufficienti."]);
    exit;
}

// Ricezione e decodifica della richiesta JSON
$input = file_get_contents("php://input");
$data = json_decode($input, true);

if (isset($data['ticket_id'])) {
    $ticket_id = intval($data['ticket_id']);
    $admin_id = intval($_SESSION['user_id']);

    // Prepara ed esegui la query per aggiornare il campo admin_id
    $stmt = $conn->prepare("UPDATE tickets SET admin_id = ? , stato = ? WHERE id = ?");
    if ($stmt === false) {
        echo json_encode(["success" => false, "message" => "Errore nella preparazione della query."]);
        exit;
    }
    $stato = "pending";
    $stmt->bind_param("isi", $admin_id, $stato, $ticket_id);

    if ($stmt->execute()) {
        echo json_encode(["success" => true, "message" => "Ticket aggiornato con successo."]);
    } else {
        echo json_encode(["success" => false, "message" => "Errore durante l'aggiornamento."]);
    }

    $stmt->close();
    $conn->close();
} else {
    echo json_encode(["success" => false, "message" => "Richiesta non valida."]);
}
?>
