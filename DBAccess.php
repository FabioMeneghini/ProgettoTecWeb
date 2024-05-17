<?php

namespace DB;
class DBAccess {
    private $HOST_DB = "localhost"; //questi valori sono da cambiare prima di provarlo sul server di unipd
    private $DATABASE_NAME = "bookclub";
    private $USERNAME = "root";
    private $PASSWORD = "";

    private $connection;

    public function openDBConnection() {
        $this -> connection = mysqli_connect(
            $this->HOST_DB,
            $this->USERNAME,
            $this->PASSWORD,
            $this->DATABASE_NAME
        );
        return mysqli_connect_errno() == 0;
    }

    public function closeConnection() {
        mysqli_close($this -> connection);
    }

    public function getListaBestSeller() {
        $query = "SELECT libri.titolo, libri.autore, libri.trama, generi.nome
                  FROM libri, generi
                  WHERE libri.id_genere=generi.id
                  LIMIT 10";
        $queryResult = mysqli_query($this -> connection, $query);
        if(mysqli_num_rows($queryResult) != 0) {
            $result = array();
            while($row = mysqli_fetch_assoc($queryResult)) {
                $result[] = $row;
            }
            $queryResult -> free();
            return $result;
        }
        else {
            return null;
        }
    }

    public function login($username, $password) {
        $query = "SELECT * FROM utenti WHERE username = '$username' AND password = '$password'";
        $queryResult = mysqli_query($this -> connection, $query);
        if(mysqli_num_rows($queryResult) != 0) {
            $row = mysqli_fetch_assoc($queryResult); //dato che username è chiave primaria, ci sarà al più un risultato
            $queryResult -> free();
            return $row;
        }
        else {
            return null;
        }
    }

    public function usernameUnico($username) { //controlla se lo username è già presente nel database
        $query = "SELECT * FROM utenti WHERE username = '$username'";
        $queryResult = mysqli_query($this -> connection, $query);
        if(mysqli_num_rows($queryResult) != 0){
            $queryResult -> free();
            return false;
        }
        else {
            return true;
        }
    }

    public function registraUtente($nome, $cognome, $username, $email, $password, $data) {
        $messaggio = "";
        $query = "INSERT INTO utenti (nome, cognome, username, email, password, data_nascita, data_iscrizione) VALUES (?, ?, ?, ?, ?, ?, NOW())";
        $stmt = $this -> connection -> prepare($query);
        if($stmt === false) {
            $messaggio .= "<li>Errore nella preparazione dell'istruzione: " . $this -> connection -> error . "</li>";
        }
        else {
            $stmt->bind_param("ssssss", $nome, $cognome, $username, $email, $password, $data);
            if (!$stmt->execute()) {
                $messaggio .= "<li>Errore durante la registrazione: " . $stmt->error . "</li>";
            }
            $stmt->close();
        }
        return $messaggio;
    }

    public function getListaGeneri() {
        $query = "SELECT nome FROM generi";
        $queryResult = mysqli_query($this -> connection, $query);
        if(mysqli_num_rows($queryResult) != 0) {
            $result = array();
            while($row = mysqli_fetch_assoc($queryResult)) {
                $result[] = $row;
            }
            $queryResult -> free();
            return $result;
        }
        else {
            return null;
        }
    }

    public function getListaLibriGenere($genere, $n=1000) {
        $query="SELECT libri.titolo, libri.id, libri.titolo_ir FROM libri, generi WHERE libri.id_genere=generi.id AND generi.nome='$genere' LIMIT $n";
        $queryResult = mysqli_query($this -> connection, $query);
        if(mysqli_num_rows($queryResult) != 0) {
            $result = array();
            while($row = mysqli_fetch_assoc($queryResult)) {
                $result[] = $row;
            }
            $queryResult -> free();
            return $result;
        }
        else {
            return null;
        }
    }
    
    //DONE ma genere deve avere una tabella? 
    public function getKeywordByGenere($genereSelezionato) {
        $query = "SELECT keyword FROM genere WHERE genere = '$genereSelezionato'";
        $queryResult = mysqli_query($this->connection, $query);
    
        if (mysqli_num_rows($queryResult) != 0) {
            $result = array();
            while ($row = mysqli_fetch_assoc($queryResult)) {
                $result[] = $row['keyword'];
            }
            mysqli_free_result($queryResult);
            return $result;
        } else {
            return null;
        }
    }
    
    public function getUtentiRegistratiCount() {
        $query = "SELECT COUNT(*) AS numeroUtenti FROM utenti";
        $queryResult = mysqli_query($this -> connection, $query);
        if(mysqli_num_rows($queryResult) != 0) {
            $row = mysqli_fetch_assoc($queryResult);
            $queryResult -> free();
            return $row['numeroUtenti'];
        }
        else {
            return null;
        }
    }

