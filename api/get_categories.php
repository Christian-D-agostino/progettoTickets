<?php
session_start();
header("Access-Control-Allow-Methods: POST");
header("Content-Type: application/json");
header("Access-Control-Allow-Origin: *");

// Verifica che la richiesta sia di tipo POST
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    // Se non è POST, restituisci un errore
    echo json_encode(["error" => "Metodo non consentito. Solo POST è supportato."]);
    http_response_code(405); // Metodo non permesso
    exit();
}

// Verifica che l'utente sia loggato
if (!isset($_SESSION['user_id'])) {
    echo json_encode(["error" => "Accesso non autorizzato. Devi essere loggato per visualizzare le categorie."]);
    http_response_code(403); // Accesso vietato
    exit();
}

//Connessione al database
require_once 'db_connection.php';
// Riporta problemi di connessione con il database
if ($conn->connect_error) {
    die(json_encode(["error" => "Connessione fallita: " . $conn->connect_error]));
}

$sql = "SELECT id, tipo, descrizione FROM cat_tickets";
$result = $conn->query($sql);

$categories = [];
if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $categories[] = $row;
    }
} else if ($result->num_rows === 0) {
    http_response_code(404);
    echo json_encode(["error" => "Nessuna categoria trovata"]);
}

echo json_encode($categories);
$conn->close();
?>