<?php
// Parametri del database
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "ticket_manager";

// Creazione istanza
$conn = new mysqli($servername, $username, $password, $dbname);

// Errori
if ($conn->connect_error) {
    die(json_encode(["success" => false, "message" => "Errore di connessione al database: " . $conn->connect_error]));
}
?>