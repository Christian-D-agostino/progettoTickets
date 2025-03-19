<?php
header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: POST');

$servername = "localhost";
$username = "root";
$password = "";
$dbname = "ticket_manager";

// Connessione al database
$conn = new mysqli($servername, $username, $password, $dbname);
if($conn->connect_error){
    die(json_encode(["success" => false, "message" => "Connessione fallita: " . $conn->connect_error]));
}

// Recupera i dati JSON inviati
$data = json_decode(file_get_contents("php://input"), true);
if(!isset($data['id']) || !isset($data['descrizione'])){
    echo json_encode(["success" => false, "message" => "Dati mancanti."]);
    exit();
}

$id = $conn->real_escape_string($data['id']);
$descrizione = $conn->real_escape_string($data['descrizione']);

// Aggiorna il ticket nel database
$sql = "UPDATE tickets SET descrizione = '$descrizione' WHERE id = '$id'";
if($conn->query($sql) === TRUE){
    echo json_encode(["success" => true]);
} else {
    echo json_encode(["success" => false, "message" => "Errore nell'aggiornamento: " . $conn->error]);
}
$conn->close();
?>