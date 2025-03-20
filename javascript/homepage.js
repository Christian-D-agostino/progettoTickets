async function getTickets() {
    const url = '/Tickets/api/get_tickets.php';
    const tableDB = '/Tickets/api/describeTable.php?table=tickets';
    try {
        // Ottieni la descrizione della tabella (chiavi delle colonne)
        const tableKeysResponse = await fetch(tableDB);
        const tableKeys = await tableKeysResponse.json();

        // Ottieni i dati dei ticket
        const response = await fetch(url);
        const data = await response.json();

        // Ottieni i container per la tabella
        let container = document.getElementById('ticketGrid');
        let containerBody = document.getElementById('ticketGridBody');

        // Aggiungi le intestazioni (colonne) alla tabella
        tableKeys.forEach(element => {
            let th = document.createElement('th');
            th.innerHTML = element.Field;  // Usa il nome della colonna per l'intestazione
            container.appendChild(th);
        });

        // Aggiungi i dati (righe) alla tabella
        data.forEach(elemento => {
            let tr = document.createElement('tr');  // Crea una riga per ogni ticket

            // Aggiungi una cella per ogni chiave (colonna) in base alle colonne descritti da tableKeys
            tableKeys.forEach(key => {
                let td = document.createElement('td');
                td.innerHTML = elemento[key.Field] || 'N/A'; // Usa il valore della chiave per ogni cella
                tr.appendChild(td);
            });

            // Aggiungi la riga al corpo della tabella
            containerBody.appendChild(tr);
        });

    } catch (error) {
        console.log('Errore:', error);
    }
}

getTickets();

function redirectToCreateTicket() {
    window.location.href='/Tickets/views/create-ticket.php';
}

const createTicketButton = document.getElementById('createTicketButton');

createTicketButton.addEventListener('click', redirectToCreateTicket);