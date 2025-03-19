<?php
// Legge il JSON ricevuto
$input = file_get_contents("php://input");
$data = json_decode($input, true);

if (!$data) {
    die(json_encode(["error" => "Connessione fallita: " . $conn->connect_error]));
}
?>