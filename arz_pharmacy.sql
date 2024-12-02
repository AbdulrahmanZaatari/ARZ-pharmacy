-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Dec 01, 2024 at 06:55 PM
-- Server version: 10.4.32-MariaDB
-- PHP Version: 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `arz_pharmacy_updated`
--

-- --------------------------------------------------------

--
-- Table structure for table `answers`
--

CREATE TABLE `answers` (
  `id` int(11) NOT NULL,
  `question_id` int(11) NOT NULL,
  `answer` text NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `answers`
--

INSERT INTO `answers` (`id`, `question_id`, `answer`, `created_at`) VALUES
(3, 11, 'Oh no!', '2024-11-29 15:57:21'),
(4, 11, 'yay', '2024-11-29 15:57:28');

-- --------------------------------------------------------

--
-- Table structure for table `approval_requests`
--

CREATE TABLE `approval_requests` (
  `id` int(11) NOT NULL,
  `customer_id` int(11) NOT NULL,
  `product_id` int(11) NOT NULL,
  `comment` text DEFAULT NULL,
  `request_date` timestamp NOT NULL DEFAULT current_timestamp(),
  `status` enum('pending','approved','rejected') DEFAULT 'pending',
  `pharmacist_comment` text DEFAULT NULL,
  `processed_date` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `ehr_id` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

--
-- Dumping data for table `approval_requests`
--

INSERT INTO `approval_requests` (`id`, `customer_id`, `product_id`, `comment`, `request_date`, `status`, `pharmacist_comment`, `processed_date`, `ehr_id`) VALUES
(11, 104, 14, NULL, '2024-11-30 11:28:30', 'rejected', 'no', '2024-11-30 22:40:01', 5),
(13, 108, 14, NULL, '2024-11-30 22:42:54', 'pending', NULL, '0000-00-00 00:00:00', 4);

-- --------------------------------------------------------

--
-- Table structure for table `blogs`
--

CREATE TABLE `blogs` (
  `id` int(11) NOT NULL,
  `title` varchar(255) NOT NULL,
  `content` text NOT NULL,
  `image_path` varchar(255) DEFAULT NULL,
  `video_link` varchar(255) DEFAULT NULL,
  `author_id` int(11) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin;

--
-- Dumping data for table `blogs`
--

INSERT INTO `blogs` (`id`, `title`, `content`, `image_path`, `video_link`, `author_id`, `created_at`) VALUES
(1, 'Panadol: Your Go-To Relief for Everyday Pain and Fever', 'Blog Content:\r\nWhen it comes to managing pain and fever, Panadol has become a household name. Trusted by millions around the globe, Panadol is known for its effectiveness, affordability, and wide availability. But what exactly makes Panadol such a reliable option? Let’s take a closer look at this popular medication.\r\n\r\nWhat is Panadol?\r\nPanadol is the brand name for paracetamol, also known as acetaminophen in some parts of the world. It’s a common over-the-counter (OTC) medication used to relieve mild to moderate pain and reduce fever. Whether you’re dealing with a headache, muscle aches, or flu symptoms, Panadol is often the first choice for quick relief.\r\n\r\nHow Does Panadol Work?\r\nPanadol works by inhibiting the production of prostaglandins in the brain. These are chemical compounds responsible for causing pain and inflammation. By blocking their production, Panadol effectively reduces pain and lowers body temperature when you have a fever.\r\n\r\nBenefits of Panadol:\r\nFast-Acting Relief\r\nPanadol provides rapid relief from various types of pain, including headaches, toothaches, back pain, and menstrual cramps.\r\n\r\nSafe When Used Correctly\r\nWhen taken as directed, Panadol is safe for most people, including children and the elderly. It’s often recommended for people who cannot take non-steroidal anti-inflammatory drugs (NSAIDs) like ibuprofen due to stomach or heart concerns.\r\n\r\nGentle on the Stomach\r\nUnlike NSAIDs, Panadol is less likely to cause stomach irritation, making it a good option for individuals with sensitive digestive systems.\r\n\r\nVersatile Options\r\nPanadol is available in various formulations, including tablets, liquid suspensions, and dissolvable powders, catering to different preferences and age groups.\r\n\r\nWhen Should You Use Panadol?\r\nPanadol is effective in treating:\r\n\r\nHeadaches and migraines\r\nFever from colds or flu\r\nMuscle aches and joint pain\r\nToothaches and post-dental pain\r\nMenstrual cramps\r\nSafety Precautions:\r\nWhile Panadol is generally safe, it’s important to follow the recommended dosage guidelines:\r\n\r\nAdults: Usually, 500 mg to 1 g every 4-6 hours as needed, but do not exceed 4 g in 24 hours.\r\nChildren: Dosage varies based on age and weight; consult a doctor or pharmacist for accurate recommendations.\r\nAvoid Overuse: Taking too much Panadol can lead to liver damage. Always check labels for hidden paracetamol in other combination medications.\r\n\r\nWhen to Seek Medical Advice?\r\nConsult a healthcare professional if:\r\n\r\nPain persists for more than a few days.\r\nFever does not improve after 3 days.\r\nYou have underlying liver or kidney conditions.\r\nConclusion:\r\nPanadol has stood the test of time as one of the most trusted pain relievers in the world. Its versatility and ease of use make it a go-to option for many individuals seeking relief from pain and fever. However, as with any medication, it’s crucial to use Panadol responsibly and seek medical advice if symptoms persist.\r\n\r\nSo, the next time you’re feeling under the weather, reach for Panadol – your trusted companion for effective relief!\r\n\r\nDisclaimer: Always consult a healthcare professional before starting any medication.', 'uploads/blogs/images.jpg', 'https://youtu.be/vX9NwI04v-o?si=qNVZScvoYiUsBFTq', 6, '2024-11-30 12:29:02');

-- --------------------------------------------------------

--
-- Table structure for table `cart`
--

CREATE TABLE `cart` (
  `customer_id` int(11) NOT NULL,
  `product_id` int(11) NOT NULL,
  `price` decimal(10,2) NOT NULL,
  `name` varchar(100) NOT NULL,
  `image_path` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `customers`
--

CREATE TABLE `customers` (
  `id` int(11) NOT NULL,
  `first_name` varchar(50) NOT NULL,
  `last_name` varchar(50) NOT NULL,
  `birth_date` date NOT NULL,
  `phone_number` varchar(15) NOT NULL,
  `address` text NOT NULL,
  `email` varchar(100) NOT NULL,
  `username` varchar(50) DEFAULT NULL,
  `password` varchar(255) NOT NULL,
  `profile_picture` varchar(255) DEFAULT NULL,
  `question1_answer` varchar(255) DEFAULT NULL,
  `question2_answer` varchar(255) DEFAULT NULL,
  `status` enum('active','blocked') DEFAULT 'active',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `register` enum('incomplete','complete') DEFAULT 'incomplete',
  `role` enum('customer','pharmacist','owner') NOT NULL DEFAULT 'customer'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

--
-- Dumping data for table `customers`
--

INSERT INTO `customers` (`id`, `first_name`, `last_name`, `birth_date`, `phone_number`, `address`, `email`, `username`, `password`, `profile_picture`, `question1_answer`, `question2_answer`, `status`, `created_at`, `register`, `role`) VALUES
(1, 'John', 'Doe', '1990-05-15', '1234567890', '123 Main Street, Springfield', 'johndoe@example.com', 'johndoe', '$2b$12$foxXQSFN946Vz0ZRIMiAH.UnqqCDV6uMf8sL55vo3ghXrN9au.Scq', 'uploads/profile1.jpg', 'Buddy', 'Mary', 'active', '2024-11-23 12:30:00', 'incomplete', 'customer'),
(2, 'Alice', 'Johnson', '1992-03-15', '+1234567890', '123 Maple Street, Cityville', 'alice.johnson@example.com', 'alicejohnson', '482c811da5d5b4bc6d497ffa98491e38', 'images/customers/alice.jpg', 'Blue', 'London', 'active', '2024-11-23 22:52:51', 'incomplete', 'customer'),
(3, 'Bob', 'Smith', '1985-09-20', '+9876543210', '456 Elm Street, Townsville', 'bob.smith@example.com', 'bobsmith', 'bb77d0d3b3f239fa5db73bdf27b8d29a', 'images/customers/bob.jpg', 'Green', 'Paris', 'active', '2024-11-23 22:52:51', 'incomplete', 'customer'),
(104, 'Abdul Rahman', 'Al Zaatari', '2024-11-03', '+96181906611', 'Beirut hamra', 'abdulrahman.alzaatari@lau.edu', NULL, '$2y$10$ID2UvUnWICrNFRh23XF31uOfqeIPpwXCGQlOaUw0aRRpZOh2ua.B6', NULL, 'Fatima', 'Gardenia', 'active', '2024-11-24 12:35:36', 'incomplete', 'customer'),
(106, 'Abdul Rahman', 'Al Zaatari', '2024-11-03', '+96181906619', 'Beirut hamra', 'abedalzaatari@gmail.com', NULL, '$2y$10$h3o8Ovd.1ELV7v8jvfSKT.JXGUCl7lXbE/DOKiYZo7iP78ZFBWjJK', NULL, 'Fatima', 'Gardenia', 'active', '2024-11-24 12:50:14', 'incomplete', 'customer'),
(107, 'Abdul Rahman', 'Al Zaatari', '2004-03-31', '+96181906611', 'Beirut hamra', 'zaatariabdulrahman@gmail.com', NULL, '$2y$10$10kKmkETnza/3pr3uiw8/eKe/.mYEpJcfqd58Z/Mnk2Lzb667MQnW', NULL, 'Fatima', 'Gardenia', 'active', '2024-11-25 17:58:52', 'incomplete', 'customer'),
(108, 'Karl', 'Ghanem', '2024-11-29', '81668802', 'jounieh', 'Karl.ghanem2004@gmail.com', NULL, '$2y$10$2EZGACeHaR6S9mpGzELho.t1DHq4xyl0V9rkwtEWoeqiyOVSxj7.C', NULL, 'kk', 'kk', 'active', '2024-11-29 20:27:10', 'complete', 'customer');

-- --------------------------------------------------------

--
-- Table structure for table `daily_sales`
--

CREATE TABLE `daily_sales` (
  `id` int(11) NOT NULL,
  `sale_date` date NOT NULL,
  `total_sales` decimal(10,2) NOT NULL DEFAULT 0.00,
  `total_orders` int(11) NOT NULL DEFAULT 0,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin;

--
-- Dumping data for table `daily_sales`
--

INSERT INTO `daily_sales` (`id`, `sale_date`, `total_sales`, `total_orders`, `created_at`) VALUES
(3, '2024-12-01', 16.00, 2, '2024-12-01 17:09:59');

-- --------------------------------------------------------

--
-- Table structure for table `electronic_health_records`
--

CREATE TABLE `electronic_health_records` (
  `id` int(11) NOT NULL,
  `customer_id` int(11) NOT NULL,
  `chronic_conditions` text DEFAULT NULL,
  `allergies` text DEFAULT NULL,
  `current_height` varchar(255) DEFAULT NULL,
  `current_weight` varchar(255) DEFAULT NULL,
  `family_history` text DEFAULT NULL,
  `medications` text DEFAULT NULL,
  `supplements` text DEFAULT NULL,
  `tobacco_use` varchar(255) DEFAULT NULL,
  `tobacco_details` text DEFAULT NULL,
  `alcohol_consumption` varchar(255) DEFAULT NULL,
  `alcohol_details` text DEFAULT NULL,
  `physical_exercise` varchar(255) DEFAULT NULL,
  `sitting_hours` varchar(255) DEFAULT NULL,
  `diet_description` text DEFAULT NULL,
  `dietary_restrictions` text DEFAULT NULL,
  `sleep_hours` varchar(255) DEFAULT NULL,
  `sleep_concerns` text DEFAULT NULL,
  `stress_level` varchar(255) DEFAULT NULL,
  `mental_health` text DEFAULT NULL,
  `additional_notes` text DEFAULT NULL,
  `vaccination_status` varchar(255) DEFAULT NULL,
  `flu_shot` varchar(255) DEFAULT NULL,
  `health_insurance` varchar(255) DEFAULT NULL,
  `insurance_provider_name` text DEFAULT NULL,
  `policy_number` text DEFAULT NULL,
  `group_number` text DEFAULT NULL,
  `emergency_contact_name` text DEFAULT NULL,
  `emergency_contact_relationship` text DEFAULT NULL,
  `emergency_contact_phone` text DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dumping data for table `electronic_health_records`
--

INSERT INTO `electronic_health_records` (`id`, `customer_id`, `chronic_conditions`, `allergies`, `current_height`, `current_weight`, `family_history`, `medications`, `supplements`, `tobacco_use`, `tobacco_details`, `alcohol_consumption`, `alcohol_details`, `physical_exercise`, `sitting_hours`, `diet_description`, `dietary_restrictions`, `sleep_hours`, `sleep_concerns`, `stress_level`, `mental_health`, `additional_notes`, `vaccination_status`, `flu_shot`, `health_insurance`, `insurance_provider_name`, `policy_number`, `group_number`, `emergency_contact_name`, `emergency_contact_relationship`, `emergency_contact_phone`, `created_at`, `updated_at`) VALUES
(1, 1, 'None', 'None', '170 cm', '70 kg', 'None', 'None', 'None', 'No', 'N/A', 'No', 'N/A', 'Daily', '4-6 hours', 'Balanced', 'None', '7-8 hours', 'None', '5', 'Good', 'None', 'Yes', 'Yes', 'Yes', 'Test Insurance', '123456789', '987654321', 'John Doe', 'Friend', '1234567890', '2024-11-24 12:15:03', '2024-11-24 12:15:03'),
(2, 106, 'yes', 'yes', '170 cm', '70 kgs', 'Grandma, Diabetes', 'panadol', 'Vitamin D', 'yes', '7', 'yes', 'hana', '1-2 times per week', '2-4 hours', 'other', 'hih', '5-6 hours', 'difficulty_falling_asleep', '8', 'yes', 'no', 'no', NULL, NULL, 'hh', '989', '989', 'Mama', 'MAMA', '81906618', '2024-11-24 13:07:51', '2024-11-24 13:07:51'),
(3, 107, 'no', 'no', '170 cm', '70 kgs', 'Grandma, Diabetes', '', 'Vitamin D', 'no', '', 'no', '', '1-2 times per week', '4-6 hours', 'balanced', '', '5-6 hours', '', '4', 'no', '', 'yes', NULL, NULL, 'Government', '12881791', '', 'Dina Mortada', 'Mother', '81906618', '2024-11-25 18:01:30', '2024-11-25 18:01:30'),
(4, 108, 'no', 'no', '5', '5', '', '', NULL, 'no', NULL, NULL, NULL, NULL, NULL, '0', NULL, '5-6 hours', NULL, NULL, NULL, NULL, 'yes', NULL, NULL, 'kkkk', '193093', '11313', 'kkk', 'kkk', '816666666', '2024-11-29 20:28:52', '2024-11-29 20:28:52'),
(5, 104, 'None', 'None', '170 cm', '70 kgs', 'Grandma, Diabetes', 'None', 'Vitamin D', 'None', 'None', 'None', 'None', '4-6 hours a week', '4-6 hours a day', 'none', 'Halal', '5-6 hours', 'None', '8', 'None', NULL, 'Yes', NULL, NULL, 'Government', '89071', '', 'Dina Mortada', 'Mom', '81906611', '2024-11-30 11:28:15', '2024-11-30 13:54:52');

-- --------------------------------------------------------

--
-- Table structure for table `orders`
--

CREATE TABLE `orders` (
  `id` int(11) NOT NULL,
  `customer_id` int(11) NOT NULL,
  `order_date` datetime DEFAULT current_timestamp(),
  `total_amount` decimal(10,2) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dumping data for table `orders`
--

INSERT INTO `orders` (`id`, `customer_id`, `order_date`, `total_amount`) VALUES
(24, 104, '2024-12-01 19:09:59', 4.00),
(25, 104, '2024-12-01 19:49:16', 12.00);

-- --------------------------------------------------------

--
-- Table structure for table `order_items`
--

CREATE TABLE `order_items` (
  `id` int(11) NOT NULL,
  `order_id` int(11) NOT NULL,
  `product_id` int(11) NOT NULL,
  `quantity` int(11) DEFAULT 1,
  `price` decimal(10,2) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

--
-- Dumping data for table `order_items`
--

INSERT INTO `order_items` (`id`, `order_id`, `product_id`, `quantity`, `price`) VALUES
(14, 24, 12, 2, 2.00),
(15, 25, 15, 2, 6.00);

-- --------------------------------------------------------

--
-- Table structure for table `owners`
--

CREATE TABLE `owners` (
  `id` int(11) NOT NULL,
  `first_name` varchar(50) NOT NULL,
  `last_name` varchar(50) NOT NULL,
  `phone_number` varchar(15) NOT NULL,
  `email` varchar(100) NOT NULL,
  `role` enum('owner','pharmacist','customer') DEFAULT 'owner',
  `username` varchar(50) NOT NULL,
  `password` varchar(255) NOT NULL,
  `profile_picture` varchar(255) DEFAULT NULL,
  `status` enum('active','blocked') DEFAULT 'active',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

--
-- Dumping data for table `owners`
--

INSERT INTO `owners` (`id`, `first_name`, `last_name`, `phone_number`, `email`, `role`, `username`, `password`, `profile_picture`, `status`, `created_at`) VALUES
(1, 'Admin', 'User', '+96112345678', 'admin@arzpharmacy.com', 'owner', 'adminuser', 'adminpassword123', 'Headshot.jpg', 'active', '2024-11-26 23:32:30');

-- --------------------------------------------------------

--
-- Table structure for table `pharmacists`
--

CREATE TABLE `pharmacists` (
  `id` int(11) NOT NULL,
  `first_name` varchar(50) NOT NULL,
  `last_name` varchar(50) NOT NULL,
  `birth_date` date NOT NULL,
  `phone_number` varchar(15) NOT NULL,
  `address` text NOT NULL,
  `email` varchar(100) NOT NULL,
  `password` varchar(255) DEFAULT NULL,
  `profile_picture` varchar(255) DEFAULT NULL,
  `license_number` varchar(50) NOT NULL,
  `degree` text NOT NULL,
  `certifications` text DEFAULT NULL,
  `status` enum('active','blocked','pending') DEFAULT 'pending',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `role` enum('pharmacist','customer','owner') NOT NULL DEFAULT 'pharmacist'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

--
-- Dumping data for table `pharmacists`
--

INSERT INTO `pharmacists` (`id`, `first_name`, `last_name`, `birth_date`, `phone_number`, `address`, `email`, `password`, `profile_picture`, `license_number`, `degree`, `certifications`, `status`, `created_at`, `role`) VALUES
(6, 'Abdul Rahman', 'Pharmacist', '1990-01-01', '+1234567890', '123 Test Street', 'pharmacist@test.com', 'testpassword', 'logoARZ (1).png', 'PHARM12345', 'Pharmacy Degree', 'Certified Pharmacist', 'active', '2024-11-26 23:16:15', 'pharmacist'),
(7, 'Fatima', 'Srour', '2004-01-01', '+96181906619', 'Beirut hamra', 'www.abed2004@gmail.com', '$2y$10$c4dL4nfCraxb0RkWQc4Ov.dsisiw8ZrfkoFzW0I2oEbWNNby1EyiK', 'uploads/pharmacist_requests/fatima.jpg', '19802', 'LAU', 'Best pharmacy student award', 'active', '2024-11-28 02:15:04', 'pharmacist'),
(11, 'Alice', 'Smith', '1987-10-22', '+9876543210', '456 Maple Avenue, Townsville', 'alicesmith@example.com', '$2y$10$xyz789hashedpasswordexample', 'uploads/pharmacist_requests/alice_smith.jpg', 'LIC67890', 'MSc in Clinical Pharmacy', 'Certified Clinical Pharmacist', 'active', '2024-11-28 02:42:49', 'pharmacist');

-- --------------------------------------------------------

--
-- Table structure for table `pharmacist_verification_requests`
--

CREATE TABLE `pharmacist_verification_requests` (
  `id` int(11) NOT NULL,
  `first_name` varchar(50) NOT NULL,
  `last_name` varchar(50) NOT NULL,
  `birth_date` date DEFAULT NULL,
  `email` varchar(100) NOT NULL,
  `password` varchar(255) NOT NULL,
  `profile_picture` varchar(255) DEFAULT NULL,
  `license_number` varchar(50) NOT NULL,
  `phone_number` varchar(15) NOT NULL,
  `address` text NOT NULL,
  `degree` text NOT NULL,
  `certifications` text DEFAULT NULL,
  `request_date` timestamp NOT NULL DEFAULT current_timestamp(),
  `status` enum('pending','approved','rejected') DEFAULT 'pending',
  `reviewed_by` int(11) DEFAULT NULL,
  `review_date` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `comments` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

--
-- Dumping data for table `pharmacist_verification_requests`
--

INSERT INTO `pharmacist_verification_requests` (`id`, `first_name`, `last_name`, `birth_date`, `email`, `password`, `profile_picture`, `license_number`, `phone_number`, `address`, `degree`, `certifications`, `request_date`, `status`, `reviewed_by`, `review_date`, `comments`) VALUES
(20, 'John', 'Doe', '1985-03-15', 'john.doe@example.com', 'hashed_password_123', 'uploads/john.jpg', 'LN12345', '1234567890', '123 Main St', 'Pharm.D', 'Certified Pharmacist', '2024-11-28 03:40:46', 'pending', NULL, '0000-00-00 00:00:00', NULL),
(21, 'Jane', 'Smith', '1990-07-25', 'jane.smith@example.com', 'hashed_password_456', 'uploads/jane.jpg', 'LN67890', '0987654321', '456 Elm St', 'M.Sc in Pharmacy', 'Clinical Pharmacist Certification', '2024-11-28 03:40:46', 'pending', NULL, '0000-00-00 00:00:00', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `products`
--

CREATE TABLE `products` (
  `id` int(11) NOT NULL,
  `name` varchar(100) NOT NULL,
  `description` text DEFAULT NULL,
  `symptoms` enum('headache','cough','fever','fatigue','nausea','other') DEFAULT NULL,
  `type` enum('medications','products','equipments') NOT NULL,
  `approval` enum('no approval needed','pharmacist check','prescription needed','ministry of health check') NOT NULL,
  `price` decimal(10,2) NOT NULL,
  `quantity` int(11) NOT NULL,
  `added_by` int(11) DEFAULT NULL,
  `image_path` varchar(255) DEFAULT NULL,
  `page` enum('cosmetic','medicine') NOT NULL DEFAULT 'medicine'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

--
-- Dumping data for table `products`
--

INSERT INTO `products` (`id`, `name`, `description`, `symptoms`, `type`, `approval`, `price`, `quantity`, `added_by`, `image_path`, `page`) VALUES
(12, 'Panadol', 'Panadol, a painkiller used for lessening pain and headaches.', 'headache', 'medications', 'no approval needed', 2.00, 20, NULL, 'uploads/product_images/images.jpg', 'medicine'),
(13, 'Carrot sun lotion', 'Used for sun protection.', 'fatigue', 'medications', 'no approval needed', 7.00, 3, NULL, 'uploads/product_images/carrot sun lotion.jpg', 'cosmetic'),
(14, 'Augmentin 1g', 'Antibiotic used for treating fevers and killing bacteria.', 'fever', 'medications', 'pharmacist check', 5.00, 10, NULL, 'uploads/product_images/Augmentin.jpg', 'medicine'),
(15, 'Aerus', 'aerus', 'other', 'medications', 'no approval needed', 6.00, 6, NULL, 'uploads/product_images/person.jpg', 'medicine');

-- --------------------------------------------------------

--
-- Table structure for table `questions`
--

CREATE TABLE `questions` (
  `id` int(11) NOT NULL,
  `user_id` int(11) DEFAULT 0,
  `email` varchar(255) NOT NULL,
  `subject` varchar(255) DEFAULT NULL,
  `message` text NOT NULL,
  `pinned` tinyint(1) NOT NULL DEFAULT 0,
  `answered` tinyint(1) NOT NULL DEFAULT 0,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `questions`
--

INSERT INTO `questions` (`id`, `user_id`, `email`, `subject`, `message`, `pinned`, `answered`, `created_at`) VALUES
(11, NULL, '', 'Is it okay if I eat vaseline?', 'I love the taste of vaseline and sometimes and I eat it. Will that cause any problems?', 1, 0, '2024-11-28 03:34:31'),
(16, 104, 'abdulrahman.alzaatari@lau.edu', 'How can I improve my health?', 'I feel I am getting old, how can I improve my health?', 0, 0, '2024-11-29 17:58:12');

-- --------------------------------------------------------

--
-- Table structure for table `sales_records`
--

CREATE TABLE `sales_records` (
  `id` int(11) NOT NULL,
  `sale_date` date NOT NULL,
  `total_price` decimal(10,2) NOT NULL DEFAULT 0.00
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin;

--
-- Dumping data for table `sales_records`
--

INSERT INTO `sales_records` (`id`, `sale_date`, `total_price`) VALUES
(3, '2024-12-01', 16.00);

-- --------------------------------------------------------

--
-- Table structure for table `sales_records_orders`
--

CREATE TABLE `sales_records_orders` (
  `id` int(11) NOT NULL,
  `sales_record_id` int(11) NOT NULL,
  `order_id` int(11) NOT NULL,
  `order_date` datetime NOT NULL,
  `order_total` decimal(10,2) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin;

--
-- Dumping data for table `sales_records_orders`
--

INSERT INTO `sales_records_orders` (`id`, `sales_record_id`, `order_id`, `order_date`, `order_total`) VALUES
(6, 3, 24, '2024-12-01 00:00:00', 4.00),
(7, 3, 25, '2024-12-01 00:00:00', 12.00);

-- --------------------------------------------------------

--
-- Table structure for table `stock_records`
--

CREATE TABLE `stock_records` (
  `id` int(11) NOT NULL,
  `product_id` int(11) NOT NULL,
  `quantity_added` int(11) NOT NULL,
  `supplier_name` varchar(100) DEFAULT NULL,
  `added_by` int(11) DEFAULT NULL,
  `added_date` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `task_assignments`
--

CREATE TABLE `task_assignments` (
  `id` int(11) NOT NULL,
  `pharmacist_id` int(11) NOT NULL,
  `task_type` enum('communication','manage_products','review_requests','other') NOT NULL,
  `description` text NOT NULL,
  `due_date` date DEFAULT NULL,
  `assigned_by` int(11) DEFAULT NULL,
  `assigned_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

--
-- Dumping data for table `task_assignments`
--

INSERT INTO `task_assignments` (`id`, `pharmacist_id`, `task_type`, `description`, `due_date`, `assigned_by`, `assigned_at`) VALUES
(4, 6, 'manage_products', 'Review and update the inventory for Category X', '2024-12-01', 1, '2024-11-26 23:52:00');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `answers`
--
ALTER TABLE `answers`
  ADD PRIMARY KEY (`id`),
  ADD KEY `question_id` (`question_id`);

--
-- Indexes for table `approval_requests`
--
ALTER TABLE `approval_requests`
  ADD PRIMARY KEY (`id`),
  ADD KEY `customer_id` (`customer_id`),
  ADD KEY `product_id` (`product_id`),
  ADD KEY `fk_ehr_id` (`ehr_id`);

--
-- Indexes for table `blogs`
--
ALTER TABLE `blogs`
  ADD PRIMARY KEY (`id`),
  ADD KEY `author_id` (`author_id`);

--
-- Indexes for table `cart`
--
ALTER TABLE `cart`
  ADD PRIMARY KEY (`customer_id`,`product_id`),
  ADD KEY `product_id` (`product_id`);

--
-- Indexes for table `customers`
--
ALTER TABLE `customers`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `email` (`email`),
  ADD UNIQUE KEY `username` (`username`);

--
-- Indexes for table `daily_sales`
--
ALTER TABLE `daily_sales`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `sale_date` (`sale_date`);

--
-- Indexes for table `electronic_health_records`
--
ALTER TABLE `electronic_health_records`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `orders`
--
ALTER TABLE `orders`
  ADD PRIMARY KEY (`id`),
  ADD KEY `customer_id` (`customer_id`);

--
-- Indexes for table `order_items`
--
ALTER TABLE `order_items`
  ADD PRIMARY KEY (`id`),
  ADD KEY `order_id` (`order_id`),
  ADD KEY `product_id` (`product_id`);

--
-- Indexes for table `owners`
--
ALTER TABLE `owners`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `email` (`email`),
  ADD UNIQUE KEY `username` (`username`);

--
-- Indexes for table `pharmacists`
--
ALTER TABLE `pharmacists`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `email` (`email`);

--
-- Indexes for table `pharmacist_verification_requests`
--
ALTER TABLE `pharmacist_verification_requests`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `email` (`email`),
  ADD KEY `reviewed_by` (`reviewed_by`);

--
-- Indexes for table `sales_records`
--
ALTER TABLE `sales_records`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `sale_date` (`sale_date`);

--
-- Indexes for table `sales_records_orders`
--
ALTER TABLE `sales_records_orders`
  ADD PRIMARY KEY (`id`),
  ADD KEY `sales_record_id` (`sales_record_id`),
  ADD KEY `order_id` (`order_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `approval_requests`
--
ALTER TABLE `approval_requests`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=15;

--
-- AUTO_INCREMENT for table `daily_sales`
--
ALTER TABLE `daily_sales`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `orders`
--
ALTER TABLE `orders`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=26;

--
-- AUTO_INCREMENT for table `order_items`
--
ALTER TABLE `order_items`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=16;

--
-- AUTO_INCREMENT for table `sales_records`
--
ALTER TABLE `sales_records`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `sales_records_orders`
--
ALTER TABLE `sales_records_orders`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `sales_records_orders`
--
ALTER TABLE `sales_records_orders`
  ADD CONSTRAINT `sales_records_orders_ibfk_1` FOREIGN KEY (`sales_record_id`) REFERENCES `sales_records` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `sales_records_orders_ibfk_2` FOREIGN KEY (`order_id`) REFERENCES `orders` (`id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
