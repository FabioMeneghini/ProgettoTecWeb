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
        $query = "SELECT titolo, autore, genere, trama FROM libri LIMIT 10";
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
        $query = "SELECT genere FROM libri GROUP BY genere";
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

    public function getListaLibriGenere($genere) {
        $query = "SELECT titolo, autore FROM libri WHERE genere = '$genere'";
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
    
        if ($queryResult) {
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
        } else {
            //se è nulla? 
            ;
        }
    }

    // 
    public function getKeywordLibro($LibroSelezionato) {
        $query = "SELECT keyword FROM libro WHERE titolo = '$LibroSelezionato'";
        $queryResult = mysqli_query($this->connection, $query);
    
        if ($queryResult) {
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
        } else {
            return null;
        }
    }
    //??????????

    public function getCapitoliUtenteOggi($utente) {
        
    }
    

    //?????????
    public function getRecensioniUtente($utente) {
       
    }
    

    //?????
    public function getLibriUtenteAnno($utente) {
        
    }
    
    
    //??????
    public function getLibriUtenteMese($utente) {
        
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

    public function getRecensioniCount() {
        $query = "SELECT COUNT(*) AS numeroRecensioni FROM recensioni";
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
}

?>