-- phpMyAdmin SQL Dump
-- version 4.5.1
-- http://www.phpmyadmin.net
--
-- Host: 127.0.0.1
-- Generation Time: 09. Aug, 2016 16:01 p.m.
-- Server-versjon: 10.1.9-MariaDB
-- PHP Version: 5.6.15

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `prosjekt6065`
--
CREATE DATABASE IF NOT EXISTS `prosjekt6065` DEFAULT CHARACTER SET latin1 COLLATE latin1_swedish_ci;
USE `prosjekt6065`;

DELIMITER $$
--
-- Prosedyrer
--
DROP PROCEDURE IF EXISTS `getAlleKategorier`$$
CREATE DEFINER=`root`@`localhost` PROCEDURE `getAlleKategorier` ()  NO SQL
SELECT kid, kNavn, kBeskrivelse
FROM kategori$$

DROP PROCEDURE IF EXISTS `getBruker`$$
CREATE DEFINER=`root`@`localhost` PROCEDURE `getBruker` (IN `navnInn` VARCHAR(255), IN `passInn` VARCHAR(255))  NO SQL
SELECT bid, bNavn, ulevel
FROM brukere
WHERE bNavn = navnInn AND passord = passInn$$

DROP PROCEDURE IF EXISTS `getIndexKategorier`$$
CREATE DEFINER=`root`@`localhost` PROCEDURE `getIndexKategorier` ()  NO SQL
    COMMENT 'Returner en liste med kategorier for index.php'
SELECT kid, kNavn, kBeskrivelse
FROM kategori$$

DROP PROCEDURE IF EXISTS `getKategoriByID`$$
CREATE DEFINER=`root`@`localhost` PROCEDURE `getKategoriByID` (IN `kidInn` INT(8))  NO SQL
SELECT kid, kNavn, kBeskrivelse
FROM kategori
WHERE kid = kidInn$$

DROP PROCEDURE IF EXISTS `getNyesteThread`$$
CREATE DEFINER=`root`@`localhost` PROCEDURE `getNyesteThread` (IN `kidInn` INT)  NO SQL
SELECT tid, tDato, kid, tTittel
            FROM thread AS t
            WHERE tDato IN (
                SELECT MAX(tDato)
                    FROM thread WHERE kid = kidInn
                    GROUP BY kid
                )
            ORDER BY kid ASC, tDato DESC$$

DROP PROCEDURE IF EXISTS `getPostByID`$$
CREATE DEFINER=`root`@`localhost` PROCEDURE `getPostByID` (IN `pidInn` INT(8))  NO SQL
    COMMENT 'Returnerer posten etter PID'
SELECT pTekst, pid, tid
FROM post
WHERE pid = pidInn$$

DROP PROCEDURE IF EXISTS `getPostsInThread`$$
CREATE DEFINER=`root`@`localhost` PROCEDURE `getPostsInThread` (IN `tidInn` INT(8))  NO SQL
SELECT 
                    p.tid,
                    p.pTekst,
                    p.pDato,
                    p.bid,
                    p.pid,
                    b.bid,
                    b.bnavn
                FROM
                    post AS p
                LEFT JOIN
                    brukere AS b
                ON
                    p.bid = b.bid
                WHERE
                    p.tid = tidInn$$

DROP PROCEDURE IF EXISTS `getThreadByID`$$
CREATE DEFINER=`root`@`localhost` PROCEDURE `getThreadByID` (IN `tidInn` INT)  NO SQL
SELECT tid, tTittel
FROM thread
WHERE tid = tidInn$$

DROP PROCEDURE IF EXISTS `getThreadsInKategori`$$
CREATE DEFINER=`root`@`localhost` PROCEDURE `getThreadsInKategori` (IN `kidInn` INT(8))  NO SQL
SELECT  
                    t.tid,
                    t.tTittel,
                    t.tDato,
                    t.kid,
                    t.bid,
                    b.bnavn
                FROM
                    thread AS t 
                INNER JOIN
                    brukere AS b
                on
                    t.bid = b.bid 
                WHERE
                    t.kid = kidInn$$

DROP PROCEDURE IF EXISTS `insertBruker`$$
CREATE DEFINER=`root`@`localhost` PROCEDURE `insertBruker` (IN `bnavn` VARCHAR(30), IN `passord` VARCHAR(255), IN `email` VARCHAR(255))  NO SQL
INSERT INTO brukere(bnavn, passord, email, bDato, ulevel)
  VALUES(bnavn, passord, email, NOW(), 0)$$

DROP PROCEDURE IF EXISTS `insertKategori`$$
CREATE DEFINER=`root`@`localhost` PROCEDURE `insertKategori` (IN `navnInn` VARCHAR(255), IN `descInn` TEXT)  NO SQL
BEGIN
INSERT INTO kategori(kNavn, kBeskrivelse)
VALUES(navnInn, descInn);
END$$

