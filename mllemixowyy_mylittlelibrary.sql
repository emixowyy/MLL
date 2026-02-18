-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: localhost
-- Generation Time: Feb 18, 2026 at 11:21 PM
-- Wersja serwera: 10.11.14-MariaDB-0+deb12u2
-- Wersja PHP: 7.3.33-24+0~20241224.123+debian12~1.gbp64cad4

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `mllemixowyy_mylittlelibrary`
--

-- --------------------------------------------------------

--
-- Struktura tabeli dla tabeli `authors`
--

CREATE TABLE `authors` (
  `aid` bigint(20) NOT NULL,
  `name` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `authors`
--

INSERT INTO `authors` (`aid`, `name`) VALUES
(4, 'Amit Tayal'),
(7, 'Jacek Winkler'),
(6, 'Jakub Danowski'),
(5, 'L Frank Baum'),
(2, 'L. Frank Baum'),
(3, 'L. Frank L. Frank Baum'),
(1, 'Lyman Frank Baum'),
(8, 'Triumph Books');

-- --------------------------------------------------------

--
-- Struktura tabeli dla tabeli `books`
--

CREATE TABLE `books` (
  `bid` bigint(20) NOT NULL,
  `aid` bigint(20) NOT NULL,
  `isbn` varchar(50) NOT NULL,
  `title` varchar(255) NOT NULL,
  `pub_date` varchar(12) NOT NULL,
  `b_desc` text DEFAULT NULL,
  `categories` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`categories`)),
  `maturity` varchar(12) DEFAULT 'NONE',
  `rating` int(2) DEFAULT NULL,
  `language` varchar(3) DEFAULT NULL,
  `thumbnail` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `books`
--

