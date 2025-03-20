async function getCategories() {
    const catURL = '/Tickets/api/get_categories.php';

    try {
        const response = await fetch(catURL, {
            method: 'POST',
            headers: { 'Content-Type': 'application/json' }
        });

        // Verifica se la risposta è valida
        if (!response.ok) {
            throw new Error('Errore nel recupero delle categorie');
        }

        const categories = await response.json();
        return categories;  // Restituisce le categorie come array
    } catch (error) {
        console.error('Errore durante il recupero delle categorie:', error);
    }
}