DROP PROCEDURE IF EXISTS `insertReply`$$
CREATE DEFINER=`root`@`localhost` PROCEDURE `insertReply` (IN `tekstInn` TEXT, IN `tidInn` INT(8), IN `bidInn` INT(8))  NO SQL
INSERT INTO post(pTekst, pDato, tid, bid)
VALUES(tekstInn, NOW(), tidInn, bidInn)$$

DROP PROCEDURE IF EXISTS `insertThread`$$
CREATE DEFINER=`root`@`localhost` PROCEDURE `insertThread` (IN `tittelInn` TEXT, IN `kidInn` INT(8), IN `bidInn` INT(8))  NO SQL
INSERT INTO thread(tTittel, tDato, kid, bid)
VALUES (tittelInn, NOW(), kidInn, bidInn)$$

DROP PROCEDURE IF EXISTS `redigerThread`$$
CREATE DEFINER=`root`@`localhost` PROCEDURE `redigerThread` (IN `tidRediger` INT(8), IN `nyTittel` VARCHAR(255), IN `nyKid` TEXT)  NO SQL
UPDATE thread
SET tNavn = nyTitel, kid = nyKid, tDato = NOW()
WHERE tid = tidRediger$$

DROP PROCEDURE IF EXISTS `slettKategori`$$
CREATE DEFINER=`root`@`localhost` PROCEDURE `slettKategori` (IN `kidInn` INT(8))  NO SQL
DELETE FROM kategori
WHERE kid = kidInn$$

DROP PROCEDURE IF EXISTS `slettPost`$$
CREATE DEFINER=`root`@`localhost` PROCEDURE `slettPost` (IN `pidInn` INT)  NO SQL
DELETE FROM post
WHERE pid = pidInn$$

DROP PROCEDURE IF EXISTS `slettThread`$$
CREATE DEFINER=`root`@`localhost` PROCEDURE `slettThread` (IN `tidInn` INT)  NO SQL
DELETE FROM thread
WHERE tid = tidInn$$

DROP PROCEDURE IF EXISTS `updatePost`$$
CREATE DEFINER=`root`@`localhost` PROCEDURE `updatePost` (IN `tekstInn` TEXT, IN `pidInn` INT(8))  NO SQL
UPDATE post
SET pTekst = tekstInn, pDato = NOW()
WHERE pid = pidInn$$

DELIMITER ;

-- --------------------------------------------------------

--
-- Tabellstruktur for tabell `brukere`
--

