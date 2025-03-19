<?php
session_start();

// Controllo che la richiesta sia di tipo POST
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    session_unset();  // Rimuove tutte le variabili di sessione
    session_destroy(); // Distrugge la sessione

    // Reindirizzamento da file
    echo json_encode(["success" => true, "message" => "Logout effettuato con successo."]);
} else {
    // Se la richiesta non è di tipo POST, invia un errore
    echo json_encode(["success" => false, "message" => "Metodo non consentito."]);
    http_response_code(405); // Metodo non consentito
}

exit();
?>