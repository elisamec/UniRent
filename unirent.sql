-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: localhost
-- Generation Time: Jul 02, 2024 at 10:17 AM
-- Server version: 10.4.28-MariaDB
-- PHP Version: 8.2.4

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
-- Table structure for table `accommodation`
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
-- Dumping data for table `accommodation`
--

INSERT INTO `accommodation` (`id`, `title`, `address`, `price`, `start`, `description`, `deposit`, `visitDuration`, `man`, `woman`, `pets`, `smokers`, `idOwner`) VALUES
(5, 'Casa Rosada', 2, 230.7, '2024-06-04', 'Presidenza della Repubblica Argentina', 230, 21, 1, 0, 0, 0, 2),
(6, 'Casa Maloni', 3, 1000, '2024-06-13', 'La casa più bella e al contempo sfasciata del mondo', 250, 30, 1, 1, 1, 0, 2);

-- --------------------------------------------------------

--
-- Table structure for table `accommodationreview`
--

CREATE TABLE `accommodationreview` (
  `idReview` int(11) NOT NULL,
  `idAccommodation` int(11) NOT NULL,
  `idAuthor` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `address`
--

CREATE TABLE `address` (
  `id` int(11) NOT NULL,
  `addressLine` varchar(200) NOT NULL,
  `postalCode` varchar(15) NOT NULL,
  `city` varchar(40) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `address`
--

INSERT INTO `address` (`id`, `addressLine`, `postalCode`, `city`) VALUES
(1, 'via Anna 3, int 1', '67100', 'L\'Aquila'),
(2, 'piazza San Marco 5A', '90761', 'Rimini'),
(3, 'via Vetoio 5', '67100', 'L\'Aquila');

-- --------------------------------------------------------

--
-- Table structure for table `contract`
--

CREATE TABLE `contract` (
  `idReservation` int(11) NOT NULL,
  `status` enum('onGoing','future','finshed') NOT NULL,
  `paymentDate` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `cardNumber` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `contract`
--

INSERT INTO `contract` (`idReservation`, `status`, `paymentDate`, `cardNumber`) VALUES
(3, 'future', '2024-06-06 15:58:05', 1192002);

-- --------------------------------------------------------

--
-- Table structure for table `creditcard`
--

CREATE TABLE `creditcard` (
  `number` int(11) NOT NULL,
  `name` varchar(20) NOT NULL,
  `surname` varchar(20) NOT NULL,
  `expiry` varchar(5) NOT NULL,
  `cvv` int(3) NOT NULL,
  `idStudent` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `creditcard`
--

INSERT INTO `creditcard` (`number`, `name`, `surname`, `expiry`, `cvv`, `idStudent`) VALUES
(1192002, 'Matteo', 'Maloni', 'NONLO', 123, 2);

-- --------------------------------------------------------

--
-- Table structure for table `day`
--

CREATE TABLE `day` (
  `id` int(11) NOT NULL,
  `day` varchar(20) NOT NULL,
  `idAccommodation` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `owner`
--

CREATE TABLE `owner` (
  `id` int(11) NOT NULL,
  `username` varchar(40) NOT NULL,
  `password` varchar(100) NOT NULL,
  `name` varchar(20) NOT NULL,
  `surname` varchar(20) NOT NULL,
  `picture` int(11) DEFAULT NULL,
  `email` varchar(40) NOT NULL,
  `phoneNumber` varchar(15) NOT NULL,
  `iban` varchar(27) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `owner`
--

INSERT INTO `owner` (`id`, `username`, `password`, `name`, `surname`, `picture`, `email`, `phoneNumber`, `iban`) VALUES
(2, 'Lupo', '$2y$10$bWaqrMre3N1hGOIRSgmq4O5jjJerKtBYK', 'MIKE', 'Lupinetti', NULL, 'michael.lupinetti.02@gmail.com', '3312456767', '123456789'),
(4, 'nadia1', '$2y$10$PqPZa3NYqrAqy.Xg8EKM0OCjazq6tHDvJvE43RB50yxRsww5cKNiK', 'Nadia', 'Muzyka', NULL, 'muzykanadia0@gmail.com', '3333333333', 'IT60X0542811101000000123456');

-- --------------------------------------------------------

--
-- Table structure for table `ownerreview`
--

CREATE TABLE `ownerreview` (
  `idOwner` int(11) NOT NULL,
  `idReview` int(11) NOT NULL,
  `idAuthor` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `photo`
--

CREATE TABLE `photo` (
  `id` int(11) NOT NULL,
  `photo` longblob NOT NULL,
  `relativeTo` enum('review','accommodation','other') NOT NULL,
  `idAccommodation` int(11) DEFAULT NULL,
  `idReview` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `photo`
--

INSERT INTO `photo` (`id`, `photo`, `relativeTo`, `idAccommodation`, `idReview`) VALUES
(4, 0xffd8ffe000104a46494600010100000100010000ffdb00840009060712111215121213121615161615151918181517171617151515161a17151315181d2820181b251d151522312125292b2f2e2e171f3338332c37282d2e2b010a0a0a0505050e05050e2b1913192b2b2b2b2b2b2b2b2b2b2b2b2b2b2b2b2b2b2b2b2b2b2b2b2b2b2b2b2b2b2b2b2b2b2b2b2b2b2b2b2b2b2b2b2b2b2b2b2b2bffc000110800e100e103012200021101031101ffc4001c0001000202030100000000000000000000000208010703050604ffc4004910000103020206050708060905000000000100020304110521061231415161071322718108325291b1e1f01442738292a1c1c22362637293c32433346483a2b2b3d13543a3d2f1ffc40014010100000000000000000000000000000000ffc40014110100000000000000000000000000000000ffda000c03010002110311003f00de288880b0d75d41ce52620922220222202228928248a16e65481419444404444044440586baf9a839ca6dd8832888808888088a24a09228290283288880b8dce5370516b501ad5344404444044440500a6b04208a900be7afad8a9e374d33dac8d82ee7b8d801f1bb7ad39a4fd3c005ccc3e00f03feecd70d39ed6c4d20db9970ee41b4349349e1a00c92a03c40f3aa666b4bd91bb2d51286f680767670045c58dae2fc5169ce16e008afa3cf8cf1b4f8871042ac98ce9e57d4bdcf74c630fb873220238df7dbd635bfd677beebcca0b8f0e97e1cf366d751b8f0151113ead65dbc13b5e3598e6b87169047ac2a42be9a1af9a076bc32c913bd28dee63bd6d20a0bb2b8dcebaae7a25d3556d390cac1f2986f62eb06ccd1979ae160fde6ce173e905beb46f1ea6af884f4d236461c8db2730edd57b76b5dc8f7ec283b46b5491101111011110141aa6b04208a900802ca02222022220222202222022220222d73d30f48270c89b0d391f2a981209b1eaa3d9d6169da49b868396449d9621ae3a7dd2b74f57f2163bf434f6d60363a770b9278ea821a06e3aeb55836539e6748e73dee2e7389739c4dcb9ce372e713b492571a09117cc788e1ee515906cb245f31e2387b904549adde767b79046b779d9ede4161ceba039d7f8d8bbed0ad2a9f0ca96cf093ab70248efd9923be6d70e3b6c7715d0220bbd4f3b64687b1c1cd70b87020820ef042e455a3a0bd2a7d2d73695ce3d4549d4d527b2d96dd87b46e248d53c758700acba02222022220222202222022220222202222022220222202aa3d31d73a6c5eab589b31cd89a38358c68cbbceb1facad72aa5d3343a98cd58b6d313bed43193f79283c52222029b06fddede4161adde767b7905873aff001b1049f9e63d5c3bb9282c8365922f98f11c3dc82288883b0d1e98c7554ef1b5b3c4e1ded91a47b15d254d343a1d7afa367a5534edf032b41572d0111101111011110111101111011145c5049142ddea4d28328888088880ab4794141ab8adfd382277a8bd9f9159755c3ca06b609eba230cac91f1c6e8240d372c7b2471d5773ed91e0506ad02f9059736c6c776db7b176b4948231aeef385cdf681bb2036efbf01b38aebaae40e792366ee400d83920e273aff001b1611101641b2c220c95845f653d39cbb3acf779addc07a4efc07c10f43d1353f598bd1b784a5ff00c3639ff955b55577a2bab8e9714864ab7430b58c9087b80682e730b0768643ce3c95a169ba0ca2220222202221402561a6f9a839d75368c90651110144292c10822a40200b2808888088880a9e620c0daba8d7b6b0a8981d6b1c8487580e0edbb776c5709c2eaa974bd4860c52a63d5b073c4a0fa5d6b43f6f005ce1e050793a89ee3541bb4119ef36161dc06761cd7ce8b2d6df241844440444405d84754637b65003816869e64340703c0dc2f808538652dbe4083b41d87dfcd0779572b1cc12cad1b0ea32f99bef2478772b6d83b08a7843b688a307bc305d53fc0289d5b594f0119492c71d87cd617006dc006dcf82b98101111011110095c6e375278ba35a80d6a9222022220222202222022220222202d2be503a2134ee86b69e27c85adea650c05ce0038ba376a8198bb9e09fdd5ba91051f7b0b4904104120822c411b411b8a8af5fd2c61069715aa658eac8febdbcc4ddb36e41c5cdfaabc820e4f3bbfdbef5c68b93ceeff6fbd071a9816ccf80fc4a016dbb787e254494025611765a39851abaa8299b7fd2cac6123686b9c359de0db9f041b2ba07d1099d5bf2c9a27b23818ed42f696eb4b20d51aa08ed00d2f37dc4b5586586340000c80c8770594044440444404444044440444404444044440444404444044441a63ca334775e286bd83388f5327d1bcde371e41e5c3fc40b422b8da72d61c3ab3ac6eb30534ee238eac6e70b73b80a9ca029816ccf80fc4a016ccf80fc4a8928277d6fdef6f7f35c68b93ceeff006fbd071adbbe4efa3a65aa92b9e3b1034c6c3c65906641fd565eff004816a256a3a1263060d4c58d0dd63297737899ed2e3cecd1e1641ee91110111101111011110111101111011110145aeba8b9d7526041244440444404451250799e93e7d4c26b4f181edfb7d9fccaa334ab4bd354c1984545c1ed185b6bee33309cfb81557a78b56d637691707973e0507192b08880888824e75fe3daacd74073eb610c1e84b337d6ed6fceab1ab11e4e137f40a86fa3525df6a28bff00541b6895869b85c64dd723420ca2220222202228137413450038290283288880b8dceba990b0d6a035aa4888088b8aa6a591b4be47b58d1b5ce706b477939041ca8bc163dd2f6154b70d98cef1f3606eb8fe21b33d44ad738f74f35525db494f14233ed3c995fc8819341e4439058271b6672016a9d3fe9821a66ba2a0d49e50754cb7bc31923711fd61eeecf3362168ec7b4aebab7fb554cd20363a85d68f2d8444db307a974ec90b766f163cc1dc505bdacc11b8861cca7a925c648622e7585fac0d6bbac23679c2f6d8730ab0e9c68cd4e1d5061a868b67d5bda2d1bd80ed8f86dcc6d04e7b6e6ddd1b6cc60fd56fb02ebb4a346e9f1181d4f52cd669cc1193d8edcf8ddb9c3d47306e090829a22f53a7da0f5184cda9276e2713d5cc0765e381f45e37b7d570bcb20222ee7453466a3129c414ccb9dae71c991b77be476e1f79d8107cd80e0b3d6ceda7a7617c8eddb80dee79f9ad1bcab4dd1e688330aa510076bbdceeb247ec05e401d91b9a00b0f13bd72e83684d3e15075508d691d6324a476e470ff4b06e68d9cc924fa56b5069ed13e979b14f25162571d5caf89b516dba8e2dfe90d1b0e5e7016cf31b4adbd4d50c918d923735ec7005ae690e6b81d85ae1910a9de998b6215a3fbd547fbcf5c382e3f5748ebd2cf34449bd98f21ae3faccd8ef10505cf455cb01e9d2be2b0a98e2a86f1b75521facd1abfe55b1b01e9a30ca8b3657494ee397e95b765f948cb8039bac8363a2f9b0fc421a86ebc12c72b0fce63daf6fada57d280a0d5358210454804016501111011174da5fa431e1d492d5499860ecb77bdeec98c1de48cf70b9dc83971fd21a5a18facaa99913775cf69c46e6305dce3dc0ad778af4f140c04410d44ce1b2e1b130fd624b87d95a2348b1d9ebe7754543cb9eefb2d6ee6307cd68becf1cc9257588367635d35e255176c3d4d33776a375df6e0e7c971e21a16bec5716a8a976bd44d2cae17ce47b9c45f681ac721c82f897203addfc78f23cd071a9816ccf80fc4f240db6df57fca89374026eb08882ee51baf1b0f16b4fdc1732ebf47a50fa5a778d8e86277ae3695d820f871ac220ac85f05446248de2c41fb8b4ed6b86e2330ab0f491d1dcf84c9ac2f252bdd68e5b662fb23980f35fcf63ad716cc0b4b5f591c11be595ed646c69739ce36000da4aab9d26e9e498b4f6617369a327aa8fd2dc65938bcee1b81b0cee5c1d4e8468754e2b3f5500b31b6324a4762269de78b8d8d9a333c80245a5d12d17a6c3601053b6c36b9e7cf91de93cef3cb60dcaade83e96cf855409e23769b092227b32b2f983c08cecedc788b836b74771c82ba9d9534eed663c7d66bb7b1e37386f1f820ec91110535d3337c42b0ff7aa8ff79eba75d8e91cbaf5750ef4a799deb91c575c827e777fb7dfedefdb0453f3bbfdbeff006f7ed0e5a1ad9607f590c9246f1b1cc7b98e1dce69057bcc13a62c569ac2491950dcbb32b3b407d23355d73c5d75af865dff00199504160b08e9ee91c3fa4d34f13bf665b2b7bf32d23d456c3d19d30a2c44134b3b5e40b961bb6468e258eb1b73d8a9d2fa30fae929e464d0bdcc918759ae69b107e376fba0bb48bc8f463a60315a312b801330f573346cd70327347a2e163c8dc6765eb9011110168cf291c733a6a169d97a878efbb23fe6fac2de6aa0f48d8dfcb712a99c1bb0c858cb1b8eae3ec308e170dd6ef7141e6d111014c0b667c07e2792016ccf80fc4f25126e8392fadb76f1e3c8ae22117203addfb8f1e45071a210882def46d56d970aa2734dc0a78a3fad13446e1f69857a55a7bc9cb1deb29a7a371ed42feb597b7f572ed0dee7b493f4816e141e1fa5dd14a8c4a8baba79087c6eeb3aab80c9ec3cc71dce1b5b7cafb76ddb56a581d1b8b646b9ae692d2c7021c1c0d8870398b1dcaee136552ba50d22662188cd3c6d688c5a2610002f6c771d6388f38937b13f37546e41e59ced6ccedf6fbfdbedb17d05e86d550c4fa8a87bd9d781ab4e76340cc4b28398908c80cac0e7726cdd09a338b7c8eae0a9d46bc45235e5ae008206db5f61b5ec771b1dcae3e1f5ac9e264d13b599235af69e2d70b83f7a0fa17056d53618df2bcd9b1b1cf71e0d602493e0173ad7dd38e3bf25c2e460367d411037f75d9c87bb5011f5820abee71249399399ef2b08880a632eff008cca0cbbfe332a0827e77ef7b7dfedefdb0453f3bbfdbeff006f7ed0822220da3e4f78d753883a9c9ecd4c6401fb48aef6ff0097adf58564552ed1dc50d25541522ffa29592586d21ae05cdf1171e2ae7c6f0e01c0dc10083c41d85049111079ee90318f91e1d55500d9cd89cd61fda49d861fb4e0a9f2b13e51988ea50c30036334f73cd9134923ed399ea5a0a9a120b6c017bbcd076347a4efc078a0f8d65a576558c962b75843da72e37e44917ee5f14f10162df35db2fb45b683ddc507113758444044441973afb7e3bd611640be483d5f45da41f20c4a0949fd1b8f5527d1c96049e4d3aaefaaada34dd536a681b1b0b8904edb8b730036fb36137d8eb5bbecb7445a4c2bf0f6126f2c27a99389d40351e47eb3357c75b820e1e9a71e751e17216121f3b9b4e0f01207179fb0c78ef21558560bca4aa2d494b1fa53b9ff0062323f98abea02b0fe4ed8f3a6a49a91f73f267b4b0fece6d73abe0e63cfd60abc2dc5e4d9556aaaa8af9ba163edf46fb5ff00f28f5a0b00ab3f4f3a45f2ac43a861bc74a0c7c8caeb194f859adef615bf74d31f6e1f453d53ad7630ea03f3a4776636f717117e573b954a635ef909275a69097927e6eb665ce3e91bdf95f8ec0eb565a6cbb1ac12444365b3da47afc6d7d61966be19e3d522c6e08b83bed72331c6e0a0e3288880888824e75fbfdbef5145cd4f017666e1a369b5edcbff00a814d4e5f7b6edbe39003992ad774518a7ca70aa579f398cea5d9dcde12582e7986b4f8aac7513b626b436c787023304eccc137b8df95b96e5f26dc4f5a0aaa63f32464a3ba56ea903b8c43ed20dca88882bef94956eb55d2c3e842e93f8afb7f296ab648e019230e718b1e233399e2db1b2f67d3a567598c4cddd13218c7f0daf3f7bcaf051c85a6e36fc6478a0ef5d5a0c6d9650d3b751837919126ff017493cc5e758fa86400e006e0b12ca5c6e7bb80038003628202222022220cb45f20bb0a30233dab83706e2e32c8ea9b8b806db4772ebdaeb1f8deb9eaaadcfb037b0e3993cc9de831513df26df56f7dd72789b7b372f77d08e93fc8b106c6f368aaad0bb807dff0044f3f5896f7484ee5af564141babca5e6bc942cf45b3bbed1887e45a517aad3dd2b3891a491c4ebc74ad8a4bef99b249acf1fbcdeadde36dcbcaa02d99e4f73eae2a47a74f2b7d4e8dff00916b35e8ba3ec79b87d7c556f04b636cd90f9c5d048d637c5ce68beeda8360794469375b3c740c3d986d2c9f4ae6f61bf55849ff001392d56d9dcd2d9596360d69e440b76b91b647fe170e235af9e592694eb3e47b9ee3c5ce373ed5c514a5a6e3bb882381076a0ef27ac018d9640d73c8ec377341de6ff1b97472c85c6e76fc64380589242e373b7e32037051404444044441cb4d0179b0f83c07a8afb3ae11b6ce6e76b019807317245f23768bdb685f0c726a9f8ddb12590b89713725061ef2e373f1c96c9f27ec47aac53ab2729e19196e2e6da41f731deb5ad17a2e8eebba8c4e8e4fef11b4feec8ed477dce282e022220a97d2d7fd5eb3e907fa18bc8a2202222022220222202222022220222202222022220222202222022220222202fbf00fed54ff004d17fb8d444174d11107ffd9, 'other', NULL, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `reservation`
--

CREATE TABLE `reservation` (
  `id` int(11) NOT NULL,
  `fromDate` date NOT NULL,
  `toDate` date NOT NULL,
  `made` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `statusAccept` tinyint(1) NOT NULL,
  `idAccommodation` int(11) NOT NULL,
  `idStudent` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `reservation`
--

INSERT INTO `reservation` (`id`, `fromDate`, `toDate`, `made`, `statusAccept`, `idAccommodation`, `idStudent`) VALUES
(3, '2024-06-06', '2024-08-06', '2024-06-06 15:58:56', 1, 5, 2),
(4, '2024-06-10', '2024-06-25', '2024-06-07 15:51:22', 0, 6, 5);

-- --------------------------------------------------------

--
-- Table structure for table `review`
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
-- Table structure for table `student`
--

CREATE TABLE `student` (
  `id` int(11) NOT NULL,
  `username` varchar(40) NOT NULL,
  `password` varchar(100) NOT NULL,
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
-- Dumping data for table `student`
--

INSERT INTO `student` (`id`, `username`, `password`, `name`, `surname`, `picture`, `universityMail`, `courseDuration`, `immatricolationYear`, `birthDate`, `sex`, `smoker`, `animals`) VALUES
(2, 'Matthew', '$2y$10$xz68jh34Sb4XRrOVIBfA7.Rd8cCaZy47r', 'MIKE', 'Maloni', NULL, 'matteo.maloni@student.univaq.it', 3, 2021, '2019-05-18', 'M', 0, 0),
(3, 'Morge', 'pippo', 'Simone', 'Cialini', 4, 'simone.cialini@student.univaq.it', 3, 2021, '2018-05-09', 'M', 0, 1),
(5, 'Fratmo', 'pippo', 'Lorenzo', 'Maloni', NULL, 'lorenzo.maloni.02@gmail.com', 3, 2021, '2002-11-09', 'M', 0, 0),
(6, 'Dante', '$2y$10$JaCOvawDGXGvg/eeLKdJGezG.pygoLPpE', 'Dante', 'Alighieri', NULL, 'danteAlighieri@gmail.com', 3, 1300, '1269-12-12', 'M', 0, 0);

-- --------------------------------------------------------

--
-- Table structure for table `studentreview`
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
-- Table structure for table `supportrequest`
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
-- Table structure for table `time`
--

CREATE TABLE `time` (
  `id` int(11) NOT NULL,
  `hour` time NOT NULL,
  `idDay` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `visit`
--

CREATE TABLE `visit` (
  `id` int(11) NOT NULL,
  `day` datetime NOT NULL,
  `idStudent` int(11) NOT NULL,
  `idAccommodation` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Indexes for dumped tables
--

--
-- Indexes for table `accommodation`
--
ALTER TABLE `accommodation`
  ADD PRIMARY KEY (`id`),
  ADD KEY `accommodation_ibfk_4` (`idOwner`),
  ADD KEY `address` (`address`);

--
-- Indexes for table `accommodationreview`
--
ALTER TABLE `accommodationreview`
  ADD KEY `idAccommodation` (`idAccommodation`),
  ADD KEY `idAuthor` (`idAuthor`),
  ADD KEY `idReview` (`idReview`);

--
-- Indexes for table `address`
--
ALTER TABLE `address`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `contract`
--
ALTER TABLE `contract`
  ADD KEY `idReservation` (`idReservation`),
  ADD KEY `cardNumber` (`cardNumber`);

--
-- Indexes for table `creditcard`
--
ALTER TABLE `creditcard`
  ADD PRIMARY KEY (`number`),
  ADD KEY `idStudent` (`idStudent`);

--
-- Indexes for table `day`
--
ALTER TABLE `day`
  ADD PRIMARY KEY (`id`),
  ADD KEY `accommodation` (`idAccommodation`);

--
-- Indexes for table `owner`
--
ALTER TABLE `owner`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `username` (`username`),
  ADD UNIQUE KEY `email` (`email`),
  ADD UNIQUE KEY `phoneNumber` (`phoneNumber`),
  ADD UNIQUE KEY `iban` (`iban`),
  ADD KEY `picture` (`picture`);

--
-- Indexes for table `ownerreview`
--
ALTER TABLE `ownerreview`
  ADD KEY `idAuthor` (`idAuthor`),
  ADD KEY `idOwner` (`idOwner`),
  ADD KEY `idReview` (`idReview`);

--
-- Indexes for table `photo`
--
ALTER TABLE `photo`
  ADD PRIMARY KEY (`id`),
  ADD KEY `photo_ibfk_1` (`idAccommodation`),
  ADD KEY `photo_ibfk_2` (`idReview`);

--
-- Indexes for table `reservation`
--
ALTER TABLE `reservation`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idAccommodation` (`idAccommodation`),
  ADD KEY `idStudent` (`idStudent`);

--
-- Indexes for table `review`
--
ALTER TABLE `review`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `student`
--
ALTER TABLE `student`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `username` (`username`),
  ADD UNIQUE KEY `universityMail` (`universityMail`),
  ADD KEY `picture` (`picture`);

--
-- Indexes for table `studentreview`
--
ALTER TABLE `studentreview`
  ADD KEY `idStudent` (`idStudent`),
  ADD KEY `idReview` (`idReview`),
  ADD KEY `authorStudent` (`authorStudent`),
  ADD KEY `authorOwner` (`authorOwner`);

--
-- Indexes for table `supportrequest`
--
ALTER TABLE `supportrequest`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idOwner` (`idOwner`),
  ADD KEY `idStudent` (`idStudent`);

--
-- Indexes for table `time`
--
ALTER TABLE `time`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idDay` (`idDay`);

--
-- Indexes for table `visit`
--
ALTER TABLE `visit`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idStudent` (`idStudent`),
  ADD KEY `idAccommodation` (`idAccommodation`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `accommodation`
--
ALTER TABLE `accommodation`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `address`
--
ALTER TABLE `address`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `day`
--
ALTER TABLE `day`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `owner`
--
ALTER TABLE `owner`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `photo`
--
ALTER TABLE `photo`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `reservation`
--
ALTER TABLE `reservation`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `review`
--
ALTER TABLE `review`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=25;

--
-- AUTO_INCREMENT for table `student`
--
ALTER TABLE `student`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `supportrequest`
--
ALTER TABLE `supportrequest`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `time`
--
ALTER TABLE `time`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `visit`
--
ALTER TABLE `visit`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `accommodation`
--
ALTER TABLE `accommodation`
  ADD CONSTRAINT `accommodation_ibfk_6` FOREIGN KEY (`address`) REFERENCES `address` (`id`),
  ADD CONSTRAINT `owner` FOREIGN KEY (`idOwner`) REFERENCES `owner` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `accommodationreview`
--
ALTER TABLE `accommodationreview`
  ADD CONSTRAINT `accommodationreview_ibfk_1` FOREIGN KEY (`idAccommodation`) REFERENCES `accommodation` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `accommodationreview_ibfk_2` FOREIGN KEY (`idAuthor`) REFERENCES `student` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `accommodationreview_ibfk_3` FOREIGN KEY (`idReview`) REFERENCES `review` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `contract`
--
ALTER TABLE `contract`
  ADD CONSTRAINT `contract_ibfk_1` FOREIGN KEY (`idReservation`) REFERENCES `reservation` (`id`),
  ADD CONSTRAINT `contract_ibfk_2` FOREIGN KEY (`cardNumber`) REFERENCES `creditcard` (`number`);

--
-- Constraints for table `creditcard`
--
ALTER TABLE `creditcard`
  ADD CONSTRAINT `creditcard_ibfk_1` FOREIGN KEY (`idStudent`) REFERENCES `student` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `day`
--
ALTER TABLE `day`
  ADD CONSTRAINT `accommodation` FOREIGN KEY (`idAccommodation`) REFERENCES `accommodation` (`id`);

--
-- Constraints for table `owner`
--
ALTER TABLE `owner`
  ADD CONSTRAINT `owner_ibfk_1` FOREIGN KEY (`picture`) REFERENCES `photo` (`id`) ON DELETE SET NULL;

--
-- Constraints for table `ownerreview`
--
ALTER TABLE `ownerreview`
  ADD CONSTRAINT `ownerreview_ibfk_1` FOREIGN KEY (`idAuthor`) REFERENCES `student` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `ownerreview_ibfk_2` FOREIGN KEY (`idOwner`) REFERENCES `owner` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `ownerreview_ibfk_3` FOREIGN KEY (`idReview`) REFERENCES `review` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `photo`
--
ALTER TABLE `photo`
  ADD CONSTRAINT `photo_ibfk_1` FOREIGN KEY (`idAccommodation`) REFERENCES `accommodation` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `photo_ibfk_2` FOREIGN KEY (`idReview`) REFERENCES `review` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `reservation`
--
ALTER TABLE `reservation`
  ADD CONSTRAINT `reservation_ibfk_1` FOREIGN KEY (`idAccommodation`) REFERENCES `accommodation` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `reservation_ibfk_2` FOREIGN KEY (`idStudent`) REFERENCES `student` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `student`
--
ALTER TABLE `student`
  ADD CONSTRAINT `student_ibfk_1` FOREIGN KEY (`picture`) REFERENCES `photo` (`id`) ON DELETE SET NULL;

--
-- Constraints for table `studentreview`
--
ALTER TABLE `studentreview`
  ADD CONSTRAINT `studentreview_ibfk_1` FOREIGN KEY (`idStudent`) REFERENCES `student` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `studentreview_ibfk_2` FOREIGN KEY (`idReview`) REFERENCES `review` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `studentreview_ibfk_3` FOREIGN KEY (`authorStudent`) REFERENCES `student` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `studentreview_ibfk_4` FOREIGN KEY (`authorOwner`) REFERENCES `owner` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `supportrequest`
--
ALTER TABLE `supportrequest`
  ADD CONSTRAINT `supportrequest_ibfk_1` FOREIGN KEY (`idOwner`) REFERENCES `owner` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `supportrequest_ibfk_2` FOREIGN KEY (`idStudent`) REFERENCES `student` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `time`
--
ALTER TABLE `time`
  ADD CONSTRAINT `time_ibfk_1` FOREIGN KEY (`idDay`) REFERENCES `day` (`id`);

--
-- Constraints for table `visit`
--
ALTER TABLE `visit`
  ADD CONSTRAINT `visit_ibfk_1` FOREIGN KEY (`idStudent`) REFERENCES `student` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `visit_ibfk_2` FOREIGN KEY (`idAccommodation`) REFERENCES `accommodation` (`id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