DROP TABLE IF EXISTS `brukere`;
CREATE TABLE IF NOT EXISTS `brukere` (
  `bid` int(8) NOT NULL AUTO_INCREMENT,
  `bnavn` varchar(30) NOT NULL,
  `passord` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `bDato` datetime NOT NULL,
  `ulevel` int(8) NOT NULL,
  PRIMARY KEY (`bid`),
  UNIQUE KEY `bnavn` (`bnavn`),
  UNIQUE KEY `email` (`email`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=latin1;

--
-- Dataark for tabell `brukere`
--

INSERT INTO `brukere` (`bid`, `bnavn`, `passord`, `email`, `bDato`, `ulevel`) VALUES
(1, 'admin', 'd033e22ae348aeb5660fc2140aec35850c4da997', 'admin@admin.no', '2016-08-06 12:09:14', 1),
(2, 'Per', 'ec40c62ddf2d2d7d353e6ad7d1e3529bb6867e0f', 'per@per.no', '2016-08-06 12:09:32', 0),
(3, 'Anne', 'ec40c62ddf2d2d7d353e6ad7d1e3529bb6867e0f', 'anne@anne.no', '2016-08-06 12:09:55', 0);

-- --------------------------------------------------------

--
-- Tabellstruktur for tabell `kategori`
--

DROP TABLE IF EXISTS `kategori`;
CREATE TABLE IF NOT EXISTS `kategori` (
  `kid` int(8) NOT NULL AUTO_INCREMENT,
  `kNavn` varchar(255) NOT NULL,
  `kBeskrivelse` varchar(255) NOT NULL,
  PRIMARY KEY (`kid`),
  UNIQUE KEY `kNavn` (`kNavn`)
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=latin1;

--
-- Dataark for tabell `kategori`
--

INSERT INTO `kategori` (`kid`, `kNavn`, `kBeskrivelse`) VALUES
(1, 'Film', 'Et forum for diskusjon, nyheter og annen informasjon rundt film.'),
(2, 'Programmering', 'Her kan du vise fram din nyeste kode og få hjelp av andre til å bli bedre. :-)'),
(3, 'Maling', 'Et diskusjonsforum for malere. Alt relatert til maling går her\r\n\r\nAndre ressursers: \r\n  <a href="https://reddit.com/r/painting">Reddit Maling</a>'),
(4, 'testkategori', 'Kategori for å teste'),
(5, 'Kategori nr 5', 'Forumet for kategori nummer 5');

-- --------------------------------------------------------

--
-- Tabellstruktur for tabell `post`
--

DROP TABLE IF EXISTS `post`;
CREATE TABLE IF NOT EXISTS `post` (
  `pid` int(8) NOT NULL AUTO_INCREMENT,
  `pTekst` text NOT NULL,
  `pDato` datetime NOT NULL,
  `tid` int(8) NOT NULL,
  `bid` int(8) NOT NULL,
  PRIMARY KEY (`pid`),
  KEY `tid` (`tid`),
  KEY `bid` (`bid`)
) ENGINE=InnoDB AUTO_INCREMENT=23 DEFAULT CHARSET=latin1;

--
-- Dataark for tabell `post`
--

INSERT INTO `post` (`pid`, `pTekst`, `pDato`, `tid`, `bid`) VALUES
(12, 'Bye\r\n', '2016-08-07 00:15:26', 5, 1),
(13, 'Jeg mente at jeg likte programmering. Kan en admin slette tråden?1', '2016-08-07 00:38:34', 7, 2),
(14, 'Tips for å begynne? Sier gjerne takk', '2016-08-07 00:39:31', 8, 2),
(15, 'Dette er en post', '2016-08-07 13:09:05', 9, 1),
(16, 'Maling :-)', '2016-08-07 13:09:22', 10, 1),
(17, 'Tråder kan ikke flyttes, du må skrive en ny.', '2016-08-07 13:09:35', 7, 1),
(18, 'post1', '2016-08-07 13:16:16', 11, 1),
(19, 'post2', '2016-08-07 13:16:20', 11, 1),
(20, 'post1', '2016-08-07 13:16:34', 12, 1),
(21, 'asd', '2016-08-07 13:16:49', 13, 1),
(22, 'trpd\r\n', '2016-08-07 13:17:01', 14, 1);

--
-- Triggere `post`
--
DROP TRIGGER IF EXISTS `editPostLogg`;
DELIMITER $$
CREATE TRIGGER `editPostLogg` AFTER UPDATE ON `post` FOR EACH ROW INSERT INTO postLogg(pid, tekstNy, tekstOld)
VALUES(NEW.pid, NEW.pTekst, OLD.pTekst)
$$
DELIMITER ;

-- --------------------------------------------------------

--
-- Tabellstruktur for tabell `postlogg`
--

DROP TABLE IF EXISTS `postlogg`;
CREATE TABLE IF NOT EXISTS `postlogg` (
  `pid` int(11) NOT NULL,
  `tekstNy` text NOT NULL,
  `tekstOld` text NOT NULL,
  PRIMARY KEY (`pid`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Tabellstruktur for tabell `thread`
--

DROP TABLE IF EXISTS `thread`;
CREATE TABLE IF NOT EXISTS `thread` (
  `tid` int(8) NOT NULL AUTO_INCREMENT,
  `tTittel` varchar(255) NOT NULL,
  `tDato` datetime NOT NULL,
  `kid` int(8) NOT NULL,
  `bid` int(8) NOT NULL,
  PRIMARY KEY (`tid`),
  KEY `kid` (`kid`),
  KEY `bid` (`bid`)
) ENGINE=InnoDB AUTO_INCREMENT=15 DEFAULT CHARSET=latin1;

--
-- Dataark for tabell `thread`
--

INSERT INTO `thread` (`tid`, `tTittel`, `tDato`, `kid`, `bid`) VALUES
(5, 'Film er teit', '2016-08-06 12:18:58', 1, 2),
(7, 'Filmer er kult', '2016-08-07 00:16:59', 2, 2),
(8, 'Maling er noe jeg vil prøve', '2016-08-07 00:39:21', 3, 2),
(9, 'En tråd om film', '2016-08-07 13:09:05', 1, 1),
(10, 'Lyst til å male? Klikk her', '2016-08-07 13:09:22', 3, 1),
(11, 'tråd 1 admin', '2016-08-07 13:16:16', 4, 1),
(12, 'tråd nr 1 admin', '2016-08-07 13:16:34', 5, 1),
(13, 'tråd 1 admin', '2016-08-07 13:16:49', 4, 1),
(14, 'tråd nr 2 admin', '2016-08-07 13:17:01', 1, 1);

--
-- Begrensninger for dumpede tabeller
--

--
-- Begrensninger for tabell `post`
--
ALTER TABLE `post`
  ADD CONSTRAINT `post_ibfk_1` FOREIGN KEY (`tid`) REFERENCES `thread` (`tid`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `post_ibfk_2` FOREIGN KEY (`bid`) REFERENCES `brukere` (`bid`) ON UPDATE CASCADE;

--
-- Begrensninger for tabell `postlogg`
--
ALTER TABLE `postlogg`
  ADD CONSTRAINT `postlogg_ibfk_1` FOREIGN KEY (`pid`) REFERENCES `post` (`pid`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Begrensninger for tabell `thread`
--
ALTER TABLE `thread`
  ADD CONSTRAINT `thread_ibfk_1` FOREIGN KEY (`kid`) REFERENCES `kategori` (`kid`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `thread_ibfk_2` FOREIGN KEY (`bid`) REFERENCES `brukere` (`bid`) ON UPDATE CASCADE;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
