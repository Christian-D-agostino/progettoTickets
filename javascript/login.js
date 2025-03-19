function togglePasswordVisibility(id) {
    let passwordField = document.getElementById(id);
    passwordField.type = passwordField.type === "password" ? "text" : "password";
}

document.getElementById('loginForm').addEventListener('submit', function(event) {
    event.preventDefault();

    let email = document.getElementById('email').value;
    let password = document.getElementById('password').value;
    let messageBox = document.getElementById('loginMessage');

    fetch('/Tickets/api/login.php', {
        method: 'POST',
        headers: { 'Content-Type': 'application/json' },
        body: JSON.stringify({ email, password })
    })
    .then(response => response.json().catch(() => null)) // Gestione risposta non JSON
    .then(data => {
        messageBox.classList.remove("d-none", "alert-danger", "alert-success");

        if (data && data.success) {
            messageBox.classList.add("alert-success");
            messageBox.textContent = "Accesso riuscito! Reindirizzamento...";
            
            // ✅ Reindirizzamento in base al ruolo
            setTimeout(() => {
                if (data.role === "superadmin") {
                    window.location.href = "homepage.php";
                } else if (data.role === "admin") {
                    window.location.href = "homepage.php";
                } else {
                    window.location.href = "homepage.php";
                }
            }, 3000);
        } else {
            messageBox.classList.add("alert-danger");
            messageBox.textContent = data ? data.message : "Errore sconosciuto.";
        }
    })
    .catch(error => {
        console.error("Errore durante la richiesta:", error);
        messageBox.classList.remove("d-none");
        messageBox.classList.add("alert-danger");
        messageBox.textContent = "Errore durante la connessione al server.";
    });
});