    public function getRecensioniUtente($username) {
        $query = "SELECT COUNT(*) AS numeroRecensioni FROM recensioni WHERE username_autore = '$username' ";
        $queryResult = mysqli_query($this -> connection, $query);
        if(mysqli_num_rows($queryResult) != 0) {
            $row = mysqli_fetch_assoc($queryResult);
            $queryResult -> free();
            return $row['numeroRecensioni'];
        }
        else {
            return null;
        }
    }


    public function getRecensioniCount() {
        $query = "SELECT COUNT(*) AS numeroRecensioni FROM recensioni";
        $queryResult = mysqli_query($this->connection, $query);
        if (mysqli_num_rows($queryResult) != 0) {
            $row = mysqli_fetch_assoc($queryResult);
            mysqli_free_result($queryResult);
            return $row['numeroRecensioni'];
        } else {
            return null;
        }
    }

    public function getLibriUtente($username) {
        $query = "SELECT COUNT(*) AS numeroLibri FROM ha_letto WHERE username = '$username' ";
        $queryResult = mysqli_query($this->connection, $query);
        if (mysqli_num_rows($queryResult) != 0) {
            $row = mysqli_fetch_assoc($queryResult);
            mysqli_free_result($queryResult);
            return $row['numeroLibri'];
        } else {
            return null;
        }
    }

    public function getUtentiCheStannoLeggendoCount() {
        $query = "SELECT COUNT(*) AS count FROM sta_leggendo";
        $queryResult = mysqli_query($this -> connection, $query);
        if(mysqli_num_rows($queryResult) != 0) {
            $row = mysqli_fetch_assoc($queryResult);
            $queryResult -> free();
            return $row['count'];
        }
        else {
            return null;
        }
    }

    public function getListaStaLeggendo($username) {
        $query = "SELECT libri.id, libri.titolo, libri.autore, libri.id_genere, sta_leggendo.n_capitoli_letti, libri.n_capitoli
                  FROM libri, sta_leggendo
                  WHERE sta_leggendo.username = '$username'
                  AND sta_leggendo.id_libro = libri.id";
        $queryResult = mysqli_query($this -> connection, $query);
        if(mysqli_num_rows($queryResult) != 0) {
            $result = array();
            while($row = mysqli_fetch_assoc($queryResult)) {
                $result[] = $row;
            }
            $queryResult -> free();
            return $result;
        }
        else {
            return null;
        }
    }

    public function getListaSalvati($username) {
        $query = "SELECT libri.id, libri.titolo, libri.autore, generi.nome AS genere
        FROM libri
        JOIN generi ON libri.id_genere = generi.id
        JOIN da_leggere ON da_leggere.id_libro = libri.id
        WHERE da_leggere.username = '$username'";
        $queryResult = mysqli_query($this -> connection, $query);
        if(mysqli_num_rows($queryResult) != 0) {
            $result = array();
            while($row = mysqli_fetch_assoc($queryResult)) {
                $result[] = $row;
            }
            $queryResult -> free();
            return $result;
        }
        else {
            return null;
        }
    }

    public function getListaTerminati($username) {
        /*$query = "SELECT libri.titolo, libri.autore, libri.id_genere, ha_letto.data_fine_lettura, recensioni.voto
                  FROM libri, ha_letto, recensioni
                  WHERE ha_letto.username = '$username'
                  AND ha_letto.id_libro = libri.id
                  AND ha_letto.id_libro = recensioni.id_libro
                  AND ha_letto.username = recensioni.username_autore
                  UNION
                  SELECT libri.titolo, libri.autore, libri.id_genere, ha_letto.data_fine_lettura, 'Non assegnato'
                  FROM libri, ha_letto
                  WHERE ha_letto.username = '$username'
                  AND ha_letto.id_libro = libri.id
                  AND ha_letto.id_libro NOT IN (SELECT DISTINCT id_libro FROM recensioni WHERE username_autore = '$username')";*/
        $query="SELECT libri.id, libri.titolo, libri.autore, generi.nome AS genere, ha_letto.data_fine_lettura, recensioni.voto
                FROM libri
                JOIN generi ON libri.id_genere = generi.id
                JOIN ha_letto ON ha_letto.id_libro = libri.id
                JOIN recensioni ON ha_letto.id_libro = recensioni.id_libro AND ha_letto.username = recensioni.username_autore
                WHERE ha_letto.username = '$username'
                UNION
                SELECT libri.id, libri.titolo, libri.autore, generi.nome AS genere, ha_letto.data_fine_lettura, 'Non assegnato'
                FROM libri
                JOIN generi ON libri.id_genere = generi.id
                JOIN ha_letto ON ha_letto.id_libro = libri.id
                WHERE ha_letto.username = '$username'
                AND ha_letto.id_libro NOT IN (SELECT DISTINCT id_libro FROM recensioni WHERE username_autore = '$username')";
        $queryResult = mysqli_query($this -> connection, $query);
        if(mysqli_num_rows($queryResult) != 0) {
            $result = array();
            while($row = mysqli_fetch_assoc($queryResult)) {
                $result[] = $row;
            }
            $queryResult -> free();
            return $result;
        }
        else {
            return null;
        }
    }

