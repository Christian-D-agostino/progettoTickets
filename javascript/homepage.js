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
       let thButton = document.createElement('th');
       thButton.innerHTML = 'Operazioni';
       
        // Aggiungi le intestazioni (colonne) alla tabella
        tableKeys.forEach(element => {
            let th = document.createElement('th');
            th.innerHTML = element.Field;  // Usa il nome della colonna per l'intestazione
            container.appendChild(th);
        });

        container.appendChild(thButton);
        // Aggiungi i dati (righe) alla tabella
        data.forEach(elemento => {
            let tr = document.createElement('tr');  // Crea una riga per ogni ticket
            const idButton = elemento.id;

            let divModale = document.createElement('div');
            divModale.setAttribute('id', idButton);
            divModale.setAttribute('tabindex', -1);
            divModale.setAttribute('aria-labelledby', myModalLabel);
            divModale.setAttribute('aria-hidden', true);
            divModale.classList.add("modal", "fade");
            divModale.innerHTML = `
    <div class="modal-dialog">
        <div class="modal-content">
            <!-- Header della modale -->
            <div class="modal-header">
                <h5 class="modal-title" id="myModalLabel">Titolo Modale</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <!-- Corpo della modale -->
            <div class="modal-body">
                <h2>Chatbox</h2>
                <div id="chat-box"></div>
                <input type="text" id="message-input" class="form-control" placeholder="Scrivi un messaggio" />
                <button id="send-message" class="btn btn-dark mt-2">Invia</button>
            </div>
            <!-- Footer della modale -->
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Chiudi</button>
            </div>
        </div>
    </div>

`;



        

            const body = document.getElementById('body');
            body.appendChild(divModale);

            let tdButton = document.createElement('td');
            tdButton.innerHTML = "<button type='button' class='btn btn-dark' data-bs-toggle='modal'>Apri chat</button>";
            tdButton.setAttribute('data-bs-target', idButton);
            // Aggiungi una cella per ogni chiave (colonna) in base alle colonne descritti da tableKeys
            tableKeys.forEach(key => {
                let td = document.createElement('td');
                td.innerHTML = elemento[key.Field] || 'N/A'; // Usa il valore della chiave per ogni cella
                
                

                tr.appendChild(td);
            });

            tr.appendChild(tdButton);
            // Aggiungi la riga al corpo della tabella
            containerBody.appendChild(tr);
        });
        // console.log(tdButton);

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

function logout() {
    fetch('/Tickets/api/logout.php', {
        method: 'POST',
        headers: { 'Content-Type': 'application/json' }
    })
    .then(response => response.json())  // Gestisci la risposta del server
    .then(data => {
        if (data.success) {
            console.log('Logout effettuato con successo');
            // Puoi aggiungere una redirect o altre azioni qui
        } else {
            console.log('Errore durante il logout');
        }
    })
    .catch(error => {
        console.log('Errore nella richiesta di logout:', error);
    });
}

const buttonLogout = document.getElementById('logoutButton');
buttonLogout.addEventListener('click', logout);

