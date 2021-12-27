-- phpMyAdmin SQL Dump
-- version 5.1.1
-- https://www.phpmyadmin.net/
--
-- ホスト: 127.0.0.1
-- 生成日時: 2021-12-27 05:10:15
-- サーバのバージョン： 10.4.22-MariaDB
-- PHP のバージョン: 7.4.26

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- データベース: `kinmu`
--
DROP DATABASE IF EXISTS `kinmu`;
CREATE DATABASE IF NOT EXISTS `kinmu` DEFAULT CHARACTER SET utf8 COLLATE utf8_general_ci;
USE `kinmu`;

-- --------------------------------------------------------

--
-- テーブルの構造 `faces`
--

CREATE TABLE `faces` (
  `id` int(11) NOT NULL COMMENT 'id',
  `user_id` int(11) DEFAULT NULL COMMENT 'ユーザID',
  `facepath` varchar(512) NOT NULL COMMENT '顔情報保存場所',
  `created` datetime DEFAULT NULL COMMENT '作成日時',
  `modified` datetime DEFAULT NULL COMMENT '終了日時'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='顔認証情報';

--
-- テーブルのデータのダンプ `faces`
--

INSERT INTO `faces` (`id`, `user_id`, `facepath`, `created`, `modified`) VALUES
(23, 4, '1640570068.8545456.jpg', '2021-12-27 10:54:29', '2021-12-27 10:54:29');

-- --------------------------------------------------------

--
-- テーブルの構造 `facesattendances`
--

CREATE TABLE `facesattendances` (
  `id` int(11) NOT NULL COMMENT 'id',
  `user_id` int(11) DEFAULT NULL COMMENT 'ユーザID',
  `inout_time` datetime NOT NULL COMMENT '入退出',
  `inout_type` tinyint(4) DEFAULT NULL COMMENT '入退出タイプ',
  `created` datetime DEFAULT NULL COMMENT '作成日時',
  `modified` datetime DEFAULT NULL COMMENT '終了日時'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='顔認証入退出';

-- --------------------------------------------------------

--
-- テーブルの構造 `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL COMMENT '主キー',
  `name` varchar(255) NOT NULL COMMENT '氏名',
  `created` datetime DEFAULT NULL COMMENT '作成日時',
  `modified` datetime DEFAULT NULL COMMENT '変更日時'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='ユーザマスタ;';

--
-- テーブルのデータのダンプ `users`
--

INSERT INTO `users` (`id`, `name`, `created`, `modified`) VALUES
(4, '太郎', '2021-12-27 10:51:58', '2021-12-27 10:51:58'),
(5, '次郎', '2021-12-27 10:52:08', '2021-12-27 10:52:08'),
(6, '三郎', '2021-12-27 10:52:25', '2021-12-27 10:52:25');

--
-- ダンプしたテーブルのインデックス
--

--
-- テーブルのインデックス `faces`
--
ALTER TABLE `faces`
  ADD PRIMARY KEY (`id`);

--
-- テーブルのインデックス `facesattendances`
--
ALTER TABLE `facesattendances`
  ADD PRIMARY KEY (`id`);

--
-- テーブルのインデックス `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`);

--
-- ダンプしたテーブルの AUTO_INCREMENT
--

--
-- テーブルの AUTO_INCREMENT `faces`
--
ALTER TABLE `faces`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT COMMENT 'id', AUTO_INCREMENT=24;

--
-- テーブルの AUTO_INCREMENT `facesattendances`
--
ALTER TABLE `facesattendances`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT COMMENT 'id';

--
-- テーブルの AUTO_INCREMENT `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT COMMENT '主キー', AUTO_INCREMENT=7;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