    public function staLeggendo($username, $id_libro) {
        $query = "SELECT * FROM sta_leggendo WHERE username = '$username' AND id_libro = '$id_libro'";
        $queryResult = mysqli_query($this -> connection, $query);
        if(mysqli_num_rows($queryResult) != 0){
            $queryResult -> free();
            return true;
        }
        else {
            return false;
        }
    }

    public function aggiungiStaLeggendo($username, $id_libro) {
        $query = "INSERT INTO sta_leggendo (username, id_libro, n_capitoli_letti) VALUES (?, ?, 0)";
        $stmt = $this -> connection -> prepare($query);
        if($stmt === false) {
            echo "<li>Errore nella preparazione dell'istruzione: " . $this -> connection -> error . "</li>";
        }
        else {
            $stmt->bind_param("si", $username, $id_libro);
            if (!$stmt->execute()) {
                echo "<li>Errore durante l'aggiunta del libro: " . $stmt->error . "</li>";
            }
            $stmt->close();
        }
    }

    public function rimuoviDaLeggere($username, $id_libro) {
        $query = "DELETE FROM da_leggere WHERE username = '$username' AND id_libro = '$id_libro'";
        $queryResult = mysqli_query($this -> connection, $query);
        if($queryResult === false) {
            echo "<li>Errore durante la rimozione del libro: " . $this -> connection -> error . "</li>";
        }
    }

    public function modificaUsername($old, $new) {
        $query = "UPDATE utenti SET username = '$new' WHERE username = '$old'";
        $queryResult = mysqli_query($this -> connection, $query);
        if($queryResult === false) {
            echo "<li>Errore durante la modifica dello username: " . $this -> connection -> error . "</li>";
        }
    }

    public function modificaPassword($username, $new) {
        $query = "UPDATE utenti SET password = '$new' WHERE username = '$username'";
        $queryResult = mysqli_query($this -> connection, $query);
        if($queryResult === false) {
            echo "<li>Errore durante la modifica della password: " . $this -> connection -> error . "</li>";
        }
    }

    public function modificaEmail($username, $new) {
        $query = "UPDATE utenti SET email = '$new' WHERE username = '$username'";
        $queryResult = mysqli_query($this -> connection, $query);
        if($queryResult === false) {
            echo "<li>Errore durante la modifica dell'email: " . $this -> connection -> error . "</li>";
        }
    }

    public function verificaPassword($username, $password) {
        $query = "SELECT * FROM utenti WHERE username = '$username' AND password = '$password'";
        $queryResult = mysqli_query($this -> connection, $query);
        if(mysqli_num_rows($queryResult) != 0){
            $queryResult -> free();
            return true;
        }
        else {
            return false;
        }
    }

    public function eliminaUtente($username) {
        $query = "DELETE FROM utenti WHERE username = '$username'";
        $queryResult = mysqli_query($this -> connection, $query);
        if($queryResult === false) {
            echo "<li>Errore durante la cancellazione dell'utente: " . $this -> connection -> error . "</li>";
        }
    }

    public function getInfoLibro($id_libro) {
        $query = "SELECT * FROM libri WHERE id = '$id_libro'";
        $queryResult = mysqli_query($this -> connection, $query);
        if(mysqli_num_rows($queryResult) != 0){
            $row = mysqli_fetch_assoc($queryResult);
            $queryResult -> free();
            return $row;
        }
        else {
            return null;
        }
    }

    //questa funzione preleva dal database i 5 generi che hanno più libri
    public function getGeneriPiuPopolari() {
        $query = "SELECT generi.nome AS genere, COUNT(*) AS numeroLibri
                  FROM libri
                  INNER JOIN generi ON libri.id_genere = generi.id
                  GROUP BY generi.nome
                  ORDER BY numeroLibri DESC LIMIT 5";
        $queryResult = mysqli_query($this -> connection, $query);
        if(mysqli_num_rows($queryResult) != 0){
            $result = array();
            while($row = mysqli_fetch_assoc($queryResult)) {
                $result[] = $row;
            }
            $queryResult -> free();
            return $result;
        }
        else {
            return null;
        }
    }

