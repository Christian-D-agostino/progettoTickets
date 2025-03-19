<?php
session_start();
header("Access-Control-Allow-Methods: POST");
header("Content-Type: application/json");
header("Access-Control-Allow-Origin: *");

//Connessione al database
require_once 'db_connection.php';

// Legge il JSON ricevuto
require_once 'json_decode.php';

$email = trim($data['email'] ?? '');
$password = $data['password'] ?? '';

if (empty($email) || empty($password)) {
    echo json_encode(["success" => false, "message" => "Email e password sono obbligatori."]);
    exit;
}

// Verifica utente nel database
$stmt = $conn->prepare("SELECT id, nome, cognome, password_hash, ruolo FROM users WHERE email = ?");
$stmt->bind_param("s", $email);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows === 1) {
    $user = $result->fetch_assoc();

    if (password_verify($password, $user['password_hash'])) {
        $_SESSION['user_id'] = $user['id'];
        $_SESSION['user_name'] = $user['nome'];
        $_SESSION['user_surname'] = $user['cognome'];
        $_SESSION['user_email'] = $email;
        $_SESSION['user_role'] = $user['ruolo']; // ✅ Salva il ruolo

        echo json_encode(["success" => true, "message" => "Accesso riuscito!", "role" => $user['ruolo']]);
    } else {
        echo json_encode(["success" => false, "message" => "Credenziali non valide."]);
    }
} else {
    echo json_encode(["success" => false, "message" => "Utente non trovato."]);
}

$stmt->close();
$conn->close();
?>