-- phpMyAdmin SQL Dump
-- version 4.9.1
-- https://www.phpmyadmin.net/
--
-- ホスト: 127.0.0.1
-- 生成日時: 
-- サーバのバージョン： 10.4.8-MariaDB
-- PHP のバージョン: 7.3.10

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- データベース: `games`
--
CREATE DATABASE IF NOT EXISTS `games` DEFAULT CHARACTER SET utf8 COLLATE utf8_general_ci;
USE `games`;

-- --------------------------------------------------------

--
-- テーブルの構造 `messages`
--

CREATE TABLE `messages` (
  `id` int(11) NOT NULL,
  `message` varchar(200) NOT NULL,
  `send_user_id` int(11) NOT NULL,
  `receive_user_id` int(11) NOT NULL,
  `date` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- テーブルのデータのダンプ `messages`
--

INSERT INTO `messages` (`id`, `message`, `send_user_id`, `receive_user_id`, `date`) VALUES
(12, 'Are you kidding...?', 14, 1, '2020-05-16 12:06:28'),
(13, 'You are fuckin\' awesome!!', 15, 1, '2020-05-16 12:08:49'),
(14, '調子はどう？', 4, 1, '2020-05-16 12:09:51');

-- --------------------------------------------------------

--
-- テーブルの構造 `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `name` varchar(10) NOT NULL,
  `email` varchar(50) NOT NULL,
  `password` varchar(4) NOT NULL,
  `janken_bp` int(11) NOT NULL DEFAULT 0,
  `osero_bp` int(11) NOT NULL DEFAULT 0,
  `slot_bp` int(11) NOT NULL DEFAULT 0,
  `memory_bp` int(11) NOT NULL DEFAULT 0,
  `ten_seconds_bp` int(11) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- テーブルのデータのダンプ `users`
--

INSERT INTO `users` (`id`, `name`, `email`, `password`, `janken_bp`, `osero_bp`, `slot_bp`, `memory_bp`, `ten_seconds_bp`) VALUES
(1, 'yusuke', 'southern_cross2047@yahoo.co.jp', '2047', 500, 300, 500, 430, 800),
(4, 'mai', '09@28', '0928', 120000, 120000, 120000, 120000, 120000),
(5, 'harui', '05@14', '0514', 1000000, 1000000, 1000000, 1000000, 1000000),
(14, 'X-2047', 'a@a', '1111', 1230, 2340, 3450, 4560, 5670),
(15, 'Mr.RED', 'b@b', '2222', 5670, 6780, 7890, 89100, 910110);

--
-- ダンプしたテーブルのインデックス
--

--
-- テーブルのインデックス `messages`
--
ALTER TABLE `messages`
  ADD PRIMARY KEY (`id`);

--
-- テーブルのインデックス `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`);

--
-- ダンプしたテーブルのAUTO_INCREMENT
--

--
-- テーブルのAUTO_INCREMENT `messages`
--
ALTER TABLE `messages`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=16;

--
-- テーブルのAUTO_INCREMENT `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=16;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
