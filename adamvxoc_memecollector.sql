-- phpMyAdmin SQL Dump
-- version 4.8.3
-- https://www.phpmyadmin.net/
--
-- Host: localhost:3306
-- Generation Time: Dec 18, 2019 at 12:14 AM
-- Server version: 10.1.43-MariaDB-cll-lve
-- PHP Version: 7.2.7

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `adamvxoc_memecollector`
--

-- --------------------------------------------------------

--
-- Table structure for table `achievements`
--

CREATE TABLE `achievements` (
  `id` int(11) NOT NULL,
  `achievementId` int(11) NOT NULL,
  `isFinal` int(11) NOT NULL,
  `stage` int(11) NOT NULL,
  `isStaged` int(11) NOT NULL,
  `stageMsg` varchar(255) NOT NULL,
  `title` varchar(255) NOT NULL,
  `reqs` varchar(255) NOT NULL,
  `image` varchar(255) NOT NULL,
  `xp` int(11) NOT NULL,
  `xpNext` int(11) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

--
-- Dumping data for table `achievements`
--

INSERT INTO `achievements` (`id`, `achievementId`, `isFinal`, `stage`, `isStaged`, `stageMsg`, `title`, `reqs`, `image`, `xp`, `xpNext`) VALUES
(1, 0, 0, 0, 1, '1 10 100', 'Going Somewhere', 'collect 1 meme.', 'https://collectmemes.com/img/achievements/g/collect1.png', 0, 100),
(2, 0, 0, 1, 1, '<b>1</b> 10 100', 'Going Somewhere', 'collected 1 meme.', 'https://collectmemes.com/img/achievements/collect1.png', 100, 1000),
(3, 0, 0, 2, 1, '<b>1 10</b> 100', 'Halfway (Kinda)', 'collected 10 memes.', 'https://collectmemes.com/img/achievements/collect10.png', 1000, 10000),
(4, 0, 1, 3, 1, '<b>1 10 100</b>', 'A Full Vault', 'collected 100 memes!', 'https://collectmemes.com/img/achievements/collect100.png', 10000, 0),
(5, 1, 0, 0, 1, '100 500 1000', 'Novice Spinner', 'spin 100 times.', 'https://collectmemes.com/img/achievements/g/spin100.png', 0, 1000),
(6, 1, 0, 1, 1, '<b>100</b> 500 1000', 'Novice Spinner', 'spun 100 times.', 'https://collectmemes.com/img/achievements/spin100.png', 1000, 5000),
(7, 1, 0, 2, 1, '<b>100 500</b> 100', 'Spin Maniac', 'spun 500 times.', 'https://collectmemes.com/img/achievements/spin500.png', 5000, 10000),
(8, 1, 1, 3, 1, '<b>100 500 1000</b>', 'God of the Spin', 'spun 1000 times!', 'https://collectmemes.com/img/achievements/spin1000.png', 10000, 0),
(9, 2, 0, 0, 1, '1 5 10', 'New Challenger', 'complete 1 challenge', 'https://collectmemes.com/img/achievements/g/challenge1.png', 0, 1000),
(10, 2, 0, 1, 1, '<b>1</b> 5 10', 'New Challenger', 'completed 1 challenge.', 'https://collectmemes.com/img/achievements/challenge1.png', 1000, 5000),
(11, 2, 0, 2, 1, '<b>1 5</b> 10', 'The Hang of It', 'completed 5 challenges.', 'https://collectmemes.com/img/achievements/challenge5.png', 5000, 10000),
(12, 2, 1, 3, 1, '<b>1 5 10</b>', 'Challenges? NP', 'completed 10 challenges!', 'https://collectmemes.com/img/achievements/challenge10.png', 10000, 0),
(13, 3, 0, 0, 1, '10 100 500', 'Liker', 'like 10 memes.', 'https://collectmemes.com/img/achievements/g/like10.png', 0, 100),
(14, 3, 0, 1, 1, '<b>10</b> 100 500', 'Liker', 'liked 10 memes.', 'https://collectmemes.com/img/achievements/like10.png', 100, 2000),
(15, 3, 0, 2, 1, '<b>10 100</b> 500', 'Like Aficionado ', 'liked 100 memes.', 'https://collectmemes.com/img/achievements/like100.png', 2000, 10000),
(16, 3, 1, 3, 1, '<b>1 100 500</b>', 'Meme Lover', 'liked 500 memes!', 'https://collectmemes.com/img/achievements/like500.png', 10000, 0),
(17, 4, 0, 0, 1, '1 10 50', 'Make A Friend', 'friend 1 person.', 'https://collectmemes.com/img/achievements/g/friends1.png', 0, 500),
(18, 4, 0, 1, 1, '<b>1</b> 10 50', 'Made A Friend', 'have 1 friend.', 'https://collectmemes.com/img/achievements/friends1.png', 500, 5000),
(19, 4, 0, 2, 1, '<b>1 10</b> 50', 'Social Circle', 'have 10 friends.', 'https://collectmemes.com/img/achievements/friends10.png', 5000, 25000),
(20, 4, 1, 3, 1, '<b>1 10 50</b>', 'Influencer', 'have 50 friends!', 'https://collectmemes.com/img/achievements/friends50.png', 25000, 0),
(21, 5, 0, 0, 0, '', 'Nvm :(', 'trash a collected meme.', 'https://collectmemes.com/img/achievements/g/trash.png', 0, 1000),
(22, 5, 1, 1, 0, '', 'Nvm :(', 'trashed a collected meme!', 'https://collectmemes.com/img/achievements/trash.png', 1000, 0),
(23, 6, 0, 0, 0, '', 'iOS User', 'log in from iOS.', 'https://collectmemes.com/img/achievements/g/ios.png', 0, 100),
(24, 6, 1, 1, 0, '', 'iOS User', 'logged in from iOS!', 'https://collectmemes.com/img/achievements/ios.png', 100, 0),
(25, 7, 0, 0, 0, '', 'Android User', 'log in from Android.', 'https://collectmemes.com/img/achievements/g/android.png', 0, 100),
(26, 7, 1, 1, 0, '', 'Android User', 'logged in from Android!', 'https://collectmemes.com/img/achievements/android.png', 100, 0),
(27, 8, 0, 0, 0, '', 'Get Pro', 'purchase CM Pro.', 'https://collectmemes.com/img/achievements/g/pro.png', 0, 1000),
(28, 8, 1, 1, 0, '', 'Get Pro', 'purchased CM Pro!', 'https://collectmemes.com/img/achievements/pro.png', 1000, 0),
(29, 9, 0, 0, 1, '1 10', 'Common Collector', 'collect 1 common meme.', 'https://collectmemes.com/img/achievements/g/common1.png', 0, 100),
(30, 9, 0, 1, 1, '<b>1</b> 10', 'Common Collector', 'collected 1 common meme.', 'https://collectmemes.com/img/achievements/common1.png', 100, 1000),
(31, 9, 1, 2, 1, '<b>1 10</b>', 'Common Master', 'collected 10 common memes!', 'https://collectmemes.com/img/achievements/common10.png', 1000, 0),
(32, 10, 0, 0, 1, '1 10', 'Uncommon Collector', 'collect 1 uncommon meme.', 'https://collectmemes.com/img/achievements/g/uncommon1.png', 0, 200),
(33, 10, 0, 1, 1, '<b>1</b> 10', 'Uncommon Collector', 'collected 1 uncommon meme.', 'https://collectmemes.com/img/achievements/uncommon1.png', 200, 2000),
(34, 10, 1, 2, 1, '<b>1 10</b>', 'Uncommon Master', 'collected 10 uncommon memes!', 'https://collectmemes.com/img/achievements/uncommon10.png', 2000, 0),
(35, 11, 0, 0, 1, '1 10', 'Rare Collector', 'collect 1 rare meme.', 'https://collectmemes.com/img/achievements/g/rare1.png', 0, 300),
(36, 11, 0, 1, 1, '<b>1</b> 10', 'Rare Collector', 'collected 1 rare meme.', 'https://collectmemes.com/img/achievements/rare1.png', 300, 3000),
(37, 11, 1, 2, 1, '<b>1 10</b>', 'Rare Master', 'collected 10 rare memes!', 'https://collectmemes.com/img/achievements/rare10.png', 3000, 0),
(38, 12, 0, 0, 1, '1 10', 'Epic Collector', 'collect 1 epic meme.', 'https://collectmemes.com/img/achievements/g/epic1.png', 0, 400),
(39, 12, 0, 1, 1, '<b>1</b> 10', 'Epic Collector', 'collected 1 epic meme.', 'https://collectmemes.com/img/achievements/epic1.png', 400, 4000),
(40, 12, 1, 2, 1, '<b>1 10</b>', 'Epic Master', 'collected 10 epic memes!', 'https://collectmemes.com/img/achievements/epic10.png', 4000, 0),
(41, 13, 0, 0, 1, '1 10', 'Legendary Collector', 'collect 1 legendary meme.', 'https://collectmemes.com/img/achievements/g/legendary1.png', 0, 1000),
(42, 13, 0, 1, 1, '<b>1</b> 10', 'Legendary Collector', 'collected 1 legendary meme.', 'https://collectmemes.com/img/achievements/legendary1.png', 1000, 10000),
(43, 13, 1, 2, 1, '<b>1 10</b>', 'Legendary Master', 'collected 10 legendary memes!', 'https://collectmemes.com/img/achievements/legendary10.png', 10000, 0),
(44, 14, 0, 0, 0, '', 'Unachievable', 'you can\'t get this.', 'https://collectmemes.com/img/achievements/g/unachievable.png', 0, 50000),
(45, 14, 1, 0, 0, '', 'Unachievable', 'you got this! nice job!', 'https://collectmemes.com/img/achievements/unachievable.png', 50000, 0);

-- --------------------------------------------------------

--
-- Table structure for table `achievementsProgress`
--

CREATE TABLE `achievementsProgress` (
  `id` int(11) NOT NULL,
  `userId` int(11) NOT NULL,
  `progress` varchar(255) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `collections`
--

CREATE TABLE `collections` (
  `id` int(11) NOT NULL,
  `title` varchar(255) NOT NULL,
  `memes` varchar(255) NOT NULL,
  `totalMemes` int(11) NOT NULL,
  `xpReward` int(11) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `collectionsProgress`
--

CREATE TABLE `collectionsProgress` (
  `id` int(11) NOT NULL,
  `userId` int(11) NOT NULL,
  `collectionId` int(11) NOT NULL,
  `memes` varchar(255) NOT NULL,
  `totalOwned` int(11) NOT NULL,
  `completed` int(11) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `friendRequests`
--

CREATE TABLE `friendRequests` (
  `id` int(11) NOT NULL,
  `userId` int(11) NOT NULL,
  `senderId` int(11) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `likes`
--

CREATE TABLE `likes` (
  `id` int(11) NOT NULL,
  `userId` int(11) NOT NULL,
  `memeId` int(11) NOT NULL,
  `dateAdded` int(11) NOT NULL,
  `rank` int(11) NOT NULL,
  `likes` int(11) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

--
-- Dumping data for table `likes`
--

INSERT INTO `likes` (`id`, `userId`, `memeId`, `dateAdded`, `rank`, `likes`) VALUES
(1, 2, 26, 1576003226, 55, 1),
(2, 2, 27, 1576003230, 52, 3),
(3, 2, 24, 1576003235, 54, 2),
(4, 2, 35, 1576003238, 68, 1),
(5, 2, 25, 1576003241, 47, 2),
(6, 2, 34, 1576003245, 83, 1),
(7, 2, 42, 1576003248, 25, 1),
(8, 2, 46, 1576003251, 23, 1),
(22, 2, 32, 1576088550, 88, 1),
(10, 2, 5, 1576004175, 60, 3),
(11, 2, 29, 1576004182, 79, 1),
(12, 2, 1, 1576004192, 65, 12),
(20, 2, 47, 1576008693, 12, 2),
(21, 2, 74, 1576088509, 10, 1),
(15, 2, 30, 1576006809, 84, 1),
(16, 2, 43, 1576006833, 35, 2),
(17, 2, 4, 1576006852, 61, 2),
(18, 2, 39, 1576008684, 19, 1),
(19, 2, 3, 1576008688, 66, 11),
(23, 2, 22, 1576088554, 53, 1),
(24, 2, 12, 1576088560, 73, 1),
(25, 2, 44, 1576088565, 5, 1),
(26, 2, 78, 1576088576, 31, 1),
(27, 2, 46, 1576088596, 23, 1),
(28, 2, 25, 1576088601, 47, 1),
(29, 2, 42, 1576088610, 25, 1),
(30, 1, 13, 1576089919, 81, 1),
(31, 1, 22, 1576089923, 53, 2),
(46, 1, 46, 1576194225, 23, 2),
(33, 1, 34, 1576089931, 83, 1),
(34, 1, 51, 1576089936, 22, 1),
(35, 1, 60, 1576089943, 28, 1),
(36, 1, 83, 1576089947, 42, 1),
(37, 1, 23, 1576089952, 11, 1),
(38, 1, 45, 1576091287, 2, 1),
(39, 1, 55, 1576091291, 15, 1),
(40, 1, 53, 1576091299, 4, 1),
(41, 1, 54, 1576091306, 20, 1),
(42, 1, 30, 1576092295, 84, 1),
(43, 1, 16, 1576092306, 63, 1),
(44, 1, 70, 1576186281, 37, 1),
(45, 1, 39, 1576190957, 19, 1),
(47, 1, 44, 1576206851, 5, 2),
(48, 1, 57, 1576264116, 14, 1),
(49, 1, 48, 1576269611, 7, 1),
(50, 2, 171, 1576622372, 171, 1),
(51, 2, 171, 1576622378, 171, 2),
(52, 2, 2, 1576627762, 59, 1),
(54, 3, 210, 1576636992, 210, 1);

-- --------------------------------------------------------

--
-- Table structure for table `memes`
--

CREATE TABLE `memes` (
  `id` int(11) NOT NULL,
  `title` varchar(50) NOT NULL,
  `image` varchar(255) NOT NULL,
  `totalOwned` int(11) NOT NULL,
  `likes` int(11) NOT NULL DEFAULT '0',
  `score` int(11) NOT NULL,
  `rating` int(11) NOT NULL,
  `rank` int(11) NOT NULL,
  `inRotation` int(11) NOT NULL,
  `edition` int(11) NOT NULL,
  `creator` varchar(50) NOT NULL,
  `dateAdded` int(11) NOT NULL,
  `display` int(11) NOT NULL DEFAULT '1'
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

--
-- Dumping data for table `memes`
--

INSERT INTO `memes` (`id`, `title`, `image`, `totalOwned`, `likes`, `score`, `rating`, `rank`, `inRotation`, `edition`, `creator`, `dateAdded`, `display`) VALUES
(1, 'Fuck Work', 'https://collectmemes.com/img/memes/20180930_103231.jpg', 0, 0, 50, 50, 65, 1, 1, 'CollectMemes', 1576032669, 1),
(2, 'Live, Laugh, Yeet', 'https://collectmemes.com/img/memes/20180930_143607.jpg', 2, 1, 63, 64, 59, 1, 1, 'CollectMemes', 1576032693, 1),
(3, 'The Ambush', 'https://collectmemes.com/img/memes/20181018_191027.jpg', 0, 0, 40, 40, 66, 1, 1, 'CollectMemes', 1576032704, 1),
(4, 'Cursed OwO', 'https://collectmemes.com/img/memes/20181018_191044.jpg', 2, 0, 63, 63, 61, 1, 1, 'CollectMemes', 1576032727, 1),
(5, 'Never An Option', 'https://collectmemes.com/img/memes/20181209_173726.jpg', 0, 0, 63, 63, 60, 1, 1, 'CollectMemes', 1576032734, 1),
(6, 'Vibe Che-', 'https://collectmemes.com/img/memes/20191019_181812.jpg', 0, 0, 34, 34, 69, 1, 1, 'CollectMemes', 1576032740, 1),
(7, 'Fat Fuck', 'https://collectmemes.com/img/memes/20191115_000926000_iOS.jpg', 0, 0, 19, 19, 82, 1, 1, 'CollectMemes', 1576032745, 1),
(8, '1 of Every 4', 'https://collectmemes.com/img/memes/20191116_120426.jpg', 0, 0, 57, 57, 64, 1, 1, 'CollectMemes', 1576032757, 1),
(9, 'Look Closer', 'https://collectmemes.com/img/memes/2c415ed0f4533723b8f54900f6bc1386.png', 0, 0, 22, 22, 78, 1, 1, 'CollectMemes', 1576032765, 1),
(10, 'Ah Shit', 'https://collectmemes.com/img/memes/3b61ef0.jpg', 3, 0, 6, 6, 87, 1, 1, 'CollectMemes', 1576032803, 1),
(11, 'Darth Thermostat', 'https://collectmemes.com/img/memes/4XbcgB3.png', 0, 0, 70, 70, 48, 1, 1, 'CollectMemes', 1576032824, 1),
(12, 'AHHHHHH', 'https://collectmemes.com/img/memes/5c8a8739ee460d0389ccccc841e034d6.png', 0, 1, 29, 30, 73, 1, 1, 'CollectMemes', 1576032830, 1),
(13, 'LOOK AT ME', 'https://collectmemes.com/img/memes/5hiqs8qng4v31.png', 1, 1, 19, 20, 81, 1, 1, 'CollectMemes', 1576032846, 1),
(14, 'Meme Creators', 'https://collectmemes.com/img/memes/646su1m273341.jpg', 0, 0, 39, 39, 67, 1, 1, 'CollectMemes', 1576032857, 1),
(15, 'It\\\'s Rewind Time', 'https://collectmemes.com/img/memes/8y97jbnycw341.jpg', 0, 0, 32, 32, 70, 1, 1, 'CollectMemes', 1576032864, 1),
(16, 'Sponge Cowboy', 'https://collectmemes.com/img/memes/92d355c639466d8db268bbef3dea3a4e.png', 1, 1, 58, 60, 63, 1, 1, 'CollectMemes', 1576032898, 1),
(17, 'FL Revival', 'https://collectmemes.com/img/memes/IMG_3768.JPG', 0, 0, 31, 31, 72, 1, 1, 'CollectMemes', 1576032906, 1),
(18, 'Coomer', 'https://collectmemes.com/img/memes/IMG_3776.JPG', 1, 0, 94, 94, 18, 1, 1, 'CollectMemes', 1576033006, 1),
(19, 'Colleg', 'https://collectmemes.com/img/memes/IMG_3784.JPG', 0, 0, 63, 63, 62, 1, 1, 'CollectMemes', 1576033011, 1),
(20, 'Halloween Candy', 'https://collectmemes.com/img/memes/IMG_3932.JPG', 0, 0, 65, 65, 58, 1, 1, 'CollectMemes', 1576033020, 1),
(21, 'Halloween Candy 2', 'https://collectmemes.com/img/memes/IMG_3934.JPG', 0, 0, 24, 24, 75, 1, 1, 'CollectMemes', 1576033026, 1),
(22, 'Halloween Candy 3', 'https://collectmemes.com/img/memes/IMG_3936.JPG', 0, 2, 68, 70, 53, 1, 1, 'CollectMemes', 1576033032, 1),
(23, 'AWOOOOOOGA', 'https://collectmemes.com/img/memes/IMG_3969.JPG', 1, 1, 96, 98, 11, 1, 1, 'CollectMemes', 1576033066, 1),
(24, '95 Boomers', 'https://collectmemes.com/img/memes/IMG_4026.JPG', 0, 0, 70, 70, 54, 1, 1, 'CollectMemes', 1576033092, 1),
(25, 'Would You Rather', 'https://collectmemes.com/img/memes/IMG_4144.JPG', 1, 1, 70, 71, 47, 1, 1, 'CollectMemes', 1576033170, 1),
(26, 'Mumble Fans', 'https://collectmemes.com/img/memes/IMG_4145.JPG', 0, 0, 69, 69, 55, 1, 1, 'CollectMemes', 1576033191, 1),
(27, 'The Best Country', 'https://collectmemes.com/img/memes/IMG_4146.JPG', 0, 0, 70, 70, 52, 1, 1, 'CollectMemes', 1576033201, 1),
(28, 'Andre Pls', 'https://collectmemes.com/img/memes/IMG_4150.JPG', 0, 0, 70, 70, 51, 1, 1, 'CollectMemes', 1576033209, 1),
(29, 'Larry', 'https://collectmemes.com/img/memes/IMG_4153.JPG', 0, 0, 22, 22, 79, 1, 1, 'CollectMemes', 1576033215, 1),
(30, 'Biker Man', 'https://collectmemes.com/img/memes/IMG_4156.JPG', 0, 1, 15, 16, 84, 1, 1, 'CollectMemes', 1576033223, 1),
(31, 'My Eyes = Burning', 'https://collectmemes.com/img/memes/IMG_4168.JPG', 0, 0, 70, 70, 49, 1, 1, 'CollectMemes', 1576033242, 1),
(32, 'Shongles', 'https://collectmemes.com/img/memes/IMG_4169.JPG', 0, 1, 5, 6, 88, 1, 1, 'CollectMemes', 1576033247, 1),
(33, 'Pro Gamer Move', 'https://collectmemes.com/img/memes/IMG_4170.JPG', 0, 0, 82, 82, 34, 1, 1, 'CollectMemes', 1576033254, 1),
(34, 'Measuring It Out', 'https://collectmemes.com/img/memes/IMG_4171.JPG', 0, 1, 15, 16, 83, 1, 1, 'CollectMemes', 1576033277, 1),
(35, 'The Brickster', 'https://collectmemes.com/img/memes/IMG_4172.JPG', 0, 0, 36, 36, 68, 1, 1, 'CollectMemes', 1576033287, 1),
(36, 'Timbaby Yoda', 'https://collectmemes.com/img/memes/IMG_4178.JPG', 0, 0, 100, 100, 8, 1, 1, 'CollectMemes', 1576033307, 1),
(37, 'WE ALL BURN', 'https://collectmemes.com/img/memes/IMG_4179.png', 0, 0, 87, 87, 27, 1, 1, 'CollectMemes', 1576033315, 1),
(38, 'Ight Imma Head Out', 'https://collectmemes.com/img/memes/IMG_4180.JPG', 0, 0, 84, 84, 32, 1, 1, 'CollectMemes', 1576033324, 1),
(39, 'Travis Puffs', 'https://collectmemes.com/img/memes/IMG_E3688.JPG', 1, 1, 93, 93, 19, 1, 1, 'CollectMemes', 1576033333, 1),
(40, 'Philosophy', 'https://collectmemes.com/img/memes/IMG_E3709.JPG', 0, 0, 22, 22, 80, 1, 1, 'CollectMemes', 1576033342, 1),
(41, 'For the Coders', 'https://collectmemes.com/img/memes/IMG_E3715.JPG', 0, 0, 84, 84, 33, 1, 1, 'CollectMemes', 1576033349, 1),
(42, 'Gen Z Weakness', 'https://collectmemes.com/img/memes/IMG_E4003.JPG', 2, 1, 86, 88, 25, 1, 1, 'CollectMemes', 1576033372, 1),
(43, 'Solid Advice', 'https://collectmemes.com/img/memes/IMG_E4011.JPG', 0, 0, 81, 81, 35, 1, 1, 'CollectMemes', 1576033378, 1),
(44, 'I Cant', 'https://collectmemes.com/img/memes/IMG_E4015.JPG', 1, 2, 99, 100, 5, 1, 1, 'CollectMemes', 1576033386, 1),
(45, 'B)', 'https://collectmemes.com/img/memes/IMG_E4030.JPG', 0, 1, 100, 101, 2, 1, 1, 'CollectMemes', 1576033396, 1),
(46, 'Jaunty Horn Solo', 'https://collectmemes.com/img/memes/IMG_E4099.JPG', 1, 2, 86, 88, 23, 1, 1, 'CollectMemes', 1576033411, 1),
(47, 'Mirror into the Moose', 'https://collectmemes.com/img/memes/IMG_E4115.JPG', 0, 0, 98, 98, 12, 1, 1, 'CollectMemes', 1576033431, 1),
(48, 'Complainer Slowpoke', 'https://collectmemes.com/img/memes/JJ90TgG.jpg', 0, 1, 100, 100, 7, 1, 1, 'CollectMemes', 1576033453, 1),
(49, 'Aypr', 'https://collectmemes.com/img/memes/apyr.jpg', 1, 0, 100, 100, 6, 1, 1, 'CollectMemes', 1576033461, 1),
(50, '?!??!??!?', 'https://collectmemes.com/img/memes/b4mfenbozt341.jpg', 1, 0, 87, 87, 29, 1, 1, 'CollectMemes', 1576033469, 1),
(51, '2017 Calendar', 'https://collectmemes.com/img/memes/calendar2017.jpg', 0, 1, 88, 89, 22, 1, 1, 'CollectMemes', 1576033478, 1),
(52, '2018 Calendar', 'https://collectmemes.com/img/memes/calendar2018.png', 0, 0, 88, 88, 24, 1, 1, 'CollectMemes', 1576033486, 1),
(53, 'Fuckboy Emoji', 'https://collectmemes.com/img/memes/communityIcon_5nfrncvuf2e21.png', 3, 1, 99, 100, 4, 1, 1, 'CollectMemes', 1576033494, 1),
(54, 'Sad Bby', 'https://collectmemes.com/img/memes/cry.jpg', 1, 1, 89, 91, 20, 1, 1, 'CollectMemes', 1576033503, 1),
(55, 'Cute McCree', 'https://collectmemes.com/img/memes/cutemccree.jpg', 0, 1, 96, 97, 15, 1, 1, 'CollectMemes', 1576033510, 1),
(56, 'Drag Spongebob', 'https://collectmemes.com/img/memes/drag.jpg', 0, 0, 100, 100, 9, 1, 1, 'CollectMemes', 1576033525, 1),
(57, 'Awkward D.Va Closeup', 'https://collectmemes.com/img/memes/dva.jpg', 1, 1, 98, 98, 14, 1, 1, 'CollectMemes', 1576033683, 1),
(58, 'Cheese?', 'https://collectmemes.com/img/memes/e5ekec5cyk141.jpg', 1, 0, 96, 96, 16, 1, 1, 'CollectMemes', 1576033696, 1),
(59, 'Aeugh', 'https://collectmemes.com/img/memes/fish.jpg', 0, 0, 100, 100, 3, 1, 1, 'CollectMemes', 1576033703, 1),
(60, 'Foot Lettuce', 'https://collectmemes.com/img/memes/footlettuce.png', 0, 1, 86, 87, 28, 1, 1, 'CollectMemes', 1576033708, 1),
(61, 'FEET FEET FEET', 'https://collectmemes.com/img/memes/fqljvqs1bq241.jpg', 0, 0, 81, 81, 36, 1, 1, 'CollectMemes', 1576033714, 1),
(62, '4th Story Window', 'https://collectmemes.com/img/memes/gimc8p9yxdh31.jpg', 0, 0, 73, 73, 46, 1, 1, 'CollectMemes', 1576033806, 1),
(63, 'Spice Things Up', 'https://collectmemes.com/img/memes/glfuzki0so341.jpg', 0, 0, 77, 77, 44, 1, 1, 'CollectMemes', 1576033813, 1),
(64, 'I Has Lamp', 'https://collectmemes.com/img/memes/igj0h6rfb3241.jpg', 1, 0, 79, 79, 40, 1, 1, 'CollectMemes', 1576033821, 1),
(65, 'Pick Youre Fighter', 'https://collectmemes.com/img/memes/image0.jpg', 0, 0, 89, 89, 21, 1, 1, 'CollectMemes', 1576033830, 1),
(66, 'AWMPH', 'https://collectmemes.com/img/memes/image0.png', 1, 0, 77, 77, 43, 1, 1, 'CollectMemes', 1576033835, 1),
(67, 'im baby', 'https://collectmemes.com/img/memes/imbaby.jpg', 0, 0, 11, 11, 85, 1, 1, 'CollectMemes', 1576033839, 1),
(68, 'Jk... Unless?', 'https://collectmemes.com/img/memes/kia.jpg', 0, 0, 87, 87, 26, 1, 1, 'CollectMemes', 1576033875, 1),
(69, 'Yief', 'https://collectmemes.com/img/memes/lol.jpg', 0, 0, 70, 70, 50, 1, 1, 'CollectMemes', 1576033881, 1),
(70, 'Awkward Mercy', 'https://collectmemes.com/img/memes/mercy.jpg', 1, 1, 80, 80, 37, 1, 1, 'CollectMemes', 1576033886, 1),
(71, 'Dress Stuck', 'https://collectmemes.com/img/memes/my35jwnv8dv31.png', 0, 0, 23, 23, 77, 1, 1, 'CollectMemes', 1576033898, 1),
(72, 'Ultimate Date', 'https://collectmemes.com/img/memes/otl0hdo14ni31.jpg', 0, 0, 6, 6, 86, 1, 1, 'CollectMemes', 1576033903, 1),
(73, 'Poopin Stages', 'https://collectmemes.com/img/memes/qs2lpxjuuq341.jpg', 0, 0, 67, 67, 57, 1, 1, 'CollectMemes', 1576033925, 1),
(74, 'You\'re Next', 'https://collectmemes.com/img/memes/s.png', 1, 1, 98, 99, 10, 1, 1, 'CollectMemes', 1576033931, 1),
(75, 'Shen Yun', 'https://collectmemes.com/img/memes/shenyun.jpg', 1, 0, 95, 95, 17, 1, 1, 'CollectMemes', 1576033936, 1),
(76, 'Gnome', 'https://collectmemes.com/img/memes/spook.png', 0, 0, 86, 86, 30, 1, 1, 'CollectMemes', 1576033943, 1),
(77, 'The Boys', 'https://collectmemes.com/img/memes/theboys.jpg', 0, 0, 77, 77, 45, 1, 1, 'CollectMemes', 1576033947, 1),
(78, 'Tide Pod', 'https://collectmemes.com/img/memes/tide.jpeg', 1, 1, 84, 85, 31, 1, 1, 'CollectMemes', 1576033951, 1),
(79, '2020', 'https://collectmemes.com/img/memes/ucpw4ak6tbv31.png', 0, 0, 24, 24, 76, 1, 1, 'CollectMemes', 1576033956, 1),
(80, 'Ugandan Knuckles', 'https://collectmemes.com/img/memes/ugandanknuckles.jpg', 0, 0, 80, 80, 38, 1, 1, 'CollectMemes', 1576033962, 1),
(81, 'Ultra Instinct Shaggy', 'https://collectmemes.com/img/memes/ultrainstinctshaggy.png', 1, 0, 100, 101, 1, 1, 1, 'CollectMemes', 1576033972, 1),
(82, 'Facts', 'https://collectmemes.com/img/memes/vxedoe6u67v31.jpg', 0, 0, 79, 79, 39, 1, 1, 'CollectMemes', 1576033977, 1),
(83, '*realization*', 'https://collectmemes.com/img/memes/w9zz6wmagh141.jpg', 1, 1, 77, 78, 42, 1, 1, 'CollectMemes', 1576033988, 1),
(84, 'Keep Charging', 'https://collectmemes.com/img/memes/wbliudtqyli31.jpg', 1, 0, 27, 27, 74, 1, 1, 'CollectMemes', 1576034000, 1),
(85, 'Daddy Kong', 'https://collectmemes.com/img/memes/weebro.jpg', 0, 0, 32, 32, 71, 1, 1, 'CollectMemes', 1576034007, 1),
(86, 'pls no', 'https://collectmemes.com/img/memes/wnrlbnd5lli31.jpg', 0, 0, 68, 68, 56, 1, 1, 'CollectMemes', 1576034022, 1),
(87, 'World Record Egg', 'https://collectmemes.com/img/memes/worldrecordegg.jpg', 1, 0, 98, 98, 13, 1, 1, 'CollectMemes', 1576034028, 1),
(88, 'Dance Party', 'https://collectmemes.com/img/memes/zxvpbo4rtdv31.png', 0, 0, 78, 78, 41, 1, 1, 'CollectMemes', 1576034033, 1),
(89, 'Fruit Ninja', 'https://collectmemes.com/img/memes/0117a58a0191b53560cbc1c1ec8e7ada.jpg', 0, 0, 41, 0, 89, 1, 1, 'CollectMemes', 1576209799, 1),
(90, 'Pengu', 'https://collectmemes.com/img/memes/09b61b4b17544434dfb9007eb722517a31410178820315e1d7e9b049175c0ac5_1.jpg', 0, 0, 8, 0, 90, 1, 1, 'CollectMemes', 1576209806, 1),
(91, ':)', 'https://collectmemes.com/img/memes/0e22cfab92b23c85bfe814e20eab6966.jpg', 0, 0, 20, 0, 91, 1, 1, 'CollectMemes', 1576209819, 1),
(92, ':)', 'https://collectmemes.com/img/memes/1.JPG', 0, 0, 20, 0, 92, 1, 1, 'CollectMemes', 1576209826, 1),
(93, 'Boomer Revenge', 'https://collectmemes.com/img/memes/10.png', 0, 0, 1, 0, 93, 1, 1, 'CollectMemes', 1576209999, 1),
(94, 'TRIGGERED', 'https://collectmemes.com/img/memes/14b9185d6691b55df7bc59b15b9698e7.png', 0, 0, 90, 0, 94, 1, 1, 'CollectMemes', 1576210007, 1),
(95, 'Alexa, Intruder Alert', 'https://collectmemes.com/img/memes/152a8b967e1565ca1f984f286696ba414d8435ae3bc60083fa95bff886e128ea_1.jpg', 0, 0, 36, 0, 95, 1, 1, 'CollectMemes', 1576210026, 1),
(96, '*breathes heavily*', 'https://collectmemes.com/img/memes/1f344a33ae13bdc3824856d589aa93aa.png', 0, 0, 87, 0, 96, 1, 1, 'CollectMemes', 1576210040, 1),
(97, 'it be like that', 'https://collectmemes.com/img/memes/2.png', 0, 0, 84, 0, 97, 1, 1, 'CollectMemes', 1576210060, 1),
(98, 'Ackchyually', 'https://collectmemes.com/img/memes/20161105_103004.png', 0, 0, 79, 0, 98, 1, 1, 'CollectMemes', 1576210069, 1),
(99, 'Shed Skin', 'https://collectmemes.com/img/memes/20180930_101319.jpg', 0, 0, 24, 0, 99, 1, 1, 'CollectMemes', 1576210237, 1),
(100, 'Bowsette', 'https://collectmemes.com/img/memes/20180930_102258.jpg', 0, 0, 96, 0, 100, 1, 1, 'CollectMemes', 1576210244, 1),
(101, 'Gibby Angry', 'https://collectmemes.com/img/memes/20180930_102325.jpg', 0, 0, 83, 0, 101, 1, 1, 'CollectMemes', 1576210250, 1),
(102, 'Some Day...', 'https://collectmemes.com/img/memes/20180930_103257.jpg', 0, 0, 26, 0, 102, 1, 1, 'CollectMemes', 1576210257, 1),
(103, 'Be Happy', 'https://collectmemes.com/img/memes/20181007_110936.jpg', 0, 0, 73, 0, 103, 1, 1, 'CollectMemes', 1576210267, 1),
(104, 'Yeet', 'https://collectmemes.com/img/memes/20181019_224016.jpg', 0, 0, 74, 0, 104, 1, 1, 'CollectMemes', 1576210272, 1),
(105, 'Thanos Whale', 'https://collectmemes.com/img/memes/20181029_214035.jpg', 0, 0, 68, 0, 105, 1, 1, 'CollectMemes', 1576210280, 1),
(106, 'Soda In the Oven', 'https://collectmemes.com/img/memes/20181029_214058.jpg', 0, 0, 29, 0, 106, 1, 1, 'CollectMemes', 1576210292, 1),
(107, 'A watermelon', 'https://collectmemes.com/img/memes/20181030_215456.jpg', 0, 0, 20, 0, 107, 1, 1, 'CollectMemes', 1576210304, 1),
(108, 'Goals', 'https://collectmemes.com/img/memes/20181107_194946.jpg', 0, 0, 31, 0, 108, 1, 1, 'CollectMemes', 1576210309, 1),
(109, 'One Time I Dreamt', 'https://collectmemes.com/img/memes/20181107_195111.jpg', 0, 0, 36, 0, 109, 1, 1, 'CollectMemes', 1576210326, 1),
(110, 'Communism Man', 'https://collectmemes.com/img/memes/20181107_195123.jpg', 0, 0, 90, 0, 110, 1, 1, 'CollectMemes', 1576210332, 1),
(111, 'You ready to die', 'https://collectmemes.com/img/memes/20181116_212725.jpg', 0, 0, 39, 0, 111, 1, 1, 'CollectMemes', 1576210340, 1),
(112, 'Kurt Cobain', 'https://collectmemes.com/img/memes/20181116_213209.jpg', 0, 0, 81, 0, 112, 1, 1, 'CollectMemes', 1576210346, 1),
(113, 'Gamer Doritos', 'https://collectmemes.com/img/memes/20181116_213352.jpg', 0, 0, 71, 0, 113, 1, 1, 'CollectMemes', 1576210354, 1),
(114, 'Nude', 'https://collectmemes.com/img/memes/20181206_205228.jpg', 0, 0, 29, 0, 114, 1, 1, 'CollectMemes', 1576210363, 1),
(115, 'Light the Ring', 'https://collectmemes.com/img/memes/20181214_213507.jpg', 0, 0, 28, 0, 115, 1, 1, 'CollectMemes', 1576210411, 1),
(116, 'Abstract Gibby', 'https://collectmemes.com/img/memes/20181214_232151.jpg', 0, 0, 64, 0, 116, 1, 1, 'CollectMemes', 1576210424, 1),
(117, ':)', 'https://collectmemes.com/img/memes/20181216_151211.jpg', 0, 0, 19, 0, 117, 1, 1, 'CollectMemes', 1576210432, 1),
(118, 'Scary Isabelle', 'https://collectmemes.com/img/memes/20181220_155935.jpg', 0, 0, 28, 0, 118, 1, 1, 'CollectMemes', 1576210440, 1),
(119, 'Oh, Shane...', 'https://collectmemes.com/img/memes/20181222_153240.jpg', 0, 0, 35, 0, 119, 1, 1, 'CollectMemes', 1576210452, 1),
(120, 'The Truth', 'https://collectmemes.com/img/memes/20181222_153410.jpg', 0, 0, 42, 0, 120, 1, 1, 'CollectMemes', 1576210461, 1),
(121, 'Bad Decisions', 'https://collectmemes.com/img/memes/20181226_170231.jpg', 0, 0, 50, 0, 121, 1, 1, 'CollectMemes', 1576210485, 1),
(122, 'AdBlock', 'https://collectmemes.com/img/memes/20181226_234140.jpg', 0, 0, 33, 0, 122, 1, 1, 'CollectMemes', 1576210493, 1),
(123, 'Creative Ideas', 'https://collectmemes.com/img/memes/20181226_234938.jpg', 0, 0, 22, 0, 123, 1, 1, 'CollectMemes', 1576210523, 1),
(124, 'AAAHHHH', 'https://collectmemes.com/img/memes/20181230_162521.jpg', 0, 0, 29, 0, 124, 1, 1, 'CollectMemes', 1576210595, 1),
(125, 'The Chungle Book', 'https://collectmemes.com/img/memes/20181230_162528.jpg', 0, 0, 92, 0, 125, 1, 1, 'CollectMemes', 1576210602, 1),
(126, 'Country Mario Mashup', 'https://collectmemes.com/img/memes/20190109_202900.jpg', 0, 0, 68, 0, 126, 1, 1, 'CollectMemes', 1576210619, 1),
(127, 'That\\\'s Direct', 'https://collectmemes.com/img/memes/20190109_202907.jpg', 0, 0, 42, 0, 127, 1, 1, 'CollectMemes', 1576210631, 1),
(128, 'McFucking Had It', 'https://collectmemes.com/img/memes/20190109_203539.jpg', 0, 0, 70, 0, 128, 1, 1, 'CollectMemes', 1576210640, 1),
(129, 'wrong bus mrs friz', 'https://collectmemes.com/img/memes/20190120_120506.jpg', 0, 0, 79, 0, 129, 1, 1, 'CollectMemes', 1576210651, 1),
(130, 'I have seen it', 'https://collectmemes.com/img/memes/20190120_120513.jpg', 0, 0, 31, 0, 130, 1, 1, 'CollectMemes', 1576210695, 1),
(131, 'Sinnoh Fog', 'https://collectmemes.com/img/memes/20190126_202017.jpg', 0, 0, 56, 0, 131, 1, 1, 'CollectMemes', 1576210759, 1),
(132, 'It\\\'s beautiful', 'https://collectmemes.com/img/memes/20190127_105245.jpg', 0, 0, 65, 0, 132, 1, 1, 'CollectMemes', 1576210765, 1),
(133, 'RIP', 'https://collectmemes.com/img/memes/20190127_105443.jpg', 0, 0, 61, 0, 133, 1, 1, 'CollectMemes', 1576210771, 1),
(134, 'Abandonment Issues', 'https://collectmemes.com/img/memes/20190127_202608.jpg', 0, 0, 70, 0, 134, 1, 1, 'CollectMemes', 1576210788, 1),
(135, 'You\\\'re Next', 'https://collectmemes.com/img/memes/20190128_191112.jpg', 0, 0, 26, 0, 135, 1, 1, 'CollectMemes', 1576210794, 1),
(136, 'The Gang', 'https://collectmemes.com/img/memes/20190130_175826.jpg', 0, 0, 94, 0, 136, 1, 1, 'CollectMemes', 1576210803, 1),
(137, 'True Love', 'https://collectmemes.com/img/memes/20190131_130955.jpg', 0, 0, 86, 0, 137, 1, 1, 'CollectMemes', 1576210808, 1),
(138, 'This is a robbery', 'https://collectmemes.com/img/memes/20190131_200604.jpg', 0, 0, 62, 0, 138, 1, 1, 'CollectMemes', 1576210817, 1),
(139, 'yo what', 'https://collectmemes.com/img/memes/20190202_172820.jpg', 0, 0, 69, 0, 139, 1, 1, 'CollectMemes', 1576210823, 1),
(140, 'Cant-Man', 'https://collectmemes.com/img/memes/20190203_175442.jpg', 0, 0, 11, 0, 140, 1, 1, 'CollectMemes', 1576210831, 1),
(141, 'Illegal Minecraft', 'https://collectmemes.com/img/memes/20190217_174414.jpg', 0, 0, 7, 0, 141, 1, 1, 'CollectMemes', 1576210838, 1),
(142, 'LMG Crusader', 'https://collectmemes.com/img/memes/20190623_141009.jpg', 0, 0, 17, 0, 142, 1, 1, 'CollectMemes', 1576210846, 1),
(143, 'Waka Flocka Wooloo', 'https://collectmemes.com/img/memes/20190623_141312.jpg', 0, 0, 65, 0, 143, 1, 1, 'CollectMemes', 1576210879, 1),
(144, 'Rocky', 'https://collectmemes.com/img/memes/26Hm8CbA.jpeg', 0, 0, 89, 0, 144, 1, 1, 'CollectMemes', 1576210895, 1),
(145, 'Master Chef', 'https://collectmemes.com/img/memes/2BACVL2w.jpeg', 0, 0, 79, 0, 145, 1, 1, 'CollectMemes', 1576210899, 1),
(146, 'I miss vine', 'https://collectmemes.com/img/memes/2Ls5bz5g.jpeg', 0, 0, 33, 0, 146, 1, 1, 'CollectMemes', 1576210905, 1),
(147, 'Video Games', 'https://collectmemes.com/img/memes/2c59119.jpg', 0, 0, 31, 0, 147, 1, 1, 'CollectMemes', 1576210923, 1),
(148, 'Sal', 'https://collectmemes.com/img/memes/2psot5.jpg', 0, 0, 96, 0, 148, 1, 1, 'CollectMemes', 1576210928, 1),
(149, 'DIPP', 'https://collectmemes.com/img/memes/3.png', 0, 0, 89, 0, 149, 1, 1, 'CollectMemes', 1576210935, 1),
(150, 'Hamster Roast', 'https://collectmemes.com/img/memes/3762eecdd27cd014b4612c44a3f8128ac60d61950a8275d4eb408ac9fcece838_1.jpg', 0, 0, 32, 0, 150, 1, 1, 'CollectMemes', 1576211027, 1),
(151, 'He\\\'s Undefeatable', 'https://collectmemes.com/img/memes/3rztH-nQ.jpeg', 0, 0, 76, 0, 151, 1, 1, 'CollectMemes', 1576211038, 1),
(152, 'stay hydrated', 'https://collectmemes.com/img/memes/4.png', 0, 0, 86, 0, 152, 1, 1, 'CollectMemes', 1576211049, 1),
(153, 'me too', 'https://collectmemes.com/img/memes/44f7e15b91c7ee594da2c315784e04565f8e3f1706d1aa49fb6ee92d390ba079_1.jpg', 0, 0, 34, 0, 153, 1, 1, 'CollectMemes', 1576211054, 1),
(154, 'To War', 'https://collectmemes.com/img/memes/4ArwO7dg.jpeg', 0, 0, 89, 0, 154, 1, 1, 'CollectMemes', 1576211059, 1),
(155, 'Soon...', 'https://collectmemes.com/img/memes/4MzLsfyQ.jpeg', 0, 0, 73, 0, 155, 1, 1, 'CollectMemes', 1576211066, 1),
(156, 'Abstract Donut', 'https://collectmemes.com/img/memes/5ICtUjvQ.jpeg', 0, 0, 61, 0, 156, 1, 1, 'CollectMemes', 1576211078, 1),
(157, 'Big Facts', 'https://collectmemes.com/img/memes/5KV5FTGw.jpeg', 0, 0, 35, 0, 157, 1, 1, 'CollectMemes', 1576211094, 1),
(158, 'Roblox Comments', 'https://collectmemes.com/img/memes/5d8ac95f47902a9ed71cb884c629bcf1.png', 0, 0, 27, 0, 158, 1, 1, 'CollectMemes', 1576211107, 1),
(159, 'Omaewu', 'https://collectmemes.com/img/memes/6d156f17652d8f2d0b4436d8e97892c8.png', 0, 0, 74, 0, 159, 1, 1, 'CollectMemes', 1576211143, 1),
(160, 'Construction Thanos', 'https://collectmemes.com/img/memes/71oWuLgg.jpeg', 0, 0, 65, 0, 160, 1, 1, 'CollectMemes', 1576211150, 1),
(161, 'Shroom Things', 'https://collectmemes.com/img/memes/75550c5974e5462f93a1ded96a433dd0.jpg', 0, 0, 20, 0, 161, 1, 1, 'CollectMemes', 1576211166, 1),
(162, 'They\\\'ll Pass', 'https://collectmemes.com/img/memes/7OKpCBYQ.jpeg', 0, 0, 25, 0, 162, 1, 1, 'CollectMemes', 1576211174, 1),
(163, 'bukkake', 'https://collectmemes.com/img/memes/7d06ea704947b0fcf4f09c652d69ebae.png', 0, 0, 20, 0, 163, 1, 1, 'CollectMemes', 1576211184, 1),
(164, 'fuck you, thanos', 'https://collectmemes.com/img/memes/7f6fe066c9fcbe3628d681e3c952feef21dc5c52c382d132fb1b5d80d293fe5a_1.jpg.jpg', 0, 0, 18, 0, 164, 1, 1, 'CollectMemes', 1576211191, 1),
(165, 'Well...', 'https://collectmemes.com/img/memes/7pAE4cy.png', 0, 0, 12, 0, 165, 1, 1, 'CollectMemes', 1576211209, 1),
(166, 'Pancake', 'https://collectmemes.com/img/memes/8.png', 0, 0, 8, 0, 166, 1, 1, 'CollectMemes', 1576211215, 1),
(167, 'RIP Stan', 'https://collectmemes.com/img/memes/815f3f3ca59fd28ec892bd46bea6ce00.jpg', 0, 0, 23, 0, 167, 1, 1, 'CollectMemes', 1576211225, 1),
(168, 'Hoes Mad', 'https://collectmemes.com/img/memes/8c-k9e4w.jpeg', 0, 0, 62, 0, 168, 1, 1, 'CollectMemes', 1576211230, 1),
(169, 'I Caught Mewtwo', 'https://collectmemes.com/img/memes/8c7f547.jpg', 0, 0, 27, 0, 169, 1, 1, 'CollectMemes', 1576211244, 1),
(170, 'Hey you...', 'https://collectmemes.com/img/memes/8fcaf95f72beb93115886237bf8332c7193b60b4093cc7e91641718a74f57cb5_1.jpg', 0, 0, 26, 0, 170, 1, 1, 'CollectMemes', 1576211259, 1),
(171, 'Anime Stereotype', 'https://collectmemes.com/img/memes/8tFcrrcg.jpeg', 1, 2, 21, 0, 171, 1, 1, 'CollectMemes', 1576211276, 1),
(172, 'Got \\\'Em', 'https://collectmemes.com/img/memes/9.png', 0, 0, 21, 0, 172, 1, 1, 'CollectMemes', 1576211288, 1),
(173, 'OH GOD', 'https://collectmemes.com/img/memes/90c3c450c66cb765a75e0edb661abd00.png', 0, 0, 35, 0, 173, 1, 1, 'CollectMemes', 1576211292, 1),
(174, 'Parent Econ 101', 'https://collectmemes.com/img/memes/9JdDcYmA.jpeg', 0, 0, 24, 0, 174, 1, 1, 'CollectMemes', 1576211307, 1),
(175, 'Lotta Fanfic', 'https://collectmemes.com/img/memes/9c7dedb4860b7118995daeda3de32ad94c2c52b704f83cbe54aaa89b66ce731c_1.jpg', 0, 0, 28, 0, 175, 1, 1, 'CollectMemes', 1576211316, 1),
(176, 'Thot Vanquished', 'https://collectmemes.com/img/memes/A37Ro93w.jpeg', 0, 0, 29, 0, 176, 1, 1, 'CollectMemes', 1576211325, 1),
(177, 'Spook Spider', 'https://collectmemes.com/img/memes/BJf_3W-Q.png', 0, 0, 71, 0, 177, 1, 1, 'CollectMemes', 1576211330, 1),
(178, 'Outta the Womb...', 'https://collectmemes.com/img/memes/CKqGcVNQ.jpeg', 0, 0, 29, 0, 178, 1, 1, 'CollectMemes', 1576211337, 1),
(179, 'Duolingo Tweet', 'https://collectmemes.com/img/memes/EcCgYOWA.jpeg', 0, 0, 31, 0, 179, 1, 1, 'CollectMemes', 1576211348, 1),
(180, 'Clues Required', 'https://collectmemes.com/img/memes/EiWY-DcQ.jpeg', 0, 0, 50, 0, 180, 1, 1, 'CollectMemes', 1576211363, 1),
(181, 'Why We\\\'re Here', 'https://collectmemes.com/img/memes/EnLE-QUA.jpeg', 0, 0, 66, 0, 181, 1, 1, 'CollectMemes', 1576211372, 1),
(182, 'Objective: Survive', 'https://collectmemes.com/img/memes/ErxV1zjg.jpeg', 0, 0, 72, 0, 182, 1, 1, 'CollectMemes', 1576211379, 1),
(183, 'Kinda Cheap', 'https://collectmemes.com/img/memes/F8qYw44g.jpeg', 0, 0, 50, 0, 183, 1, 1, 'CollectMemes', 1576211389, 1),
(184, 'Halo Meme', 'https://collectmemes.com/img/memes/FSEUPLtg.jpeg', 0, 0, 29, 0, 184, 1, 1, 'CollectMemes', 1576211395, 1),
(185, 'Bungee Fail', 'https://collectmemes.com/img/memes/FfomJ96Q.jpeg', 0, 0, 50, 0, 185, 1, 1, 'CollectMemes', 1576211404, 1),
(186, 'God left.', 'https://collectmemes.com/img/memes/Fjz37L6A.jpeg', 0, 0, 18, 0, 186, 1, 1, 'CollectMemes', 1576211408, 1),
(187, 'Burger King', 'https://collectmemes.com/img/memes/GcE_MO1Q.jpeg', 0, 0, 37, 0, 187, 1, 1, 'CollectMemes', 1576211414, 1),
(188, 'Florida', 'https://collectmemes.com/img/memes/HGr-wNBw.jpeg', 0, 0, 50, 0, 188, 1, 1, 'CollectMemes', 1576211419, 1),
(189, 'Meme Stolen', 'https://collectmemes.com/img/memes/Hssosykw.jpeg', 0, 0, 50, 0, 189, 1, 1, 'CollectMemes', 1576211423, 1),
(190, 'Razor vs. Ankle', 'https://collectmemes.com/img/memes/IMG_4181.JPG', 0, 0, 59, 0, 190, 1, 1, 'CollectMemes', 1576211434, 1),
(191, 'Todd', 'https://collectmemes.com/img/memes/IMG_4188.JPG', 0, 0, 72, 0, 191, 1, 1, 'CollectMemes', 1576211439, 1),
(192, 'I wish...', 'https://collectmemes.com/img/memes/IMG_4190.JPG', 1, 0, 29, 0, 192, 1, 1, 'CollectMemes', 1576211457, 1),
(193, 'Teletubbies', 'https://collectmemes.com/img/memes/IMG_4191.JPG', 0, 0, 50, 0, 193, 1, 1, 'CollectMemes', 1576211466, 1),
(194, '*shivers*', 'https://collectmemes.com/img/memes/IMG_4192.JPG', 0, 0, 50, 0, 194, 1, 1, 'CollectMemes', 1576211474, 1),
(195, 'wtf bro', 'https://collectmemes.com/img/memes/IMG_4197.JPG', 0, 0, 32, 0, 195, 1, 1, 'CollectMemes', 1576211480, 1),
(196, 'Hate Crimes', 'https://collectmemes.com/img/memes/IMG_4204.JPG', 0, 0, 50, 0, 196, 1, 1, 'CollectMemes', 1576211524, 1),
(197, 'Open It, I Dare You', 'https://collectmemes.com/img/memes/IMG_4205.JPG', 0, 0, 28, 0, 197, 1, 1, 'CollectMemes', 1576211542, 1),
(198, 'Heehoo Pupper', 'https://collectmemes.com/img/memes/IMG_4206.JPG', 0, 0, 50, 0, 198, 1, 1, 'CollectMemes', 1576211554, 1),
(199, 'Caught by Admin', 'https://collectmemes.com/img/memes/IMG_4207.JPG', 0, 0, 70, 0, 199, 1, 1, 'CollectMemes', 1576211567, 1),
(200, 'What\\\'s the diff?', 'https://collectmemes.com/img/memes/IMG_4208.JPG', 0, 0, 37, 0, 200, 1, 1, 'CollectMemes', 1576211594, 1),
(201, 'Boomer Life Support', 'https://collectmemes.com/img/memes/IMG_4209.JPG', 0, 0, 50, 0, 201, 1, 1, 'CollectMemes', 1576211607, 1),
(202, 'wait, let\\\'s talk', 'https://collectmemes.com/img/memes/IMG_4213.JPG', 0, 0, 50, 0, 202, 1, 1, 'CollectMemes', 1576211620, 1),
(203, 'this aint it skipper', 'https://collectmemes.com/img/memes/IMG_4214.JPG', 0, 0, 13, 0, 203, 1, 1, 'CollectMemes', 1576211627, 1),
(204, 'Betrayed', 'https://collectmemes.com/img/memes/IMG_4217.PNG', 0, 0, 31, 0, 204, 1, 1, 'CollectMemes', 1576211659, 1),
(205, 'hi!', 'https://collectmemes.com/img/memes/IMG_4219.JPG', 0, 0, 29, 0, 205, 1, 1, 'CollectMemes', 1576211665, 1),
(206, 'time to wait', 'https://collectmemes.com/img/memes/IMG_4220.JPG', 0, 0, 61, 0, 206, 1, 1, 'CollectMemes', 1576211672, 1),
(207, 'Multitasking', 'https://collectmemes.com/img/memes/IMG_4221.JPG', 0, 0, 50, 0, 207, 1, 1, 'CollectMemes', 1576211683, 1),
(208, 'Stop, But Go', 'https://collectmemes.com/img/memes/IMG_4222.JPG', 0, 0, 26, 0, 208, 1, 1, 'CollectMemes', 1576211690, 1),
(209, 'What is Celcius?', 'https://collectmemes.com/img/memes/IMG_4223.JPG', 0, 0, 50, 0, 209, 1, 1, 'CollectMemes', 1576211708, 1),
(210, 'society', 'https://collectmemes.com/img/memes/IMG_4224.JPG', 0, 1, 50, 0, 210, 1, 1, 'CollectMemes', 1576211716, 1),
(211, 'it\\\'ll work for now', 'https://collectmemes.com/img/memes/IMG_4225.JPG', 0, 0, 50, 0, 211, 1, 1, 'CollectMemes', 1576211723, 1),
(212, 'Group Chats', 'https://collectmemes.com/img/memes/IMG_4226.JPG', 0, 0, 65, 0, 212, 1, 1, 'CollectMemes', 1576211730, 1),
(213, 'I raised you', 'https://collectmemes.com/img/memes/IMG_4228.JPG', 0, 0, 50, 0, 213, 1, 1, 'CollectMemes', 1576211739, 1),
(214, 'Have Some Memes', 'https://collectmemes.com/img/memes/IMG_4229.JPG', 0, 0, 50, 0, 214, 1, 1, 'CollectMemes', 1576211748, 1),
(215, 'Pen Repair', 'https://collectmemes.com/img/memes/IMG_4230.JPG', 0, 0, 61, 0, 215, 1, 1, 'CollectMemes', 1576211757, 1),
(216, 'I swear I did it...', 'https://collectmemes.com/img/memes/IMG_4231.JPG', 0, 0, 50, 0, 216, 1, 1, 'CollectMemes', 1576211768, 1),
(217, 'Papa Palpatine', 'https://collectmemes.com/img/memes/IMG_4232.JPG', 0, 0, 50, 0, 217, 1, 1, 'CollectMemes', 1576211782, 1),
(218, 'Big Oof', 'https://collectmemes.com/img/memes/IMG_4233.JPG', 0, 0, 65, 0, 218, 1, 1, 'CollectMemes', 1576211789, 1),
(219, 'The Trickster', 'https://collectmemes.com/img/memes/IMG_4234.JPG', 0, 0, 55, 0, 219, 1, 1, 'CollectMemes', 1576211803, 1),
(220, 'Clown', 'https://collectmemes.com/img/memes/IMG_4235.JPG', 0, 0, 67, 0, 220, 1, 1, 'CollectMemes', 1576211810, 1),
(221, 'Xbox Live Courtesy', 'https://collectmemes.com/img/memes/IMG_4236.JPG', 0, 0, 59, 0, 221, 1, 1, 'CollectMemes', 1576211822, 1),
(222, 'im wet', 'https://collectmemes.com/img/memes/IMG_4237.JPG', 0, 0, 29, 0, 222, 1, 1, 'CollectMemes', 1576211831, 1),
(223, 'yeah, they all ugly', 'https://collectmemes.com/img/memes/IMG_4238.JPG', 0, 0, 66, 0, 223, 1, 1, 'CollectMemes', 1576211843, 1),
(224, 'literally crying rn', 'https://collectmemes.com/img/memes/IMG_4239.JPG', 0, 0, 70, 0, 224, 1, 1, 'CollectMemes', 1576211851, 1),
(225, 'baby gorilla', 'https://collectmemes.com/img/memes/IMG_4240.JPG', 0, 0, 71, 0, 225, 1, 1, 'CollectMemes', 1576211858, 1);

-- --------------------------------------------------------

--
-- Table structure for table `owns`
--

CREATE TABLE `owns` (
  `id` int(11) NOT NULL,
  `userId` int(11) NOT NULL,
  `memeId` int(11) NOT NULL,
  `dateAdded` int(11) NOT NULL,
  `rank` int(11) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

--
-- Dumping data for table `owns`
--

INSERT INTO `owns` (`id`, `userId`, `memeId`, `dateAdded`, `rank`) VALUES
(36, 1, 75, 1576191468, 17),
(44, 1, 18, 1576204090, 18),
(33, 1, 58, 1576188442, 16),
(35, 1, 39, 1576190450, 19),
(45, 1, 42, 1576205768, 25),
(25, 1, 25, 1576175001, 47),
(24, 1, 66, 1576107621, 43),
(23, 1, 2, 1576106115, 59),
(22, 1, 81, 1576094388, 1),
(21, 1, 16, 1576092308, 63),
(20, 1, 54, 1576091313, 20),
(19, 1, 23, 1576089954, 11),
(32, 1, 70, 1576185977, 37),
(37, 1, 46, 1576193339, 23),
(38, 1, 87, 1576194330, 13),
(39, 1, 74, 1576198878, 10),
(40, 1, 4, 1576199151, 61),
(41, 1, 53, 1576200219, 4),
(43, 1, 78, 1576202277, 31),
(46, 1, 44, 1576206857, 5),
(47, 1, 83, 1576211936, 42),
(48, 1, 192, 1576255453, 192),
(49, 1, 49, 1576257480, 6),
(50, 1, 50, 1576259220, 29),
(51, 1, 57, 1576264113, 14),
(52, 2, 171, 1576622254, 171),
(53, 2, 2, 1576627758, 59),
(54, 2, 4, 1576631495, 61),
(55, 3, 10, 1576634924, 87),
(59, 3, 64, 1576644423, 40),
(58, 3, 53, 1576638900, 4);

-- --------------------------------------------------------

--
-- Table structure for table `resetRequests`
--

CREATE TABLE `resetRequests` (
  `id` int(11) NOT NULL,
  `userId` int(11) NOT NULL,
  `resetCode` varchar(255) NOT NULL,
  `time` int(11) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `username` varchar(255) NOT NULL,
  `password` mediumtext NOT NULL,
  `email` text NOT NULL,
  `avatar` varchar(255) NOT NULL DEFAULT 'https://collectmemes.com/icons/default.png',
  `xp` int(11) NOT NULL,
  `totalSpins` int(11) NOT NULL DEFAULT '0',
  `likesSize` int(11) NOT NULL DEFAULT '0',
  `avgRank` int(11) DEFAULT '0',
  `collectionSize` int(11) NOT NULL DEFAULT '0',
  `collectionSum` int(11) NOT NULL DEFAULT '0',
  `friends` mediumtext NOT NULL,
  `friendsSize` int(11) NOT NULL DEFAULT '0',
  `blockList` int(11) NOT NULL,
  `blockListSize` int(11) NOT NULL DEFAULT '0',
  `displayInFriendsList` int(11) NOT NULL DEFAULT '1',
  `dateRegistered` int(11) NOT NULL,
  `lastPassChange` int(11) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `username`, `password`, `email`, `avatar`, `xp`, `totalSpins`, `likesSize`, `avgRank`, `collectionSize`, `collectionSum`, `friends`, `friendsSize`, `blockList`, `blockListSize`, `displayInFriendsList`, `dateRegistered`, `lastPassChange`) VALUES
(1, 'CollectMemes', '$2a$07$5jh843257hquiyo7ghfkgefk5o6dleMCJFfr/WpRTRHVBkkp2Hc32', 'museumtowny@gmail.com', 'https://collectmemes.com/icons/default.png', 0, 212, 19, 34, 28, 965, '', 0, 0, 0, 1, 1575846523, 1576024002),
(2, 'Penguin', '$2a$07$5jh843257hquiyo7ghfkge11EtAz91gsVmYbHbF6cWzUEMVn7pQKu', 'cer989@gmail.com', 'https://collectmemes.com/icons/default.png', 0, 10, 3, 97, 3, 291, '', 0, 0, 0, 1, 1576622241, 1576622241),
(3, 'fowliegirl', '$2a$07$5jh843257hquiyo7ghfkgejxK2sf6CzCuw2JcJdlgQXIzpjoiL1Du', 'bullard_tracy@yahoo.com', 'https://collectmemes.com/icons/default.png', 0, 14, 1, 43, 3, 131, '', 0, 0, 0, 1, 1576634793, 1576634793);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `achievements`
--
ALTER TABLE `achievements`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `achievementsProgress`
--
ALTER TABLE `achievementsProgress`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `collections`
--
ALTER TABLE `collections`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `collectionsProgress`
--
ALTER TABLE `collectionsProgress`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `friendRequests`
--
ALTER TABLE `friendRequests`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `likes`
--
ALTER TABLE `likes`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `memes`
--
ALTER TABLE `memes`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `owns`
--
ALTER TABLE `owns`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `resetRequests`
--
ALTER TABLE `resetRequests`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `achievements`
--
ALTER TABLE `achievements`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=46;

--
-- AUTO_INCREMENT for table `achievementsProgress`
--
ALTER TABLE `achievementsProgress`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `collections`
--
ALTER TABLE `collections`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `collectionsProgress`
--
ALTER TABLE `collectionsProgress`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `friendRequests`
--
ALTER TABLE `friendRequests`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `likes`
--
ALTER TABLE `likes`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=55;

--
-- AUTO_INCREMENT for table `memes`
--
ALTER TABLE `memes`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=226;

--
-- AUTO_INCREMENT for table `owns`
--
ALTER TABLE `owns`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=60;

--
-- AUTO_INCREMENT for table `resetRequests`
--
ALTER TABLE `resetRequests`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