    //questa funzione preleva dal database i 5 generi che l'utente con username $username ha letto di più
    public function getGeneriPiuLetti($username) {
        $query = "SELECT generi.nome AS genere, COUNT(*) AS numeroLibri
                  FROM libri
                  INNER JOIN generi ON libri.id_genere = generi.id
                  WHERE EXISTS (
                      SELECT 1
                      FROM ha_letto
                      WHERE ha_letto.username = '$username'
                      AND ha_letto.id_libro = libri.id
                  )
                  GROUP BY generi.nome
                  ORDER BY numeroLibri DESC
                  LIMIT 5";
        $queryResult = mysqli_query($this -> connection, $query);
        if(mysqli_num_rows($queryResult) != 0){
            $result = array();
            while($row = mysqli_fetch_assoc($queryResult)) {
                $result[] = $row;
            }
            $queryResult -> free();
            return $result;
        }
        else {
            return null;
        }
    }

    public function cercaLibro($stringa, $autore, $genere, $lingua) {
        $query = "SELECT libri.id, libri.titolo, libri.titolo_ir,
                         libri.descrizione, libri.autore, libri.lingua, generi.nome AS genere
                  FROM libri
                  INNER JOIN generi ON libri.id_genere = generi.id
                  WHERE libri.titolo LIKE '%$stringa%'
                  AND libri.autore LIKE '%$autore%'
                  AND generi.nome LIKE '%$genere%'
                  AND libri.lingua LIKE '%$lingua%'";
        $queryResult = mysqli_query($this->connection, $query);
        if ($queryResult === false) {
            echo "<li>Errore durante l'esecuzione della query: " . mysqli_error($this->connection) . "</li>";
            return null;
        }
        if (mysqli_num_rows($queryResult) != 0) {
            $result = array();
            while ($row = mysqli_fetch_assoc($queryResult)) {
                $result[] = $row;
            }
            mysqli_free_result($queryResult);
            return $result;
        } else {
            return null;
        }
    }

    public function getLingueLibri() {
        $query = "SELECT DISTINCT lingua FROM libri";
        $queryResult = mysqli_query($this -> connection, $query);
        if(mysqli_num_rows($queryResult) != 0){
            $result = array();
            while($row = mysqli_fetch_assoc($queryResult)) {
                $result[] = $row;
            }
            $queryResult -> free();
            return $result;
        }
        else {
            return null;
        }
    }
    // controllare presenza di un genere
    public function controllagenere($genereSelezionato) {
        //$query = "SELECT genere FROM genere WHERE id = '$genereSelezionato'";
        $query = "SELECT nome FROM generi WHERE nome = '$genereSelezionato'";
        $queryResult = mysqli_query($this -> connection, $query);
        if(mysqli_num_rows($queryResult) != 0){
            $queryResult -> free();
            return true;
        }
        else {
            return false;
        }
    }
    //DONE 
    //controllare presenza di un libro 
    public function controllareIdLibro($LibroSelezionato) {
        $query = "SELECT id FROM libri WHERE id = '$LibroSelezionato'";
        $queryResult = mysqli_query($this -> connection, $query);
        if(mysqli_num_rows($queryResult) != 0){
            $queryResult -> free();
            return true;
        }
        else {
            return false;
        }
    }
    //DONE 
    //restituire dal data base il campo dati immagine della tabella libri
    //questo del libro si poteva condenzare in 1 sola con un array ummm in futuro :(
    public function getcopertina($LibroSelezionato) {
        $query = "SELECT titolo_ir FROM libri WHERE id = '$LibroSelezionato'";
        $queryResult = mysqli_query($this -> connection, $query);
        if(mysqli_num_rows($queryResult) != 0){
            $row = mysqli_fetch_assoc($queryResult);
            $queryResult -> free();
            return $row['titolo_ir'];
        }
        else {
            return null;
        }
        //TODO 
        //da qui dovrebbe partire img replace
    }
    //fai una fuznione che restituisce il titolo di un libro dato il suo id

    public function getkeywords($LibroSelezionato) {
        $query = "SELECT keywords FROM libri WHERE id = '$LibroSelezionato'";
        $queryResult = mysqli_query($this -> connection, $query);
        if(mysqli_num_rows($queryResult) != 0){
            $row = mysqli_fetch_assoc($queryResult);
            $queryResult -> free();
            return $row['keywords'];
        }
        else {
            return null;
        }
        //TODO 
        //da qui dovrebbe partire img replace
    }

    public function getalt($LibroSelezionato) {
        $query = "SELECT descrizione FROM libri WHERE id = '$LibroSelezionato'";
        $queryResult = mysqli_query($this -> connection, $query);
        if(mysqli_num_rows($queryResult) != 0){
            $row = mysqli_fetch_assoc($queryResult);
            $queryResult -> free();
            return $row['descrizione'];
        }
        else {
            return null;
        }
        //TODO 
        //da qui dovrebbe partire img replace
    }

