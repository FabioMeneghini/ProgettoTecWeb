<?php

namespace DB;
class DBAccess {
    /*private $HOST_DB = "localhost"; //SERVER TECWEB
    private $DATABASE_NAME = "famenegh";
    private $USERNAME = "famenegh";
    private $PASSWORD = "einohn7yie1soaBu";*/

    private $HOST_DB = "localhost"; //SERVER LOCALE
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

    public function get_migliore_recensione($id_libro){
        $query="SELECT recensioni.commento, recensioni.voto
                FROM recensioni 
                WHERE recensioni.id_libro='$id_libro'
                ORDER BY recensioni.voto DESC
                LIMIT 1";
        $queryResult = mysqli_query($this -> connection, $query);
        if(mysqli_num_rows($queryResult) != 0){
            $row = mysqli_fetch_assoc($queryResult);
            mysqli_free_result($queryResult);
            return $row['commento'];
        } else {
            return null; // Ritorna null se non ci sono recensioni
        }
    }

    public function getListaBestSeller() {
        $query = "SELECT libri.titolo, libri.titolo_ir, libri.autore, generi.nome AS genere, libri.descrizione, libri.id, AVG(recensioni.voto) AS voto_medio
              FROM libri
              JOIN generi ON libri.id_genere = generi.id
              LEFT JOIN recensioni ON libri.id = recensioni.id_libro
              GROUP BY libri.id
              ORDER BY voto_medio DESC
              LIMIT 10";
        $queryResult = mysqli_query($this -> connection, $query);
        if(mysqli_num_rows($queryResult) != 0) {
            $result = array();
            while($row = mysqli_fetch_assoc($queryResult)) {
                // Chiama get_migliore_recensione per ogni libro
                $migliore_recensione = $this -> get_migliore_recensione($row["id"]);
                $voto_medio = $this -> getmediavoti($row["id"]);
                $row['voto_medio'] = $voto_medio;
                if ($migliore_recensione) {
                    // Aggiungi la migliore recensione al risultato del libro
                    $row['migliore_recensione'] = $migliore_recensione;
                } else {
                    // Se non ci sono recensioni, imposta 'migliore_recensione' su null
                    $row['migliore_recensione'] = null;
                }
                $result[] = $row;
            }
            mysqli_free_result($queryResult);
            return $result;
        } else
            return null;
    }

    public function login($username, $password) {
        $query = "SELECT * FROM utenti WHERE username = ? AND password = ?";
        $stmt = $this->connection->prepare($query);
        if (!$stmt)
            return null;
        $stmt->bind_param("ss", $username, $password);
        $stmt->execute();
        $result = $stmt->get_result();
        if ($result->num_rows != 0) {
            $row = $result->fetch_assoc();
            $result->free();
            return $row;
        } else
            return null;
    }

    public function usernameUnico($username) {
        $query = "SELECT * FROM utenti WHERE username = ?";
        $stmt = $this->connection->prepare($query);
        if (!$stmt)
            return false;
        $stmt->bind_param("s", $username);
        $stmt->execute();
        $result = $stmt->get_result();
        if ($result->num_rows != 0) {
            $result->free();
            return false;
        } else
            return true;
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
        $query = "SELECT libri.titolo, libri.id, libri.titolo_ir FROM libri, generi WHERE libri.id_genere=generi.id AND generi.nome=? LIMIT ?";
        $stmt = $this->connection->prepare($query);
        if ($stmt === false)
            return null;
        $stmt->bind_param("si", $genere, $n);
        $stmt->execute();
        $result = $stmt->get_result();
        if ($result->num_rows != 0) {
            $books = array();
            while ($row = $result->fetch_assoc()) {
                $books[] = $row;
            }
            $result->free();
            return $books;
        } else
            return null;
    }
    
