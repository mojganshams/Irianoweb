-- phpMyAdmin SQL Dump
-- version 4.7.0
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Nov 18, 2018 at 11:22 AM
-- Server version: 10.1.25-MariaDB
-- PHP Version: 7.1.7

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `irianoweb`
--

-- --------------------------------------------------------

--
-- Table structure for table `comments`
--

CREATE TABLE `comments` (
  `id` int(11) NOT NULL,
  `post_id` int(11) DEFAULT NULL,
  `author` varchar(255) CHARACTER SET utf8 COLLATE utf8_persian_ci NOT NULL,
  `body` text CHARACTER SET utf8 COLLATE utf8_persian_ci NOT NULL,
  `email` varchar(100) CHARACTER SET utf8 COLLATE utf8_persian_ci DEFAULT NULL,
  `status` varchar(20) DEFAULT NULL,
  `created_at` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `comments`
--

INSERT INTO `comments` (`id`, `post_id`, `author`, `body`, `email`, `status`, `created_at`) VALUES
(1, 1, '\0Kevin', 'I love this picture!', NULL, 'published', '2009-01-01 11:30:39'),
(5, 5, 'Doug', 'Pretty flowers.', NULL, 'published', '2009-01-01 20:46:39'),
(6, 5, 'Mary', 'I like them too.', NULL, 'published', '2009-01-01 21:08:58'),
(10, 1, 'یزدان', 'تست', 'yaztak@hotmail.com', 'published', '2017-09-30 08:34:08');

-- --------------------------------------------------------

--
-- Table structure for table `frontpage`
--

CREATE TABLE `frontpage` (
  `id` int(11) NOT NULL,
  `page` varchar(50) DEFAULT NULL,
  `section` varchar(100) CHARACTER SET utf8 COLLATE utf8_persian_ci DEFAULT NULL,
  `title` varchar(150) CHARACTER SET utf8 COLLATE utf8_persian_ci DEFAULT NULL,
  `content` text CHARACTER SET utf8 COLLATE utf8_persian_ci,
  `filename` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `frontpage`
--

INSERT INTO `frontpage` (`id`, `page`, `section`, `title`, `content`, `filename`) VALUES
(1, 'home', 'header', 'ایریانو وب', 'ارائه دهنده خدمات طراحی گرافیک و برنامه نویسی', 'header.jpg'),
(2, 'home', 'header', 'logo', 'logo', 'logo-iriano.png'),
(11, 'home', 'hexagon', 'FRONTEND', 'HTML, CSS, Javascript + Branches', 'frontend-bg.jpg'),
(12, 'home', 'hexagon', 'icon-f', 'icon', 'frontend.png'),
(13, 'home', 'hexagon', 'BACKEND', 'PHP, SQL, NodeJS, Ruby', 'backend-bg.png'),
(14, 'home', 'hexagon', 'icon-b', 'icon', 'backend.png'),
(15, 'home', 'hexagon', 'JAVASCRIPT', 'Javascript, Jquery, AngularJS, NodeJS', 'javascript-bg.jpg'),
(16, 'home', 'hexagon', 'icon-j', 'icon', 'javascript.png'),
(17, 'home', 'hexagon', 'CSS3', 'CSS3 + Responsive design', 'css-bg.png'),
(18, 'home', 'hexagon', 'icon-c', 'icon', 'css.png'),
(19, 'home', 'hexagon', 'HTML5', 'HTML5 components + Custom look', 'html-bg.jpg'),
(20, 'home', 'hexagon', 'icon-h', 'icon', 'html.png'),
(21, 'home', 'hexagon', 'SEO', 'Up-to-date SEO Practices', 'seo-bg.png'),
(22, 'home', 'hexagon', 'icon-s', 'icon', 'seo.png'),
(23, 'home', 'hexagon', 'GUI', 'Wordpress, Custom GUI Creation', 'wordpress-bg.jpg'),
(24, 'home', 'hexagon', 'icon-g', 'icon', 'gui.png'),
(25, 'home', 'services', 'اپلیکیشن', 'طراحی و پیاده سازی اپلیکیشن Android و iOS با استفاده از متد های به روز و بهینه', 'app-development.jpg'),
(26, 'home', 'services', 'Android, iOS', 'alt_title', NULL),
(27, 'home', 'services', 'وب سایت', 'ارائه خدمات طراحی و پشتیبانی انواع وب سایت های فروشگاهی و شرکتی طراحی واکنش گرا (Responsive) و بهینه سازی برای دستگاه های مختلف بهینه سازی وب سایت برای موتور های جستجو (SEO)', 'web-development.jpg'),
(28, 'home', 'services', 'Wordpress, Custom CMS', 'alt_title', NULL),
(29, 'home', 'services', 'گرافیک', 'طراحی انواع کارت ویزیت، بروشور، تراکت و پوستر در کمترین زمان', 'graphic-design.jpg'),
(30, 'home', 'services', 'Photoshop, Illustrator', 'alt_title', NULL),
(31, 'home', 'description', 'یک وب سایت، یک هویت!', 'یک وب سایت میتواند نقش یک بروشور و یت یک کاتالوگ تبلیغاتی رابرای شما داشته باشد با این تفاوت که امکانات یک سایت بیشتر از یک بروشور و یک کاتالوگ ساده است . شما می توانید محصولات و خدمات خود را از طریق وب سایت معرفی کنید و حتی می توانید محصولات خود را از طریق وب سایت ها فروشگاهی به فروش برسانید . ضمن این که شرکتهای بسیار زیادی برای طراحی وب سایت وجود دارند ولی مبلغ بسیار زیادی را بابت طراحی وب سایت دریافت خواهند کرد. گروه مهندسی ایریانو وب با دانش و تجربه بسیار زیاد در این زمینه و فعالیت در شرکت های بزرگ و نامی توانستند با گردهمایی و تشکیل گروه مهندسی ایریانو وب شرایطی را به وجود آورند تا با مبلغ بسیار کم وب سایت های حرفه ای را طراحی نمایند.', 'work-samples.jpg'),
(32, 'home', 'description', 'مشاهده نمونه کارها', 'button_text', NULL),
(33, 'home', 'contact', 'تماس با ما', '', 'contact.jpg'),
(34, 'home', 'contact', 'ارسال', 'button_text', NULL),
(36, 'blog', 'header', 'وبلاگ', '', 'blog-header.jpg'),
(37, 'about', 'about', 'no-title', 'گروهی مهندسی ایریانو با هدف ارائه خدمات با کیفیت بالا ایجاد شده و در زمینه های طراحی و برنامه نویسی فعالیت می کند.', 'about-us.jpg'),
(38, 'portfolios', 'websites', '1', '', 'websites1.png'),
(39, 'portfolios', 'websites', '2', '', 'websites2.png'),
(40, 'portfolios', 'websites', '3', '', 'websites3.png'),
(41, 'portfolios', 'websites', '4', '', 'websites4.png'),
(42, 'portfolios', 'websites', '5', '', 'websites5.png'),
(43, 'portfolios', 'websites', '6', '', 'websites6.png'),
(44, 'portfolios', 'websites', '7', '', 'websites7.png'),
(45, 'portfolios', 'websites', '8', '', 'websites8.png'),
(46, 'portfolios', 'websites', '9', '', 'websites9.png'),
(47, 'portfolios', 'websites', '10', '', 'websites10.png'),
(48, 'portfolios', 'posters', '1', '', 'posters1.jpg'),
(49, 'portfolios', 'posters', '2', '', 'posters2.jpg'),
(50, 'portfolios', 'posters', '3', '', 'posters3.jpg'),
(51, 'portfolios', 'posters', '4', '', 'posters4.jpg'),
(52, 'portfolios', 'posters', '5', '', 'posters5.jpg'),
(53, 'portfolios', 'posters', '6', '', 'posters6.jpg'),
(54, 'portfolios', 'posters', '7', '', 'posters7.jpg'),
(55, 'portfolios', 'posters', '8', '', 'posters8.jpg'),
(56, 'portfolios', 'posters', '9', '', 'posters9.jpg'),
(57, 'portfolios', 'posters', '10', '', 'posters10.jpg'),
(58, 'portfolios', 'posters', '11', '', 'posters11.jpg'),
(59, 'portfolios', 'catalogs', '1', '', 'catalogs1-1.jpg'),
(60, 'portfolios', 'catalogs', '2', '', 'catalogs1-2.jpg'),
(61, 'portfolios', 'catalogs', '3', '', 'catalogs1-3.jpg'),
(62, 'portfolios', 'catalogs', '4', '', 'catalogs2-1.jpg'),
(63, 'portfolios', 'catalogs', '5', '', 'catalogs2-2.jpg'),
(64, 'portfolios', 'catalogs', '6', '', 'catalogs2-3.jpg'),
(65, 'portfolios', 'catalogs', '7', '', 'catalogs3-1.jpg'),
(66, 'portfolios', 'catalogs', '8', '', 'catalogs3-2.jpg'),
(67, 'portfolios', 'catalogs', '9', '', 'catalogs3-3.jpg'),
(68, 'portfolios', 'catalogs', '10', '', 'catalogs4-1.jpg'),
(69, 'portfolios', 'catalogs', '11', '', 'catalogs4-2.jpg'),
(70, 'portfolios', 'catalogs', '12', '', 'catalogs4-3.jpg'),
(71, 'portfolios', 'catalogs', '13', '', 'catalogs5-1.jpg'),
(72, 'portfolios', 'catalogs', '14', '', 'catalogs5-2.jpg'),
(73, 'portfolios', 'catalogs', '15', '', 'catalogs5-3.jpg'),
(74, 'portfolios', 'catalogs', '16', '', 'catalogs6-1.jpg'),
(75, 'portfolios', 'catalogs', '17', '', 'catalogs6-2.jpg'),
(76, 'portfolios', 'catalogs', '18', '', 'catalogs6-3.jpg'),
(77, 'portfolios', 'catalogs', '19', '', 'catalogs7-1.jpg'),
(78, 'portfolios', 'catalogs', '20', '', 'catalogs7-2.jpg'),
(79, 'portfolios', 'catalogs', '21', '', 'catalogs7-3.jpg'),
(80, 'portfolios', 'catalogs', '22', '', 'catalogs8-1.jpg'),
(81, 'portfolios', 'catalogs', '23', '', 'catalogs8-2.jpg'),
(82, 'portfolios', 'catalogs', '24', '', 'catalogs8-3.jpg'),
(83, 'portfolios', 'catalogs', '25', '', 'catalogs9-1.jpg'),
(84, 'portfolios', 'catalogs', '26', '', 'catalogs9-2.jpg'),
(85, 'portfolios', 'catalogs', '27', '', 'catalogs9-3.jpg'),
(86, 'portfolios', 'catalogs', '28', '', 'catalogs10-1.jpg'),
(87, 'portfolios', 'catalogs', '29', '', 'catalogs10-2.jpg'),
(88, 'portfolios', 'catalogs', '30', '', 'catalogs10-3.jpg'),
(89, 'portfolios', 'catalogs', '31', '', 'catalogs11-1.jpg'),
(90, 'portfolios', 'catalogs', '32', '', 'catalogs11-2.jpg'),
(91, 'portfolios', 'catalogs', '33', '', 'catalogs11-3.jpg'),
(92, 'portfolios', 'catalogs', '34', '', 'catalogs12-1.jpg'),
(93, 'portfolios', 'catalogs', '35', '', 'catalogs12-2.jpg'),
(94, 'portfolios', 'catalogs', '36', '', 'catalogs12-3.jpg'),
(95, 'portfolios', 'catalogs', '37', '', 'catalogs13-1.jpg'),
(96, 'portfolios', 'catalogs', '38', '', 'catalogs13-2.jpg'),
(97, 'portfolios', 'catalogs', '39', '', 'catalogs13-3.jpg'),
(98, 'portfolios', 'catalogs', '40', '', 'catalogs14-1.jpg'),
(99, 'portfolios', 'catalogs', '41', '', 'catalogs14-2.jpg'),
(100, 'portfolios', 'catalogs', '42', '', 'catalogs14-3.jpg'),
(101, 'portfolios', 'catalogs', '43', '', 'catalogs15-1.jpg'),
(102, 'portfolios', 'catalogs', '44', '', 'catalogs15-2.jpg'),
(103, 'portfolios', 'catalogs', '45', '', 'catalogs15-3.jpg'),
(104, 'portfolios', 'catalogs', '46', '', 'catalogs16-1.jpg'),
(105, 'portfolios', 'catalogs', '47', '', 'catalogs16-2.jpg'),
(106, 'portfolios', 'catalogs', '48', '', 'catalogs16-3.jpg'),
(107, 'portfolios', 'banners', '1', '', 'banners1.png'),
(108, 'portfolios', 'banners', '2', '', 'banners2.png'),
(109, 'portfolios', 'banners', '3', '', 'banners3.png'),
(110, 'portfolios', 'banners', '4', '', 'banners4.png'),
(111, 'portfolios', 'banners', '5', '', 'banners5.png'),
(112, 'portfolios', 'banners', '6', '', 'banners6.png'),
(113, 'portfolios', 'banners', '7', '', 'banners7.png'),
(114, 'portfolios', 'banners', '8', '', 'banners8.png'),
(115, 'portfolios', 'banners', '9', '', 'banners9.png'),
(116, 'portfolios', 'banners', '10', '', 'banners10.png'),
(117, 'portfolios', 'intervals', 'websites', 'طراحی و پیاده سازی وب سایت با استفاده از ابزار های استاندارد، به روز و بهینه', NULL),
(118, 'portfolios', 'intervals', 'posters', 'طراحی انواع پوستر های تبلیغاتی و غیر تبلیغاتی', NULL),
(119, 'portfolios', 'intervals', 'catalogs', 'طراحی انواع تراکت و کاتالوگ بر حسب سلیقه مشتری', NULL),
(120, 'portfolios', 'titles', 'websites', 'وب سایت', NULL),
(121, 'portfolios', 'titles', 'posters', 'پوستر', NULL),
(122, 'portfolios', 'titles', 'catalogs', 'تراکت و کاتالوگ', NULL),
(123, 'portfolios', 'titles', 'banners', 'بنر تبلیغاتی', NULL),
(124, 'home', 'header', 'test', '', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `posts`
--

CREATE TABLE `posts` (
  `id` int(11) NOT NULL,
  `title` varchar(255) CHARACTER SET utf8 COLLATE utf8_persian_ci NOT NULL,
  `content` text CHARACTER SET utf8 COLLATE utf8_persian_ci,
  `filename` varchar(255) NOT NULL,
  `type` varchar(100) NOT NULL,
  `size` int(11) NOT NULL,
  `status` varchar(20) DEFAULT NULL,
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `posts`
--

INSERT INTO `posts` (`id`, `title`, `content`, `filename`, `type`, `size`, `status`, `created_at`, `updated_at`) VALUES
(1, 'Bamboo', NULL, 'bamboo.jpg', 'image/jpeg', 265437, 'published', NULL, '0000-00-00 00:00:00'),
(4, 'Roof', NULL, 'roof.jpg', 'image/jpeg', 322870, 'published', NULL, '0000-00-00 00:00:00'),
(5, 'Flowers', NULL, 'flowers.jpg', 'image/jpeg', 394552, 'published', NULL, '0000-00-00 00:00:00'),
(6, 'Buddhas', NULL, 'buddhas.jpg', 'image/jpeg', 261152, 'published', NULL, '0000-00-00 00:00:00'),
(7, 'Wall', NULL, 'wall.jpg', 'image/jpeg', 369592, 'published', NULL, '0000-00-00 00:00:00'),
(8, 'Wood', NULL, 'wood.jpg', 'image/jpeg', 353050, 'published', NULL, '0000-00-00 00:00:00'),
(10, 'تست', 'تست', '59d76334a2b7d1507287860.jpg', '', 0, 'published', '2017-10-06 08:37:58', '0000-00-00 00:00:00'),
(12, 'تست', '<p>All Star</p>\r\n<p><span style=\"font-family: \'arial black\', sans-serif;\">All Star</span></p>\r\n<p style=\"text-align: right;\"><span style=\"font-family: \'comic sans ms\', sans-serif;\">All Star</span></p>\r\n<p style=\"text-align: right;\">متن فارسی</p>', '59e11ae93b9901507924713.jpg', '', 0, 'published', '2017-10-07 19:33:17', '2017-10-07 19:33:17'),
(13, 'درخواست سایت', '<p style=\"text-align: right;\">افزودن سایت !!!</p>', '5bf0f13032b9c1542517040.jpg', '', 0, 'published', '2018-11-18 04:56:09', '2018-11-18 04:56:09');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `username` varchar(50) CHARACTER SET utf8 COLLATE utf8_persian_ci NOT NULL,
  `password` varchar(255) DEFAULT NULL,
  `first_name` varchar(30) CHARACTER SET utf8 COLLATE utf8_persian_ci NOT NULL,
  `last_name` varchar(30) CHARACTER SET utf8 COLLATE utf8_persian_ci NOT NULL,
  `email` text,
  `is_admin` tinyint(1) NOT NULL DEFAULT '0',
  `avatar` varchar(30) DEFAULT NULL,
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `username`, `password`, `first_name`, `last_name`, `email`, `is_admin`, `avatar`, `created_at`, `updated_at`) VALUES
(1, 'kskoglund', 'secretpwd', 'Kevin', 'Skoglund', NULL, 0, NULL, NULL, '0000-00-00 00:00:00'),
(5, 'yaztak', '$2y$10$OTgxM2M1YWEyNzc3ODI5NOaWl/rMocOkLEWNvStgICoxgAsW861jy', 'Yazdan', 'Takbiri', 'yaztak@gmail.com', 1, '80tDEtAkjv.png', NULL, '2018-07-08 05:08:08'),
(7, 'yaztak2', '$2y$10$F8x2/SpNubYNNMj.Er2PL.9c7iiW1Cy9OvCd0X/mIT843ZyvRhLXG', ' یزدان', 'تکبیری', 'yaztak@hotmail.com', 0, NULL, '2017-10-09 16:24:42', '2017-10-09 16:24:42');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `comments`
--
ALTER TABLE `comments`
  ADD PRIMARY KEY (`id`),
  ADD KEY `photograph_id` (`post_id`);

--
-- Indexes for table `frontpage`
--
ALTER TABLE `frontpage`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `posts`
--
ALTER TABLE `posts`
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
-- AUTO_INCREMENT for table `comments`
--
ALTER TABLE `comments`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;
--
-- AUTO_INCREMENT for table `frontpage`
--
ALTER TABLE `frontpage`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=125;
--
-- AUTO_INCREMENT for table `posts`
--
ALTER TABLE `posts`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=14;
--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