    public function gettitololibro($LibroSelezionato) {
        $query = "SELECT titolo FROM libri WHERE id = '$LibroSelezionato'";
        $queryResult = mysqli_query($this -> connection, $query);
        if(mysqli_num_rows($queryResult) != 0){
            $row = mysqli_fetch_assoc($queryResult);
            $queryResult -> free();
            return $row['titolo'];
        }
        else {
            return null;
        }
    }
    //TO CHECK
    //a cosa serviva ? public function getLibriUtente($LibroSelezionato) 
    
    public function getgenereLibro($LibroSelezionato) {
        $query = "SELECT generi.nome FROM libri, generi WHERE libri.id_genere=generi.id AND libri.id = '$LibroSelezionato'";
        $queryResult = mysqli_query($this -> connection, $query);
        if(mysqli_num_rows($queryResult) != 0){
            $row = mysqli_fetch_assoc($queryResult);
            $queryResult -> free();
            return $row['nome'];
        }
        else {
            return null;
        }
    } 

    public function getlinguaLibro($LibroSelezionato) {
        $query = "SELECT lingua FROM libri WHERE id = '$LibroSelezionato'";
        $queryResult = mysqli_query($this -> connection, $query);
        if(mysqli_num_rows($queryResult) != 0){
            $row = mysqli_fetch_assoc($queryResult);
            $queryResult -> free();
            return $row['lingua'];
        }
        else {
            return null;
        }
    }
    public function gettramaLibro($LibroSelezionato) {
        $query = "SELECT trama FROM libri WHERE id = '$LibroSelezionato'";
        $queryResult = mysqli_query($this -> connection, $query);
        if(mysqli_num_rows($queryResult) != 0){
            $row = mysqli_fetch_assoc($queryResult);
            $queryResult -> free();
            return $row['trama'];
        }
        else {
            return null;
        }
    }


    public function getncapitoliLibro($LibroSelezionato) {
        $query = "SELECT n_capitoli FROM libri WHERE id = '$LibroSelezionato'";
        $queryResult = mysqli_query($this -> connection, $query);
        if(mysqli_num_rows($queryResult) != 0){
            $row = mysqli_fetch_assoc($queryResult);
            $queryResult -> free();
            return $row['n_capitoli'];
        }
        else {
            return null;
        }
    }
    
    public function getautoreLibro($LibroSelezionato) {
        $query = "SELECT autore FROM libri WHERE id = '$LibroSelezionato'";
        $queryResult = mysqli_query($this -> connection, $query);
        if(mysqli_num_rows($queryResult) != 0){
            $row = mysqli_fetch_assoc($queryResult);
            $queryResult -> free();
            return $row['autore'];
        }
        else {
            return null;
        }
    }
    public function getmediavoti($LibroSelezionato) {
        $query = "SELECT AVG(voto) AS media_voti FROM recensioni WHERE id_libro = '$LibroSelezionato'";
        $queryResult = mysqli_query($this -> connection, $query);
        if(mysqli_num_rows($queryResult) != 0){
            $row = mysqli_fetch_assoc($queryResult); //dato che username è chiave primaria, ci sarà al più un risultato
            $queryResult -> free();
            return round($row['media_voti'], 2);
        }
        else {
            return null;
        }
    }
    //aggiungere che toni il nome dell'utente che ha lasciato la recensione
    public function getaltrerecensioni($LibroSelezionato) {
        $query = "SELECT commento FROM recensioni WHERE id_libro = '$LibroSelezionato'";
        $queryResult = mysqli_query($this -> connection, $query);
        if(mysqli_num_rows($queryResult) != 0){
            $result = array();
            while($row = mysqli_fetch_assoc($queryResult)) {
                $result[] = $row;
            }
            $queryResult -> free();
            return $result; 
        }
        else {
            return null;
        }
    }
    
    public function getrecensionetua($LibroSelezionato,$utente) {
        $query = "SELECT commento FROM recensioni WHERE id_libro = '$LibroSelezionato' AND username_autore = '$utente'";
        $queryResult = mysqli_query($this -> connection, $query);
        if(mysqli_num_rows($queryResult) != 0){
            $row = mysqli_fetch_assoc($queryResult);
            $queryResult -> free();
            return $row['commento'];
        }
        else {
            return null;
        }
    }

