<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header("Location: login.html");
    exit();
}
?>
<!DOCTYPE html>
<html lang="it">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Inserimento Ticket</title>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- DataTables CSS -->
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/jquery.dataTables.min.css">
    <!-- jQuery e DataTables JS -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
    <!-- Bootstrap JS (per i modali) -->
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <style>
        /* Stili per il ridimensionamento delle colonne */
        th {
            position: relative;
        }
        th::after {
            content: "";
            position: absolute;
            right: 0;
            top: 0;
            width: 5px;
            height: 100%;
            cursor: col-resize;
            background-color: transparent;
        }
        th.resizing {
            border-right: 2px solid #007bff;
        }
    </style>
</head>
<body>
<div class="container mt-4">
    <!-- Barra superiore con logout e info utente -->
    <div class="d-flex justify-content-between">
        <span>Benvenuto, <?php echo htmlspecialchars($_SESSION['user_email']); ?></span>
        <button class="btn btn-danger" onclick="logout()">Logout</button>
    </div>
    <!-- Sezione per l'inserimento del ticket -->
    <div class="row mb-4">
        <div class="col">
            <h2 class="text-center mt-3">Inserisci un Ticket di Assistenza</h2>
            <!-- Selezione categoria -->
            <div class="mb-3">
                <label for="categorySelect" class="form-label">Tipologia del problema</label>
                <select id="categorySelect" class="form-select">
                    <option value="">Seleziona una categoria</option>
                    <!-- Le opzioni verranno caricate dinamicamente se disponibile get_categories.php -->
                </select>
                <div id="categoryDescription" class="mt-2 text-muted" style="display: none;"></div>
            </div>
            <!-- Descrizione del problema -->
            <div class="mb-3">
                <label for="description" class="form-label">Descrizione del problema</label>
                <textarea id="description" class="form-control" maxlength="1000" rows="5"></textarea>
                <small id="charCount" class="text-muted">1000 caratteri rimasti</small>
            </div>
            <!-- Pulsante invio -->
            <button id="submitBtn" class="btn btn-primary">Invia Ticket</button>
        </div>
    </div>

    <!-- Sezione per la tabella -->
    <div class="row">
        <div class="col">
            <h2 class="text-center mt-3">Lista Ticket</h2>
            <table id="ticketTable" class="display table table-striped table-bordered" style="width:100%">
                <thead class="table-dark">
                    <tr>
                        <th>ID</th>
                        <th>User ID</th>
                        <th>Categoria</th>
                        <th>Data/Ora</th>
                        <th>Descrizione</th>
                        <th>Stato</th>
                        <th>Admin</th>
                        <th>Azioni</th>
                    </tr>
                </thead>
                <tbody>
                    <!-- I dati verranno caricati dinamicamente -->
                </tbody>
            </table>
        </div>
    </div>
</div>

<!-- Modal per Visualizzare i dettagli del ticket -->
<div class="modal fade" id="viewModal" tabindex="-1" aria-labelledby="viewModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h1 class="modal-title fs-5" id="viewModalLabel">Dettagli Ticket</h1>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Chiudi"></button>
      </div>
      <div class="modal-body">
        <p><strong>ID:</strong> <span id="viewTicketId"></span></p>
        <p><strong>User ID:</strong> <span id="viewUserId"></span></p>
        <p><strong>Categoria:</strong> <span id="viewCategoria"></span></p>
        <p><strong>Data/Ora:</strong> <span id="viewDataOra"></span></p>
        <p><strong>Descrizione:</strong> <span id="viewDescrizione"></span></p>
        <p><strong>Stato:</strong> <span id="viewStato"></span></p>
        <p><strong>Admin:</strong> <span id="viewAdmin"></span></p>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Chiudi</button>
      </div>
    </div>
  </div>
</div>

<!-- Modal per Modificare la descrizione del ticket -->
<div class="modal fade" id="editModal" tabindex="-1" aria-labelledby="editModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <form id="editForm">
      <div class="modal-content">
        <div class="modal-header">
          <h1 class="modal-title fs-5" id="editModalLabel">Modifica Ticket</h1>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Chiudi"></button>
        </div>
        <div class="modal-body">
          <input type="hidden" id="editTicketId">
          <div class="mb-3">
            <label for="editDescrizione" class="form-label">Descrizione</label>
            <textarea id="editDescrizione" class="form-control" rows="4"></textarea>
          </div>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Annulla</button>
          <button type="submit" class="btn btn-primary">Salva Modifiche</button>
        </div>
      </div>
    </form>
  </div>
