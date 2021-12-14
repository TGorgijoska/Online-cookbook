-- phpMyAdmin SQL Dump
-- version 4.7.5
-- https://www.phpmyadmin.net/
--
-- Host: localhost
-- Generation Time: Jun 16, 2021 at 02:04 PM
-- Server version: 5.6.34
-- PHP Version: 5.6.32

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `onlinerecipes`
--

DELIMITER $$
--
-- Procedures
--
CREATE DEFINER=`root`@`localhost` PROCEDURE `presmetaj_nutri` (IN `id` INT(11))  BEGIN
    INSERT INTO recept_nutri(
        `recept_id`,
        ``.`jaglehidrati`,
        ``.`proteini`,
        ``.`masti`
    )
VALUES(
    id,
    (
    SELECT
        SUM((sostojki.jaglehidrati*(recept_sostojki.kolicina/100))/ recept.brojPorcii)
    FROM
        (
            (
                sostojki
            INNER JOIN recept_sostojki ON sostojki.id = recept_sostojki.sostojka_id
            )
        INNER JOIN recept ON recept.id = recept_sostojki.recept_id
        )
    WHERE
        recept.id = id
),
(
    SELECT
        SUM((sostojki.proteini*(recept_sostojki.kolicina/100)) / recept.brojPorcii)
    FROM
        (
            (
                sostojki
            INNER JOIN recept_sostojki ON sostojki.id = recept_sostojki.sostojka_id
            )
        INNER JOIN recept ON recept.id = recept_sostojki.recept_id
        )
    WHERE
        recept.id = id
),
(
    SELECT
        SUM((sostojki.masti*(recept_sostojki.kolicina/100)) / recept.brojPorcii)
    FROM
        (
            (
                sostojki
            INNER JOIN recept_sostojki ON sostojki.id = recept_sostojki.sostojka_id
            )
        INNER JOIN recept ON recept.id = recept_sostojki.recept_id
        )
    WHERE
        recept.id = id
)
);
end$$

DELIMITER ;

-- --------------------------------------------------------

--
-- Table structure for table `korisnik`
--