    public function getMetaGenere($genereSelezionato) {
        $query = "SELECT keywords, descrizione FROM generi WHERE nome = ?";
        $stmt = $this->connection->prepare($query);
        if (!$stmt)
            return null;
        $stmt->bind_param("s", $genereSelezionato);
        $stmt->execute();
        $result = $stmt->get_result();
        if ($result->num_rows != 0) {
            $row = $result->fetch_assoc();
            $result->free();
            return $row;
        } else
            return null;
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

    public function getNumeroRecensioniUtente($username) {
        $query = "SELECT COUNT(*) AS numeroRecensioni FROM recensioni WHERE username_autore = ?";
        $stmt = $this->connection->prepare($query);
        if (!$stmt)
            return null;
        $stmt->bind_param("s", $username);
        $stmt->execute();
        $result = $stmt->get_result();
        if ($result->num_rows != 0) {
            $row = $result->fetch_assoc();
            $result->free();
            return $row['numeroRecensioni'];
        } else
            return null;
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
                  FROM libri
                  INNER JOIN sta_leggendo ON sta_leggendo.id_libro = libri.id
                  WHERE sta_leggendo.username = ?";
        $stmt = $this->connection->prepare($query);
        if (!$stmt)
            return null;
        $stmt->bind_param("s", $username);
        $stmt->execute();
        $queryResult = $stmt->get_result();
        if ($queryResult->num_rows != 0) {
            $result = array();
            while ($row = $queryResult->fetch_assoc()) {
                $result[] = $row;
            }
            $queryResult->free();
            return $result;
        } else
            return null;
    }

    public function getListaSalvati($username) {
        $query = "SELECT libri.id, libri.titolo, libri.autore, generi.nome AS genere
                  FROM libri
                  JOIN generi ON libri.id_genere = generi.id
                  JOIN da_leggere ON da_leggere.id_libro = libri.id
                  WHERE da_leggere.username = ?";
        $stmt = $this->connection->prepare($query);
        if (!$stmt)
            return null;
        $stmt->bind_param("s", $username);
        $stmt->execute();
        $queryResult = $stmt->get_result();
        if ($queryResult->num_rows != 0) {
            $result = array();
            while ($row = $queryResult->fetch_assoc()) {
                $result[] = $row;
            }
            $queryResult->free();
            return $result;
        } else
            return null;
    }

    public function getListaTerminati($username) {
        $query = "SELECT libri.id, libri.titolo, libri.autore, generi.nome AS genere, ha_letto.data_fine_lettura, IFNULL(recensioni.voto, 'Non assegnato') AS voto
                  FROM libri
                  JOIN generi ON libri.id_genere = generi.id
                  JOIN ha_letto ON ha_letto.id_libro = libri.id
                  LEFT JOIN recensioni ON ha_letto.id_libro = recensioni.id_libro AND ha_letto.username = recensioni.username_autore
                  WHERE ha_letto.username = ?
                  ORDER BY ha_letto.data_fine_lettura DESC";
        $stmt = $this->connection->prepare($query);
        if (!$stmt)
            return null;
        $stmt->bind_param("s", $username);
        $stmt->execute();
        $queryResult = $stmt->get_result();
        if ($queryResult->num_rows != 0) {
            $result = array();
            while ($row = $queryResult->fetch_assoc()) {
                $result[] = $row;
            }
            $queryResult->free();
            return $result;
        } else
            return null;
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

    /********************************************************************************************** */
    public function aggiungiStaLeggendo($username, $id_libro) {
        $query = "INSERT INTO sta_leggendo (username, id_libro, n_capitoli_letti) VALUES (?, ?, 0)";
        $stmt = $this -> connection -> prepare($query);
        if($stmt === false) {
            return;
        }
        else {
            $stmt->bind_param("si", $username, $id_libro);
            if (!$stmt->execute()) {
                return;
            }
            $stmt->close();
        }
    }

    public function aggiungiDaLeggere($username, $id_libro) {
        $query = "INSERT INTO da_leggere (username, id_libro) VALUES (?, ?)";
        $stmt = $this -> connection -> prepare($query);
        if($stmt === false) {
            return;
        }
        else {
            $stmt->bind_param("si", $username, $id_libro);
            if (!$stmt->execute()) {
                return;
            }
            $stmt->close();
        }
    }

    public function rimuoviDaLeggere($username, $id_libro) {
        $query = "DELETE FROM da_leggere WHERE username = ? AND id_libro = ?";
        $stmt = $this -> connection -> prepare($query);
        if($stmt === false)
            return;
        else {
            $stmt->bind_param("si", $username, $id_libro);
            if (!$stmt->execute())
                return;
            $stmt->close();
        }
    }
    /*********************************************************************************************** */

    public function modificaUsername($old, $new) {
        $query = "UPDATE utenti SET username = ? WHERE username = ?";
        $stmt = $this->connection->prepare($query);
        if (!$stmt)
            return;
        $stmt->bind_param("ss", $new, $old);
        if(!$stmt->execute())
            return;
        $stmt->close();
    }
    
    public function modificaPassword($username, $new) {
        $query = "UPDATE utenti SET password = ? WHERE username = ?";
        $stmt = $this->connection->prepare($query);
        if (!$stmt)
            return;
        $stmt->bind_param("ss", $new, $username);
        if(!$stmt->execute())
            return;
        $stmt->close();
    }

    public function modificaEmail($username, $new) {
        $query = "UPDATE utenti SET email = ? WHERE username = ?";
        $stmt = $this->connection->prepare($query);
        if (!$stmt)
            return;
        $stmt->bind_param("ss", $new, $username);
        if(!$stmt->execute())
            return;
        $stmt->close();
    }

    public function verificaPassword($username, $password) {
        $query = "SELECT * FROM utenti WHERE username = ? AND password = ?";
        $stmt = $this->connection->prepare($query);
        if (!$stmt)
            return false;
        $stmt->bind_param("ss", $username, $password);
        $stmt->execute();
        $result = $stmt->get_result();
        if ($result->num_rows != 0) {
            $result->free();
            return true;
        } else
            return false;
    }

    public function eliminaUtente($username) {
        $query = "DELETE FROM utenti WHERE username = ?";
        $stmt = $this->connection->prepare($query);
        if (!$stmt)
            return;
        $stmt->bind_param("s", $username);
        if(!$stmt->execute())
            return;
        $stmt->close();
    }

    /*public function getInfoLibro($id_libro) {
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
    }*/

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
                      WHERE ha_letto.username = ?
                      AND ha_letto.id_libro = libri.id
                  )
                  GROUP BY generi.nome
                  ORDER BY numeroLibri DESC
                  LIMIT 5";
        $stmt = $this->connection->prepare($query);
        if (!$stmt)
            return null;
        $stmt->bind_param("s", $username);
        $stmt->execute();
        $queryResult = $stmt->get_result();
        if ($queryResult->num_rows != 0) {
            $result = array();
            while ($row = $queryResult->fetch_assoc()) {
                $result[] = $row;
            }
            $queryResult->free();
            return $result;
        } else
            return null;
    }

