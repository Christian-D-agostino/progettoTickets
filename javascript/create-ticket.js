// Funzione per inviare il ticket
async function sendTicket(event) {
    event.preventDefault(); // Previeni l'invio del form

    const creaTicketUrl = '/Tickets/api/submit_ticket.php'; // URL per l'invio del ticket

    const formContainer = document.getElementById('createForm');
    const tipologiaProblemaElement = formContainer.querySelector('select[name="tipologia_problema"]');
    const descrizioneProblemaElement = formContainer.querySelector('textarea[name="descrizione_problema"]');

    // Verifica che i dati siano validi
    const tipologiaProblema = tipologiaProblemaElement.value;
    const descrizioneProblema = descrizioneProblemaElement.value;

    if (!tipologiaProblema || !descrizioneProblema) {
        alert("Tutti i campi sono obbligatori.");
        return;
    }

    const formData = {
        tipologia_problema: tipologiaProblema,
        descrizione_problema: descrizioneProblema
    };

    try {
        const response = await fetch(creaTicketUrl, {
            method: 'POST',
            headers: { 'Content-Type': 'application/json' },
            body: JSON.stringify(formData)
        });

        // Log per vedere la risposta del server
        const textResponse = await response.text();  // Ottieni la risposta come testo
        console.log('Risposta del server (raw):', textResponse);  // Stampa la risposta raw

        // Ora prova a fare il parsing del JSON se la risposta è corretta
        const data = JSON.parse(textResponse);  // Fa il parsing solo se la risposta è valida JSON

        if (data.success) {
            alert('Ticket inserito con successo!');
            formContainer.reset(); // Resetta il modulo
        } else {
            alert('Errore durante l\'inserimento del ticket: ' + data.message);
        }
    } catch (error) {
        console.error('Errore durante l\'invio del ticket:', error);
        alert('Errore durante l\'invio dei dati.');
    }
}

// Aggiungi l'event listener al form
document.getElementById('createForm').addEventListener("submit", sendTicket);