INSERT INTO `books` (`bid`, `aid`, `isbn`, `title`, `pub_date`, `b_desc`, `categories`, `maturity`, `rating`, `language`, `thumbnail`) VALUES
(1, 1, '9781905716524', 'The Wizard of Oz', '2009', '.0000000000This is the story of Dorothy and her little dog Toto, who are carried away from Kansas by a cyclone and transported to the wonderful world of Oz. She meets three companions - the Scarecrow, the Tin Woodman and the Cowardly Lion - and the three journey to the Emerald City of Oz to ask the Wizard of Oz to give them their hearts\' desires, which in Dorothy\'s case is to return home to Kansas. On their way to Oz and while fulfilling the tasks that the surprising Wizard asks of them they encounter witches, winged monkeys, the Deadly Desert, fighting trees and magic shoes.This edition is evocatively illustrated with the original drawings of W. W. Denslow, with an Afterword by Ned Halley.', '[\"Fiction\"]', 'NOT_MATURE', 5, 'en', 'http://books.google.com/books/content?id=hRd7dJ-9G1IC&printsec=frontcover&img=1&zoom=1&edge=curl&source=gbs_api'),
(2, 2, '9781515416203', 'Dorothy and the Wizard in Oz', '2017-01-29', 'Lyman Frank Baum penned fourteen novels in his famous Oz chronology. The fourth, Dorothy and the Wizard in Oz, was published in 1908 and introduced readers to Dorothy Gale’s second cousin, Zeb, as well as to Eureka the cat, and Jim the Cab-horse. This volume of Original Oz Stories is formatted not only for ease of reading, but to emulate the textual structure of that original publication.', '[\"Juvenile Fiction\"]', 'NOT_MATURE', NULL, 'en', 'http://books.google.com/books/content?id=poycEQAAQBAJ&printsec=frontcover&img=1&zoom=1&edge=curl&source=gbs_api'),
(3, 3, '9781521915899', 'Dorothy and the Wizard in OZ', '2017-07-23', 'How is this book unique? Font adjustments & biography included Unabridged (100% Original content) Illustrated About Dorothy And The Wizard In OZ by L. Frank Baum Dorothy and the Wizard in Oz is the fourth book set in the Land of Oz written by L. Frank Baum and illustrated by John R. Neill. It was published on June 18, 1908 and reunites Dorothy with the humbug Wizard from The Wonderful Wizard of Oz. This is one of only two of the original fourteen Oz books (the other being The Emerald City of Oz) to be illustrated with watercolor paintings.', NULL, 'NOT_MATURE', NULL, 'en', NULL),
(4, 4, '9781789898781', 'The Wizard of Oz', '2023', NULL, NULL, 'NOT_MATURE', NULL, 'en', 'http://books.google.com/books/content?id=xdBf0QEACAAJ&printsec=frontcover&img=1&zoom=1&source=gbs_api'),
(5, 5, '9798687851855', 'Dorothy and the Wizard in Oz Illustrated', '2020-09-19', 'Dorothy and the Wizard in Oz is the fourth book set in the Land of Oz written by L. Frank Baum and illustrated by John R. Neill. It was published on June 18, 1908 and reunites Dorothy with the humbug Wizard from The Wonderful Wizard of Oz (1900). This is one of only two of the original fourteen Oz books (the other being The Emerald City of Oz (1910), to be illustrated with watercolor paintings.', NULL, 'NOT_MATURE', NULL, 'en', 'http://books.google.com/books/content?id=9pzYzQEACAAJ&printsec=frontcover&img=1&zoom=1&source=gbs_api'),
(6, 6, '9788324698523', 'Minecraft. Crafting, czary i świetna zabawa (demo)', '2014-06-13', 'Minecraft to niezależna gra komputerowa, która mimo stosunkowo ubogiej szaty graficznej przebojem wdarła się na rynek i w bardzo krótkim czasie podbiła serca milinów użytkowników komputerów, smartfonów i konsol. Minecraft zawdzięcza swoją popularność między innymi powszechnej dostępności na różnych platformach systemowych, najważniejszymi i najbardziej docenianymi jej cechami są ponadprzeciętna grywalność, niemal całkowity brak ograniczeń narzucanych przez środowisko oraz niesamowita elastyczność zabawy, którą bez trudu można odpowiednio dopasować do wieku, predyspozycji i preferencji gracza. Jeśli chcesz szybko rozpocząć swoją przygodę z Minecraftem, dowiedzieć się jak zainstalować i skonfigurować grę, a także sprawnie się w niej poruszać, sięgnij po książkę „Minecraft. Crafting, czary i świetna zabawa”. Krok po kroku wprowadzi Cię ona w świat najpopularniejszej gry ostatnich lat, przedstawiając sposoby prowadzenia rozgrywki, od podstaw ucząc budowania własnego świata, przedstawiając przydatne skróty i wskazówki oraz doradzając, jak rozbudować środowisko za pomocą modyfikacji, skórek i zasobów. Lektura pomoże Ci rozwinąć Twoje umiejętności, zdobyć niezbędne doświadczenie i zaangażować się w zaawansowane rozgrywki z innymi użytkownikami. Jeśli chcesz zostać świadomym graczem, trudno o lepszy wybór! - Pobieranie, instalacja i konfiguracja Maincrafta - Korzystanie z wersji darmowej i zakup płatnej - Tryby rozgrywki, podstawy nawigacji i elementy gry - Rozszerzenia oferowane przez modyfikacje, skórki i paczki zasobów - Przegląd dostępnych komend i poleceń - Gra w trybie jedno- i wieloosobowym - Porady i wskazówki doświadczonych graczy', '[\"Games & Activities\"]', 'NOT_MATURE', NULL, 'pl', 'http://books.google.com/books/content?id=Y3sZEQAAQBAJ&printsec=frontcover&img=1&zoom=1&edge=curl&source=gbs_api'),
(7, 7, '9785042742989', 'Minecraft: Story Mode', '2020-08-04', 'Minecraft: Story Mode – A Block and a Hard Place to czwarty już odcinek nowej przygodówki od studia Telltale Games. Niniejszy poradnik pomoże ci przebrnąć przez główną historię opowiedzianą w grze. Czwarty epizod zawiera pełen opis przejścia wraz z wyszczególnionymi ważnymi wyborami oraz rozwiązaniem zagadek logicznych. W pierwszym rozdziale poradnika znajdziesz część główną czyli szczegółowy opis przejścia. Rozdział ten zawiera także tłumaczenie części dialogów na język polski, w taki sposób, byś mógł zrozumieć przedstawiany ci obraz danej sytuacji. Kolejny rozdział zawiera ważne wybory. Znajdziesz tu ich dokładne omówienie oraz wyszczególnione ewentualne konsekwencje i różnice między nimi, które mogą mieć duże znaczenie w przyszłych przygodach. W czwartym epizodzie Minecrafta: Story Mode gra skupi się na dalszym budowaniu fabuły i zostanie w nim rozwiązana część ważnych wyborów z poprzednich epizodów. Czwarty epizod prezentuje także apogeum historii związanej z Wither Stormem i przedstawionymi bohaterami. Poradnik do Minecraft: Story Mode – A Block and a Hard Place zawiera: Szczegółowy opis przejścia wraz z tłumaczeniem części dialogów na język polski Rozwiązanie ewentualnych zagadek logicznych Omówienie ważnych wyborów oraz ich ewentualnych konsekwencji Niniejszy poradnik dotyczy czwartego epizodu gry Minecraft: Story Mode – A Block and a Hard Place. Tekst zawiera dokładny opis przejścia wszystkich rozdziałów i skupia się na dostępnych wyborach oraz ich konsekwencjach. Minecraft: Story Mode – A Block and a Hard Place – poradnik do gry zawiera poszukiwane przez graczy tematy i lokacje jak m.in. Ważne wybory Rozdział 6 Rozdział 5 Rozdział 4 Rozdział 3 Rozdział 2 Rozdział 1', '[\"Computers\"]', 'NOT_MATURE', NULL, 'pl', 'http://books.google.com/books/content?id=UikREAAAQBAJ&printsec=frontcover&img=1&zoom=1&edge=curl&source=gbs_api'),
(8, 8, '9781623688851', 'Minecrafter 2.0 Advanced', '2014-04-01', 'One of the most popular video games of all time, Minecraft has become a global craze thanks to nearly 40 million registered users worldwide across all platforms. In Minecrafter 2.0 Advanced, those who mastered the basics laid out in the first Minecrafter strategy guide now find tips on more complex areas of game play, including Redstone circuitry and other inventions, and advice for beating \"The End.\" This must-have guide for even the most advanced of experts includes the game\'s latest innovations and features 100 color images. This book is not authorized, sponsored, endorsed or licensed by Mojang AB. The trademark Minecraft is owned by Mojang AB; and other company names and/or trademarks mentioned in this book are the property of their respective companies and are used for identification purposes only.', '[\"Games & Activities\"]', 'NOT_MATURE', NULL, 'en', 'http://books.google.com/books/content?id=jP62EQAAQBAJ&printsec=frontcover&img=1&zoom=1&edge=curl&source=gbs_api');