    public function cercaLibro($stringa, $autore, $genere, $lingua) {
        $query = "SELECT libri.id, libri.titolo, libri.titolo_ir,
                         libri.descrizione, libri.autore, libri.lingua, generi.nome AS genere
                  FROM libri
                  INNER JOIN generi ON libri.id_genere = generi.id
                  WHERE libri.titolo LIKE CONCAT('%', ?, '%')
                  AND libri.autore LIKE CONCAT('%', ?, '%')
                  AND generi.nome LIKE CONCAT('%', ?, '%')
                  AND libri.lingua LIKE CONCAT('%', ?, '%')";
        $stmt = $this->connection->prepare($query);
        if (!$stmt)
            return null;
        $stmt->bind_param("ssss", $stringa, $autore, $genere, $lingua);
        $stmt->execute();
        $queryResult = $stmt->get_result();
        if ($queryResult === false)
            return null;
        if ($queryResult->num_rows != 0) {
            $result = array();
            while ($row = $queryResult->fetch_assoc()) {
                $result[] = $row;
            }
            $queryResult->free();
            return $result;
        } else
            return null;
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

    // controlla la presenza di un genere
    public function controllagenere($genereSelezionato) {
        $query = "SELECT nome FROM generi WHERE nome = ?";
        $stmt = $this->connection->prepare($query);
        if ($stmt === false)
            return false;
        $stmt->bind_param("s", $genereSelezionato);
        $stmt->execute();
        $result = $stmt->get_result();
        if ($result->num_rows != 0) {
            $result->free();
            return true;
        } else
            return false;
    }
    
    //controlla la presenza di un libro 
    public function controllareIdLibro($LibroSelezionato) {
        $query = "SELECT id FROM libri WHERE id = ?";
        $stmt = $this->connection->prepare($query);
        if (!$stmt)
            return false;
        $stmt->bind_param("i", $LibroSelezionato);
        $stmt->execute();
        $result = $stmt->get_result();
        if ($result->num_rows != 0) {
            $result->free();
            return true;
        } else
            return false;
    }
    
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
    }

