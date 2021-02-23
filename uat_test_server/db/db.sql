CREATE DATABASE `pcabinet` /*!40100 DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci */ /*!80016 DEFAULT ENCRYPTION='N' */;


CREATE TABLE `momo_payments` (
  `id` int NOT NULL AUTO_INCREMENT,
  `transaction_id` varchar(255) DEFAULT NULL,
  `external_transaction_id` varchar(255) DEFAULT NULL,
  `correlation_id` varchar(255) DEFAULT NULL,
  `initiate_status` varchar(45) DEFAULT NULL,
  `initiate_code` varchar(45) DEFAULT NULL,
  `initiate_description` varchar(255) DEFAULT NULL,
  `payment_id` varchar(255) DEFAULT NULL,
  `payment_reference` varchar(255) DEFAULT NULL,
  `response_code` varchar(45) DEFAULT NULL,
  `utibaTransactionId` varchar(255) DEFAULT NULL,
  `response_description` varchar(255) DEFAULT NULL,
  `contestant_num` varchar(45) DEFAULT NULL,
  `contestant_name` varchar(100) DEFAULT NULL,
  `cabinet` varchar(100) DEFAULT NULL,
  `momo_number` varchar(45) DEFAULT NULL,
  `amount` double DEFAULT '0',
  `entry_date` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=62 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

CREATE TABLE `contestants` (
  `contestant_id` int NOT NULL,
  `name` varchar(45) DEFAULT NULL,
  `num_of_votes` int DEFAULT '0',
  `category` varchar(255) DEFAULT NULL,
  `cabinet` varchar(255) DEFAULT NULL,
  `contestant_num` varchar(45) DEFAULT NULL,
  `thumbnail` varchar(255) DEFAULT NULL,
  `last_voteddate` timestamp NULL DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP,
  `entry_date` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`contestant_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

CREATE TABLE `completed_transactions` (
  `id` int NOT NULL AUTO_INCREMENT,
  `transaction_id` varchar(255) DEFAULT NULL,
  `external_transaction_id` varchar(255) DEFAULT NULL,
  `correlation_id` varchar(255) DEFAULT NULL,
  `status` varchar(45) DEFAULT NULL,
  `response_code` varchar(45) DEFAULT NULL,
  `customer_msisdn` varchar(45) DEFAULT NULL,
  `description` varchar(255) DEFAULT NULL,
  `amount` double DEFAULT '0',
  `entry_date` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `update_at` timestamp NULL DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=8 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;





