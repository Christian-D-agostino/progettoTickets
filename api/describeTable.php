<?php
header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: GET');

$servername = "localhost"; // Modifica se necessario
$username = "root"; // Modifica con il tuo utente del database
$password = ""; // Modifica con la tua password
$dbname = "ticket_manager";
$table = $_GET['table'] ?? ''; // Prendi il nome della tabella dalla richiesta

if (!$table) {
    echo json_encode(["error" => "Nome della tabella non specificato"]);
    exit;
}

// Connessione al database
$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die(json_encode(["error" => "Connessione fallita: " . $conn->connect_error]));
}

$sql = "DESCRIBE $table";
$result = $conn->query($sql);

if (!$result) {
    echo json_encode(["error" => "Errore nella query: " . $conn->error]);
    exit;
}

$columns = [];
while ($row = $result->fetch_assoc()) {
    $columns[] = $row;
}

$conn->close();
echo json_encode($columns);
?>
