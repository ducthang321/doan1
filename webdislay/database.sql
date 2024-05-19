-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Máy chủ: 127.0.0.1
-- Thời gian đã tạo: Th12 30, 2023 lúc 09:57 AM
-- Phiên bản máy phục vụ: 10.4.28-MariaDB
-- Phiên bản PHP: 8.2.4

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Cơ sở dữ liệu: `Fridge`
--

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `fruits`
--

CREATE TABLE `fruits` (
  `id` int(11) NOT NULL,
  `name` varchar(255) DEFAULT NULL,
  `quantity` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;






CREATE TABLE `all_information` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `fruits_id` int(11) DEFAULT NULL,
  `name` varchar(255) DEFAULT NULL,
  `quantity` int(11) DEFAULT NULL,
  `time` time DEFAULT NULL,
  `date` date DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;



CREATE TABLE `quan_information` (
  `id` int(11) NOT NULL,
  `fruits_id` int(11) DEFAULT NULL,
  `name` varchar(255) DEFAULT NULL,
  `quantity` int(11) DEFAULT NULL,
  `time` time DEFAULT NULL,
  `date` date DEFAULT NULL,
  PRIMARY KEY(`id`)
)ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
--
-- Chỉ mục cho các bảng đã đổ
--

--
-- Chỉ mục cho bảng `fruits`
--
ALTER TABLE `fruits`
  ADD PRIMARY KEY (`id`);




--
-- Các ràng buộc cho các bảng đã đổ
--

--
-- Các ràng buộc cho bảng `information`
--
ALTER TABLE `all_information` 
ADD CONSTRAINT `information_ibfk_1` 
FOREIGN KEY (`fruits_id`) 
REFERENCES `fruits` (`id`);
COMMIT;

DELIMITER //
CREATE TRIGGER update_quan_information
AFTER INSERT ON all_information
FOR EACH ROW
BEGIN
    INSERT INTO quan_information (fruits_id, name,quantity, time, date)
    VALUES (NEW.fruits_id, NEW.name, NEW.quantity, NEW.time, NEW.date)
    ON DUPLICATE KEY UPDATE
    name = VALUES(name),
    quantity = VALUES(quantity),
    time = VALUES(time),
    date = VALUES(date);
END;
//
DELIMITER ;
---------
DELIMITER $$
CREATE TRIGGER insert_into_fruits_trigger
AFTER INSERT ON quan_information
FOR EACH ROW
BEGIN
    INSERT INTO fruits (id, name, quantity)
    VALUES (NEW.fruits_id, NEW.name, NEW.quantity);
END$$
DELIMITER ;


/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
 
