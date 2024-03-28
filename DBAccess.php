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

    public function registraUtente($nome, $cognome, $username, $email, $password) {
        $messaggio = "";
        $query = "INSERT INTO utenti (nome, cognome, username, email, password) VALUES (?, ?, ?, ?, ?)";
        $stmt = $this -> connection -> prepare($query);
        if($stmt === false) {
            $messaggio .= "<li>Errore nella preparazione dell'istruzione: " . $this -> connection -> error . "</li>";
        }
        else {
            $stmt->bind_param("sssss", $nome, $cognome, $username, $email, $password);
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
        $query="SELECT libri.titolo, libri.id FROM libri, generi WHERE libri.id_genere=generi.id AND generi.nome='$genere' LIMIT $n";
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
        $query = "SELECT libri.titolo, libri.autore, libri.genere, sta_leggendo.n_capitoli_letti, libri.n_capitoli
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
        $query = "SELECT libri.id, libri.titolo, libri.autore, libri.genere
                  FROM libri, da_leggere
                  WHERE da_leggere.username = '$username'
                  AND da_leggere.id_libro = libri.id";
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
        $query = "SELECT libri.titolo, libri.autore, libri.genere, ha_letto.data_fine_lettura, recensioni.voto
                  FROM libri, ha_letto, recensioni
                  WHERE ha_letto.username = '$username'
                  AND ha_letto.id_libro = libri.id
                  AND ha_letto.id_libro = recensioni.id_libro
                  AND ha_letto.username = recensioni.username_autore
                  UNION
                  SELECT libri.titolo, libri.autore, libri.genere, ha_letto.data_fine_lettura, 'Non assegnato'
                  FROM libri, ha_letto
                  WHERE ha_letto.username = '$username'
                  AND ha_letto.id_libro = libri.id
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
                  OR libri.autore LIKE '%$autore%'
                  OR generi.nome LIKE '%$genere%'
                  OR libri.lingua = '$lingua'";
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
                $result[] = $row['lingua'];
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
            return $row['media_voti'];
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
        $query = "INSERT INTO libri (titolo, autore, lingua, n_capitoli, trama, id_genere) 
                  VALUES (?, ?, ?, ?, ?, (SELECT id FROM generi WHERE nome = ?))";
        $stmt = $this -> connection -> prepare($query);
        if($stmt === false) {
            echo "<li>Errore nella preparazione dell'istruzione: " . $this -> connection -> error . "</li>";
        }
        else {
            $stmt->bind_param("sssiss", $titolo, $autore, $lingua, $capitoli, $trama, $genere);
            if (!$stmt->execute()) {
                echo "<li>Errore durante l'aggiunta del libro: " . $stmt->error . "</li>";
            }
            $stmt->close();
        }
    }

    public function eliminaLibro($id_libro) {
        $query = "DELETE FROM libri WHERE id = '$id_libro'";
        $queryResult = mysqli_query($this -> connection, $query);
        if($queryResult === false) {
            echo "<li>Errore durante la cancellazione del libro: " . $this -> connection -> error . "</li>";
        }
        return $queryResult;
    }

    public function getTuttiLibriOrdinati($opzione) {
        if($opzione=="alfabetico")
            $query = "SELECT id, titolo_ir, titolo, descrizione, autore, lingua FROM libri ORDER BY titolo ASC";
        else if($opzione=="piu_recente")
            $query = "SELECT id, titolo_ir, titolo, descrizione, autore, lingua FROM libri ORDER BY titolo ASC";
        else if($opzione=="meno_recente")
            $query = "SELECT id, titolo_ir, titolo, descrizione, autore, lingua FROM libri ORDER BY titolo ASC";
        else if($opzione=="popolarita")
            $query = "SELECT l.id, l.titolo_ir, l.titolo, l.descrizione, l.autore, l.lingua, COUNT(hl.id_libro) + COUNT(sl.id_libro) AS conteggio
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
            $query = "SELECT nome, cognome, username, email FROM utenti ORDER BY username ASC";
        else if($opzione=="alfabetico_nome")
            $query = "SELECT nome, cognome, username, email FROM utenti ORDER BY nome ASC";
        else if($opzione=="alfabetico_cognome")
            $query = "SELECT nome, cognome, username, email FROM utenti ORDER BY cognome ASC";
        else if($opzione=="data_piu_recente")
            $query = "SELECT nome, cognome, username, email FROM utenti ORDER BY username ASC";
        else if($opzione=="data_meno_recente")
            $query = "SELECT nome, cognome, username, email FROM utenti ORDER BY username ASC";
        else// ($opzione=="attivi")
            $query = "SELECT utenti.nome, utenti.cognome, utenti.username, utenti.email, COUNT(sta_leggendo.username) AS numeroLibri
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

}
?> 