CREATE TABLE `korisnik` (
  `id` int(11) NOT NULL,
  `email` varchar(50) NOT NULL,
  `korisnickoIme` varchar(50) NOT NULL,
  `lozinka` varchar(255) NOT NULL,
  `date_created` date NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `korisnik`
--

INSERT INTO `korisnik` (`id`, `email`, `korisnickoIme`, `lozinka`, `date_created`) VALUES
(2, 'admin1@admin.com', 'Админ', '21232f297a57a5a743894a0e4a801fc3', '2021-05-29'),
(3, 'admin@gmail.com', 'татјана', 'e00cf25ad42683b3df678c61f42c6bda', '2021-06-05');

-- --------------------------------------------------------

--
-- Table structure for table `recept`
--

CREATE TABLE `recept` (
  `id` int(11) NOT NULL,
  `korisnik_id` int(11) NOT NULL,
  `ime` varchar(50) NOT NULL,
  `kategorija` varchar(50) NOT NULL,
  `vremeZaPodgotovka` int(11) DEFAULT NULL,
  `brojPorcii` int(11) NOT NULL,
  `slika` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `recept`
--

INSERT INTO `recept` (`id`, `korisnik_id`, `ime`, `kategorija`, `vremeZaPodgotovka`, `brojPorcii`, `slika`) VALUES
(1, 2, 'Овошна салата', 'десерт', 10, 1, 'fruit_salad.jpg'),
(3, 3, 'салата', 'салати', 10, 1, 'salad.jpg'),
(4, 3, 'ориз', 'главно јадење', 60, 2, 'rice.jpg'),
(36, 3, 'Макарони со наут и сос', 'главно јадење', 30, 2, 'mac.jpg'),
(37, 3, 'енергични барови', 'десерт', 20, 10, 'bars.jpg'),
(38, 3, 'Пица', 'главно јадење', 60, 3, 'pizza.jpg');

-- --------------------------------------------------------

--
-- Table structure for table `recept_cekori`
--

CREATE TABLE `recept_cekori` (
  `id` int(11) NOT NULL,
  `broj` int(11) NOT NULL,
  `opis` text NOT NULL,
  `recept_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `recept_cekori`
--

INSERT INTO `recept_cekori` (`id`, `broj`, `opis`, `recept_id`) VALUES
(53, 1, 'исечи го овошјето', 1),
(54, 2, 'стави во чинија и додади шлаг', 1),
(55, 1, 'изми го оризот', 4),
(56, 2, 'запржи кромид', 4),
(57, 3, 'додади го оризот', 4),
(58, 4, 'печи на 180 степени ', 4);

-- --------------------------------------------------------

--
-- Table structure for table `recept_nutri`
--

CREATE TABLE `recept_nutri` (
  `recept_id` int(11) NOT NULL,
  `jaglehidrati` decimal(5,2) NOT NULL,
  `masti` decimal(5,2) NOT NULL,
  `proteini` decimal(5,2) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `recept_nutri`
--

INSERT INTO `recept_nutri` (`recept_id`, `jaglehidrati`, `masti`, `proteini`) VALUES
(1, '50.60', '0.54', '1.46'),
(3, '11.13', '0.44', '2.37'),
(4, '13.90', '0.15', '1.35');

-- --------------------------------------------------------

--
-- Stand-in structure for view `recept_search`
-- (See below for the actual view)
--
CREATE TABLE `recept_search` (
`id` int(11)
,`korisnik_id` int(11)
,`ime` varchar(50)
,`kategorija` varchar(50)
,`vremeZaPodgotovka` int(11)
,`brojPorcii` int(11)
,`slika` varchar(255)
,`tag_ime` varchar(50)
);

-- --------------------------------------------------------

--
-- Table structure for table `recept_sostojki`
--

CREATE TABLE `recept_sostojki` (
  `recept_id` int(11) NOT NULL,
  `sostojka_id` int(11) NOT NULL,
  `kolicina` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `recept_sostojki`
--

INSERT INTO `recept_sostojki` (`recept_id`, `sostojka_id`, `kolicina`) VALUES
(1, 1, 120),
(1, 2, 100),
(3, 3, 147),
(3, 7, 150),
(4, 5, 100);

-- --------------------------------------------------------

--
-- Table structure for table `recept_tags`
--

CREATE TABLE `recept_tags` (
  `id` int(50) NOT NULL,
  `recept_id` int(50) NOT NULL,
  `ime` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `recept_tags`
--

INSERT INTO `recept_tags` (`id`, `recept_id`, `ime`) VALUES
(25, 1, 'здраво'),
(26, 4, 'посно'),
(27, 3, 'посно');

-- --------------------------------------------------------

--
-- Table structure for table `shoping_lista`
--

CREATE TABLE `shoping_lista` (
  `id` int(11) NOT NULL,
  `korisnik_id` int(11) NOT NULL,
  `sostojka` varchar(50) NOT NULL,
  `kupeno` tinyint(1) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `shoping_lista`
--

INSERT INTO `shoping_lista` (`id`, `korisnik_id`, `sostojka`, `kupeno`) VALUES
(5, 2, 'млеко 1л', 0),
(6, 2, 'јајца 10', 0),
(7, 2, 'брашно 2кг', 0),
(8, 2, 'кашкавал', 1),
(9, 3, 'јогурт', 0);

-- --------------------------------------------------------

--
-- Table structure for table `sostojki`
--

CREATE TABLE `sostojki` (
  `id` int(11) NOT NULL,
  `ime` varchar(50) NOT NULL,
  `jaglehidrati` decimal(4,1) NOT NULL,
  `proteini` decimal(4,1) NOT NULL,
  `masti` decimal(4,1) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `sostojki`
--

INSERT INTO `sostojki` (`id`, `ime`, `jaglehidrati`, `proteini`, `masti`) VALUES
(1, 'банана', '23.0', '0.3', '0.2'),
(2, 'јаболка', '23.0', '1.1', '0.3'),
(3, 'домат', '3.9', '0.9', '0.2'),
(4, 'леб', '47.0', '8.0', '1.0'),
(5, 'ориз', '27.8', '2.7', '0.3'),
(6, 'макарони(зготвени)', '29.0', '5.8', '0.9'),
(7, 'краставица', '3.6', '0.7', '0.1'),
(8, 'млеко', '4.4', '3.1', '3.1');

-- --------------------------------------------------------

--
-- Stand-in structure for view `sostojki_na_recept`
-- (See below for the actual view)
--
CREATE TABLE `sostojki_na_recept` (
`recept_id` int(11)
,`ime` varchar(50)
,`kolicina` int(11)
);

-- --------------------------------------------------------

--
-- Structure for view `recept_search`
--
DROP TABLE IF EXISTS `recept_search`;

CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `recept_search`  AS  select `r`.`id` AS `id`,`r`.`korisnik_id` AS `korisnik_id`,`r`.`ime` AS `ime`,`r`.`kategorija` AS `kategorija`,`r`.`vremeZaPodgotovka` AS `vremeZaPodgotovka`,`r`.`brojPorcii` AS `brojPorcii`,`r`.`slika` AS `slika`,`rt`.`ime` AS `tag_ime` from (`recept` `r` join `recept_tags` `rt` on((`r`.`id` = `rt`.`recept_id`))) order by `r`.`ime` ;

-- --------------------------------------------------------

--
-- Structure for view `sostojki_na_recept`
--
DROP TABLE IF EXISTS `sostojki_na_recept`;

CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `sostojki_na_recept`  AS  select `rs`.`recept_id` AS `recept_id`,`s`.`ime` AS `ime`,`rs`.`kolicina` AS `kolicina` from (`recept_sostojki` `rs` join `sostojki` `s`) where (`rs`.`sostojka_id` = `s`.`id`) ;

--
-- Indexes for dumped tables
--

--
-- Indexes for table `korisnik`
--
ALTER TABLE `korisnik`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `recept`
--
ALTER TABLE `recept`
  ADD PRIMARY KEY (`id`),
  ADD KEY `recept_ibfk_1` (`korisnik_id`);

--
-- Indexes for table `recept_cekori`
--
ALTER TABLE `recept_cekori`
  ADD PRIMARY KEY (`id`),
  ADD KEY `recept_cekori_ibfk_1` (`recept_id`);

--
-- Indexes for table `recept_nutri`
--
ALTER TABLE `recept_nutri`
  ADD PRIMARY KEY (`recept_id`);

--
-- Indexes for table `recept_sostojki`
--
ALTER TABLE `recept_sostojki`
  ADD PRIMARY KEY (`recept_id`,`sostojka_id`),
  ADD KEY `recept_sostojki_ibfk_2` (`sostojka_id`);

--
-- Indexes for table `recept_tags`
--
ALTER TABLE `recept_tags`
  ADD PRIMARY KEY (`id`),
  ADD KEY `recept_tags_ibfk_1` (`recept_id`);

--
-- Indexes for table `shoping_lista`
--
ALTER TABLE `shoping_lista`
  ADD PRIMARY KEY (`id`),
  ADD KEY `korisnik_id` (`korisnik_id`);

--
-- Indexes for table `sostojki`
--
ALTER TABLE `sostojki`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `korisnik`
--
ALTER TABLE `korisnik`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `recept`
--
ALTER TABLE `recept`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=48;

--
-- AUTO_INCREMENT for table `recept_cekori`
--
ALTER TABLE `recept_cekori`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=59;

--
-- AUTO_INCREMENT for table `recept_nutri`
--
ALTER TABLE `recept_nutri`
  MODIFY `recept_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=48;

--
-- AUTO_INCREMENT for table `recept_tags`
--
ALTER TABLE `recept_tags`
  MODIFY `id` int(50) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=28;

--
-- AUTO_INCREMENT for table `shoping_lista`
--
ALTER TABLE `shoping_lista`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT for table `sostojki`
--
ALTER TABLE `sostojki`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `recept`
--
ALTER TABLE `recept`
  ADD CONSTRAINT `recept_ibfk_1` FOREIGN KEY (`korisnik_id`) REFERENCES `korisnik` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `recept_cekori`
--
ALTER TABLE `recept_cekori`
  ADD CONSTRAINT `recept_cekori_ibfk_1` FOREIGN KEY (`recept_id`) REFERENCES `recept` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `recept_nutri`
--
ALTER TABLE `recept_nutri`
  ADD CONSTRAINT `recept_nutri_ibfk_1` FOREIGN KEY (`recept_id`) REFERENCES `recept` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `recept_sostojki`
--
ALTER TABLE `recept_sostojki`
  ADD CONSTRAINT `recept_sostojki_ibfk_1` FOREIGN KEY (`recept_id`) REFERENCES `recept` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `recept_sostojki_ibfk_2` FOREIGN KEY (`sostojka_id`) REFERENCES `sostojki` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `recept_tags`
--
ALTER TABLE `recept_tags`
  ADD CONSTRAINT `recept_tags_ibfk_1` FOREIGN KEY (`recept_id`) REFERENCES `recept` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `shoping_lista`
--
ALTER TABLE `shoping_lista`
  ADD CONSTRAINT `shoping_lista_ibfk_1` FOREIGN KEY (`korisnik_id`) REFERENCES `korisnik` (`id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
