-- phpMyAdmin SQL Dump
-- version 5.1.1
-- https://www.phpmyadmin.net/
--
-- ホスト: 127.0.0.1
-- 生成日時: 2022-01-03 14:48:17
-- サーバのバージョン： 10.4.22-MariaDB
-- PHP のバージョン: 8.0.13

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
CREATE DATABASE IF NOT EXISTS `kinmu` DEFAULT CHARACTER SET utf8 COLLATE utf8_general_ci;
USE `kinmu`;

-- --------------------------------------------------------

--
-- テーブルの構造 `faces`
--

DROP TABLE IF EXISTS `faces`;
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

INSERT INTO `faces` (`id`, `user_id`, `facepath`, `created`, `modified`) VALUES(40, 4, '1641005921.172242.jpg', '2022-01-01 11:58:50', '2022-01-01 11:58:50');
INSERT INTO `faces` (`id`, `user_id`, `facepath`, `created`, `modified`) VALUES(41, 4, '1641006072.8511364.jpg', '2022-01-01 12:01:13', '2022-01-01 12:01:13');
INSERT INTO `faces` (`id`, `user_id`, `facepath`, `created`, `modified`) VALUES(42, 5, '1641006142.7087317.jpg', '2022-01-01 12:02:23', '2022-01-01 12:02:23');
INSERT INTO `faces` (`id`, `user_id`, `facepath`, `created`, `modified`) VALUES(43, 6, '1641006169.5722003.jpg', '2022-01-01 12:02:49', '2022-01-01 12:02:49');
INSERT INTO `faces` (`id`, `user_id`, `facepath`, `created`, `modified`) VALUES(44, 4, '1641006449.0787706.jpg', '2022-01-01 12:07:29', '2022-01-01 12:07:29');
INSERT INTO `faces` (`id`, `user_id`, `facepath`, `created`, `modified`) VALUES(45, 4, '1641031540.3277164.jpg', '2022-01-01 19:05:40', '2022-01-01 19:05:40');
INSERT INTO `faces` (`id`, `user_id`, `facepath`, `created`, `modified`) VALUES(46, 4, '1641180339.4831147.jpg', '2022-01-03 12:25:48', '2022-01-03 12:25:48');

-- --------------------------------------------------------

--
-- テーブルの構造 `facesattendances`
--

DROP TABLE IF EXISTS `facesattendances`;
CREATE TABLE `facesattendances` (
  `id` int(11) NOT NULL COMMENT 'id',
  `user_id` int(11) DEFAULT NULL COMMENT 'ユーザID',
  `inout_time` datetime NOT NULL COMMENT '入退出',
  `inout_type` tinyint(4) DEFAULT NULL COMMENT '入退出タイプ',
  `created` datetime DEFAULT NULL COMMENT '作成日時',
  `modified` datetime DEFAULT NULL COMMENT '終了日時'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='顔認証入退出';

--
-- テーブルのデータのダンプ `facesattendances`
--

INSERT INTO `facesattendances` (`id`, `user_id`, `inout_time`, `inout_type`, `created`, `modified`) VALUES(1, 4, '2022-01-03 14:34:58', 0, '2022-01-03 14:35:11', '2022-01-03 14:35:11');
INSERT INTO `facesattendances` (`id`, `user_id`, `inout_time`, `inout_type`, `created`, `modified`) VALUES(90, 4, '2022-01-03 22:41:51', 1, NULL, NULL);
INSERT INTO `facesattendances` (`id`, `user_id`, `inout_time`, `inout_type`, `created`, `modified`) VALUES(91, 4, '2022-01-04 22:42:12', 0, NULL, NULL);
INSERT INTO `facesattendances` (`id`, `user_id`, `inout_time`, `inout_type`, `created`, `modified`) VALUES(92, 4, '2022-01-04 22:42:30', 1, NULL, NULL);

-- --------------------------------------------------------

--
-- テーブルの構造 `users`
--

DROP TABLE IF EXISTS `users`;
CREATE TABLE `users` (
  `id` int(11) NOT NULL COMMENT '主キー',
  `username` varchar(255) NOT NULL COMMENT '氏名',
  `email` varchar(255) NOT NULL,
  `password` varchar(255) NOT NULL COMMENT 'パスワード',
  `role` varchar(20) NOT NULL COMMENT 'ロール',
  `created` datetime DEFAULT NULL COMMENT '作成日時',
  `modified` datetime DEFAULT NULL COMMENT '変更日時'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='ユーザマスタ;';

--
-- テーブルのデータのダンプ `users`
--

INSERT INTO `users` (`id`, `username`, `email`, `password`, `role`, `created`, `modified`) VALUES(4, '中里', '', '$2y$10$uzgwtlJgVOQ6JBqTHbljKeZ4BgQbLptBZNWI.WjrcYszCh5TwBDPu', '', '2021-12-27 10:51:58', '2021-12-27 10:51:58');
INSERT INTO `users` (`id`, `username`, `email`, `password`, `role`, `created`, `modified`) VALUES(5, 'ブルースウィルス', '', '', '', '2021-12-27 10:52:08', '2021-12-27 10:52:08');
INSERT INTO `users` (`id`, `username`, `email`, `password`, `role`, `created`, `modified`) VALUES(6, '阿部寛', '', '', '', '2021-12-27 10:52:25', '2021-12-27 10:52:25');
INSERT INTO `users` (`id`, `username`, `email`, `password`, `role`, `created`, `modified`) VALUES(8, 'sys', 'nakaara085@gmail.com', '$2y$10$tbkap6YUjwMb.x2LsLNpNO73JN/YAXWxzS0cn.jbS7HyfiVPWpERG', 'admin', '2022-01-01 20:51:04', '2022-01-01 20:51:04');
INSERT INTO `users` (`id`, `username`, `email`, `password`, `role`, `created`, `modified`) VALUES(9, 'test', 'test@test', '$2y$10$0fM6YMr54tHY.rLY.mT2/OOa7DkiTSQ3hYg.Yap6q11eXlBiiPbYC', 'admin', '2022-01-01 22:04:12', '2022-01-01 22:04:12');
INSERT INTO `users` (`id`, `username`, `email`, `password`, `role`, `created`, `modified`) VALUES(11, '123', '123@123', '$2y$10$uzgwtlJgVOQ6JBqTHbljKeZ4BgQbLptBZNWI.WjrcYszCh5TwBDPu', 'admin', '2022-01-01 22:19:06', '2022-01-01 22:19:06');

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
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT COMMENT 'id', AUTO_INCREMENT=47;

--
-- テーブルの AUTO_INCREMENT `facesattendances`
--
ALTER TABLE `facesattendances`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT COMMENT 'id', AUTO_INCREMENT=93;

--
-- テーブルの AUTO_INCREMENT `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT COMMENT '主キー', AUTO_INCREMENT=12;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
