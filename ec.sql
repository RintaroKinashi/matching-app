-- phpMyAdmin SQL Dump
-- version 5.1.1
-- https://www.phpmyadmin.net/
--
-- ホスト: 127.0.0.1
-- 生成日時: 2021-08-02 05:09:39
-- サーバのバージョン： 10.4.19-MariaDB
-- PHP のバージョン: 8.0.7

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- データベース: `ec`
--

-- --------------------------------------------------------

--
-- テーブルの構造 `m_question`
--

CREATE TABLE `m_question` (
  `questionID` int(20) UNSIGNED NOT NULL,
  `sentence` text NOT NULL,
  `Choice1` text NOT NULL,
  `Choice2` text NOT NULL,
  `Choice3` text NOT NULL,
  `Choice4` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- テーブルのデータのダンプ `m_question`
--

INSERT INTO `m_question` (`questionID`, `sentence`, `Choice1`, `Choice2`, `Choice3`, `Choice4`) VALUES
(1, '一番好きなものを選択してください。', '音楽', 'ゲーム', '酒', '職場'),
(2, '一番好きなものを選択してください。', '大麦若葉', 'ケール', 'キャベツ', 'モロヘイヤ'),
(3, '一番好きなものを選択してください。', '犬', 'お金', '青汁', 'ゲーム'),
(4, '好きな場所は？', 'にぎやかな都会', '山のほとりのロッジ', '海の見える家', 'その他');

-- --------------------------------------------------------

--
-- テーブルの構造 `r_answer`
--

CREATE TABLE `r_answer` (
  `UserID` varchar(256) NOT NULL,
  `q1` int(2) NOT NULL,
  `q2` int(2) NOT NULL,
  `q3` int(2) NOT NULL,
  `q4` int(2) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- テーブルのデータのダンプ `r_answer`
--

INSERT INTO `r_answer` (`UserID`, `q1`, `q2`, `q3`, `q4`) VALUES
('asahi003003', 3, 0, 3, 2),
('jiru003003', 1, 1, 3, 0),
('naganohara003', 0, 2, 1, 0),
('nyu003003', 0, 0, 1, 2),
('sen003003', 0, 0, 2, 0),
('minakami003', 2, 2, 0, 0),
('ori003003', 0, 1, 2, 2),
('kinashi003003', 2, 2, 0, 1),
('hirabaru', 1, 0, 1, 0);

-- --------------------------------------------------------

--
-- テーブルの構造 `r_request`
--

CREATE TABLE `r_request` (
  `MEMBER_ID` varchar(30) NOT NULL,
  `REQUEST_USERID` varchar(30) NOT NULL,
  `REQUEST_TIME` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- テーブルのデータのダンプ `r_request`
--

INSERT INTO `r_request` (`MEMBER_ID`, `REQUEST_USERID`, `REQUEST_TIME`) VALUES
('kinashi003003', 'minakami003', '2021-07-28 03:22:15'),
('kinashi003003', 'minakami003', '2021-07-28 03:22:30'),
('kinashi003003', 'minakami003', '2021-07-28 03:24:00'),
('kinashi003003', 'minakami003', '2021-07-28 03:24:03'),
('kinashi003003', 'minakami003', '2021-07-28 03:24:27'),
('kinashi003003', 'minakami003', '2021-07-28 03:27:03'),
('kinashi003003', 'minakami003', '2021-07-28 03:27:07'),
('kinashi003003', 'minakami003', '2021-07-28 03:27:26'),
('kinashi003003', 'minakami003', '2021-07-28 03:31:14'),
('kinashi003003', 'minakami003', '2021-07-28 03:32:43'),
('kinashi003003', 'minakami003', '2021-07-28 03:33:18'),
('kinashi003003', 'minakami003', '2021-07-28 03:38:17'),
('kinashi003003', 'minakami003', '2021-07-28 03:38:57'),
('kinashi003003', 'minakami003', '2021-07-28 03:39:38');

-- --------------------------------------------------------

--
-- テーブルの構造 `user`
--

CREATE TABLE `user` (
  `UserID` varchar(30) NOT NULL,
  `password` varchar(256) NOT NULL,
  `name` varchar(30) NOT NULL,
  `sex` int(1) NOT NULL,
  `address` varchar(30) NOT NULL,
  `age` int(11) NOT NULL,
  `height` int(11) DEFAULT NULL,
  `weight` int(11) DEFAULT NULL,
  `comment` varchar(256) DEFAULT NULL,
  `image` varchar(256) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- テーブルのデータのダンプ `user`
--

INSERT INTO `user` (`UserID`, `password`, `name`, `sex`, `address`, `age`, `height`, `weight`, `comment`, `image`) VALUES
('asahi003003', '$2y$10$jjtniRmLz9JuoyS3c9vfD.lqUEH9kblP4G5Pu5UZwfzVTimjxNaR6', '麦若丸', 0, '麦若丸の里', 16, 50, 5, 'まつ毛長いから勝手に女設定\r\n修行中なのだぁぁ\r\n男いなすぎて性別変更ぅぅ\r\nよろしくね!', 'img/50903853760f12feec9d3a4.08947752.jpg'),
('jiru003003', '$2y$10$laE2x7bX9h9CljUHPJPDwuH5Of5jwAX89A4rQcCULI68Su2bJCHCm', 'ジル―', 0, '麦若丸の里', 25, 59, 49, '日本語で書くと汁ってんだぜ\r\nよろしくな', 'img/68064679760f1330cd075a5.50875072.PNG'),
('naganohara003', '$2y$10$M5hqT1.xIw5IdEc0gflABOcj7gcLRE3C.SQJ2h9PDPGiAJPT/0C1C', '長野原', 1, 'nichijo', 16, 156, 49, '許してヒヤシンス', 'img/noimage.jpg'),
('nyu003003', '$2y$10$5CcvwE6n5ImOYDSjtPIsgO8yAwjUl8uUyaOZFopU6Su6J6nXm9Qni', 'ニューちゃん', 1, '麦若丸の村', 14, 46, 12, '腸内環境を整えまっせ\r\nにゅうさんと呼んでくださいまし', 'img/117889603460f6351ac04de0.61794872.jpg'),
('sen003003', '$2y$10$r5uSO/V0tduDtIVKgn6bDeAQINuHqjkCqOxmVJoIKCPRwY6drJaTu', 'せん', 1, '麦若丸の村', 22, 63, 26, '腸内環境を整える食物繊維だから\r\n掃除好きっていう設定ね。なるほど', 'img/210806600360f646cd9efc69.22585126.PNG'),
('minakami003', '$2y$10$9.vOYAZtNaC/5BD15xakTOFfaqGGFf06suEzKApumZThmZJphoVTm', '水上', 1, 'nichijo', 16, 160, 53, 'ふっかつのじゅもん', 'img/noimage.jpg'),
('ori003003', '$2y$10$LS2DFq6g8sxoWNho/nbjlOo6Dky/V7ImA2LL2u0JEgRUJ3.R34mIe', 'おり', 0, '麦若丸の里', 26, 68, 70, 'オリゴ糖の妖精でさぁ', 'img/80813274360f64b71e392b9.10224789.PNG'),
('kinashi003003', '$2y$10$wJ0Bh8G78JOAiItoIPqobun8qhPGAl6GoQnfJojhmvyQgE8Ns.TOa', 'キナシ', 0, '福岡市', 24, 180, 68, 'きなっしー', 'img/noimage.jpg'),
('hirabaru', '$2y$10$pHAcC9oy2qD/ZjiQR7dz1O9VhNjTNqFaVMeF8.2T3AE4.aJUqJyFi', 'ゆういち', 0, '', 24, 0, 0, '', 'img/noimage.jpg');

--
-- ダンプしたテーブルのインデックス
--

--
-- テーブルのインデックス `m_question`
--
ALTER TABLE `m_question`
  ADD UNIQUE KEY `questionID` (`questionID`);

--
-- ダンプしたテーブルの AUTO_INCREMENT
--

--
-- テーブルの AUTO_INCREMENT `m_question`
--
ALTER TABLE `m_question`
  MODIFY `questionID` int(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