    public function getMetaLibro($LibroSelezionato) {
        $query = "SELECT keywords, descrizione_pagina FROM libri WHERE id = '$LibroSelezionato'";
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
        $query = "SELECT n_capitoli FROM libri WHERE id = ?";
        $stmt = $this->connection->prepare($query);
        if (!$stmt)
            return null;
        $stmt->bind_param("i", $LibroSelezionato);
        $stmt->execute();
        $queryResult = $stmt->get_result();
        if ($queryResult->num_rows != 0) {
            $row = $queryResult->fetch_assoc();
            $queryResult->free();
            return $row['n_capitoli'];
        } else
            return null;
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
        $query = "SELECT AVG(voto) AS media_voti FROM recensioni WHERE id_libro = ?";
        $stmt = $this->connection->prepare($query);
        if (!$stmt)
            return null;
        $stmt->bind_param("i", $LibroSelezionato);
        $stmt->execute();
        $result = $stmt->get_result();
        if ($result->num_rows != 0) {
            $row = $result->fetch_assoc();
            $result->free();
            return round($row['media_voti'], 2);
        } else
            return null;
    }
    
    public function getaltrerecensioni($LibroSelezionato, $username="") {
        $query = "SELECT username_autore, commento FROM recensioni WHERE id_libro = ? AND username_autore != ?";
        $stmt = $this->connection->prepare($query);
        if (!$stmt)
            return null;
        $stmt->bind_param("is", $LibroSelezionato, $username);
        $stmt->execute();
        $result = $stmt->get_result();
        if ($result->num_rows != 0) {
            $recensioni = array();
            while ($row = $result->fetch_assoc()) {
                $recensioni[] = $row;
            }
            $result->free();
            return $recensioni;
        } else
            return null;
    }

    public function getTuaRecensione($LibroSelezionato, $utente) {
        $query = "SELECT commento, voto FROM recensioni WHERE id_libro = ? AND username_autore = ?";
        $stmt = $this->connection->prepare($query);
        if (!$stmt)
            return null;
        $stmt->bind_param("is", $LibroSelezionato, $utente);
        $stmt->execute();
        $result = $stmt->get_result();
        if ($result->num_rows != 0) {
            $row = $result->fetch_assoc();
            $result->free();
            return $row;
        } else
            return null;
    }
    
    /*public function getrecensionetua($LibroSelezionato, $utente) {
        $query = "SELECT commento FROM recensioni WHERE id_libro = ? AND username_autore = ?";
        $stmt = $this->connection->prepare($query);
        if (!$stmt)
            return null;
        $stmt->bind_param("is", $LibroSelezionato, $utente);
        $stmt->execute();
        $result = $stmt->get_result();
        if ($result->num_rows != 0) {
            $row = $result->fetch_assoc();
            $result->free();
            return $row['commento'];
        } else
            return null;
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
    }*/

    public function is_terminato($LibroSelezionato, $utente) {
        $query = "SELECT * FROM ha_letto WHERE id_libro = ? AND username = ?";
        $stmt = $this->connection->prepare($query);
        if (!$stmt)
            return false;
        $stmt->bind_param("is", $LibroSelezionato, $utente);
        $stmt->execute();
        $result = $stmt->get_result();
        if ($result->num_rows != 0) {
            $result->free();
            return true;
        } else
            return false;
    }