    public function getvototuo($LibroSelezionato,$utente) {
        $query = "SELECT voto FROM recensioni WHERE id_libro = '$LibroSelezionato' AND username_autore = '$utente'";
        $queryResult = mysqli_query($this -> connection, $query);
        if(mysqli_num_rows($queryResult) != 0){
            $row = mysqli_fetch_assoc($queryResult); //dato che username è chiave primaria, ci sarà al più un risultato
            $queryResult -> free();
            return $row['voto'];
        }
        else {
            return null;
        }
    }

    public function is_terminato($LibroSelezionato,$utente){
        $query = "SELECT * FROM ha_letto WHERE id_libro = '$LibroSelezionato' AND username = '$utente'";
        $queryResult = mysqli_query($this -> connection, $query);
        if(mysqli_num_rows($queryResult) != 0){
            $queryResult -> free();
            return true;
        }
        else {
            return false;
        }
    }

    public function is_salvato($LibroSelezionato,$utente){
        $query = "SELECT * FROM da_leggere WHERE id_libro = '$LibroSelezionato' AND username = '$utente'";
        $queryResult = mysqli_query($this -> connection, $query);
        if(mysqli_num_rows($queryResult) != 0){
            $queryResult -> free();
            return true;
        }
        else {
            return false;
        }
    }
    public function is_iniziato($LibroSelezionato,$utente){
        $query = "SELECT * FROM sta_leggendo WHERE id_libro = '$LibroSelezionato' AND username = '$utente'";
        $queryResult = mysqli_query($this -> connection, $query);
        if(mysqli_num_rows($queryResult) != 0){
            $queryResult -> free();
            return true;
        }
        else {
            return false;
        }
    }
    public function modificaRecensione($LibroSelezionato, $utente, $commento, $voto) {
        $query = "UPDATE recensioni SET commento = '$commento', voto = '$voto' WHERE id_libro = '$LibroSelezionato' AND username_autore = '$utente'";
        $queryResult = mysqli_query($this -> connection, $query);
        return $queryResult;
    }

    public function getTuttiUtenti() {
        $query = "SELECT nome, cognome, username, email FROM utenti";
        $queryResult = mysqli_query($this -> connection, $query);
        if(mysqli_num_rows($queryResult) != 0){
            $result = array();
            while($row = mysqli_fetch_assoc($queryResult)) {
                $result[] = $row;
            }
            $queryResult -> free();
            return $result; 
        }
        else {
            return null;
        }
    }

    public function getTuttiLibri() {
        $query = "SELECT id, titolo_ir, titolo, descrizione, autore, lingua FROM libri";
        $queryResult = mysqli_query($this -> connection, $query);
        if(mysqli_num_rows($queryResult) != 0){
            $result = array();
            while($row = mysqli_fetch_assoc($queryResult)) {
                $result[] = $row;
            }
            $queryResult -> free();
            return $result; 
        }
        else {
            return null;
        }
    }

    public function aggiungiLibro($titolo, $autore, $lingua, $capitoli, $trama, $genere) {
        $query = "INSERT INTO libri (titolo, autore, lingua, n_capitoli, trama, id_genere,data_inserimento) 
                  VALUES (?, ?, ?, ?, ?, (SELECT id FROM generi WHERE nome = ?), NOW())";
        $stmt = $this -> connection -> prepare($query);
        if($stmt === false) {
            echo "<li>Errore nella preparazione dell'istruzione: " . $this -> connection -> error . "</li>";
        }
        else {
            $stmt->bind_param("sssiss", $titolo, $autore, $lingua, $capitoli, $trama, $genere);
            if (!$stmt->execute()) {
                /*echo "<li>Errore durante l'aggiunta del libro: " . $stmt->error . "</li>";*/
                return false;
            }
            return true;
            $stmt->close();
        }
    }

    public function eliminaLibro($id_libro) {
        $query = "DELETE FROM libri WHERE id = '$id_libro'";
        $queryResult = mysqli_query($this -> connection, $query);
        /*if($queryResult === false) {
            echo "<li>Errore durante la cancellazione del libro: " . $this -> connection -> error . "</li>";
        }*/
        return $queryResult;
    }