-- --------------------------------------------------------

--
-- Struktura tabeli dla tabeli `users`
--

CREATE TABLE `users` (
  `uid` bigint(20) NOT NULL,
  `username` varchar(50) NOT NULL,
  `email` varchar(255) NOT NULL,
  `pass_hash` varchar(255) NOT NULL,
  `is_active` tinyint(1) DEFAULT 1,
  `created_at` timestamp NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`uid`, `username`, `email`, `pass_hash`, `is_active`, `created_at`) VALUES
(1, 'john', 'john@example.com', '$2y$10$aaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaa', 1, '2026-02-02 15:35:53'),
(2, 'emma', 'emma@example.com', '$2y$10$bbbbbbbbbbbbbbbbbbbbbbbbbbbbbbbbbbbbbbbbbbbbbbbb', 1, '2026-02-02 15:35:53'),
(3, 'michael', 'michael@example.com', '$2y$10$cccccccccccccccccccccccccccccccccccccccccccccccc', 1, '2026-02-02 15:35:53'),
(4, 'sarah', 'sarah@example.com', '$2y$10$dddddddddddddddddddddddddddddddddddddddddddddddd', 1, '2026-02-02 15:35:53'),
(5, 'david', 'david@example.com', '$2y$10$eeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeee', 1, '2026-02-02 15:35:53'),
(6, 'john_doe', 'john.doe@example.com', '$2y$12$Yqzu69OXowE7zakhtIu0EeXWJUDkZLlPZ.i3HVB4wcP6d.Xfrt26O', 1, '2026-02-04 15:28:44'),
(10, 'Krzysztof_Komarnowy', 'Krzysztofkomarofficialnowe@proton.com', '$2y$12$rqFQODK7kQ9KcJgWgd3bt.jCSHBXEdH3uUozpJabyxWC7PTfitbdu', 1, '2026-02-05 12:12:45'),
(11, 'Krzysztof_Komar', 'Krzysztofkomarofficial@proton.com', '$2y$12$dd8S0sw1GnXR8hWPIk/OPekzCfDgVrKtwana635LXKmCoeUJKbSVW', 1, '2026-02-05 12:32:30');

