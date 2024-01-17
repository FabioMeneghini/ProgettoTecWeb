/*
SQLyog Community v13.1.7 (64 bit)
MySQL - 5.7.17 : Database - bookclub
*********************************************************************
*/

/*!40101 SET NAMES utf8 */;

/*!40101 SET SQL_MODE=''*/;

/*!40014 SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;
CREATE DATABASE /*!32312 IF NOT EXISTS*/`bookclub` /*!40100 DEFAULT CHARACTER SET latin1 */;

USE `bookclub`;

/*Table structure for table `da_leggere` */

DROP TABLE IF EXISTS `da_leggere`;

CREATE TABLE `da_leggere` (
  `username` varchar(25) NOT NULL,
  `id_libro` int(10) unsigned NOT NULL,
  PRIMARY KEY (`username`,`id_libro`),
  KEY `fk_id_libro_2` (`id_libro`),
  CONSTRAINT `fk_id_libro_2` FOREIGN KEY (`id_libro`) REFERENCES `libri` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `fk_username` FOREIGN KEY (`username`) REFERENCES `utenti` (`username`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

/*Data for the table `da_leggere` */

insert  into `da_leggere`(`username`,`id_libro`) values 
('abianchi',1);
insert  into `da_leggere`(`username`,`id_libro`) values 
('mrossi',1);
insert  into `da_leggere`(`username`,`id_libro`) values 
('lrosa',2);
insert  into `da_leggere`(`username`,`id_libro`) values 
('pviola',2);
insert  into `da_leggere`(`username`,`id_libro`) values 
('mgialli',3);
insert  into `da_leggere`(`username`,`id_libro`) values 
('mrossi',3);
insert  into `da_leggere`(`username`,`id_libro`) values 
('admin',5);
insert  into `da_leggere`(`username`,`id_libro`) values 
('pviola',6);
insert  into `da_leggere`(`username`,`id_libro`) values 
('lverdi',7);
insert  into `da_leggere`(`username`,`id_libro`) values 
('sneri',7);
insert  into `da_leggere`(`username`,`id_libro`) values 
('abianchi',8);
insert  into `da_leggere`(`username`,`id_libro`) values 
('mrossi',8);
insert  into `da_leggere`(`username`,`id_libro`) values 
('pviola',8);
insert  into `da_leggere`(`username`,`id_libro`) values 
('amarrone',10);
insert  into `da_leggere`(`username`,`id_libro`) values 
('sneri',11);
insert  into `da_leggere`(`username`,`id_libro`) values 
('abianchi',12);
insert  into `da_leggere`(`username`,`id_libro`) values 
('mgialli',12);
insert  into `da_leggere`(`username`,`id_libro`) values 
('admin',13);
insert  into `da_leggere`(`username`,`id_libro`) values 
('pviola',13);
insert  into `da_leggere`(`username`,`id_libro`) values 
('sneri',13);
insert  into `da_leggere`(`username`,`id_libro`) values 
('abianchi',14);
insert  into `da_leggere`(`username`,`id_libro`) values 
('earancio',15);
insert  into `da_leggere`(`username`,`id_libro`) values 
('abianchi',16);
insert  into `da_leggere`(`username`,`id_libro`) values 
('admin',17);
insert  into `da_leggere`(`username`,`id_libro`) values 
('mrossi',17);
insert  into `da_leggere`(`username`,`id_libro`) values 
('amarrone',18);
insert  into `da_leggere`(`username`,`id_libro`) values 
('sneri',18);
insert  into `da_leggere`(`username`,`id_libro`) values 
('admin',19);
insert  into `da_leggere`(`username`,`id_libro`) values 
('gblu',20);
insert  into `da_leggere`(`username`,`id_libro`) values 
('admin',21);
insert  into `da_leggere`(`username`,`id_libro`) values 
('amarrone',22);
insert  into `da_leggere`(`username`,`id_libro`) values 
('pviola',22);
insert  into `da_leggere`(`username`,`id_libro`) values 
('earancio',23);
insert  into `da_leggere`(`username`,`id_libro`) values 
('amarrone',24);
insert  into `da_leggere`(`username`,`id_libro`) values 
('lrosa',25);
insert  into `da_leggere`(`username`,`id_libro`) values 
('abianchi',26);
insert  into `da_leggere`(`username`,`id_libro`) values 
('amarrone',26);
insert  into `da_leggere`(`username`,`id_libro`) values 
('earancio',27);
insert  into `da_leggere`(`username`,`id_libro`) values 
('sneri',27);
insert  into `da_leggere`(`username`,`id_libro`) values 
('gblu',28);
insert  into `da_leggere`(`username`,`id_libro`) values 
('earancio',29);
insert  into `da_leggere`(`username`,`id_libro`) values 
('lverdi',30);
insert  into `da_leggere`(`username`,`id_libro`) values 
('admin',31);
insert  into `da_leggere`(`username`,`id_libro`) values 
('earancio',31);
insert  into `da_leggere`(`username`,`id_libro`) values 
('gblu',32);
insert  into `da_leggere`(`username`,`id_libro`) values 
('lrosa',33);
insert  into `da_leggere`(`username`,`id_libro`) values 
('gblu',34);
insert  into `da_leggere`(`username`,`id_libro`) values 
('mgialli',35);
insert  into `da_leggere`(`username`,`id_libro`) values 
('amarrone',36);
insert  into `da_leggere`(`username`,`id_libro`) values 
('gblu',36);
insert  into `da_leggere`(`username`,`id_libro`) values 
('lrosa',37);
insert  into `da_leggere`(`username`,`id_libro`) values 
('lverdi',38);
insert  into `da_leggere`(`username`,`id_libro`) values 
('lrosa',39);
insert  into `da_leggere`(`username`,`id_libro`) values 
('mrossi',40);
insert  into `da_leggere`(`username`,`id_libro`) values 
('earancio',41);
insert  into `da_leggere`(`username`,`id_libro`) values 
('lverdi',42);
insert  into `da_leggere`(`username`,`id_libro`) values 
('lverdi',43);
insert  into `da_leggere`(`username`,`id_libro`) values 
('mgialli',43);
insert  into `da_leggere`(`username`,`id_libro`) values 
('mgialli',44);

/*Table structure for table `ha_letto` */

DROP TABLE IF EXISTS `ha_letto`;

CREATE TABLE `ha_letto` (
  `username` varchar(25) NOT NULL,
  `id_libro` int(10) unsigned NOT NULL,
  `data_fine_lettura` date DEFAULT NULL,
  PRIMARY KEY (`username`,`id_libro`),
  KEY `fk_id_libro_4` (`id_libro`),
  CONSTRAINT `fk_id_libro_4` FOREIGN KEY (`id_libro`) REFERENCES `libri` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `fk_username_3` FOREIGN KEY (`username`) REFERENCES `utenti` (`username`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

/*Data for the table `ha_letto` */

insert  into `ha_letto`(`username`,`id_libro`,`data_fine_lettura`) values 
('abianchi',1,'2023-01-10');
insert  into `ha_letto`(`username`,`id_libro`,`data_fine_lettura`) values 
('abianchi',12,'2023-01-05');
insert  into `ha_letto`(`username`,`id_libro`,`data_fine_lettura`) values 
('abianchi',14,'2023-01-15');
insert  into `ha_letto`(`username`,`id_libro`,`data_fine_lettura`) values 
('admin',5,'2023-02-15');
insert  into `ha_letto`(`username`,`id_libro`,`data_fine_lettura`) values 
('admin',17,'2023-02-10');
insert  into `ha_letto`(`username`,`id_libro`,`data_fine_lettura`) values 
('admin',19,'2023-02-20');
insert  into `ha_letto`(`username`,`id_libro`,`data_fine_lettura`) values 
('amarrone',10,'2023-03-20');
insert  into `ha_letto`(`username`,`id_libro`,`data_fine_lettura`) values 
('amarrone',22,'2023-03-15');
insert  into `ha_letto`(`username`,`id_libro`,`data_fine_lettura`) values 
('amarrone',24,'2023-03-25');
insert  into `ha_letto`(`username`,`id_libro`,`data_fine_lettura`) values 
('earancio',15,'2023-04-25');
insert  into `ha_letto`(`username`,`id_libro`,`data_fine_lettura`) values 
('earancio',27,'2023-04-20');
insert  into `ha_letto`(`username`,`id_libro`,`data_fine_lettura`) values 
('earancio',29,'2023-04-30');
insert  into `ha_letto`(`username`,`id_libro`,`data_fine_lettura`) values 
('gblu',20,'2023-05-30');
insert  into `ha_letto`(`username`,`id_libro`,`data_fine_lettura`) values 
('gblu',32,'2023-05-25');
insert  into `ha_letto`(`username`,`id_libro`,`data_fine_lettura`) values 
('gblu',34,'2023-05-05');
insert  into `ha_letto`(`username`,`id_libro`,`data_fine_lettura`) values 
('lrosa',25,'2023-06-05');
insert  into `ha_letto`(`username`,`id_libro`,`data_fine_lettura`) values 
('lrosa',37,'2023-06-30');
insert  into `ha_letto`(`username`,`id_libro`,`data_fine_lettura`) values 
('lrosa',39,'2023-06-10');
insert  into `ha_letto`(`username`,`id_libro`,`data_fine_lettura`) values 
('lverdi',30,'2023-07-10');
insert  into `ha_letto`(`username`,`id_libro`,`data_fine_lettura`) values 
('lverdi',42,'2023-07-05');
insert  into `ha_letto`(`username`,`id_libro`,`data_fine_lettura`) values 
('lverdi',43,'2023-07-15');
insert  into `ha_letto`(`username`,`id_libro`,`data_fine_lettura`) values 
('mgialli',4,'2023-08-20');
insert  into `ha_letto`(`username`,`id_libro`,`data_fine_lettura`) values 
('mgialli',35,'2023-08-15');
insert  into `ha_letto`(`username`,`id_libro`,`data_fine_lettura`) values 
('mgialli',44,'2023-08-10');
insert  into `ha_letto`(`username`,`id_libro`,`data_fine_lettura`) values 
('mrossi',3,'2023-09-15');
insert  into `ha_letto`(`username`,`id_libro`,`data_fine_lettura`) values 
('mrossi',9,'2023-09-25');
insert  into `ha_letto`(`username`,`id_libro`,`data_fine_lettura`) values 
('mrossi',40,'2023-09-20');
insert  into `ha_letto`(`username`,`id_libro`,`data_fine_lettura`) values 
('pviola',2,'2023-10-25');
insert  into `ha_letto`(`username`,`id_libro`,`data_fine_lettura`) values 
('pviola',8,'2023-10-20');
insert  into `ha_letto`(`username`,`id_libro`,`data_fine_lettura`) values 
('pviola',16,'2023-10-30');
insert  into `ha_letto`(`username`,`id_libro`,`data_fine_lettura`) values 
('sneri',7,'2023-11-30');
insert  into `ha_letto`(`username`,`id_libro`,`data_fine_lettura`) values 
('sneri',13,'2023-11-25');
insert  into `ha_letto`(`username`,`id_libro`,`data_fine_lettura`) values 
('sneri',21,'2023-11-05');

/*Table structure for table `libri` */

DROP TABLE IF EXISTS `libri`;

CREATE TABLE `libri` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `titolo` varchar(100) NOT NULL,
  `genere` varchar(100) NOT NULL,
  `lingua` varchar(25) NOT NULL,
  `trama` varchar(1000) NOT NULL,
  `autore` varchar(100) NOT NULL,
  `n_capitoli` int(10) unsigned NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=81 DEFAULT CHARSET=latin1;

/*Data for the table `libri` */

insert  into `libri`(`id`,`titolo`,`genere`,`lingua`,`trama`,`autore`,`n_capitoli`) values 
(1,'Il Signore degli Anelli','Fantasy','Italiano','Frodo Baggins parte in un epico viaggio per distruggere l\'Anello Unico e sconfiggere il Signore Oscuro Sauron.','J.R.R. Tolkien',100);
insert  into `libri`(`id`,`titolo`,`genere`,`lingua`,`trama`,`autore`,`n_capitoli`) values 
(2,'Cronache del ghiaccio e del fuoco','Fantasy','Italiano','In un mondo fantasy, nobili famiglie si contendono il Trono di Spade in una lotta per il potere, mentre creature misteriose minacciano il regno.','George R.R. Martin',80);
insert  into `libri`(`id`,`titolo`,`genere`,`lingua`,`trama`,`autore`,`n_capitoli`) values 
(3,'1984','Distopico','Inglese','Nel futuro distopico di Oceania, il governo totalitario controlla ogni aspetto della vita, manipolando la verità attraverso Big Brother.','George Orwell',40);
insert  into `libri`(`id`,`titolo`,`genere`,`lingua`,`trama`,`autore`,`n_capitoli`) values 
(4,'Il Piccolo Principe','Favola','Francese','La storia di un piccolo principe proveniente da un asteroide lontano, che esplora mondi e impara preziose lezioni di vita.','Antoine de Saint-Exupéry',15);
insert  into `libri`(`id`,`titolo`,`genere`,`lingua`,`trama`,`autore`,`n_capitoli`) values 
(5,'Orgoglio e Pregiudizio','Romanzo','Inglese','La complessa storia d\'amore tra Elizabeth Bennet e Mr. Darcy nella società aristocratica dell\'Inghilterra del XIX secolo.','Jane Austen',30);
insert  into `libri`(`id`,`titolo`,`genere`,`lingua`,`trama`,`autore`,`n_capitoli`) values 
(6,'Harry Potter e la Pietra Filosofale','Fantasy','Italiano','Le avventure di un giovane mago, Harry Potter, che scopre il suo retaggio magico e affronta il malvagio stregone Lord Voldemort.','J.K. Rowling',50);
insert  into `libri`(`id`,`titolo`,`genere`,`lingua`,`trama`,`autore`,`n_capitoli`) values 
(7,'Il nome della rosa','Mistero','Italiano','Un giallo storico ambientato in un monastero nel XIV secolo, dove un frate e il suo allievo indagano su una serie di omicidi misteriosi.','Umberto Eco',40);
insert  into `libri`(`id`,`titolo`,`genere`,`lingua`,`trama`,`autore`,`n_capitoli`) values 
(8,'Cime tempestose','Romanzo','Inglese','La storia tormentata tra Heathcliff e Catherine, intrecciata con vendette, amore non corrisposto e una tenace ricerca di felicità nelle brughiere dell\'Inghilterra.','Emily Brontë',35);
insert  into `libri`(`id`,`titolo`,`genere`,`lingua`,`trama`,`autore`,`n_capitoli`) values 
(9,'Il vecchio e il mare','Romanzo','Inglese','La struggente storia di Santiago, un anziano pescatore cubano, e la sua epica lotta con un enorme pesce marlino nel Mar dei Caraibi.','Ernest Hemingway',20);
insert  into `libri`(`id`,`titolo`,`genere`,`lingua`,`trama`,`autore`,`n_capitoli`) values 
(10,'Dune','Sci-Fi','Inglese','La storia epica di Paul Atreides che diventa il leader del popolo deserto in un futuro lontano.','Frank Herbert',60);
insert  into `libri`(`id`,`titolo`,`genere`,`lingua`,`trama`,`autore`,`n_capitoli`) values 
(11,'Il Gattopardo','Storico','Italiano','La storia del Principe di Salina durante il Risorgimento italiano, affrontando il cambiamento sociale e politico.','Giuseppe Tomasi di Lampedusa',25);
insert  into `libri`(`id`,`titolo`,`genere`,`lingua`,`trama`,`autore`,`n_capitoli`) values 
(12,'Guerra e pace','Storico','Russo','La storia di cinque famiglie aristocratiche russe durante le guerre napoleoniche, con una trama intricata di amore, guerra e politica.','Lev Tolstoj',150);
insert  into `libri`(`id`,`titolo`,`genere`,`lingua`,`trama`,`autore`,`n_capitoli`) values 
(13,'Il conte di Montecristo','Avventura','Francese','Edmond Dantès, ingiustamente imprigionato, cerca vendetta con un elaborato piano contro coloro che lo hanno tradito.','Alexandre Dumas',70);
insert  into `libri`(`id`,`titolo`,`genere`,`lingua`,`trama`,`autore`,`n_capitoli`) values 
(14,'Moby Dick','Romanzo','Inglese','La caccia ossessiva di Captain Ahab alla balena bianca Moby Dick, con riflessioni profonde sulla follia e la vendetta.','Herman Melville',90);
insert  into `libri`(`id`,`titolo`,`genere`,`lingua`,`trama`,`autore`,`n_capitoli`) values 
(15,'Don Chisciotte','Romanzo','Spagnolo','Le avventure comiche e romantiche di Don Chisciotte e il suo fedele scudiero Sancho Panza, che cercano di diventare eroi cavallereschi.','Miguel de Cervantes',80);
insert  into `libri`(`id`,`titolo`,`genere`,`lingua`,`trama`,`autore`,`n_capitoli`) values 
(16,'Anna Karenina','Romanzo','Russo','La tragedia di Anna Karenina, che infrange le convenzioni sociali in una storia di amore e tragedia nella Russia imperiale.','Lev Tolstoj',80);
insert  into `libri`(`id`,`titolo`,`genere`,`lingua`,`trama`,`autore`,`n_capitoli`) values 
(17,'Orgoglio e pregiudizio e zombie','Horror','Inglese','Una versione modificata del classico di Jane Austen con l\'aggiunta di zombi e orrore.','Seth Grahame-Smith',40);
insert  into `libri`(`id`,`titolo`,`genere`,`lingua`,`trama`,`autore`,`n_capitoli`) values 
(18,'Il giovane Holden','Romanzo','Inglese','La storia di Holden Caulfield, un giovane ribelle e alienato, che cerca di trovare un significato nella vita.','J.D. Salinger',35);
insert  into `libri`(`id`,`titolo`,`genere`,`lingua`,`trama`,`autore`,`n_capitoli`) values 
(19,'Il mondo nuovo','Sci-Fi','Inglese','In un futuro distopico, la società è controllata scientificamente e la tecnologia ha eliminato il dolore, ma a quale costo per la libertà umana?','Aldous Huxley',60);
insert  into `libri`(`id`,`titolo`,`genere`,`lingua`,`trama`,`autore`,`n_capitoli`) values 
(20,'Lo strano caso del dottor Jekyll e Mr. Hyde','Romanzo','Inglese','La storia del dottor Henry Jekyll, che sperimenta con droghe per separare il bene e il male nella sua personalità.','Robert Louis Stevenson',30);
insert  into `libri`(`id`,`titolo`,`genere`,`lingua`,`trama`,`autore`,`n_capitoli`) values 
(21,'Il fu Mattia Pascal','Romanzo','Italiano','La storia di Mattia Pascal, che finge la sua morte per iniziare una nuova vita, solo per affrontare nuove sfide.','Luigi Pirandello',40);
insert  into `libri`(`id`,`titolo`,`genere`,`lingua`,`trama`,`autore`,`n_capitoli`) values 
(22,'Il dottor Zhivago','Storico','Russo','La storia d\'amore di Yuri Zhivago e Lara Antipova durante la Rivoluzione russa e la guerra civile.','Boris Pasternak',65);
insert  into `libri`(`id`,`titolo`,`genere`,`lingua`,`trama`,`autore`,`n_capitoli`) values 
(23,'Piccole donne','Romanzo','Inglese','La vita quotidiana delle quattro sorelle March durante la guerra civile americana.','Louisa May Alcott',50);
insert  into `libri`(`id`,`titolo`,`genere`,`lingua`,`trama`,`autore`,`n_capitoli`) values 
(24,'Lo Hobbit','Fantasy','Italiano','La storia di Bilbo Baggins, un hobbit che parte in un viaggio epico per conquistare un tesoro custodito da un drago.','J.R.R. Tolkien',80);
insert  into `libri`(`id`,`titolo`,`genere`,`lingua`,`trama`,`autore`,`n_capitoli`) values 
(25,'Il lupo della steppa','Romanzo','Tedesco','La storia di Harry Haller, un uomo diviso tra la sua natura umana e l\'anima di un lupo della steppa.','Hermann Hesse',40);
insert  into `libri`(`id`,`titolo`,`genere`,`lingua`,`trama`,`autore`,`n_capitoli`) values 
(26,'Il sole sorge ancora','Romanzo','Inglese','La vita dissoluta dei \"persi\" nella Parigi del dopoguerra, con una profonda riflessione sulla generazione perduta.','Ernest Hemingway',35);
insert  into `libri`(`id`,`titolo`,`genere`,`lingua`,`trama`,`autore`,`n_capitoli`) values 
(27,'La metà di un sole giallo','Storico','Inglese','La storia di diverse persone durante la guerra civile nigeriana negli anni \'60, con focus su Biafra.','Chimamanda Ngozi Adichie',55);
insert  into `libri`(`id`,`titolo`,`genere`,`lingua`,`trama`,`autore`,`n_capitoli`) values 
(28,'Il ritratto di Dorian Gray','Romanzo','Inglese','La storia di Dorian Gray, che scambia la sua anima per l\'immortalità mentre il suo ritratto invecchia e porta le tracce dei suoi peccati.','Oscar Wilde',30);
insert  into `libri`(`id`,`titolo`,`genere`,`lingua`,`trama`,`autore`,`n_capitoli`) values 
(29,'La tregua','Romanzo','Italiano','La storia di un impiegato di sessantacinque anni, intercalata da eventi della Seconda Guerra Mondiale.','Primo Levi',40);
insert  into `libri`(`id`,`titolo`,`genere`,`lingua`,`trama`,`autore`,`n_capitoli`) values 
(30,'L\'isola del tesoro','Avventura','Inglese','Le avventure di Jim Hawkins alla ricerca del tesoro dell\'isola, guidato dalla mappa del pirata Billy Bones.','Robert Louis Stevenson',60);
insert  into `libri`(`id`,`titolo`,`genere`,`lingua`,`trama`,`autore`,`n_capitoli`) values 
(31,'L\'isola misteriosa','Avventura','Francese','Durante la guerra civile americana, cinque prigionieri fuggono in un pallone e atterrano su un\'isola misteriosa.','Jules Verne',50);
insert  into `libri`(`id`,`titolo`,`genere`,`lingua`,`trama`,`autore`,`n_capitoli`) values 
(32,'Il Labirinto del Fauno','Fantasy','Spagnolo','La storia di Ofelia, una giovane incantata che si imbatte in creature magiche mentre affronta la crudezza del mondo reale durante la guerra civile spagnola.','Guillermo del Toro',30);
insert  into `libri`(`id`,`titolo`,`genere`,`lingua`,`trama`,`autore`,`n_capitoli`) values 
(33,'Il Grande Gatsby','Romanzo','Inglese','La narrazione di Nick Carraway sulla vita e l\'ascesa e caduta del misterioso Jay Gatsby nella società degli anni \'20 a Long Island.','F. Scott Fitzgerald',45);
insert  into `libri`(`id`,`titolo`,`genere`,`lingua`,`trama`,`autore`,`n_capitoli`) values 
(34,'Il Cacciatore di Aquiloni','Drama','Inglese','La storia di un\'amicizia tra due ragazzi a Kabul, Afghanistan, durante periodi tumultuosi e il successivo viaggio di redenzione del protagonista Amir.','Khaled Hosseini',40);
insert  into `libri`(`id`,`titolo`,`genere`,`lingua`,`trama`,`autore`,`n_capitoli`) values 
(35,'La Ragazza con il Drago Tattoo','Thriller','Svedese','Il giornalista Mikael Blomkvist e la hacker Lisbeth Salander indagano su un mistero familiare che si rivela pieno di oscuri segreti.','Stieg Larsson',35);
insert  into `libri`(`id`,`titolo`,`genere`,`lingua`,`trama`,`autore`,`n_capitoli`) values 
(36,'Il Corvo','Fantasy','Inglese','Dopo essere stato brutalmente assassinato, Eric Draven ritorna dalla morte per vendicare la sua morte e quella della sua fidanzata.','James O\'Barr',25);
insert  into `libri`(`id`,`titolo`,`genere`,`lingua`,`trama`,`autore`,`n_capitoli`) values 
(37,'Il Processo','Romanzo','Tedesco','La storia di Josef K., un uomo arrestato e processato da un sistema giuridico oscuro e disumano, senza conoscerne il motivo.','Franz Kafka',50);
insert  into `libri`(`id`,`titolo`,`genere`,`lingua`,`trama`,`autore`,`n_capitoli`) values 
(38,'Il Colore della Magia','Fantasy','Inglese','Le avventure di Rincewind, il mago più incompetente del Mondo Disco, e del turista Twoflower mentre esplorano un mondo fantastico e surreale.','Terry Pratchett',35);
insert  into `libri`(`id`,`titolo`,`genere`,`lingua`,`trama`,`autore`,`n_capitoli`) values 
(39,'Cent\'anni di Solitudine','Romanzo','Spagnolo','La saga della famiglia Buendía a Macondo, attraverso generazioni, guerre, amori e una serie di eventi fantastici.','Gabriel García Márquez',100);
insert  into `libri`(`id`,`titolo`,`genere`,`lingua`,`trama`,`autore`,`n_capitoli`) values 
(40,'Cronache di Narnia','Fantasy','Inglese','Le avventure dei fratelli Pevensie mentre esplorano il mondo magico di Narnia, governato dal leone Aslan.','C.S. Lewis',60);
insert  into `libri`(`id`,`titolo`,`genere`,`lingua`,`trama`,`autore`,`n_capitoli`) values 
(41,'La Caduta dei Giganti','Storico','Inglese','Le vicende di diverse famiglie durante la Prima Guerra Mondiale, mostrando come le loro vite sono intrecciate con gli eventi storici.','Ken Follett',90);
insert  into `libri`(`id`,`titolo`,`genere`,`lingua`,`trama`,`autore`,`n_capitoli`) values 
(42,'Il Codice Da Vinci','Thriller','Inglese','Il professore Robert Langdon indaga su un omicidio al Louvre, scoprendo indizi che conducono a una cospirazione che coinvolge segreti antichi e organizzazioni segrete.','Dan Brown',55);
insert  into `libri`(`id`,`titolo`,`genere`,`lingua`,`trama`,`autore`,`n_capitoli`) values 
(43,'La Storia Infinita','Fantasy','Tedesco','Le avventure di Bastian Balthazar Bux, che scopre un libro magico e si immerge nella storia fantastica di Fantàsia, scoprendo il potere della sua propria immaginazione.','Michael Ende',40);
insert  into `libri`(`id`,`titolo`,`genere`,`lingua`,`trama`,`autore`,`n_capitoli`) values 
(44,'Il Rosso e il Nero','Romanzo','Francese','La storia di Julien Sorel, un giovane ambizioso che cerca l\'ascesa sociale attraverso l\'inganno e la manipolazione nell\'ambiente della Francia post-napoleonica.','Stendhal',60);

/*Table structure for table `recensioni` */

DROP TABLE IF EXISTS `recensioni`;

CREATE TABLE `recensioni` (
  `username_autore` varchar(25) NOT NULL,
  `id_libro` int(10) unsigned NOT NULL,
  `commento` varchar(1000) DEFAULT NULL,
  `voto` int(10) unsigned NOT NULL,
  PRIMARY KEY (`username_autore`,`id_libro`),
  KEY `fk_id_libro` (`id_libro`),
  CONSTRAINT `fk_id_libro` FOREIGN KEY (`id_libro`) REFERENCES `libri` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `fk_username_autore` FOREIGN KEY (`username_autore`) REFERENCES `utenti` (`username`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

/*Data for the table `recensioni` */

insert  into `recensioni`(`username_autore`,`id_libro`,`commento`,`voto`) values 
('abianchi',1,'Un capolavoro epico, una storia che mi ha rapito dall\'inizio alla fine. Consigliato a tutti gli amanti del fantasy!',9);
insert  into `recensioni`(`username_autore`,`id_libro`,`commento`,`voto`) values 
('abianchi',8,'Una delusione totale. La trama sembrava promettente, ma è risultata noiosa e prevedibile. Non lo consiglio affatto.',3);
insert  into `recensioni`(`username_autore`,`id_libro`,`commento`,`voto`) values 
('abianchi',12,'Un libro avvincente che affronta temi importanti. Mi ha fatto riflettere molto sulla società.',8);
insert  into `recensioni`(`username_autore`,`id_libro`,`commento`,`voto`) values 
('abianchi',16,'Un libro che ha completamente mancato il bersaglio. La trama era confusa e poco avvincente. Voto basso per la scarsa qualità della storia.',2);
insert  into `recensioni`(`username_autore`,`id_libro`,`commento`,`voto`) values 
('abianchi',42,'Un libro che mi ha deluso profondamente. La trama era prevedibile e i personaggi erano poco interessanti. Voto basso per la mancanza di originalità.',3);
insert  into `recensioni`(`username_autore`,`id_libro`,`commento`,`voto`) values 
('admin',5,'Intrigante e ben scritto, ma alcune parti sono risultate un po\' troppo complesse per il mio gusto.',7);
insert  into `recensioni`(`username_autore`,`id_libro`,`commento`,`voto`) values 
('admin',13,'Ho faticato a finirlo. La storia era confusa e i personaggi poco interessanti. Voto basso per l\'esperienza di lettura deludente.',4);
insert  into `recensioni`(`username_autore`,`id_libro`,`commento`,`voto`) values 
('admin',17,'Una trama complessa e avvincente, ma il finale mi ha lasciato un po\' perplesso. Nel complesso, una buona lettura.',7);
insert  into `recensioni`(`username_autore`,`id_libro`,`commento`,`voto`) values 
('admin',21,'Mi dispiace di avere speso tempo su questo libro. La trama non ha senso e i personaggi sembrano fuori luogo. Voto basso per la delusione.',3);
insert  into `recensioni`(`username_autore`,`id_libro`,`commento`,`voto`) values 
('amarrone',6,'Un romanzo fantasy che ha superato le mie aspettative. La creatività dell\'autore è sorprendente. Voto massimo per l\'originalità della storia.',10);
insert  into `recensioni`(`username_autore`,`id_libro`,`commento`,`voto`) values 
('amarrone',10,'Una lettura coinvolgente, l\'autore è riuscito a creare un mondo unico e affascinante. Voto massimo!',10);
insert  into `recensioni`(`username_autore`,`id_libro`,`commento`,`voto`) values 
('amarrone',18,'Non capisco l\'hype intorno a questo libro. La trama è scontata e i personaggi non sono ben sviluppati. Voto basso per la delusione.',2);
insert  into `recensioni`(`username_autore`,`id_libro`,`commento`,`voto`) values 
('amarrone',22,'Un romanzo che mescola abilmente elementi di fantascienza e avventura. Davvero originale!',9);
insert  into `recensioni`(`username_autore`,`id_libro`,`commento`,`voto`) values 
('amarrone',26,'Un romanzo che sembrava promettente, ma si è rivelato una completa delusione. La trama era prevedibile e senza profondità. Non lo consiglio.',2);
insert  into `recensioni`(`username_autore`,`id_libro`,`commento`,`voto`) values 
('earancio',15,'Un giallo avvincente con colpi di scena inaspettati. Non riuscivo a metterlo giù!',8);
insert  into `recensioni`(`username_autore`,`id_libro`,`commento`,`voto`) values 
('earancio',23,'Mi aspettavo molto di più. La storia era piatta e i colpi di scena erano prevedibili. Non lo consiglio a chi cerca un\'esperienza avvincente.',5);
insert  into `recensioni`(`username_autore`,`id_libro`,`commento`,`voto`) values 
('earancio',27,'Una storia che cattura l\'immaginazione, con personaggi ben sviluppati e una trama avvincente. Voto alto!',9);
insert  into `recensioni`(`username_autore`,`id_libro`,`commento`,`voto`) values 
('earancio',31,'Non capisco come questo libro abbia ottenuto recensioni positive. La storia era piatta e i personaggi erano stereotipati. Voto basso per la mancanza di originalità.',3);
insert  into `recensioni`(`username_autore`,`id_libro`,`commento`,`voto`) values 
('gblu',18,'Un libro che non ha lasciato alcuna impressione su di me. La trama era banale e i personaggi poco interessanti. Voto basso per la mancanza di coinvolgimento.',4);
insert  into `recensioni`(`username_autore`,`id_libro`,`commento`,`voto`) values 
('gblu',20,'Una storia romantica e commovente, mi ha fatto riflettere molto. Voto alto per l\'emozione che trasmette.',9);
insert  into `recensioni`(`username_autore`,`id_libro`,`commento`,`voto`) values 
('gblu',28,'Un romanzo che non ha lasciato alcun impatto su di me. La trama era banale e i personaggi poco memorabili. Voto basso per la mancanza di coinvolgimento.',4);
insert  into `recensioni`(`username_autore`,`id_libro`,`commento`,`voto`) values 
('gblu',32,'Un libro che mi ha tenuto incollato alle pagine dall\'inizio alla fine. Assolutamente consigliato.',10);
insert  into `recensioni`(`username_autore`,`id_libro`,`commento`,`voto`) values 
('gblu',36,'Un libro che non ha lasciato alcuna impressione su di me. La trama era banale e i personaggi poco interessanti. Voto basso per la noia.',4);
insert  into `recensioni`(`username_autore`,`id_libro`,`commento`,`voto`) values 
('lrosa',23,'Mi aspettavo di più. La storia non ha mai decollato e i personaggi erano poco sviluppati. Non lo consiglio.',3);
insert  into `recensioni`(`username_autore`,`id_libro`,`commento`,`voto`) values 
('lrosa',25,'Un classico intramontabile, la trama è affascinante e i personaggi ben sviluppati. Voto positivo!',8);
insert  into `recensioni`(`username_autore`,`id_libro`,`commento`,`voto`) values 
('lrosa',33,'Non ho apprezzato per niente questo libro. La trama era confusa e i personaggi erano stereotipati. Sconsigliato.',2);
insert  into `recensioni`(`username_autore`,`id_libro`,`commento`,`voto`) values 
('lrosa',37,'Una storia romantica con un tocco di mistero, mi ha emozionato molto. Voto positivo!',8);
insert  into `recensioni`(`username_autore`,`id_libro`,`commento`,`voto`) values 
('lrosa',41,'Mi aspettavo molto di più. La storia non ha mai decollato e i personaggi erano poco sviluppati. Non lo consiglio.',3);
insert  into `recensioni`(`username_autore`,`id_libro`,`commento`,`voto`) values 
('lverdi',29,'Una lettura deludente. La trama era confusa e poco avvincente. Non riesco a trovare nulla di positivo da dire su questo libro.',2);
insert  into `recensioni`(`username_autore`,`id_libro`,`commento`,`voto`) values 
('lverdi',30,'Un thriller avvincente con una trama ben costruita. Mi ha tenuto sulle spine fino all\'ultima pagina.',9);
insert  into `recensioni`(`username_autore`,`id_libro`,`commento`,`voto`) values 
('lverdi',38,'Una lettura deludente. La storia non ha mai decollato e i personaggi erano piatti. Non lo consiglio a nessuno.',3);
insert  into `recensioni`(`username_autore`,`id_libro`,`commento`,`voto`) values 
('lverdi',42,'Un thriller psicologico intrigante, pieno di colpi di scena. Non vedo l\'ora di leggere altri libri dell\'autore.',9);
insert  into `recensioni`(`username_autore`,`id_libro`,`commento`,`voto`) values 
('mgialli',3,'Un giallo che ha completamente mancato il bersaglio. La soluzione del mistero era ovvia e poco originale. Voto basso per la mancanza di suspense.',4);
insert  into `recensioni`(`username_autore`,`id_libro`,`commento`,`voto`) values 
('mgialli',35,'Un libro divertente e leggero, perfetto per una lettura rilassante. Il protagonista è davvero simpatico!',7);
insert  into `recensioni`(`username_autore`,`id_libro`,`commento`,`voto`) values 
('mgialli',43,'Mi aspettavo un giallo avvincente, ma sono rimasto deluso. La soluzione del mistero era ovvia e poco originale. Voto basso per la mancanza di suspense.',4);
insert  into `recensioni`(`username_autore`,`id_libro`,`commento`,`voto`) values 
('mgialli',44,'Un giallo avvincente con una trama ben intrecciata. Mi ha tenuto sulle spine fino all\'ultima pagina.',8);
insert  into `recensioni`(`username_autore`,`id_libro`,`commento`,`voto`) values 
('mrossi',1,'Il peggior libro che abbia mai letto. La trama era assurda e i personaggi erano insopportabili. Evitate questo libro a tutti i costi.',1);
insert  into `recensioni`(`username_autore`,`id_libro`,`commento`,`voto`) values 
('mrossi',3,'Una storia coinvolgente con un protagonista affascinante. Non potevo smettere di leggere!',9);
insert  into `recensioni`(`username_autore`,`id_libro`,`commento`,`voto`) values 
('mrossi',8,'Il peggior libro che abbia mai letto. La trama era assurda e i personaggi erano irritanti. Sconsigliato a chiunque.',1);
insert  into `recensioni`(`username_autore`,`id_libro`,`commento`,`voto`) values 
('mrossi',39,'Il peggior libro che abbia mai letto. La trama era assurda e i personaggi erano irritanti. Sconsigliato a chiunque.',1);
insert  into `recensioni`(`username_autore`,`id_libro`,`commento`,`voto`) values 
('mrossi',40,'Una lettura coinvolgente che mi ha tenuto sveglio fino a tarda notte. Consigliato agli amanti del mistero.',8);
insert  into `recensioni`(`username_autore`,`id_libro`,`commento`,`voto`) values 
('pviola',2,'Un romanzo appassionante, ho apprezzato particolarmente la caratterizzazione dei personaggi. Voto molto alto!',9);
insert  into `recensioni`(`username_autore`,`id_libro`,`commento`,`voto`) values 
('pviola',6,'Una storia che non ha suscitato alcuna emozione in me. I personaggi erano insipidi e la trama era noiosa. Voto basso per la mancanza di coinvolgimento.',3);
insert  into `recensioni`(`username_autore`,`id_libro`,`commento`,`voto`) values 
('pviola',8,'Un libro che mi ha toccato profondamente. La storia è potente e i personaggi sono ben sviluppati.',10);
insert  into `recensioni`(`username_autore`,`id_libro`,`commento`,`voto`) values 
('pviola',13,'Una storia che non ha suscitato alcuna emozione in me. I personaggi erano insipidi e la trama era noiosa. Voto basso per la mancanza di coinvolgimento.',3);
insert  into `recensioni`(`username_autore`,`id_libro`,`commento`,`voto`) values 
('pviola',44,'Una storia che non ha suscitato alcuna emozione in me. I personaggi erano insipidi e la trama era noiosa. Voto basso per la mancanza di coinvolgimento.',3);
insert  into `recensioni`(`username_autore`,`id_libro`,`commento`,`voto`) values 
('sneri',1,'Mi aspettavo di più da questo libro. La trama era inconsistente e i personaggi poco convincenti. Voto basso per la delusione.',4);
insert  into `recensioni`(`username_autore`,`id_libro`,`commento`,`voto`) values 
('sneri',7,'Una storia toccante, mi ha commosso profondamente. Consigliato per chi ama le storie emozionanti.',10);
insert  into `recensioni`(`username_autore`,`id_libro`,`commento`,`voto`) values 
('sneri',11,'Mi aspettavo di più da questo libro. La trama era inconsistente e i personaggi poco convincenti. Voto basso per la delusione.',4);
insert  into `recensioni`(`username_autore`,`id_libro`,`commento`,`voto`) values 
('sneri',13,'Una lettura leggera e divertente, perfetta per rilassarsi. Consigliato per chi ama le commedie romantiche.',7);
insert  into `recensioni`(`username_autore`,`id_libro`,`commento`,`voto`) values 
('sneri',18,'Mi aspettavo di più da questo libro. La trama era inconsistente e i personaggi poco convincenti. Voto basso per la delusione.',4);

/*Table structure for table `sta_leggendo` */

DROP TABLE IF EXISTS `sta_leggendo`;

CREATE TABLE `sta_leggendo` (
  `username` varchar(25) NOT NULL,
  `id_libro` int(10) unsigned NOT NULL,
  `n_capitoli_letti` int(10) unsigned NOT NULL,
  PRIMARY KEY (`username`,`id_libro`),
  KEY `fk_id_libro_3` (`id_libro`),
  CONSTRAINT `fk_id_libro_3` FOREIGN KEY (`id_libro`) REFERENCES `libri` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `fk_username_2` FOREIGN KEY (`username`) REFERENCES `utenti` (`username`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

/*Data for the table `sta_leggendo` */

insert  into `sta_leggendo`(`username`,`id_libro`,`n_capitoli_letti`) values 
('abianchi',14,13);
insert  into `sta_leggendo`(`username`,`id_libro`,`n_capitoli_letti`) values 
('admin',19,18);
insert  into `sta_leggendo`(`username`,`id_libro`,`n_capitoli_letti`) values 
('admin',31,13);
insert  into `sta_leggendo`(`username`,`id_libro`,`n_capitoli_letti`) values 
('amarrone',24,2);
insert  into `sta_leggendo`(`username`,`id_libro`,`n_capitoli_letti`) values 
('amarrone',36,10);
insert  into `sta_leggendo`(`username`,`id_libro`,`n_capitoli_letti`) values 
('earancio',29,1);
insert  into `sta_leggendo`(`username`,`id_libro`,`n_capitoli_letti`) values 
('earancio',41,9);
insert  into `sta_leggendo`(`username`,`id_libro`,`n_capitoli_letti`) values 
('gblu',34,0);
insert  into `sta_leggendo`(`username`,`id_libro`,`n_capitoli_letti`) values 
('lrosa',2,3);
insert  into `sta_leggendo`(`username`,`id_libro`,`n_capitoli_letti`) values 
('lrosa',39,0);
insert  into `sta_leggendo`(`username`,`id_libro`,`n_capitoli_letti`) values 
('lverdi',7,5);
insert  into `sta_leggendo`(`username`,`id_libro`,`n_capitoli_letti`) values 
('lverdi',43,7);
insert  into `sta_leggendo`(`username`,`id_libro`,`n_capitoli_letti`) values 
('mgialli',4,5);
insert  into `sta_leggendo`(`username`,`id_libro`,`n_capitoli_letti`) values 
('mgialli',12,4);
insert  into `sta_leggendo`(`username`,`id_libro`,`n_capitoli_letti`) values 
('mrossi',9,3);
insert  into `sta_leggendo`(`username`,`id_libro`,`n_capitoli_letti`) values 
('mrossi',17,11);
insert  into `sta_leggendo`(`username`,`id_libro`,`n_capitoli_letti`) values 
('pviola',22,3);
insert  into `sta_leggendo`(`username`,`id_libro`,`n_capitoli_letti`) values 
('sneri',27,8);

/*Table structure for table `utenti` */

DROP TABLE IF EXISTS `utenti`;

CREATE TABLE `utenti` (
  `nome` varchar(25) NOT NULL,
  `cognome` varchar(25) NOT NULL,
  `username` varchar(25) NOT NULL,
  `email` varchar(60) NOT NULL,
  `password` varchar(100) NOT NULL,
  `admin` bit(1) NOT NULL,
  PRIMARY KEY (`username`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

/*Data for the table `utenti` */

insert  into `utenti`(`nome`,`cognome`,`username`,`email`,`password`,`admin`) values 
('Anna','Bianchi','abianchi','anna.bianchi@email.com','myp@ssword','\0');
insert  into `utenti`(`nome`,`cognome`,`username`,`email`,`password`,`admin`) values 
('Fabio','Mene','admin','admin@email.com','admin','');
insert  into `utenti`(`nome`,`cognome`,`username`,`email`,`password`,`admin`) values 
('Alessio','Marrone','amarrone','alessio.marrone@email.com','12345678','\0');
insert  into `utenti`(`nome`,`cognome`,`username`,`email`,`password`,`admin`) values 
('Elena','Arancio','earancio','elena.arancio@email.com','pa$$word','\0');
insert  into `utenti`(`nome`,`cognome`,`username`,`email`,`password`,`admin`) values 
('Giovanni','Blu','gblu','giovanni.blu@email.com','pwd456','\0');
insert  into `utenti`(`nome`,`cognome`,`username`,`email`,`password`,`admin`) values 
('Laura','Rosa','lrosa','laura.rosa@email.com','letmein','\0');
insert  into `utenti`(`nome`,`cognome`,`username`,`email`,`password`,`admin`) values 
('Luigi','Verdi','lverdi','luigi.verdi@email.com','securepass','\0');
insert  into `utenti`(`nome`,`cognome`,`username`,`email`,`password`,`admin`) values 
('Marco','Gialli','mgialli','marco.gialli@email.com','p@ssw0rd','\0');
insert  into `utenti`(`nome`,`cognome`,`username`,`email`,`password`,`admin`) values 
('Mario','Rossi','mrossi','mario.rossi@email.com','password123','\0');
insert  into `utenti`(`nome`,`cognome`,`username`,`email`,`password`,`admin`) values 
('Paolo','Viola','pviola','paolo.viola@email.com','secret123','\0');
insert  into `utenti`(`nome`,`cognome`,`username`,`email`,`password`,`admin`) values 
('Sara','Neri','sneri','sara.neri@email.com','strongpassword','\0');

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;