    public function is_salvato($LibroSelezionato, $utente) {
        $query = "SELECT * FROM da_leggere WHERE id_libro = ? AND username = ?";
        $stmt = $this->connection->prepare($query);
        if (!$stmt)
            return false;
        $stmt->bind_param("is", $LibroSelezionato, $utente);
        $stmt->execute();
        $result = $stmt->get_result();
        if ($result->num_rows != 0) {
            $result->free();
            return true;
        } else
            return false;
    }

    public function is_iniziato($LibroSelezionato, $utente) {
        $query = "SELECT * FROM sta_leggendo WHERE id_libro = ? AND username = ?";
        $stmt = $this->connection->prepare($query);
        if (!$stmt)
            return false;
        $stmt->bind_param("is", $LibroSelezionato, $utente);
        $stmt->execute();
        $result = $stmt->get_result();
        if ($result->num_rows != 0) {
            $result->free();
            return true;
        } else
            return false;
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
            return false;
        }
        else {
            $stmt->bind_param("sssiss", $titolo, $autore, $lingua, $capitoli, $trama, $genere);
            if(!$stmt->execute()) {
                return false;
            }
            return true;
            $stmt->close();
        }
    }

    public function valutazionePresente($username, $id_libro) {
        $query = "SELECT * FROM recensioni WHERE username_autore = ? AND id_libro = ?";
        $stmt = $this->connection->prepare($query);
        if (!$stmt)
            return false;
        $stmt->bind_param("si", $username, $id_libro);
        $stmt->execute();
        $result = $stmt->get_result();
        if ($result->num_rows != 0) {
            $result->free();
            return true;
        } else
            return false;
    }

    public function aggiungiValutazione($username, $id_libro, $voto, $commento) {
        if($this -> valutazionePresente($username, $id_libro)) {
            $query = "UPDATE recensioni SET voto = ?, commento = ? WHERE username_autore = ? AND id_libro = ?";
            $stmt = $this -> connection -> prepare($query);
            if($stmt === false) {
                return false;
            }
            else {
                $stmt->bind_param("issi", $voto, $commento, $username, $id_libro);
                if(!$stmt->execute()) {
                    return false;
                }
                $stmt->close();
            }
        }
        else {
            $query = "INSERT INTO recensioni (username_autore, id_libro, voto, commento) VALUES (?, ?, ?, ?)";
            $stmt = $this -> connection -> prepare($query);
            if($stmt === false) {
                return false;
            }
            else {
                $stmt->bind_param("siis", $username, $id_libro, $voto, $commento);
                if (!$stmt->execute()) {
                    return false;
                }
                $stmt->close();
            }
        }
    }

    public function eliminaLibro($id_libro) {
        $query = "DELETE FROM libri WHERE id = '$id_libro'";
        $queryResult = mysqli_query($this -> connection, $query);
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
        else// if($opzione=="attivi")
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

    public function aggiungiHaLetto($username, $id_libro) {
        $query = "INSERT INTO ha_letto (username, id_libro, data_fine_lettura) VALUES (?, ?, NOW())";
        $stmt = $this -> connection -> prepare($query);
        if($stmt === false) {
            return false;
        }
        else {
            $stmt->bind_param("si", $username, $id_libro);
            if(!$stmt->execute()) {
                return false;
            }
            $stmt->close();
        }
    }

    public function rimuoviStaLeggendo($username, $id_libro) {
        $query = "DELETE FROM sta_leggendo WHERE username = ? AND id_libro = ?";
        $stmt = $this->connection->prepare($query);
        if (!$stmt)
            return false;
        $stmt->bind_param("si", $username, $id_libro);
        if(!$stmt->execute())
            return false;
        $stmt->close();
    }

    public function aggiornaStaLeggendo($username, $id_libri, $capitoli) {
        $n = count($capitoli);
        $query = "UPDATE sta_leggendo SET n_capitoli_letti = ? WHERE username = ? AND id_libro = ?";
        $stmt = mysqli_prepare($this -> connection, $query);
        if($stmt === false) {
            return false;
        }
        for($i=0; $i<$n; $i++) {
            $n_capitoli_totali = $this -> getncapitoliLibro($id_libri[$i]);
            if($capitoli[$i] >= $n_capitoli_totali) {
                $this -> aggiungiHaLetto($username, $id_libri[$i]);
                $this -> rimuoviStaLeggendo($username, $id_libri[$i]);
            }
            else {
                mysqli_stmt_bind_param($stmt, 'isi', $capitoli[$i], $username, $id_libri[$i]);
                $queryResult = mysqli_stmt_execute($stmt);
                if(!$queryResult) {
                    return false; // Se una delle query fallisce, ritorna false
                }
            }
        }
        return true; // Se tutte le query hanno successo, ritorna true
    }

    public function rimuoviHaLetto($username, $id_libro) {
        $query = "DELETE FROM ha_letto WHERE username = ? AND id_libro = ?";
        $stmt = $this->connection->prepare($query);
        if (!$stmt)
            return;
        $stmt->bind_param("si", $username, $id_libro);
        if(!$stmt->execute())
            return;
        $stmt->close();
    }

    public function eliminaLibriTerminati($username, $id_libri) {
        $n = count($id_libri);
        for($i=0; $i<$n; $i++) {
            $this -> rimuoviHaLetto($username, $id_libri[$i]);
        }
    }

    public function iniziaALeggere($username, $id_libri) {
        $n = count($id_libri);
        for($i=0; $i<$n; $i++) {
            $this -> aggiungiLibroStaLeggendo($username, $id_libri[$i]);
        }
        $this -> eliminaLibriDaLeggere($username, $id_libri);
    }

    public function aggiungiLibroStaLeggendo($username, $id_libro) {
        $n = count($id_libri);
        $query = "INSERT INTO sta_leggendo (username, id_libro, n_capitoli_letti) VALUES (?, ?, 0)";
        $stmt = $this -> connection -> prepare($query);
        if($stmt === false) {
            return false;
        }
        else {
            $stmt->bind_param("si", $username, $id_libro);
            $stmt->execute();
            $stmt->close();
            return true;
        }
    }

    public function eliminaLibriDaLeggere($username, $id_libri) {
        $n = count($id_libri);
        for($i=0; $i<$n; $i++) {
            $this -> rimuoviDaLeggere($username, $id_libri[$i]);
        }
    }

    public function getLibro($id_libro) {
        $query = "SELECT libri.*, generi.nome AS genere FROM libri INNER JOIN generi ON libri.id_genere = generi.id WHERE libri.id = ?";
        $stmt = $this->connection->prepare($query);
        if ($stmt === false)
            return;
        $stmt->bind_param('i', $id_libro);
        $result = $stmt->execute();
        if ($result === false)
            return;
        $queryResult = $stmt->get_result();
        if ($queryResult->num_rows != 0) {
            $row = $queryResult->fetch_assoc();
            $queryResult->free();
            return $row;
        } else
            return null;
    }

    public function eliminaValutazione($username, $id_libro) {
        $query = "DELETE FROM recensioni WHERE username_autore = ? AND id_libro = ?";
        $stmt = $this->connection->prepare($query);
        if ($stmt === false) {
            return;
        }
        $stmt->bind_param('si', $username, $id_libro);
        $result = $stmt->execute();
        if ($result === false) {
            return;
        }
        $stmt->close();
    }

    public function eliminaValutazioni($username, $id_libri) {
        $n = count($id_libri);
        for($i=0; $i<$n; $i++) {
            $this -> eliminaValutazione($username, $id_libri[$i]);
        }
    }

    public function getDataIscrizione($username) {
        $query = "SELECT data_iscrizione FROM utenti WHERE username = ?";
        $stmt = $this->connection->prepare($query);
        if (!$stmt)
            return null;
        $stmt->bind_param("s", $username);
        $stmt->execute();
        $result = $stmt->get_result();
        if ($result->num_rows != 0) {
            $row = $result->fetch_assoc();
            $result->free();
            return $row['data_iscrizione'];
        } else
            return null;
    }

    /* STATISTICHE */

    public function getNumeroLibriSalvati($username) {
        $query = "SELECT COUNT(*) AS numeroLibri FROM da_leggere WHERE username = ?";
        $stmt = $this->connection->prepare($query);
        if (!$stmt)
            return null;
        $stmt->bind_param("s", $username);
        $stmt->execute();
        $result = $stmt->get_result();
        if ($result->num_rows != 0) {
            $row = $result->fetch_assoc();
            $result->free();
            return $row['numeroLibri'];
        } else
            return null;
    }

    public function getNumeroLibriStaLeggendo($username) {
        $query = "SELECT COUNT(*) AS numeroLibri FROM sta_leggendo WHERE username = ?";
        $stmt = $this->connection->prepare($query);
        if (!$stmt)
            return null;
        $stmt->bind_param("s", $username);
        $stmt->execute();
        $result = $stmt->get_result();
        if ($result->num_rows != 0) {
            $row = $result->fetch_assoc();
            $result->free();
            return $row['numeroLibri'];
        } else
            return null;
    }

    public function getNumeroLibriLettiUltimoAnno($username) {
        $query = "SELECT COUNT(*) AS numeroLibri FROM ha_letto WHERE username = ? AND data_fine_lettura >= DATE_SUB(NOW(), INTERVAL 1 YEAR)";
        $stmt = $this->connection->prepare($query);
        if (!$stmt)
            return null;
        $stmt->bind_param("s", $username);
        $stmt->execute();
        $result = $stmt->get_result();
        if ($result->num_rows != 0) {
            $row = $result->fetch_assoc();
            $result->free();
            return $row['numeroLibri'];
        } else
            return null;
    }

    public function getNumeroLibriLetti($username) {
        $query = "SELECT COUNT(*) AS numeroLibri FROM ha_letto WHERE username = ?";
        $stmt = $this->connection->prepare($query);
        if (!$stmt)
            return null;
        $stmt->bind_param("s", $username);
        $stmt->execute();
        $result = $stmt->get_result();
        if ($result->num_rows != 0) {
            $row = $result->fetch_assoc();
            $result->free();
            return $row['numeroLibri'];
        } else
            return null;
    }

    public function getNumeroUtentiRegistratiOggi(){
        $query = "SELECT COUNT(*) AS iscritti_oggi FROM utenti WHERE DATE(NOW()) = DATE(data_iscrizione)";
        $queryResult = mysqli_query($this->connection, $query);
        if (mysqli_num_rows($queryResult) != 0) {
            $row = mysqli_fetch_assoc($queryResult);
            return $row['iscritti_oggi'];
        } else {
            return null;
        }
    }
    
    public function getNumeroLibriTerminatiOggi(){
        $query = "SELECT COUNT(*) AS letti_oggi FROM ha_letto WHERE DATE(NOW()) = DATE(data_fine_lettura)";
        $queryResult = mysqli_query($this->connection, $query);
        if (mysqli_num_rows($queryResult) != 0) {
            $row = mysqli_fetch_assoc($queryResult);
            return $row['letti_oggi'];
        } else {
            return null;
        }
    }
    
    public function getEtaMediaUtenti(){
        $query = "SELECT AVG(YEAR(NOW()) - YEAR(data_nascita)) AS eta_media FROM utenti";
        $queryResult = mysqli_query($this->connection, $query);
        if (mysqli_num_rows($queryResult) != 0) {
            $row = mysqli_fetch_assoc($queryResult);
            return round($row['eta_media'], 2);
        } else {
            return null;
        }
    }
    
    public function getNumeroLibri(){
        $query = "SELECT COUNT(*) as numero FROM libri";
        $queryResult = mysqli_query($this->connection, $query);
        if(mysqli_num_rows($queryResult) != 0){
            $row = mysqli_fetch_assoc($queryResult);
            return $row['numero'];
        } else {
            return null;
        }
    }
}
?> 