    public function getTuttiLibriOrdinati($opzione) {
        if($opzione=="alfabetico")
            $query = "SELECT id, titolo_ir, titolo, descrizione, autore, lingua, data_inserimento FROM libri ORDER BY titolo ASC";
        else if($opzione=="piu_recente")
            $query = "SELECT id, titolo_ir, titolo, descrizione, autore, lingua, data_inserimento FROM libri ORDER BY data_inserimento DESC";
        else if($opzione=="meno_recente")
            $query = "SELECT id, titolo_ir, titolo, descrizione, autore, lingua, data_inserimento FROM libri ORDER BY data_inserimento ASC";
        else if($opzione=="popolarita")
            $query = "SELECT l.id, l.titolo_ir, l.titolo, l.descrizione, l.autore, l.lingua, data_inserimento, COUNT(hl.id_libro) + COUNT(sl.id_libro) AS conteggio
                      FROM libri l
                      LEFT JOIN ha_letto hl ON l.id = hl.id_libro
                      LEFT JOIN sta_leggendo sl ON l.id = sl.id_libro
                      GROUP BY l.id, l.titolo_ir, l.titolo, l.descrizione, l.autore, l.lingua
                      ORDER BY conteggio DESC";
        $queryResult = mysqli_query($this -> connection, $query);
        if(mysqli_num_rows($queryResult) != 0){
            $result = array();
            while($row = mysqli_fetch_assoc($queryResult)) {
                $result[] = $row;
            }
            $queryResult -> free();
            return $result; 
        }
        else {
            return null;
        }
    }
    public function getTuttiUtentiOrdinati($opzione) {
        if($opzione=="alfabetico_username")
            $query = "SELECT nome, cognome, username, email, data_iscrizione FROM utenti ORDER BY username ASC";
        else if($opzione=="alfabetico_nome")
            $query = "SELECT nome, cognome, username, email, data_iscrizione FROM utenti ORDER BY nome ASC";
        else if($opzione=="alfabetico_cognome")
            $query = "SELECT nome, cognome, username, email, data_iscrizione FROM utenti ORDER BY cognome ASC";
        else if($opzione=="data_iscrizione_piu_recente")
            $query = "SELECT nome, cognome, username, email, data_iscrizione FROM utenti ORDER BY data_iscrizione DESC";
        else if($opzione=="data_iscrizione_meno_recente")
            $query = "SELECT nome, cognome, username, email, data_iscrizione FROM utenti ORDER BY data_iscrizione ASC";
        else// ($opzione=="attivi")
            $query = "SELECT utenti.nome, utenti.cognome, utenti.username, utenti.email, utenti.data_iscrizione, COUNT(sta_leggendo.username) AS numeroLibri
                      FROM utenti
                      LEFT JOIN sta_leggendo ON utenti.username = sta_leggendo.username
                      GROUP BY utenti.username
                      ORDER BY numeroLibri DESC";
        $queryResult = mysqli_query($this -> connection, $query);
        if(mysqli_num_rows($queryResult) != 0){
            $result = array();
            while($row = mysqli_fetch_assoc($queryResult)) {
                $result[] = $row;
            }
            $queryResult -> free();
            return $result; 
        }
        else {
            return null;
        }
    }

    public function modificaLibro($id_libro=45, $titolo='ciaooooooooo', $autore='ciao', $lingua ='ciao', $capitoli=3, $trama='ciao', $genere=2) {
        $query='UPDATE libri SET titolo=?, autore=?, lingua=?, n_capitoli=?, trama=?, id_genere=(SELECT id FROM generi WHERE nome = ?) WHERE id = ?';
        $stmt = $this -> connection -> prepare($query);
        if($stmt===false)
            return false;
        else {
            $stmt->bind_param('sssissi', $titolo, $autore, $lingua, $capitoli, $trama, $genere, $id_libro);
            $result = $stmt->execute();
            $stmt->close();
            return $result;
        }
    }

    public function verificaUsername($username) {
        $query = "SELECT * FROM utenti WHERE username = '$username'";
        $queryResult = mysqli_query($this -> connection, $query);
        if(mysqli_num_rows($queryResult) != 0){
            $queryResult -> free();
            return true;
        }
        else {
            return false;
        }
    }

    public function aggiungiHaLetto($username, $id_libro) {
        $query = "INSERT INTO ha_letto (username, id_libro, data_fine_lettura) VALUES (?, ?, NOW())";
        $stmt = $this -> connection -> prepare($query);
        if($stmt === false) {
            echo "<li>Errore nella preparazione dell'istruzione: " . $this -> connection -> error . "</li>";
        }
        else {
            $stmt->bind_param("si", $username, $id_libro);
            if (!$stmt->execute()) {
                echo "<li>Errore durante l'aggiunta del libro: " . $stmt->error . "</li>";
            }
            $stmt->close();
        }
    }

    public function rimuoviStaLeggendo($username, $id_libro) {
        $query = "DELETE FROM sta_leggendo WHERE username = '$username' AND id_libro = '$id_libro'";
        $queryResult = mysqli_query($this -> connection, $query);
        if($queryResult === false) {
            echo "<li>Errore durante la rimozione del libro: " . $this -> connection -> error . "</li>";
        }
    }

