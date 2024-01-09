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
        $query = "SELECT titolo, autore, genere FROM libri LIMIT 10";
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
        $query = "SELECT libri.titolo, libri.autore, libri.genere FROM libri, sta_leggendo WHERE sta_leggendo.username = '$username' AND sta_leggendo.id_libro = libri.id";
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
}

?>