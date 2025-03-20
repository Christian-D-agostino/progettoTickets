<?php
// Avvia la sessione
session_start();

// Controlla se l'utente è autenticato
if (!isset($_SESSION['user_id'])) {
    echo json_encode(["success" => false, "message" => "Utente non autenticato."]);
    exit();
}

// Verifica che la richiesta sia di tipo POST
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    echo json_encode(["success" => false, "message" => "Richiesta non valida."]);
    exit();
}
?>
