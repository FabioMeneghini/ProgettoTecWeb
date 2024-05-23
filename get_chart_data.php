<?php

require_once "DBAccess.php";
use DB\DBAccess;

// Crea la connessione al database
$connection = new DBAccess();
$connectionOk = $connection->openDBConnection();

if ($connectionOk) {
    $n_recensioni = $connection -> getRecensioniUtente($_SESSION['username']);
    $n_libri_letti = $connection ->  getLibriUtente($_SESSION['username']);
    $n_libri_stai_leggendo = $connection -> getNumeroLibriStaLeggendo($_SESSION['username']);
    $n_libri_salvati = $connection -> getNumeroLibriSalvati($_SESSION['username']);
    var_dump($n_recensioni);
    var_dump($n_libri_letti);
    var_dump($n_libri_stai_leggendo);
    var_dump($n_libri_salvati);
    $connection -> closeConnection();

    // Genera i dati che vuoi inviare a JavaScript
    $data = [
        "labels" => ["Libri terminati", "Libri salvati", "Libri iniziati", "Libri recensiti"],
        //"values" => [$n_libri_letti, $n_libri_salvati, $n_libri_stai_leggendo, $n_recensioni]
        "values" => [1, 2, 3, 4]
    ];

    // Converte i dati in JSON e li invia come risposta
    header('Content-Type: application/json'); // Imposta il tipo di contenuto come JSON
    echo json_encode($data); // Invia i dati JSON al client
} else {
    http_response_code(500); // Restituisce un errore HTTP se la connessione fallisce
    echo json_encode(["error" => "Connessione al database fallita"]);
}

?>
