<?php
session_start();

require_once 'db_connection.php';

// var_dump($_SESSION['user_id']);
// require_once 'session_POST_check.php';

$sql = "SELECT message, user_id FROM messages";
$result = $conn->query($sql);

$messages = [];
while ($row = $result->fetch_assoc()) {
    $messages[] = [
        'message' => $row['message'],
        'user_id' => $row['user_id']
    ];
}

echo json_encode(["messages" => $messages]);

$conn->close();
?>
