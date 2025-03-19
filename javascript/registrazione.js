function submitForm(event) {
    event.preventDefault();
    let form = document.getElementById('registrationForm');
    let messageBox = document.getElementById('messageBox');

    let formData = {
        nome: form.nome.value,
        cognome: form.cognome.value,
        email: form.email.value,
        password: form.password.value,
        confirmPassword: form.confirmPassword.value
    };

    fetch('/Tickets/api/registration.php', {
        method: 'POST',
        headers: { 'Content-Type': 'application/json' },
        body: JSON.stringify(formData)
    })
    .then(response => response.json().catch(() => null)) // Gestisce risposte non JSON
    .then(data => {
        messageBox.classList.remove("d-none", "alert-danger", "alert-success");

        if (data && data.success) {
            messageBox.classList.add("alert-success");
            messageBox.textContent = "Registrazione avvenuta con successo! Reindirizzamento al login...";
            
            // Reindirizzamento al login dopo 3 secondi
            setTimeout(() => window.location.href = "login.html", 3000);
        } else {
            messageBox.classList.add("alert-danger");
            messageBox.textContent = data.message || "Errore sconosciuto.";
        }
    })
    .catch(error => {
        console.error("Errore durante la richiesta:", error);
        messageBox.classList.remove("d-none");
        messageBox.classList.add("alert-danger");
        messageBox.textContent = "Errore durante la connessione al server.";
        console.log(response);
    });
}

function togglePasswordVisibility(id) {
    let passwordField = document.getElementById(id);
    passwordField.type = passwordField.type === "password" ? "text" : "password";
}

document.getElementById('registrationForm').addEventListener('submit', submitForm);