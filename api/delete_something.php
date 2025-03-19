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
    echo json_encode(["error" => "Accesso non autorizzato. Devi essere loggato e avere i permessi necessari per visualizzare lgli utenti."]);
    http_response_code(403); // Accesso vietato
    exit();
}

// Connessione al database
require_once 'db_connection.php';

// Recupera i dati JSON inviati
$data = json_decode(file_get_contents("php://input"), true);
if(!isset($data['id']) || !isset($data['table'])){
    echo json_encode(["success" => false, "message" => "ID mancante."]);
    exit();
}

$id = $conn->real_escape_string($data['id']);
$table = $conn->real_escape_string($data['table']);

// Controlla che l'utente non stia cancellando se stesso
if ($table == 'users' && $_SESSION['user_id'] == $id) {
    echo json_encode(["success" => false, "message" => "Stai cancellando te stesso."]);
    exit();
} else {
    // Elimina il ticket dal database
    $sql = "DELETE FROM $table WHERE id = '$id'";
    if($conn->query($sql) === TRUE){
        echo json_encode(["success" => true]);
    } else {
        echo json_encode(["success" => false, "message" => "Errore nell'eliminazione: " . $conn->error]);
    }
}

$conn->close();

?>