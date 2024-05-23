
async function fetchChartData() {
    const response = await fetch('get_chart_data.php'); // Assicurati che il percorso sia corretto
    if (!response.ok) {
    console.error("Errore nel caricamento dei dati");
    return;
    }
    const data = await response.json(); // Converte la risposta in JSON
    console.log(data); // Aggiungi questo per vedere i dati nel console log
    return data;
}

async function createChart() {
    const data = await fetchChartData(); // Ottiene i dati da PHP
    if (data) {
    const ctx = document.getElementById('myChart').getContext('2d');
    const myChart = new Chart(ctx, {
        type: 'bar', // Tipo di grafico
        data: {
        labels: data.labels, // Etichette degli assi
        datasets: [{
            label: 'Valori', // Nome del dataset
            data: data.values, // Dati da visualizzare
            backgroundColor: 'rgba(75, 192, 192, 0.2)', // Colore delle barre
            borderColor: 'rgba(75, 192, 192, 1)', // Colore dei bordi
            borderWidth: 1 // Spessore del bordo
        }]
        },
        options: {
        scales: {
            y: {
            beginAtZero: true // L'asse Y parte da zero
            }
        }
        }
    });
    }
}
createChart(); // Crea il grafico al caricamento della pagina