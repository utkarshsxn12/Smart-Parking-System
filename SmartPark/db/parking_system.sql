-- SmartPark: booking schema
CREATE DATABASE IF NOT EXISTS `parking_system`
  CHARACTER SET utf8mb4
  COLLATE utf8mb4_unicode_ci;

USE `parking_system`;

CREATE TABLE IF NOT EXISTS `bookings` (
  `id` INT AUTO_INCREMENT PRIMARY KEY,
  `booking_id` VARCHAR(32) NOT NULL UNIQUE,
  `slot_number` VARCHAR(20) NOT NULL,
  `booking_date` DATE NOT NULL,
  `check_in_time` TIME NOT NULL,
  `check_out_time` TIME NOT NULL,
  `user_name` VARCHAR(150) NOT NULL,
  `user_email` VARCHAR(255) NOT NULL,
  `user_phone` VARCHAR(30) NOT NULL,
  `amount` DECIMAL(10,2) DEFAULT NULL,
  `status` VARCHAR(20) DEFAULT 'confirmed',
  `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE INDEX `idx_bookings_email_date` ON `bookings` (`user_email`, `booking_date`);

