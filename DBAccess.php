<?php
    namespace DB;
    class DBAccess {
        private const HOST_DB = "localhost"; //qiesti valori sono da cambiare prima di provarlo sul server di unipd
        private const DATABASE_NAME = "bookclub";
        private const USERNAME = "root";
        private const PASSWORD = "";

        private $connection;

        public function openDBConnection() {
            $this -> connection = mysqli_connect(
                self::HOST_DB,
                self::USERNAME,
                self::PASSWORD,
                self::DATABASE_NAME
            );
            return mysqli_connect_errno() == 0;
        }

        public function closeConnection() {
            mysqli_close($this -> connection);
        }

        /*
        public function getListaAlbum() {
            $query = "SELECT ID, Titolo, Copertina, idCss FROM Album ORDER BY DataPubblicazione DESC";
            $queryResult = mysqli_query($this -> connection, $query) or die("Errore in DBAccess" . mysqli_error($this -> connection));
            if(mysqli_num_rows($queryResult) != 0){
                $result = array();
                while($row = mysqli_fetch_assoc($queryResult)) {
                    $result[] = $row;
                }
                $queryResult -> free();
                return $result;
            }else{
                return null;
            }
        }
        

        public function getListaAlbum($id) {
            $query = "SELECT Album.ID,
                        Album.Titolo,
                        Copertina,
                        DataPubblicazione,
                        SEC_TO_TIME(SUM(TIME_TO_SEC(Traccia.Durata))) as DurataAlbum
                        FROM Album
                        JOIN Traccia ON Album.ID = Traccia.DurataAlbum
                        WHERE Album.ID = $id";
            $queryResult = mysqli_query($this -> connection, $query) or die("Errore in DBAccess" . mysqli_error($this -> connection));
            if(mysqli_num_rows($queryResult) != 0){
                $row = mysqli_fetch_assoc($queryResult);
                return array($row["ID"],
                        $row["Titolo"],
                        $row["Copertina"],
                        $row["DataPubblicazione"],
                        $row["DurataAlbum"]);
            }else{
                return null;
            }
        }

        public function getTracceAlbum($id) {
            $query = "SELECT Traccia.Titolo,
                Traccia.Esplicito,
                Traccia.Durata,
                DataRadio,
                URLVideo
            FROM Traccia
            JOIN Album ON Traccia.Album = Album.id
            WHERE Traccia.Album = $id";
        }

        public insertNewTrack($album, $titotlo, $durata, $esplicito, $dataRadio, $urlVideo, $note) {
            $queryInsert = "INSERT INTO Traccia(Titolo, Durata, Esplicito, URLVideo, DataRadio, Album, Note)
                VALUES (\"$titolo\",
                    \"$durata\",
                    \"$esplicito\",
                    NULLIF(\"$urlVideo\",\"\"),
                    NULLIF(\"$dataradio\",\"\"), 
                    NULLIF(\"$titolo\",\"\"), 
                    NULLIF(\"$album\",\"\"), 
                    NULLIF(\"$note\",\"\"));";
            mysqli_query(&this->connection, &queryInsert) or die(mysqli_error($this->connection));
            return mysqli_affected_rows(&this->connection) > 0;
        }
        */
    }
?>