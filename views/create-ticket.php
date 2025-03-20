<!DOCTYPE html>
<html lang="it">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Crea Ticket</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="container mt-5">
    <h2>Crea Nuovo Ticket</h2>
    
    <form id="createForm" class="d-flex border p-4 justify-content-center w-100 flex-column">
        <!-- Dynamically populated fields will go here -->
        <div class="form-group mb-3">
            <label for="tipologia_problema" class="form-label">Tipologia Problema</label>
            <select name="tipologia_problema" id="tipologia_problema" class="form-control">
                <!-- Options will be dynamically populated -->
            </select>
        </div>
        
        <div class="form-group mb-3">
            <label for="descrizione_problema" class="form-label">Descrizione Problema</label>
            <textarea name="descrizione_problema" id="descrizione_problema" class="form-control"></textarea>
        </div>
        
        <button id="formButton" class="btn btn-dark" type="submit">Invia</button>
    </form>

    <script src="/Tickets/javascript/create-form.js" defer></script>
    <script src="/Tickets/javascript/create-ticket.js" defer></script>
</body>
</html>