</div>

<script>
$(document).ready(function() {
    // Inizializza DataTables
    var table = $('#ticketTable').DataTable({
        "paging": true,
        "lengthMenu": [5, 10, 25, 50, 100],
        "pageLength": 10,
        "searching": true,
        "ordering": false
    });

    // Funzione per rendere le colonne della tabella ridimensionabili
    function makeTableResizable() {
        const headers = document.querySelectorAll("#ticketTable th");
        let isResizing = false;
        let startX, startWidth, header;

        headers.forEach(th => {
            th.addEventListener("mousedown", function (e) {
                // Controlla se il click è vicino al bordo destro
                if (e.offsetX > th.offsetWidth - 10) {
                    isResizing = true;
                    startX = e.pageX;
                    startWidth = th.offsetWidth;
                    header = th;
                    th.classList.add("resizing");
                }
            });
        });

        document.addEventListener("mousemove", function (e) {
            if (isResizing) {
                const newWidth = startWidth + (e.pageX - startX);
                if (newWidth > 50) {
                    header.style.width = newWidth + "px";
                }
            }
        });

        document.addEventListener("mouseup", function () {
            if (isResizing) {
                isResizing = false;
                header.classList.remove("resizing");
            }
        });
    }

    // Inizializza il ridimensionamento delle colonne
    makeTableResizable();

  // Funzione per caricare i ticket
  function fetchTickets() {
    fetch('get_tickets.php')
      .then(response => response.json())
      .then(data => {
        console.log("Dati ricevuti:", data);
        table.clear();
        data.forEach(ticket => {
          // Colonna "Azioni" con pulsanti Visualizza, Modifica, Elimina
          let azioni = `<button class="btn btn-info btn-sm viewBtn" data-id="${ticket.id}" data-userid="${ticket.user_id}" data-cat="${ticket.cat_id}" data-dataora="${ticket.data_ora}" data-descrizione="${ticket.descrizione}" data-stato="${ticket.stato}" data-adminid="${ticket.admin_id}">Visualizza</button>
                        <button class="btn btn-warning btn-sm editBtn" data-id="${ticket.id}" data-descrizione="${ticket.descrizione}">Modifica</button>
                        <button class="btn btn-primary btn-sm takeBtn" data-id="${ticket.id}">Presa in carico</button>
                        <button class="btn btn-danger btn-sm deleteBtn" data-id="${ticket.id}">Elimina</button>`;
          table.row.add([
            ticket.id,
            ticket.user_id,
            ticket.cat_id,
            ticket.data_ora,
            ticket.descrizione,
            ticket.stato,
            ticket.admin_id,
            azioni
          ]);
        });
        table.draw();
      })
      .catch(error => console.error('Errore nel recupero dei dati:', error));
  }

    // Carica i ticket all'avvio
    fetchTickets();

  // Evento per Visualizza: mostra i dettagli in un modal
  $(document).on('click', '.viewBtn', function() {
    let btn = $(this);
    $('#viewTicketId').text(btn.data('id'));
    $('#viewUserId').text(btn.data('userid'));
    $('#viewCategoria').text(btn.data('cat'));
    $('#viewDataOra').text(btn.data('dataora'));
    $('#viewDescrizione').text(btn.data('descrizione'));
    $('#viewStato').text(btn.data('stato'));
    $('#viewAdmin').text(btn.data('adminid'));
    let viewModal = new bootstrap.Modal(document.getElementById('viewModal'));
    viewModal.show();
  });

  // Evento per Modifica: apre il modal per modificare la descrizione
  $(document).on('click', '.editBtn', function() {
    let btn = $(this);
    $('#editTicketId').val(btn.data('id'));
    $('#editDescrizione').val(btn.data('descrizione'));
    let editModal = new bootstrap.Modal(document.getElementById('editModal'));
    editModal.show();
  });

  // Sottomissione del form di modifica: chiama l’API update_ticket.php
  $('#editForm').on('submit', function(e) {
    e.preventDefault();
    let ticketId = $('#editTicketId').val();
    let nuovaDescrizione = $('#editDescrizione').val().trim();
    if(nuovaDescrizione === "") {
      alert("La descrizione non può essere vuota!");
      return;
    }
    fetch('update_ticket.php', {
      method: 'POST',
      headers: { 'Content-Type': 'application/json' },
      body: JSON.stringify({ id: ticketId, descrizione: nuovaDescrizione })
    })
    .then(response => response.json())
    .then(result => {
      if(result.success) {
        alert("Ticket aggiornato con successo!");
        $('#editModal').modal('hide');
        fetchTickets();
      } else {
        alert("Errore nell'aggiornamento: " + result.message);
      }
    })
    .catch(error => {
      console.error('Errore:', error);
      alert("Errore nell'aggiornamento del ticket.");
    });
  });

  // Evento per Elimina: conferma ed esegue l'eliminazione
  $(document).on('click', '.deleteBtn', function() {
    let ticketId = $(this).data('id');
    if(confirm("Sei sicuro di voler eliminare questo ticket?")) {
      fetch('delete_ticket.php', {
        method: 'POST',
        headers: { 'Content-Type': 'application/json' },
        body: JSON.stringify({ id: ticketId })
      })
      .then(response => response.json())
      .then(result => {
        if(result.success) {
          alert("Ticket eliminato con successo!");
          fetchTickets();
        } else {
          alert("Errore nell'eliminazione: " + result.message);
        }
      })
      .catch(error => {
        console.error('Errore:', error);
        alert("Errore nell'eliminazione del ticket.");
      });
    }
  });

  // Evento per Assegnazione: conferma ed esegue la presa in carico
  $(document).on('click', '.takeBtn', function() {
    let ticket_id = $(this).data('id');
    if(confirm("Sei sicuro di voler prendere in carico questo ticket?")) {
      fetch('update_admin.php', {
        method: 'POST',
        headers: { 'Content-Type': 'application/json' },
        body: JSON.stringify({ticket_id: ticket_id})
      })
      .then(response => response.json())
      .then(result => {
        if(result.success) {
          alert("Ticket assegnato con successo!");
          fetchTickets();
        } else {
          alert("Errore nell'assegnazione: " + result.message);
        }
      })
      .catch(error => {
        console.error('Errore:', error);
        alert("Errore nell'assegnazione del ticket.");
      });
    }
  });

    // Gestione dell'invio del ticket (opzionale)
    $('#submitBtn').click(function() {
        let tipologia_problema = $("#categorySelect").val();
        let descrizione_problema = $("#description").val().trim();

        if (!tipologia_problema || !descrizione_problema) {
            alert("Tutti i campi sono obbligatori!");
            return;
        }

        $.ajax({
            url: "submit_ticket.php",
            method: "POST",
            contentType: "application/json",
            data: JSON.stringify({
                tipologia_problema: tipologia_problema,
                descrizione_problema: descrizione_problema
            }),
            success: function(response) {
                alert(response.message);
                if (response.success) {
                    $("#categorySelect").val("");
                    $("#description").val("");
                    $("#charCount").text("1000 caratteri rimasti");
                    fetchTickets();
                }
            },
            error: function() {
                alert("Errore durante l'invio del ticket.");
            }
        });
    });

    // Caricamento delle categorie
    $.ajax({
        url: "get_categories.php", 
        method: "POST",            
        dataType: "json",         
        success: function(data) {
            let categorySelect = $("#categorySelect");
            categorySelect.empty();
            categorySelect.append('<option value="">Seleziona una categoria</option>');
            data.forEach(item => {
                categorySelect.append(`<option value="${item.id}" data-description="${item.descrizione}">${item.id} - ${item.tipo}</option>`);
            });
        },
        error: function(jqXHR, textStatus, errorThrown) {
            console.error("Errore durante il recupero delle categorie: ", textStatus, errorThrown);
            // Puoi anche mostrare un messaggio di errore all'utente
            alert("Si è verificato un errore nel recupero delle categorie.");
        }
    });


    // Mostra la descrizione della categoria selezionata
    $("#categorySelect").change(function() {
        let selectedOption = $(this).find(":selected");
        let description = selectedOption.data("description");
        if (description) {
            $("#categoryDescription").text(description).show();
        } else {
            $("#categoryDescription").hide();
        }
    });

    // Aggiorna il contatore dei caratteri per la descrizione
    $("#description").on("input", function() {
        let remaining = 1000 - $(this).val().length;
        $("#charCount").text(remaining + " caratteri rimasti");
    });
});

// Logout tramite POST
function logout() {
    $.post("logout.php", function() {
        window.location.href = "login.html";
    });
}
</script>
</body>
</html>