-- --------------------------------------------------------

--
-- Struktura tabeli dla tabeli `user_books`
--

CREATE TABLE `user_books` (
  `id` bigint(20) NOT NULL,
  `uid` bigint(20) NOT NULL,
  `bid` bigint(20) NOT NULL,
  `status` enum('planned','reading','finished','dropped') NOT NULL,
  `rating` int(2) DEFAULT NULL,
  `review` text DEFAULT NULL,
  `review_pub` tinyint(1) DEFAULT 0,
  `start_date` date DEFAULT NULL,
  `end_date` date DEFAULT NULL,
  `pages_read` int(11) DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `user_books`
--

INSERT INTO `user_books` (`id`, `uid`, `bid`, `status`, `rating`, `review`, `review_pub`, `start_date`, `end_date`, `pages_read`) VALUES
(2, 6, 7, 'finished', 1, 'chujowe', 1, '2026-01-01', '2026-02-08', 407);

-- --------------------------------------------------------

--
-- Struktura tabeli dla tabeli `user_logs`
--

CREATE TABLE `user_logs` (
  `id` bigint(20) NOT NULL,
  `uid` bigint(20) NOT NULL,
  `login_time` datetime NOT NULL DEFAULT current_timestamp(),
  `ip_address` varchar(45) DEFAULT NULL,
  `user_agent` text DEFAULT NULL,
  `action` varchar(3) NOT NULL,
  `fail_reason` varchar(15) DEFAULT NULL,
  `success` tinyint(1) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `user_logs`
--

INSERT INTO `user_logs` (`id`, `uid`, `login_time`, `ip_address`, `user_agent`, `action`, `fail_reason`, `success`) VALUES
(1, 6, '2026-02-16 14:13:15', '127.0.0.1', 'PostmanRuntime/7.51.1', '', NULL, 0),
(2, 6, '2026-02-16 14:13:37', '127.0.0.1', 'PostmanRuntime/7.51.1', '', NULL, 1);

-- --------------------------------------------------------

--
-- Struktura tabeli dla tabeli `user_refresh_tokens`
--

CREATE TABLE `user_refresh_tokens` (
  `id` bigint(20) NOT NULL,
  `uid` bigint(20) NOT NULL,
  `token` char(64) NOT NULL,
  `created_at` datetime DEFAULT current_timestamp(),
  `expires_at` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `user_refresh_tokens`
--

INSERT INTO `user_refresh_tokens` (`id`, `uid`, `token`, `created_at`, `expires_at`) VALUES
(1, 6, '2bc80abb0110775d49e70840d57852f97e7ca8560a737bc59a951a19979b4144', '2026-02-16 14:13:37', '2026-03-02 14:13:37');

-- --------------------------------------------------------

--
-- Struktura tabeli dla tabeli `user_sessions`
--

CREATE TABLE `user_sessions` (
  `id` bigint(20) NOT NULL,
  `uid` bigint(20) NOT NULL,
  `token` char(64) NOT NULL,
  `created_at` datetime NOT NULL DEFAULT current_timestamp(),
  `expires_at` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `user_sessions`
--

INSERT INTO `user_sessions` (`id`, `uid`, `token`, `created_at`, `expires_at`) VALUES
(2, 6, '89afaf97e6dfc02b423eab6b94000b8533d880d26ceb082744055c758a83e657', '2026-02-18 22:35:26', '2026-02-19 22:35:26');

--
-- Indeksy dla zrzutów tabel
--

--
-- Indeksy dla tabeli `authors`
--
ALTER TABLE `authors`
  ADD PRIMARY KEY (`aid`),
  ADD UNIQUE KEY `name` (`name`);

--
-- Indeksy dla tabeli `books`
--
ALTER TABLE `books`
  ADD PRIMARY KEY (`bid`),
  ADD UNIQUE KEY `unique_author_title` (`aid`,`title`);

--
-- Indeksy dla tabeli `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`uid`),
  ADD UNIQUE KEY `username` (`username`),
  ADD UNIQUE KEY `email` (`email`);

--
-- Indeksy dla tabeli `user_books`
--
ALTER TABLE `user_books`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_books_uid` (`uid`),
  ADD KEY `user_books_bid` (`bid`);

--
-- Indeksy dla tabeli `user_logs`
--
ALTER TABLE `user_logs`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_logins_uid` (`uid`);

--
-- Indeksy dla tabeli `user_refresh_tokens`
--
ALTER TABLE `user_refresh_tokens`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `token` (`token`),
  ADD UNIQUE KEY `uid` (`uid`);

--
-- Indeksy dla tabeli `user_sessions`
--
ALTER TABLE `user_sessions`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `token` (`token`),
  ADD KEY `user_sessions_uid` (`uid`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `authors`
--
ALTER TABLE `authors`
  MODIFY `aid` bigint(20) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT for table `books`
--
ALTER TABLE `books`
  MODIFY `bid` bigint(20) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `uid` bigint(20) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

--
-- AUTO_INCREMENT for table `user_books`
--
ALTER TABLE `user_books`
  MODIFY `id` bigint(20) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `user_logs`
--
ALTER TABLE `user_logs`
  MODIFY `id` bigint(20) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `user_refresh_tokens`
--
ALTER TABLE `user_refresh_tokens`
  MODIFY `id` bigint(20) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `user_sessions`
--
ALTER TABLE `user_sessions`
  MODIFY `id` bigint(20) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `books`
--
ALTER TABLE `books`
  ADD CONSTRAINT `books_aid` FOREIGN KEY (`aid`) REFERENCES `authors` (`aid`) ON DELETE CASCADE;

--
-- Constraints for table `user_books`
--
ALTER TABLE `user_books`
  ADD CONSTRAINT `user_books_bid` FOREIGN KEY (`bid`) REFERENCES `books` (`bid`) ON DELETE CASCADE,
  ADD CONSTRAINT `user_books_uid` FOREIGN KEY (`uid`) REFERENCES `users` (`uid`) ON DELETE CASCADE;

--
-- Constraints for table `user_refresh_tokens`
--
ALTER TABLE `user_refresh_tokens`
  ADD CONSTRAINT `user_refresh_tokens_uid` FOREIGN KEY (`uid`) REFERENCES `users` (`uid`) ON DELETE CASCADE;

--
-- Constraints for table `user_sessions`
--
ALTER TABLE `user_sessions`
  ADD CONSTRAINT `user_sessions_uid` FOREIGN KEY (`uid`) REFERENCES `users` (`uid`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
