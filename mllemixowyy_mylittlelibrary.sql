-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: localhost
-- Generation Time: Feb 04, 2026 at 01:02 PM
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
  `aid` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `birth_date` date NOT NULL,
  `death_date` date DEFAULT NULL,
  `a_desc` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `authors`
--

INSERT INTO `authors` (`aid`, `name`, `birth_date`, `death_date`, `a_desc`) VALUES
(1, 'George Orwell', '1903-06-25', '1950-01-21', 'English writer and journalist, known for dystopian novels.'),
(2, 'Jane Austen', '1775-12-16', '1817-07-18', 'English novelist known for her social commentary and romance novels.'),
(3, 'J.R.R. Tolkien', '1892-01-03', '1973-09-02', 'English writer, philologist, and author of high fantasy works.'),
(4, 'Fyodor Dostoevsky', '1821-11-11', '1881-02-09', 'Russian novelist, philosopher, and journalist.'),
(5, 'Ernest Hemingway', '1899-07-21', '1961-07-02', 'American novelist and short-story writer.');

-- --------------------------------------------------------

--
-- Struktura tabeli dla tabeli `books`
--

CREATE TABLE `books` (
  `bid` int(11) NOT NULL,
  `aid` int(11) NOT NULL,
  `title` varchar(255) NOT NULL,
  `pub_date` date NOT NULL,
  `b_desc` text DEFAULT NULL,
  `categories` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`categories`)),
  `maturity` enum('NONE','PG','PG-13','18+') DEFAULT 'NONE',
  `thumbnail` varchar(255) DEFAULT NULL,
  `cover` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `books`
--

INSERT INTO `books` (`bid`, `aid`, `title`, `pub_date`, `b_desc`, `categories`, `maturity`, `thumbnail`, `cover`) VALUES
(1, 1, '1984', '1949-06-08', 'A dystopian novel about totalitarianism, surveillance, and loss of freedom.', '[\"Dystopian\",\"Political fiction\"]', 'PG-13', NULL, NULL),
(2, 2, 'Pride and Prejudice', '1813-01-28', 'A romantic novel that critiques the British landed gentry of the early 19th century.', '[\"Romance\",\"Classic\"]', 'NONE', NULL, NULL),
(3, 3, 'The Hobbit', '1937-09-21', 'A fantasy novel about the journey of Bilbo Baggins.', '[\"Fantasy\",\"Adventure\"]', 'PG', NULL, NULL),
(4, 4, 'Crime and Punishment', '1866-01-01', 'A psychological novel exploring guilt, morality, and redemption.', '[\"Classic\",\"Psychological fiction\"]', 'PG-13', NULL, NULL),
(5, 5, 'The Old Man and the Sea', '1952-09-01', 'A short novel about an aging fisherman struggling with a giant marlin.', '[\"Classic\",\"Literary fiction\"]', 'NONE', NULL, NULL);

-- --------------------------------------------------------

--
-- Struktura tabeli dla tabeli `users`
--

CREATE TABLE `users` (
  `uid` int(11) NOT NULL,
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
(5, 'david', 'david@example.com', '$2y$10$eeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeee', 1, '2026-02-02 15:35:53');

-- --------------------------------------------------------

--
-- Struktura tabeli dla tabeli `user_books`
--

CREATE TABLE `user_books` (
  `uid` int(11) NOT NULL,
  `bid` int(11) NOT NULL,
  `status` enum('planned','reading','finished','dropped') NOT NULL,
  `rating` tinyint(3) UNSIGNED DEFAULT NULL CHECK (`rating` between 1 and 10),
  `review` text DEFAULT NULL,
  `review_pub` tinyint(1) DEFAULT 0,
  `start_date` date DEFAULT NULL,
  `end_date` date DEFAULT NULL,
  `pages_read` int(11) DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `user_books`
--

INSERT INTO `user_books` (`uid`, `bid`, `status`, `rating`, `review`, `review_pub`, `start_date`, `end_date`, `pages_read`) VALUES
(1, 1, 'finished', 9, 'A powerful and disturbing vision of the future.', 1, '2025-01-01', '2025-01-10', 328),
(1, 3, 'finished', 10, 'An amazing adventure, timeless and magical.', 1, '2025-02-01', '2025-02-14', 310),
(2, 2, 'reading', NULL, NULL, 0, '2025-03-01', NULL, 120),
(2, 5, 'planned', NULL, NULL, 0, NULL, NULL, 0),
(3, 4, 'finished', 8, 'Deep and challenging, but very rewarding.', 1, '2025-01-15', '2025-02-20', 430);

--
-- Indeksy dla zrzutów tabel
--

--
-- Indeksy dla tabeli `authors`
--
ALTER TABLE `authors`
  ADD PRIMARY KEY (`aid`);

--
-- Indeksy dla tabeli `books`
--
ALTER TABLE `books`
  ADD PRIMARY KEY (`bid`),
  ADD KEY `fk_books_author` (`aid`);

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
  ADD PRIMARY KEY (`uid`,`bid`),
  ADD KEY `fk_userbooks_book` (`bid`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `authors`
--
ALTER TABLE `authors`
  MODIFY `aid` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `books`
--
ALTER TABLE `books`
  MODIFY `bid` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `uid` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `books`
--
ALTER TABLE `books`
  ADD CONSTRAINT `fk_books_author` FOREIGN KEY (`aid`) REFERENCES `authors` (`aid`) ON DELETE CASCADE;

--
-- Constraints for table `user_books`
--
ALTER TABLE `user_books`
  ADD CONSTRAINT `fk_userbooks_book` FOREIGN KEY (`bid`) REFERENCES `books` (`bid`) ON DELETE CASCADE,
  ADD CONSTRAINT `fk_userbooks_user` FOREIGN KEY (`uid`) REFERENCES `users` (`uid`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
