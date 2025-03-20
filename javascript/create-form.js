// Funzione per ottenere le categorie
async function getCategories() {
    const catURL = '/Tickets/api/get_categories.php';

    try {
        const response = await fetch(catURL, { method: 'POST' });
        const categories = await response.json();
        return categories;
    } catch (error) {
        console.error("Errore nel recupero delle categorie:", error);
        return [];
    }
}

// Funzione per creare il form dinamicamente
async function createForm() {
    const selectContainer = document.getElementById('tipologia_problema');

    // Ottieni le categorie
    const categories = await getCategories();

    if (categories.length === 0) {
        alert("Nessuna categoria disponibile.");
        return;
    }

    // Aggiungi un'opzione vuota di default
    const defaultOption = document.createElement('option');
    defaultOption.value = '';
    defaultOption.textContent = 'Seleziona una categoria';
    selectContainer.appendChild(defaultOption);

    // Aggiungi le categorie come opzioni nel select
    categories.forEach(category => {
        const option = document.createElement('option');
        option.value = category.id;
        option.textContent = category.tipo;
        selectContainer.appendChild(option);
    });
}

// Inizializza il form
createForm();
