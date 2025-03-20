// Aggiungi un event listener per il pulsante di invio del messaggio
document.getElementById("send-message").addEventListener("click", function() {
    const messageInput = document.getElementById("message-input");
    const message = messageInput.value.trim();

    if (message) {
        // Invia il messaggio al server
        sendMessage(message);
        messageInput.value = ''; // Pulisci il campo di input
    }
});

// Funzione per inviare il messaggio al server
async function sendMessage(message) {
    const response = await fetch('/Tickets/api/send_message.php', {
        method: 'POST',
        headers: { 'Content-Type': 'application/json' },
        body: JSON.stringify({ message: message })
    });

       
    const data = await response.json();
    
    if (data.success) {
        console.log('Messaggio inviato con successo!');
        loadMessages();  // Ricarica i messaggi dopo aver inviato un nuovo messaggio
    } else if(data.success == undefined){
    
    } else {
        console.log("Errore nell'invio del messaggio.");
    }
}

// Funzione per caricare i messaggi dal server
async function loadMessages() {
    const response = await fetch('/Tickets/api/get_messages.php');
    const data = await response.json();

    if (data.messages) {
        const chatBox = document.getElementById("chat-box");
        chatBox.innerHTML = ''; // Pulisci la chat prima di caricare i nuovi messaggi

        // Aggiungi ogni messaggio al div della chat
        data.messages.forEach(msg => {
            const messageDiv = document.createElement('div');
            messageDiv.classList.add('message', 'w-100');

            messageDiv.textContent = msg.user_id + ': ' + msg.message;
            if(msg.user_id == 8){
                messageDiv.classList.add("text-end");
            }

            console.log(msg);
            
            chatBox.appendChild(messageDiv);
        });

        // Scorri fino all'ultimo messaggio
        chatBox.scrollTop = chatBox.scrollHeight;
    }
}

// Carica i messaggi iniziali quando la pagina si carica
window.addEventListener('load', loadMessages);
