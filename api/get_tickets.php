<?php
header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: GET');

$servername = "localhost"; // Modifica se necessario
$username = "root"; // Modifica con il tuo utente del database
$password = ""; // Modifica con la tua password
$dbname = "ticket_manager";

// Connessione al database
$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die(json_encode(["error" => "Connessione fallita: " . $conn->connect_error]));
}

// Recupera i ticket
$sql = "SELECT id, user_id, cat_id, data_ora, descrizione, stato, admin_id FROM tickets ORDER BY data_ora DESC";
$result = $conn->query($sql);

$tickets = [];
if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $tickets[] = $row;
    }
}

// Restituisce i ticket in formato JSON
echo json_encode($tickets);

$conn->close();
?>