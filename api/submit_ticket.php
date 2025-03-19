<?php
// Headers, Session & POST check
require_once 'session_POST_check.php';

//Connessione al database
require_once 'db_connection.php';

// Recupera i dati dal JSON inviato
require_once 'json_decode.php';

$tipologia_problema = isset($data['tipologia_problema']) ? $data['tipologia_problema'] : "";
$descrizione_problema = isset($data['descrizione_problema']) ? trim($data['descrizione_problema']) : "";
$user_id = $_SESSION['user_id'];

// Validazione campi obbligatori
if (empty($tipologia_problema) || empty($descrizione_problema)) {
    echo json_encode(["success" => false, "message" => "Tutti i campi sono obbligatori."]);
    exit();
}

// Query di inserimento
$sql = "INSERT INTO tickets (user_id, cat_id, descrizione) VALUES (?, ?, ?)";
$stmt = $conn->prepare($sql);
$stmt->bind_param("iis", $user_id, $tipologia_problema, $descrizione_problema);

if ($stmt->execute()) {
    echo json_encode(["success" => true, "message" => "Ticket inserito con successo."]);
} else {
    echo json_encode(["success" => false, "message" => "Errore durante l'inserimento del ticket."]);
}

// Chiudi connessione
$stmt->close();
$conn->close();
?>