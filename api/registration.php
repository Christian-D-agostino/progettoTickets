<?php
header("Access-Control-Allow-Methods: POST");
header("Content-Type: application/json");
header("Access-Control-Allow-Origin: *");

//Connessione al database
require_once 'db_connection.php';

// Legge il JSON ricevuto
require_once 'json_decode.php';

// Estrai i dati ricevuti
$nome = trim($data['nome'] ?? '');
$cognome = trim($data['cognome'] ?? '');
$email = trim($data['email'] ?? '');
$password = $data['password'] ?? '';
$confirmPassword = $data['confirmPassword'] ?? '';

// Controllo campi obbligatori
if (empty($nome) || empty($cognome) || empty($email) || empty($password) || empty($confirmPassword)) {
    echo json_encode(["success" => false, "message" => "Tutti i campi sono obbligatori."]);
    exit;
}

// Controllo validità email
if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    echo json_encode(["success" => false, "message" => "Email non valida."]);
    exit;
}

// Controllo password
if (!preg_match('/^(?=.*[A-Z])(?=.*[!@#$%^&*])[A-Za-z\d!@#$%^&*]{12,}$/', $password)) {
    echo json_encode(["success" => false, "message" => "La password deve avere almeno 12 caratteri, una lettera maiuscola e un carattere speciale."]);
    exit;
}

// Controllo corrispondenza password
if ($password !== $confirmPassword) {
    echo json_encode(["success" => false, "message" => "Le password non coincidono."]);
    exit;
}

// Controllo se l'email è già registrata
$checkEmail = $conn->prepare("SELECT id FROM users WHERE email = ?");
$checkEmail->bind_param("s", $email);
$checkEmail->execute();
$checkEmail->store_result();

// Forse è meglio rimuoverla?
if ($checkEmail->num_rows > 0) {
    echo json_encode(["success" => false, "message" => "L'email è già registrata."]);
    exit;
}

$checkEmail->close();

// Hash della password
$password_hash = password_hash($password, PASSWORD_BCRYPT);

// Query di inserimento con controllo del risultato
$stmt = $conn->prepare("INSERT INTO users (nome, cognome, email, password_hash) VALUES (?, ?, ?, ?)");
$stmt->bind_param("ssss", $nome, $cognome, $email, $password_hash);

if ($stmt->execute()) {
    echo json_encode(["success" => true, "message" => "Registrazione avvenuta con successo!"]);
} else {
    echo json_encode(["success" => false, "message" => "Errore durante la registrazione: " . $stmt->error]);
}

// Chiudi connessione
$stmt->close();
$conn->close();
exit;
?>