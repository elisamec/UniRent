-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Creato il: Giu 15, 2024 alle 13:34
-- Versione del server: 10.4.32-MariaDB
-- Versione PHP: 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `unirent`
--

-- --------------------------------------------------------

--
-- Struttura della tabella `accommodation`
--

CREATE TABLE `accommodation` (
  `id` int(11) NOT NULL,
  `title` varchar(30) NOT NULL,
  `address` int(11) NOT NULL,
  `price` double NOT NULL,
  `start` date NOT NULL,
  `description` varchar(500) NOT NULL,
  `deposit` double DEFAULT NULL,
  `visitDuration` int(11) NOT NULL COMMENT 'minutes',
  `man` tinyint(1) NOT NULL,
  `woman` tinyint(1) NOT NULL,
  `pets` tinyint(1) NOT NULL,
  `smokers` tinyint(1) NOT NULL,
  `idOwner` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dump dei dati per la tabella `accommodation`
--

INSERT INTO `accommodation` (`id`, `title`, `address`, `price`, `start`, `description`, `deposit`, `visitDuration`, `man`, `woman`, `pets`, `smokers`, `idOwner`) VALUES
(2, 'Casa ', 2, 200, '2024-05-01', 'bellissima casa', NULL, 30, 0, 1, 1, 1, 2),
(3, 'Casa', 6, 100, '2021-06-01', 'casetta bellissima', 50, 30, 0, 1, 1, 0, 2),
(15, 'Casa', 18, 100, '2021-06-01', 'casetta bellissima', 50, 30, 0, 1, 1, 0, 2),
(21, 'Casa', 25, 100, '2021-06-01', 'casetta bellissima v2', 100, 30, 0, 1, 1, 0, 2),
(32, 'Casa', 36, 200, '2021-06-01', 'casetta bellissima', 50, 30, 0, 1, 1, 0, 2),
(33, 'Casa', 37, 100, '2021-06-01', 'casetta bellissima v2', 100, 30, 0, 1, 1, 0, 2);

-- --------------------------------------------------------

--
-- Struttura della tabella `accommodationreview`
--

CREATE TABLE `accommodationreview` (
  `idAccommodation` int(11) NOT NULL,
  `idReview` int(11) NOT NULL,
  `idAuthor` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Struttura della tabella `address`
--

CREATE TABLE `address` (
  `id` int(11) NOT NULL,
  `addressLine` varchar(100) NOT NULL,
  `postalCode` varchar(15) NOT NULL,
  `city` varchar(20) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dump dei dati per la tabella `address`
--

INSERT INTO `address` (`id`, `addressLine`, `postalCode`, `city`) VALUES
(2, 'via Vetoio 100', '67100', 'L\'Aquila'),
(5, 'Via Roma, 1', '00100', 'Roma'),
(6, 'Via Anna, 1', '00100', 'Milano'),
(18, 'Via Anna, 1', '67100', 'Milano'),
(22, 'Via Anna, 1', '00100', 'Milano'),
(23, 'Via Anna, 1', '00100', 'Milano'),
(24, 'Via Anna, 1', '67100', 'Mil'),
(25, 'Via Anna, 1', '55555', 'Milano'),
(26, 'Via Anna, 1', '00100', 'Milano'),
(27, 'Via Anna, 1', '00100', 'Milano'),
(28, 'Via Anna, 1', '00100', 'Milano'),
(29, 'Via Anna, 1', '00100', 'Milano'),
(30, 'Via Anna, 1', '00100', 'Milano'),
(31, 'Via Anna, 1', '00100', 'Milano'),
(32, 'Via Anna, 1', '00100', 'Milano'),
(33, 'Via Anna, 1', '00100', 'Milano'),
(34, 'Via Anna, 1', '00100', 'Milano'),
(35, 'Via Anna, 1', '00100', 'Milano'),
(36, 'Via Anna, 1', '00100', 'Milano'),
(37, 'Via Anna, 1', '55555', 'Milano');

-- --------------------------------------------------------

--
-- Struttura della tabella `administrator`
--

CREATE TABLE `administrator` (
  `id` int(11) NOT NULL,
  `username` varchar(40) NOT NULL,
  `password` varchar(40) NOT NULL,
  `email` varchar(40) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Struttura della tabella `contract`
--

CREATE TABLE `contract` (
  `reservationId` int(11) NOT NULL,
  `status` enum('onGoing','future','finshed') NOT NULL,
  `paymentDate` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `cardNumber` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Struttura della tabella `creditcard`
--

CREATE TABLE `creditcard` (
  `number` int(11) NOT NULL,
  `name` varchar(20) NOT NULL,
  `surname` varchar(20) NOT NULL,
  `expiry` varchar(5) NOT NULL,
  `cvv` int(3) NOT NULL,
  `idStudent` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Struttura della tabella `day`
--

CREATE TABLE `day` (
  `id` int(11) NOT NULL,
  `day` varchar(20) NOT NULL,
  `idAccommodation` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dump dei dati per la tabella `day`
--

INSERT INTO `day` (`id`, `day`, `idAccommodation`) VALUES
(1, 'monday', 2),
(24, 'moday', 21),
(30, 'moday', 32),
(31, 'thursday', 32),
(32, 'moday', 33),
(33, 'thursday', 33);

-- --------------------------------------------------------

--
-- Struttura della tabella `owner`
--

CREATE TABLE `owner` (
  `id` int(11) NOT NULL,
  `username` varchar(40) NOT NULL,
  `password` varchar(40) NOT NULL,
  `name` varchar(20) NOT NULL,
  `surname` varchar(20) NOT NULL,
  `picture` int(11) DEFAULT NULL,
  `email` varchar(40) NOT NULL,
  `phoneNumber` varchar(15) NOT NULL,
  `iban` varchar(27) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dump dei dati per la tabella `owner`
--

INSERT INTO `owner` (`id`, `username`, `password`, `name`, `surname`, `picture`, `email`, `phoneNumber`, `iban`) VALUES
(2, 'nadia', 'pippo', 'Pippo', 'Pluto', NULL, 'pippo@pluto.it', '1234567890', '9872035410937251');

-- --------------------------------------------------------

--
-- Struttura della tabella `ownerreview`
--

CREATE TABLE `ownerreview` (
  `idOwner` int(11) NOT NULL,
  `idReview` int(11) NOT NULL,
  `idAuthor` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Struttura della tabella `photo`
--

CREATE TABLE `photo` (
  `id` int(11) NOT NULL,
  `photo` blob NOT NULL,
  `relativeTo` enum('review','accommodation','other') NOT NULL,
  `idAccommodation` int(11) DEFAULT NULL,
  `idReview` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dump dei dati per la tabella `photo`
--

INSERT INTO `photo` (`id`, `photo`, `relativeTo`, `idAccommodation`, `idReview`) VALUES
(3, '', 'accommodation', 2, NULL),
(4, 0x89504e470d0a1a0a0000000d49484452000000e0000000e008030000002df39ad3000000f0504c5445ffffff000000cf79a02f302bfcffffcd7aa0f2f2f22a2a2af6f6f6eaeaea2d2d2dfbfbfbfffeffe0e0e0adadad6868685a5a5ad4d4d4c8c8c8848484434343c0c0c02424249999998f8f8fe5e5e51818187676769f9f9fdadada5555553535352526206565650d0d0db2b2b24646464d4d4d727272151515c3c3c33939391e1e1e7e7e7e939393d2729c23251d181a1150514c98a3a0e9e2e8fff7fde8d5e0e0becedbaac0d9a0b8e1d3db090c00d7b7c8e8abc4d795b3d281a0eccadbf6e2ecd88eb0ca8dadd26c98c57093ddb0c2d4a5bfab989f432b3382757942212c1a27233c30342b2024ecdce476686c4d3f43f497123a0000096749444154789ced9d0977da4812803984c48dc158b641dcb1b12182acb14db28b8fb13d99ddd9cdccffff37d3129790faa8166a4176ea7b33c97b3c545d85baabaaab8f241208822008822008822008822008822008822008822008822008822008724c1472a552e9523fb41a4a28b4ac4abe9f74319a67d9b8dbd707a7757344308d7ad3aa46ddfecd7523b94bb31571135c6a27bee693bdb3e8a4e72efcd25d4e4ad135c1a77a4b55601c8d0297d754e90e17913420e494a940657f13f531533aa11e83bfd13ff134382dec27bdcc134ee8e7a2b1824d6e2450619fb198bb12084f263b915942a79017aa701dfa259e09653be2a33427480fa08279194eb605b12f692a1d8639900ec99b30b2e9b1c1c7a708a31195c28d09d163202ff91c20f63a964878c375a32bcab25245ee933056ee41d75401bfb6a485374281f99a1a63e85445c142325c9444d2cc109d7e3fc42e412239ce0a06f650bac747404e346a46f019468f2fe962cff4282c2d41d03f810ae2ff549f4286d52810381b60c7e207574bad09022e7b5ce56049074f46e780af6f496dc8510f9437d63802629a007229f02670104f5a673f1e6be863c37905003fc37efaea68aa69d91e534971f2c87cf63406cdc130673a422d75d693b1a72e7c981d4df42023060e63aba04169310c14f98940fdd1e57886df96123d5e08fa68819a8556e25159929c41d3b5ce7fe832c48f7238da1465fbfcce46ebd9874dceb83429eaf2732d8a733ac4d4080c25fbe62733c199fc9185073f41afcf77a301038fdcbe4462e0d7985f42f41b78d4fd7389dfc22af7dbbe31f8392625f7c2370ef929c9ae173dcef817a0b7a334bf98b913078d9814dc97ac5769535030f26407a323cccfe85c7ab2b6b6e0bb9ee5dc835727e07846d6b9e0ab5b2f136ac5e6506c1d8d70dab3eea3cd38f48a8ef526905be13707b0b17a6ce8ab610808dccb2d1bfc7079845497ef05facd63a80f4a720d761c17b01fe2d870aa49c0996bf9580aa0725870c718eb36b4e850bd5f00411004411004411004f9dba1e75ca212978b56dc9e94acedf191c6e9ded5b8d6c5761fc449f9e04616cafead6df5bdea3935ff599bf641eb103af55c583bf4a24d8eb66d62781efb61ccb53acc7d8d217f75e64ee3cf0729b3f3363e87aa1b57d8f246f117da0b3d8e7d61966eb29c7daa617fb23dc8894ebd491f4a139ddf69c7da4d4b7d813ad2fb17d8c758d7dcc6e86baa426d9292eb531089f12da983ec032c457ae00fc0156a8f2a6e81d927152c40674d934923160ba93b2e69481cef151fd45d925767d6960ebbfd8b827ee6d9320c5ea4f2f489ca59ab45df75eca2f8c4b003e758c6727d7c7b0e0e1cbb9abe47383984f2d4943300d7ebff9b17f0092a741374faab60c7be7c01783c293c9c0ebaee91db940b18b9b6db78d6c193734073acc2aa2d3c77b7de82d3db7c020c85db1eb9de9dca3b00a7760316fd5a9025ebc9e9d640e06edacf9b07d6dbc379062add02c93b99b6e9a2db491d703bed364d5bbb25ee196295af90e3c03727bf3c3b70816e746b607f590ed0b9ed281c85828b177a855dfba0db56bc8936e9a48533412aaf2eeb169d83362bcd9d9a8af41b249cb485331575b190db7328008f9488a74abba87333928a409d0ce4da8c1d54d9079d466c00f625c0bd19bba8aad0482b022cdb72830f0d55a739b8f757d1009651b26249bba89a53c8fa981e54302f3fa221da591f16505dc103b827c9bad1bc9a0a1bfd742807704e25ebbd1a6a427d9677c90205c161532ffe8b1305f41519282e86ee2071320f7601d8d64035b35e4903657e66e6e97d3a1257e3481928d74545676c7600dd71b641a2f34b21e7cda53c9d5c28045ffd23494f4609c96c432a4b52b5d424a38454e1de4126c8aa3af500bc68d045fa6482f0a6330f2a8c73a12da1d30971b900bc7fa89b0f8aefcb5b01ce42bd70aabdbb285ccf06fa51e901b804380cc105f310c0666e61d7f1b2b04526a51b12383b2136344227523a249d577b341510903b2cfbb2b5f3f1b5c3d8aa31b2009db3f4b146996d4b849d94e15f0a8376723ae92e994c93d78c58269c542bbf7b4990f83336570c3a0d23ed216f0edb74550559690c271b79f1caa43b8042af9fcfe7770ccce78d217d3ec5ed23b1ec78aa32a7a7ac09c497499a06eb75302b189d98ee06cbd26e1a4a264f59dea53ca5da974eb3a63d8c3b28f7bdf65d8256205c742c7670a8741906b23d4629f81657574217135ac2f94f192bd9fa3fb6cb2ca3b6c5ed3b4d96817dde63b5b1276f6a5b77334291b4af15354d998144f4ecfee1713e9f3f3edc7fad96ad73ab5cbb1415106adfe8f6754513d75c6b60912606b57ffe6b91b16d3bf5b4787c9e69ce6b544091487df978b2df6ca729e78fcce33dec518b6a6117f88f39dccfdd1697d8a9f75762a316bd89a4efdf2dd6cdac5bb333cf454877a97546fe30610cc780d2919678f9e52de583b41afd2824767cbcd9197f5b297b0eaa70652d63d8dd0cc5eea4d1ef41425a51bb7fb35381566d7baee0153e10f3ec808129fb09d854edf4cbd2c4ee55f3e20cb6f0a4bd645299807de4b3b7c7c85fa1f61434ce6dcd7e06cb280c484f1d4994a3b407ca4feaf21af51b2c16df59063ec0a5e8246593b90844fb6019f81475d027065246a063e09b8481892bc3b8a208677ddd7983c12e4afaa8bd88ba8b1613bfd27f4cfb7dc6508ea6c289615016f7d806ce326f411fe3b4fa127dac9ffd92a2b465db8c5848578018285596d67ebcd31c5be6397a2faa25668fab0e9271ff70feb2538b17e6f77f503c659b62a096f8fa952584b4faebfbb251b7555781f705797f2ab235edeed14d981c13dd5c2633bf67fe90b3efbf7d0f7c5830c944302036f16f933d8fd5b49787d7d4db2a7b72dede5c41f77421b99a567c7998bf9296dedf9f161ff7334e5e7ff79fdfff1b48c52c12261a8179eeddff4ccefe6ecd49807f3c7fcc178bc5ebfce187ba5c5b2b3a266ac45007cded409cb6beff6efee1f3e535621f49d27c7388bb3f8d2167478ddbd8b299a56945b5d32530857a7af225ebd585d867743a86e1fd270fc820fbd334556d9b50cbe05bba7b3bd818981bf7f38691d36f4dc3db4b2dc33046073fe8198e8b6f69a3512f97b2d9ac5e1b9b66de305b249bb935f38df6999e75b8a98ff2665ca596e8198c468631f9369d90ffbbe9fc28ef2eaae9ed46be3b251f4e26d32999365dfda4efcfc5aaf727cb595277dad9aca959e6743577ea0eeb3fc135c23c0ad5f3934e979853b9f1b8547d50b94d073efd79c95d52ea363af9f4ffc23a044110044110044110044110044110044110044110044110044110e4efce5f30899f4237eac0e20000000049454e44ae426082, 'other', NULL, NULL),
(12, 0x666f746f, 'accommodation', 2, NULL),
(13, 0x6f, 'accommodation', 2, NULL),
(18, 0x666f746f31, 'accommodation', 15, NULL),
(19, 0x666f31, 'accommodation', 15, NULL),
(28, 0x666f746f31, 'accommodation', 21, NULL),
(29, 0x666f31, 'accommodation', 21, NULL),
(50, 0x666f746f31, 'accommodation', 32, NULL),
(51, 0x666f31, 'accommodation', 32, NULL),
(52, 0x666f746f31, 'accommodation', 33, NULL),
(53, 0x666f31, 'accommodation', 33, NULL);

-- --------------------------------------------------------

--
-- Struttura della tabella `reservation`
--

CREATE TABLE `reservation` (
  `id` int(11) NOT NULL,
  `fromDate` date NOT NULL,
  `toDate` date NOT NULL,
  `made` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `statusAccept` tinyint(1) NOT NULL,
  `accommodationId` int(11) NOT NULL,
  `idStudent` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Struttura della tabella `review`
--

CREATE TABLE `review` (
  `id` int(11) NOT NULL,
  `title` varchar(40) NOT NULL,
  `valutation` int(1) NOT NULL,
  `description` varchar(500) DEFAULT NULL,
  `type` enum('student','accommodation','owner') NOT NULL,
  `creationDate` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Struttura della tabella `student`
--

CREATE TABLE `student` (
  `id` int(11) NOT NULL,
  `username` varchar(40) NOT NULL,
  `password` varchar(40) NOT NULL,
  `name` varchar(20) NOT NULL,
  `surname` varchar(20) NOT NULL,
  `picture` int(11) DEFAULT NULL,
  `universityMail` varchar(40) NOT NULL,
  `courseDuration` int(1) NOT NULL,
  `immatricolationYear` int(4) NOT NULL,
  `birthDate` date NOT NULL,
  `sex` varchar(1) NOT NULL,
  `smoker` tinyint(1) NOT NULL,
  `animals` tinyint(1) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dump dei dati per la tabella `student`
--

INSERT INTO `student` (`id`, `username`, `password`, `name`, `surname`, `picture`, `universityMail`, `courseDuration`, `immatricolationYear`, `birthDate`, `sex`, `smoker`, `animals`) VALUES
(1, 'nadia', 'pippo', 'Pippo', 'Pluto', NULL, 'nadia@univaq.it', 3, 2021, '0000-00-00', 'f', 0, 0);

-- --------------------------------------------------------

--
-- Struttura della tabella `studentreview`
--

CREATE TABLE `studentreview` (
  `idStudent` int(11) NOT NULL,
  `idReview` int(11) NOT NULL,
  `authorType` enum('student','owner') NOT NULL,
  `authorStudent` int(11) DEFAULT NULL,
  `authorOwner` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Struttura della tabella `supportrequest`
--

CREATE TABLE `supportrequest` (
  `id` int(11) NOT NULL,
  `message` varchar(1000) NOT NULL,
  `topic` enum('registration','appUse','bug') NOT NULL,
  `idStudent` int(11) DEFAULT NULL,
  `idOwner` int(11) DEFAULT NULL,
  `authorType` enum('student','owner') NOT NULL,
  `status` tinyint(1) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Struttura della tabella `time`
--

CREATE TABLE `time` (
  `id` int(11) NOT NULL,
  `hour` time NOT NULL,
  `idDay` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dump dei dati per la tabella `time`
--

INSERT INTO `time` (`id`, `hour`, `idDay`) VALUES
(14, '10:30:00', 24),
(15, '11:20:00', 24),
(23, '10:30:00', 30),
(24, '11:20:00', 30),
(25, '20:40:00', 31),
(26, '10:30:00', 32),
(27, '11:20:00', 32),
(28, '20:40:00', 33);

-- --------------------------------------------------------

--
-- Struttura della tabella `visit`
--

CREATE TABLE `visit` (
  `id` int(11) NOT NULL,
  `visitDay` datetime NOT NULL,
  `idStudent` int(11) NOT NULL,
  `idAccommodation` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dump dei dati per la tabella `visit`
--

INSERT INTO `visit` (`id`, `visitDay`, `idStudent`, `idAccommodation`) VALUES
(5, '2024-06-09 10:39:22', 1, 21),
(7, '2024-01-01 15:30:00', 1, 21),
(8, '2024-01-01 15:30:00', 1, 21),
(9, '2024-01-01 15:30:00', 1, 21),
(10, '2024-01-01 15:30:00', 1, 21),
(11, '2024-01-01 15:30:00', 1, 21),
(13, '2024-01-01 15:30:00', 1, 21);

--
-- Indici per le tabelle scaricate
--

--
-- Indici per le tabelle `accommodation`
--
ALTER TABLE `accommodation`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `address` (`address`),
  ADD KEY `idOwner` (`idOwner`);

--
-- Indici per le tabelle `accommodationreview`
--
ALTER TABLE `accommodationreview`
  ADD KEY `idAccommodation` (`idAccommodation`),
  ADD KEY `idAuthor` (`idAuthor`),
  ADD KEY `idReview` (`idReview`);

--
-- Indici per le tabelle `address`
--
ALTER TABLE `address`
  ADD PRIMARY KEY (`id`);

--
-- Indici per le tabelle `administrator`
--
ALTER TABLE `administrator`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `email` (`email`),
  ADD UNIQUE KEY `username` (`username`),
  ADD UNIQUE KEY `email_2` (`email`);

--
-- Indici per le tabelle `contract`
--
ALTER TABLE `contract`
  ADD KEY `reservationId` (`reservationId`),
  ADD KEY `cardNumber` (`cardNumber`);

--
-- Indici per le tabelle `creditcard`
--
ALTER TABLE `creditcard`
  ADD PRIMARY KEY (`number`),
  ADD KEY `studentId` (`idStudent`);

--
-- Indici per le tabelle `day`
--
ALTER TABLE `day`
  ADD PRIMARY KEY (`id`);

--
-- Indici per le tabelle `owner`
--
ALTER TABLE `owner`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `username` (`username`),
  ADD UNIQUE KEY `email` (`email`),
  ADD UNIQUE KEY `phoneNumber` (`phoneNumber`),
  ADD UNIQUE KEY `iban` (`iban`),
  ADD KEY `picture` (`picture`);

--
-- Indici per le tabelle `ownerreview`
--
ALTER TABLE `ownerreview`
  ADD KEY `idAuthor` (`idAuthor`),
  ADD KEY `idOwner` (`idOwner`),
  ADD KEY `idReview` (`idReview`);

--
-- Indici per le tabelle `photo`
--
ALTER TABLE `photo`
  ADD PRIMARY KEY (`id`),
  ADD KEY `photo_ibfk_1` (`idAccommodation`),
  ADD KEY `photo_ibfk_2` (`idReview`);

--
-- Indici per le tabelle `reservation`
--
ALTER TABLE `reservation`
  ADD PRIMARY KEY (`id`),
  ADD KEY `accommodationId` (`accommodationId`),
  ADD KEY `studentId` (`idStudent`);

--
-- Indici per le tabelle `review`
--
ALTER TABLE `review`
  ADD PRIMARY KEY (`id`);

--
-- Indici per le tabelle `student`
--
ALTER TABLE `student`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `username` (`username`),
  ADD UNIQUE KEY `universityMail` (`universityMail`),
  ADD KEY `picture` (`picture`);

--
-- Indici per le tabelle `studentreview`
--
ALTER TABLE `studentreview`
  ADD KEY `idStudent` (`idStudent`),
  ADD KEY `idReview` (`idReview`),
  ADD KEY `authorStudent` (`authorStudent`),
  ADD KEY `authorOwner` (`authorOwner`);

--
-- Indici per le tabelle `supportrequest`
--
ALTER TABLE `supportrequest`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idOwner` (`idOwner`),
  ADD KEY `studentId` (`idStudent`);

--
-- Indici per le tabelle `time`
--
ALTER TABLE `time`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idDay` (`idDay`);

--
-- Indici per le tabelle `visit`
--
ALTER TABLE `visit`
  ADD PRIMARY KEY (`id`),
  ADD KEY `studentId` (`idStudent`),
  ADD KEY `accommodationId` (`idAccommodation`);

--
-- AUTO_INCREMENT per le tabelle scaricate
--

--
-- AUTO_INCREMENT per la tabella `accommodation`
--
ALTER TABLE `accommodation`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=34;

--
-- AUTO_INCREMENT per la tabella `address`
--
ALTER TABLE `address`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=38;

--
-- AUTO_INCREMENT per la tabella `administrator`
--
ALTER TABLE `administrator`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT per la tabella `day`
--
ALTER TABLE `day`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=34;

--
-- AUTO_INCREMENT per la tabella `owner`
--
ALTER TABLE `owner`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT per la tabella `photo`
--
ALTER TABLE `photo`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=54;

--
-- AUTO_INCREMENT per la tabella `reservation`
--
ALTER TABLE `reservation`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT per la tabella `review`
--
ALTER TABLE `review`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=25;

--
-- AUTO_INCREMENT per la tabella `student`
--
ALTER TABLE `student`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT per la tabella `supportrequest`
--
ALTER TABLE `supportrequest`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT per la tabella `time`
--
ALTER TABLE `time`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=29;

--
-- AUTO_INCREMENT per la tabella `visit`
--
ALTER TABLE `visit`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=14;

--
-- Limiti per le tabelle scaricate
--

--
-- Limiti per la tabella `accommodation`
--
ALTER TABLE `accommodation`
  ADD CONSTRAINT `accommodation_ibfk_2` FOREIGN KEY (`address`) REFERENCES `address` (`id`),
  ADD CONSTRAINT `accommodation_ibfk_3` FOREIGN KEY (`idOwner`) REFERENCES `owner` (`id`);

--
-- Limiti per la tabella `accommodationreview`
--
ALTER TABLE `accommodationreview`
  ADD CONSTRAINT `accommodationreview_ibfk_1` FOREIGN KEY (`idAccommodation`) REFERENCES `accommodation` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `accommodationreview_ibfk_2` FOREIGN KEY (`idAuthor`) REFERENCES `student` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `accommodationreview_ibfk_3` FOREIGN KEY (`idReview`) REFERENCES `review` (`id`) ON DELETE CASCADE;

--
-- Limiti per la tabella `contract`
--
ALTER TABLE `contract`
  ADD CONSTRAINT `contract_ibfk_1` FOREIGN KEY (`reservationId`) REFERENCES `reservation` (`id`),
  ADD CONSTRAINT `contract_ibfk_2` FOREIGN KEY (`cardNumber`) REFERENCES `creditcard` (`number`);

--
-- Limiti per la tabella `creditcard`
--
ALTER TABLE `creditcard`
  ADD CONSTRAINT `creditcard_ibfk_1` FOREIGN KEY (`idStudent`) REFERENCES `student` (`id`) ON DELETE CASCADE;

--
-- Limiti per la tabella `owner`
--
ALTER TABLE `owner`
  ADD CONSTRAINT `owner_ibfk_1` FOREIGN KEY (`picture`) REFERENCES `photo` (`id`) ON DELETE SET NULL;

--
-- Limiti per la tabella `ownerreview`
--
ALTER TABLE `ownerreview`
  ADD CONSTRAINT `ownerreview_ibfk_1` FOREIGN KEY (`idAuthor`) REFERENCES `student` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `ownerreview_ibfk_2` FOREIGN KEY (`idOwner`) REFERENCES `owner` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `ownerreview_ibfk_3` FOREIGN KEY (`idReview`) REFERENCES `review` (`id`) ON DELETE CASCADE;

--
-- Limiti per la tabella `photo`
--
ALTER TABLE `photo`
  ADD CONSTRAINT `photo_ibfk_1` FOREIGN KEY (`idAccommodation`) REFERENCES `accommodation` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `photo_ibfk_2` FOREIGN KEY (`idReview`) REFERENCES `review` (`id`) ON DELETE CASCADE;

--
-- Limiti per la tabella `reservation`
--
ALTER TABLE `reservation`
  ADD CONSTRAINT `reservation_ibfk_1` FOREIGN KEY (`accommodationId`) REFERENCES `accommodation` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `reservation_ibfk_2` FOREIGN KEY (`idStudent`) REFERENCES `student` (`id`) ON DELETE CASCADE;

--
-- Limiti per la tabella `student`
--
ALTER TABLE `student`
  ADD CONSTRAINT `student_ibfk_1` FOREIGN KEY (`picture`) REFERENCES `photo` (`id`) ON DELETE SET NULL;

--
-- Limiti per la tabella `studentreview`
--
ALTER TABLE `studentreview`
  ADD CONSTRAINT `studentreview_ibfk_1` FOREIGN KEY (`idStudent`) REFERENCES `student` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `studentreview_ibfk_2` FOREIGN KEY (`idReview`) REFERENCES `review` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `studentreview_ibfk_3` FOREIGN KEY (`authorStudent`) REFERENCES `student` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `studentreview_ibfk_4` FOREIGN KEY (`authorOwner`) REFERENCES `owner` (`id`) ON DELETE CASCADE;

--
-- Limiti per la tabella `supportrequest`
--
ALTER TABLE `supportrequest`
  ADD CONSTRAINT `supportrequest_ibfk_1` FOREIGN KEY (`idOwner`) REFERENCES `owner` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `supportrequest_ibfk_2` FOREIGN KEY (`idStudent`) REFERENCES `student` (`id`) ON DELETE CASCADE;

--
-- Limiti per la tabella `time`
--
ALTER TABLE `time`
  ADD CONSTRAINT `time_ibfk_1` FOREIGN KEY (`idDay`) REFERENCES `day` (`id`);

--
-- Limiti per la tabella `visit`
--
ALTER TABLE `visit`
  ADD CONSTRAINT `visit_ibfk_1` FOREIGN KEY (`idStudent`) REFERENCES `student` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `visit_ibfk_2` FOREIGN KEY (`idAccommodation`) REFERENCES `accommodation` (`id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
