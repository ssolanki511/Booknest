-- phpMyAdmin SQL Dump
-- version 5.2.2
-- https://www.phpmyadmin.net/
--
-- Host: localhost:3306
-- Generation Time: Oct 27, 2025 at 04:03 AM
-- Server version: 8.0.30
-- PHP Version: 8.1.10

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `booknest`
--

-- --------------------------------------------------------

--
-- Table structure for table `books`
--

CREATE TABLE `books` (
  `b_id` int NOT NULL,
  `b_name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NOT NULL,
  `b_author` varchar(100) NOT NULL,
  `b_price` int DEFAULT NULL,
  `b_discount` int DEFAULT '0',
  `b_desc` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NOT NULL,
  `b_category` varchar(30) NOT NULL,
  `b_publish_date` date NOT NULL,
  `b_cover_tmp` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NOT NULL,
  `b_file` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `books`
--

INSERT INTO `books` (`b_id`, `b_name`, `b_author`, `b_price`, `b_discount`, `b_desc`, `b_category`, `b_publish_date`, `b_cover_tmp`, `b_file`) VALUES
(57, 'Rich Dad Poor Dad', 'Robert Kiyosaki and Sharon Lechter', 359, 5, 'The book is based on Kiyosaki\'s personal experiences with his two fathers - his biological father (poor dad) and his best friend\'s father (rich dad). The book provides a guide to financial literacy and teaches readers about the importance of financial education, creating wealth, and achieving financial freedom.', 'business', '2025-03-22', '67de4f466f227rich-dad-poor-dad.jpg', '67de4f466f22crich-dad-poor-dad.pdf'),
(58, 'The Science of Getting Rich', 'Wallace D. Wattles', 249, 0, 'The Science of Getting Rich highlights the importance of adding value to others. The idea is to attract others to you. Magnetize people to come to you. Adding lots of value is not only possible, it\'s the best way to build a large and loyal following.', 'business', '2025-03-22', '67de4fc6d9178The-science-of-getting-rich.jpg', '67de4fc6d917dthe-science-of-getting-rich.pdf'),
(59, 'The Last of the Mohicans', 'James Fenimore Cooper', 225, 7, 'It is the late 1750s, and the French and Indian War grips the wild forest frontier of western New York. The French army is attacking Fort William Henry, a British outpost commanded by Colonel Munro. Munro’s daughters Alice and Cora set out from Fort Edward to visit their father, escorted through the dangerous forest by Major Duncan Heyward and guided by an Indian named Magua. Soon they are joined by David Gamut, a singing master and religious follower of Calvinism. Traveling cautiously, the group encounters the white scout Natty Bumppo, who goes by the name Hawkeye, and his two Indian companions, Chingachgook and Uncas, Chingachgook’s son, the only surviving members of the once great Mohican tribe. Hawkeye says that Magua, a Huron, has betrayed the group by leading them in the wrong direction. The Mohicans attempt to capture the traitorous Huron, but he escapes.', 'historical', '2025-03-22', '67de5058c3d4ethe_last_of_the_mohicans.jpg', '67de5058c3d52the_last_of_the_mohicans.pdf'),
(60, 'The Lost Tales of Mercia', 'Jayden Woods', 254, 0, 'In the years near 1000 A.D., the Vikings and their king, Sweyn Forkbeard, constantly attack Engla-lond. A weak king, Ethelred II, rules the Anglo-Saxons. He tries to pay off each Viking attack with a steep tax called the “Danegald,” but again and again the pagans return. A masked vigilante called the Golden Cross tries to aid the people of Engla-lond and rally them to warfare, but this rebel is constantly way-laid by the king’s most trusted advisor, Eadric Streona. Eadric Streona, Ealdorman of Mercia, is a charming master of the king’s court who always manages to get what he wants; but what he wants remains a mystery to all.', 'historical', '2025-03-22', '67de598a1ffe1lost_tales_of_mercia.jpg', '67de598a1ffe7Lost_Tales_of_Mercia.pdf'),
(63, 'Beautiful World, Where Are You', 'Sally Rooney', 259, 12, '\"Beautiful world, where are you?\" is a question her two main female characters, best friends from college now on the cusp of 30, grapple with repeatedly in their struggles to figure out how they should live and find meaning in a troubled world that has become increasingly unviable on multiple levels — ecologically, economically, ethically and emotionally.', 'novel', '2025-03-22', '67de5c7983084Beautiful_World_Where_Are_You.jpg', '67de5c7983088Beautiful_World_Where_Are_You.pdf'),
(64, 'Tell Me Everything', 'Elizabeth Strout', 229, 0, 'With her remarkable insight into the human condition and silences that contain multitudes, Elizabeth Strout returns to the town of Crosby, Maine, and to her beloved cast of characters—Lucy Barton, Olive Kitteridge, Bob Burgess, and more—as they deal with a shocking crime in their midst, fall in love and yet choose to be apart, and grapple with the question, as Lucy Barton puts it, “What does anyone’s life mean?”', 'novel', '2025-03-22', '67de5ceb5c9f0Tell_Me_Everythong.jpg', '67de5ceb5c9f4Tell_Me_Everything.pdf'),
(65, 'Change Your Day, Not Your Life', 'Andy Core', 299, 8, 'A Realistic Guide to Sustained Motivation, More Productivity, and the Art of Working Well. Increase your employees\' and your own productivity at work If you look out over today\'s workforce, you\'ll find millions of hard-working people who are overly tired, overly stressed, and less than enchanted with work.', 'self-help', '2025-03-26', '67e42045ea5ebChange_Your_Day_Not_Your_Life.jpg', '67e42045ea5eeChange_Your_Day_Not_Your_Life.pdf'),
(66, '100 Universal Law', 'Brian Tracy', 359, 0, 'Human potential expert Brian Tracy has focused the light of Universal Laws through the lens of business and created a most fascinating, richly written guide that can enhance both your personal and professional life. He explains each of the 100 laws in philosophical, behavioral and practical detail. Then he applies them to various aspects of business, career enhancement, creativity and financial reward. The book is destined to become a business classic. There is nothing trite, shallow, or gimmicky about this book, and getAbstract recommends it highly to all professionals', 'self-help', '2025-03-26', '67e42112897a1100_Universal_law.jpg', '67e42112897a5105-Universal-Laws-You-Need.pdf'),
(68, 'fight by the wright', 'Richard Wright', 399, 0, '\"The Wright Brothers\" by David McCullough chronicles the lives of Wilbur and Orville Wright, focusing on their childhood, their entrepreneurial spirit, and their relentless pursuit of flight, culminating in their historic 1903 achievement and subsequent struggles to gain recognition and financial success. ', 'biography', '2025-03-26', '67e422d278c88flights-by-the-wrights.jpg', '67e422d278c8cflights-by-the-wrights.pdf');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `books`
--
ALTER TABLE `books`
  ADD PRIMARY KEY (`b_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `books`
--
ALTER TABLE `books`
  MODIFY `b_id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=70;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
