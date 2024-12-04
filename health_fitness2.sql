-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Anamakine: 127.0.0.1
-- Üretim Zamanı: 04 Ara 2024, 13:45:19
-- Sunucu sürümü: 10.4.32-MariaDB
-- PHP Sürümü: 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Veritabanı: health_fitness2
--
CREATE DATABASE IF NOT EXISTS health_fitness2 DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci;
USE health_fitness2;

-- --------------------------------------------------------

--
-- Tablo için tablo yapısı admins
--

CREATE TABLE admins (
  admin_id int(11) NOT NULL,
  username varchar(50) NOT NULL,
  password varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Tablo için tablo yapısı dietitians
--

CREATE TABLE dietitians (
  dietitian_id int(11) NOT NULL,
  first_name varchar(50) NOT NULL,
  last_name varchar(50) NOT NULL,
  username varchar(50) NOT NULL,
  email varchar(100) NOT NULL,
  password varchar(255) NOT NULL,
  identity_number varchar(20) NOT NULL,
  birthdate date NOT NULL,
  gender char(5) NOT NULL,
  university varchar(100) NOT NULL,
  profession varchar(50) NOT NULL,
  is_approved tinyint(1) DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Tablo için tablo yapısı diet_days
--

CREATE TABLE diet_days (
  day_id int(11) NOT NULL,
  package_id int(11) NOT NULL,
  day_number int(11) NOT NULL,
  total_calories decimal(10,2) DEFAULT 0.00
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Tablo için tablo yapısı diet_meals
--

CREATE TABLE diet_meals (
  meal_id int(11) NOT NULL,
  day_id int(11) NOT NULL,
  meal_name varchar(100) NOT NULL,
  amount varchar(50) NOT NULL,
  calories decimal(10,2) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Tablo için tablo yapısı diet_packages
--

CREATE TABLE diet_packages (
  package_id int(11) NOT NULL,
  dietitian_id int(11) NOT NULL,
  package_name varchar(100) NOT NULL,
  price decimal(10,2) NOT NULL,
  days int(11) NOT NULL,
  is_approved tinyint(1) DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Tablo için tablo yapısı messages
--

CREATE TABLE messages (
  message_id int(11) NOT NULL,
  sender_id int(11) NOT NULL,
  receiver_id int(11) NOT NULL,
  message text NOT NULL,
  timestamp timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Tablo için tablo yapısı trainers
--

CREATE TABLE trainers (
  trainer_id int(11) NOT NULL,
  first_name varchar(50) NOT NULL,
  last_name varchar(50) NOT NULL,
  username varchar(50) NOT NULL,
  email varchar(100) NOT NULL,
  password varchar(255) NOT NULL,
  identity_number varchar(20) NOT NULL,
  birthdate date NOT NULL,
  gender char(5) NOT NULL,
  university varchar(100) NOT NULL,
  profession varchar(50) NOT NULL,
  is_approved tinyint(1) DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Tablo için tablo yapısı training_days
--

CREATE TABLE training_days (
  day_id int(11) NOT NULL,
  package_id int(11) NOT NULL,
  day_number int(11) NOT NULL,
  total_calories_burned decimal(10,2) DEFAULT 0.00
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Tablo için tablo yapısı training_exercises
--

CREATE TABLE training_exercises (
  exercise_id int(11) NOT NULL,
  day_id int(11) NOT NULL,
  exercise_name varchar(100) NOT NULL,
  repetitions int(11) NOT NULL,
  calories_burned decimal(10,2) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Tablo için tablo yapısı training_packages
--

CREATE TABLE training_packages (
  package_id int(11) NOT NULL,
  trainer_id int(11) NOT NULL,
  package_name varchar(100) NOT NULL,
  price decimal(10,2) NOT NULL,
  days int(11) NOT NULL,
  is_approved tinyint(1) DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Tablo için tablo yapısı users
--

CREATE TABLE users (
  user_id int(11) NOT NULL,
  first_name varchar(50) NOT NULL,
  last_name varchar(50) NOT NULL,
  username varchar(50) NOT NULL,
  email varchar(100) NOT NULL,
  password varchar(255) NOT NULL,
  birthdate date NOT NULL,
  gender varchar(10) NOT NULL,
  height decimal(5,2) DEFAULT NULL,
  weight decimal(5,2) DEFAULT NULL,
  bmi decimal(5,2) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Tablo için tablo yapısı user_diet_purchases
--

CREATE TABLE user_diet_purchases (
  purchase_id int(11) NOT NULL,
  user_id int(11) NOT NULL,
  package_id int(11) NOT NULL,
  purchase_date date NOT NULL,
  is_completed tinyint(1) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Tablo için tablo yapısı user_training_purchases
--

CREATE TABLE user_training_purchases (
  purchase_id int(11) NOT NULL,
  user_id int(11) NOT NULL,
  package_id int(11) NOT NULL,
  purchase_date date NOT NULL,
  is_completed tinyint(1) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dökümü yapılmış tablolar için indeksler
--

--
-- Tablo için indeksler admins
--
ALTER TABLE admins
  ADD PRIMARY KEY (admin_id),
  ADD UNIQUE KEY username (username);

--
-- Tablo için indeksler dietitians
--
ALTER TABLE dietitians
  ADD PRIMARY KEY (dietitian_id),
  ADD UNIQUE KEY username (username);

--
-- Tablo için indeksler diet_days
--
ALTER TABLE diet_days
  ADD PRIMARY KEY (day_id),
  ADD KEY diet_days_ibfk_1 (package_id);

--
-- Tablo için indeksler diet_meals
--
ALTER TABLE diet_meals
  ADD PRIMARY KEY (meal_id),
  ADD KEY diet_meals_ibfk_1 (day_id);

--
-- Tablo için indeksler diet_packages
--
ALTER TABLE diet_packages
  ADD PRIMARY KEY (package_id),
  ADD KEY dietitian_id (dietitian_id);

--
-- Tablo için indeksler messages
--
ALTER TABLE messages
  ADD PRIMARY KEY (message_id),
  ADD KEY sender_id (sender_id),
  ADD KEY fk_receiver_trainer (receiver_id);

--
-- Tablo için indeksler trainers
--
ALTER TABLE trainers
  ADD PRIMARY KEY (trainer_id),
  ADD UNIQUE KEY username (username);

--
-- Tablo için indeksler training_days
--
ALTER TABLE training_days
  ADD PRIMARY KEY (day_id),
  ADD KEY training_days_ibfk_1 (package_id);

--
-- Tablo için indeksler training_exercises
--
ALTER TABLE training_exercises
  ADD PRIMARY KEY (exercise_id),
  ADD KEY training_exercises_ibfk_1 (day_id);

--
-- Tablo için indeksler training_packages
--
ALTER TABLE training_packages
  ADD PRIMARY KEY (package_id),
  ADD KEY trainer_id (trainer_id);

--
-- Tablo için indeksler users
--
ALTER TABLE users
  ADD PRIMARY KEY (user_id),
  ADD UNIQUE KEY username (username);

--
-- Tablo için indeksler user_diet_purchases
--
ALTER TABLE user_diet_purchases
  ADD PRIMARY KEY (purchase_id),
  ADD KEY user_id (user_id),
  ADD KEY fk_diet_package (package_id);

--
-- Tablo için indeksler user_training_purchases
--
ALTER TABLE user_training_purchases
  ADD PRIMARY KEY (purchase_id),
  ADD KEY user_id (user_id),
  ADD KEY fk_training_package (package_id);

--
-- Dökümü yapılmış tablolar için AUTO_INCREMENT değeri
--

--
-- Tablo için AUTO_INCREMENT değeri admins
--
ALTER TABLE admins
  MODIFY admin_id int(11) NOT NULL AUTO_INCREMENT;

--
-- Tablo için AUTO_INCREMENT değeri dietitians
--
ALTER TABLE dietitians
  MODIFY dietitian_id int(11) NOT NULL AUTO_INCREMENT;

--
-- Tablo için AUTO_INCREMENT değeri diet_days
--
ALTER TABLE diet_days
  MODIFY day_id int(11) NOT NULL AUTO_INCREMENT;

--
-- Tablo için AUTO_INCREMENT değeri diet_meals
--
ALTER TABLE diet_meals
  MODIFY meal_id int(11) NOT NULL AUTO_INCREMENT;

--
-- Tablo için AUTO_INCREMENT değeri diet_packages
--
ALTER TABLE diet_packages
  MODIFY package_id int(11) NOT NULL AUTO_INCREMENT;

--
-- Tablo için AUTO_INCREMENT değeri messages
--
ALTER TABLE messages
  MODIFY message_id int(11) NOT NULL AUTO_INCREMENT;

--
-- Tablo için AUTO_INCREMENT değeri trainers
--
ALTER TABLE trainers
  MODIFY trainer_id int(11) NOT NULL AUTO_INCREMENT;

--
-- Tablo için AUTO_INCREMENT değeri training_days
--
ALTER TABLE training_days
  MODIFY day_id int(11) NOT NULL AUTO_INCREMENT;

--
-- Tablo için AUTO_INCREMENT değeri training_exercises
--
ALTER TABLE training_exercises
  MODIFY exercise_id int(11) NOT NULL AUTO_INCREMENT;

--
-- Tablo için AUTO_INCREMENT değeri training_packages
--
ALTER TABLE training_packages
  MODIFY package_id int(11) NOT NULL AUTO_INCREMENT;

--
-- Tablo için AUTO_INCREMENT değeri users
--
ALTER TABLE users
  MODIFY user_id int(11) NOT NULL AUTO_INCREMENT;

--
-- Tablo için AUTO_INCREMENT değeri user_diet_purchases
--
ALTER TABLE user_diet_purchases
  MODIFY purchase_id int(11) NOT NULL AUTO_INCREMENT;

--
-- Tablo için AUTO_INCREMENT değeri user_training_purchases
--
ALTER TABLE user_training_purchases
  MODIFY purchase_id int(11) NOT NULL AUTO_INCREMENT;

--
-- Dökümü yapılmış tablolar için kısıtlamalar
--

--
-- Tablo kısıtlamaları diet_days
--
ALTER TABLE diet_days
  ADD CONSTRAINT diet_days_ibfk_1 FOREIGN KEY (package_id) REFERENCES diet_packages (package_id) ON DELETE CASCADE;

--
-- Tablo kısıtlamaları diet_meals
--
ALTER TABLE diet_meals
  ADD CONSTRAINT diet_meals_ibfk_1 FOREIGN KEY (day_id) REFERENCES diet_days (day_id) ON DELETE CASCADE;

--
-- Tablo kısıtlamaları diet_packages
--
ALTER TABLE diet_packages
  ADD CONSTRAINT diet_packages_ibfk_1 FOREIGN KEY (dietitian_id) REFERENCES dietitians (dietitian_id);

--
-- Tablo kısıtlamaları messages
--
ALTER TABLE messages
  ADD CONSTRAINT fk_receiver_trainer FOREIGN KEY (receiver_id) REFERENCES trainers (trainer_id) ON DELETE CASCADE,
  ADD CONSTRAINT messages_ibfk_1 FOREIGN KEY (sender_id) REFERENCES `users` (user_id),
  ADD CONSTRAINT messages_ibfk_2 FOREIGN KEY (receiver_id) REFERENCES dietitians (dietitian_id) ON DELETE CASCADE;

--
-- Tablo kısıtlamaları training_days
--
ALTER TABLE training_days
  ADD CONSTRAINT training_days_ibfk_1 FOREIGN KEY (package_id) REFERENCES training_packages (package_id) ON DELETE CASCADE;

--
-- Tablo kısıtlamaları training_exercises
--
ALTER TABLE training_exercises
  ADD CONSTRAINT training_exercises_ibfk_1 FOREIGN KEY (day_id) REFERENCES training_days (day_id) ON DELETE CASCADE;

--
-- Tablo kısıtlamaları training_packages
--
ALTER TABLE training_packages
  ADD CONSTRAINT training_packages_ibfk_1 FOREIGN KEY (trainer_id) REFERENCES trainers (trainer_id);

--
-- Tablo kısıtlamaları user_diet_purchases
--
ALTER TABLE user_diet_purchases
  ADD CONSTRAINT fk_diet_package FOREIGN KEY (package_id) REFERENCES diet_packages (package_id) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT user_diet_purchases_ibfk_1 FOREIGN KEY (user_id) REFERENCES `users` (user_id),
  ADD CONSTRAINT user_diet_purchases_ibfk_2 FOREIGN KEY (package_id) REFERENCES diet_packages (package_id);

--
-- Tablo kısıtlamaları user_training_purchases
--
ALTER TABLE user_training_purchases
  ADD CONSTRAINT fk_training_package FOREIGN KEY (package_id) REFERENCES training_packages (package_id) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT user_training_purchases_ibfk_1 FOREIGN KEY (user_id) REFERENCES `users` (user_id),
  ADD CONSTRAINT user_training_purchases_ibfk_2 FOREIGN KEY (package_id) REFERENCES training_packages (package_id);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