    public function aggiornaStaLeggendo($username, $id_libri, $capitoli) {
        $n = count($capitoli);
        $query = "UPDATE sta_leggendo SET n_capitoli_letti = ? WHERE username = ? AND id_libro = ?";
        $stmt = mysqli_prepare($this -> connection, $query);
        if($stmt === false) {
            echo "<li>Errore nella preparazione dell'istruzione: " . $this -> connection -> error . "</li>";
        } //ELSE??
        for($i=0; $i<$n; $i++) {
            $n_capitoli_totali = $this -> getncapitoliLibro($id_libri[$i]);
            if($capitoli[$i] >= $n_capitoli_totali) {
                $this -> aggiungiHaLetto($username, $id_libri[$i]);
                $this -> rimuoviStaLeggendo($username, $id_libri[$i]);
            }
            else {
                mysqli_stmt_bind_param($stmt, 'isi', $capitoli[$i], $username, $id_libri[$i]);
                $queryResult = mysqli_stmt_execute($stmt);
                if (!$queryResult) {
                    return false; // Se una delle query fallisce, ritorna false
                }
            }
        }
        return true; // Se tutte le query hanno successo, ritorna true
    }

    public function rimuoviHaLetto($username, $id_libro) {
        $query = "DELETE FROM ha_letto WHERE username = '$username' AND id_libro = '$id_libro'";
        $queryResult = mysqli_query($this -> connection, $query);
        if($queryResult === false) {
            echo "<li>Errore durante la rimozione del libro: " . $this -> connection -> error . "</li>";
        }
    }

    public function eliminaLibriHaLetto($username, $id_libri) {
        $n = count($id_libri);
        for($i=0; $i<$n; $i++) {
            $this -> rimuoviHaLetto($username, $id_libri[$i]);
        }
    }

    public function iniziaALeggere($username, $id_libri) {
        $n = count($id_libri);
        $query = "INSERT INTO sta_leggendo (username, id_libro, n_capitoli_letti) VALUES (?, ?, 0)";
        $stmt = $this -> connection -> prepare($query);
        if($stmt === false) {
            echo "<li>Errore nella preparazione dell'istruzione: " . $this -> connection -> error . "</li>";
        }
        else {
            for($i=0; $i<$n; $i++) {
                $stmt->bind_param("si", $username, $id_libri[$i]);
                $stmt->execute();
                $stmt->close();
            }
            $this -> eliminaLibriDaLeggere($username, $id_libri);
        }
        /*
        for($i=0; $i<$n; $i++) {
            $n_capitoli_totali = $this -> getncapitoliLibro($id_libri[$i]);
            if($capitoli[$i] >= $n_capitoli_totali) {
                $this -> aggiungiHaLetto($username, $id_libri[$i]);
                $this -> rimuoviStaLeggendo($username, $id_libri[$i]);
            }
            else {
                mysqli_stmt_bind_param($stmt, 'isi', $capitoli[$i], $username, $id_libri[$i]);
                $queryResult = mysqli_stmt_execute($stmt);
                if (!$queryResult) {
                    return false; // Se una delle query fallisce, ritorna false
                }
            }
        }
        */
    }

    public function eliminaLibriDaLeggere($username, $id_libri) {
        $n = count($id_libri);
        for($i=0; $i<$n; $i++) {
            $this -> rimuoviDaLeggere($username, $id_libri[$i]);
        }
    }

    public function getDataIscrione($username) {
        $query = "SELECT data_iscrizione FROM utenti WHERE username = '$username'";
        $queryResult = mysqli_query($this -> connection, $query);
        if(mysqli_num_rows($queryResult) != 0){
            $row = mysqli_fetch_assoc($queryResult); //dato che username è chiave primaria, ci sarà al più un risultato
            $queryResult -> free();
            return $row['data_iscrizione'];
        }
        else {
            return null;
        }
    }

    /* STATISTICHE */

    public function getNumeroLibriSalvati($username) {
        $query = "SELECT COUNT(*) AS numeroLibri FROM da_leggere WHERE username = '$username'";
        $queryResult = mysqli_query($this -> connection, $query);
        if(mysqli_num_rows($queryResult) != 0){
            $row = mysqli_fetch_assoc($queryResult); //dato che username è chiave primaria, ci sarà al più un risultato
            $queryResult -> free();
            return $row['numeroLibri'];
        }
        else {
            return null;
        }
    }

    public function getNumeroLibriStaLeggendo($username) {
        $query = "SELECT COUNT(*) AS numeroLibri FROM sta_leggendo WHERE username = '$username'";
        $queryResult = mysqli_query($this -> connection, $query);
        if(mysqli_num_rows($queryResult) != 0){
            $row = mysqli_fetch_assoc($queryResult); //dato che username è chiave primaria, ci sarà al più un risultato
            $queryResult -> free();
            return $row['numeroLibri'];
        }
        else {
            return null;
        }
    }

}
?> 