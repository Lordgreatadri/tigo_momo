CREATE DATABASE `airtel_tigo_mobile_money` /*!40100 DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci */ /*!80016 DEFAULT ENCRYPTION='N' */;


CREATE TABLE `general_tansactions_callback` (
  `id` bigint NOT NULL AUTO_INCREMENT,
  `is_reversed` enum('True','False') DEFAULT 'False',
  `transaction_id` varchar(255) DEFAULT NULL,
  `external_transaction_id` varchar(255) DEFAULT NULL,
  `correlation_id` varchar(255) DEFAULT NULL,
  `msisdn` varchar(45) DEFAULT NULL,
  `amount` double(45,2) DEFAULT NULL,
  `utibaTransactionId` varchar(255) DEFAULT NULL,
  `payment_status` varchar(50) DEFAULT NULL,
  `response_code` varchar(50) DEFAULT NULL,
  `payment_description` varchar(255) DEFAULT NULL,
  `entry_date` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `reversed_on` timestamp NULL DEFAULT NULL,
  `updated_on` timestamp NULL DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;


INSERT INTO `airtel_tigo_mobile_money`.`general_tansactions_callback`
(`id`,
`is_reversed`,
`transaction_id`,
`external_transaction_id`,
`correlation_id`,
`msisdn`,
`amount`,
`utibaTransactionId`,
`payment_status`,
`response_code`,
`payment_description`,
`entry_date`,
`reversed_on`,
`updated_on`)
VALUES
(<{id: }>,
<{is_reversed: False}>,
<{transaction_id: }>,
<{external_transaction_id: }>,
<{correlation_id: }>,
<{msisdn: }>,
<{amount: }>,
<{utibaTransactionId: }>,
<{payment_status: }>,
<{response_code: }>,
<{payment_description: }>,
<{entry_date: CURRENT_TIMESTAMP}>,
<{reversed_on: }>,
<{updated_on: }>);


CREATE TABLE `general_transactions` (
  `id` bigint NOT NULL AUTO_INCREMENT,
  `is_reversed` enum('True','False') DEFAULT 'False',
  `transaction_id` varchar(255) DEFAULT NULL,
  `external_transaction_id` varchar(255) DEFAULT NULL,
  `correlation_id` varchar(255) DEFAULT NULL,
  `payment_reference` varchar(100) DEFAULT NULL,
  `msisdn` varchar(45) DEFAULT NULL,
  `item` varchar(100) DEFAULT NULL,
  `amount` double(45,2) DEFAULT '0.00',
  `initiate_status` varchar(50) DEFAULT NULL,
  `initiate_code` varchar(50) DEFAULT NULL,
  `initiate_description` varchar(255) DEFAULT NULL,
  `payment_id` varchar(50) DEFAULT NULL,
  `utibaTransactionId` varchar(255) DEFAULT NULL,
  `payment_status` varchar(45) DEFAULT NULL,
  `response_code` varchar(50) DEFAULT NULL,
  `payment_description` varchar(255) DEFAULT NULL,
  `initiate_date` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `reversed_on` timestamp NULL DEFAULT NULL,
  `updated_on` timestamp NULL DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=8 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;


CREATE TABLE `get_transaction_details` (
  `id` bigint NOT NULL AUTO_INCREMENT,
  `tranasactionId` varchar(255) DEFAULT NULL,
  `extTransactionId` varchar(255) DEFAULT NULL,
  `utibaTransactionId` varchar(255) DEFAULT NULL,
  `soaTransactionId` varchar(255) DEFAULT NULL,
  `correlationId` varchar(100) DEFAULT NULL,
  `payerWallet` varchar(45) DEFAULT NULL,
  `payeeWallet` varchar(45) DEFAULT NULL,
  `amount` double(45,2) DEFAULT '0.00',
  `itemName` varchar(100) DEFAULT NULL,
  `status` varchar(45) DEFAULT NULL,
  `paymentReference` varchar(100) DEFAULT NULL,
  `responseCode` varchar(45) DEFAULT NULL,
  `description` varchar(255) DEFAULT NULL,
  `purchaseStatus` varchar(50) DEFAULT NULL,
  `mwCode` varchar(100) DEFAULT NULL,
  `mwDescription` varchar(255) DEFAULT NULL,
  `transactionDate` varchar(100) DEFAULT NULL,
  `additionalInfo` varchar(255) DEFAULT NULL,
  `entry_date` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=12 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

CREATE TABLE `mcc_user_details` (
  `id` int NOT NULL AUTO_INCREMENT,
  `accessKey` varchar(255) DEFAULT NULL,
  `consumer_id` varchar(45) DEFAULT NULL,
  `customer_account` varchar(45) DEFAULT NULL,
  `mw_code` varchar(45) DEFAULT NULL,
  `country` varchar(45) DEFAULT NULL,
  `merchant_name` varchar(100) DEFAULT NULL,
  `username` varchar(100) DEFAULT NULL,
  `userpassword` varchar(100) DEFAULT NULL,
  `webuser` varchar(100) DEFAULT NULL,
  `web_password` varchar(100) DEFAULT NULL,
  `authorization_code` varchar(100) DEFAULT NULL,
  `purchase_initiate_url` varchar(255) DEFAULT NULL,
  `service_payment_url` varchar(255) DEFAULT NULL,
  `get_purchase_details_url` varchar(255) DEFAULT NULL,
  `reverse_transaction_url` varchar(255) DEFAULT NULL,
  `primary_callback_url` varchar(255) DEFAULT NULL,
  `created_date` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `update_on` timestamp NULL DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

CREATE TABLE `reverse_transaction` (
  `rev_id` bigint NOT NULL AUTO_INCREMENT,
  `transaction_id` varchar(255) DEFAULT NULL,
  `external_transaction_id` varchar(255) DEFAULT NULL,
  `request_correlator_id` varchar(255) DEFAULT NULL,
  `request_transaction_id` varchar(255) DEFAULT NULL,
  `transaction_type` varchar(100) DEFAULT NULL,
  `payment_reference` varchar(100) DEFAULT NULL,
  `api_type` varchar(45) DEFAULT NULL,
  `utiba_transaction_id` varchar(100) DEFAULT NULL,
  `status` varchar(45) DEFAULT NULL,
  `response_code` varchar(45) DEFAULT NULL,
  `description` varchar(255) DEFAULT NULL,
  `transaction_date` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `update_time` timestamp NULL DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`rev_id`)
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;


CREATE TABLE `service_payments` (
  `payment_id` int NOT NULL AUTO_INCREMENT,
  `is_reversed` enum('True','False') DEFAULT 'False',
  `ext_transaction_id` varchar(255) DEFAULT NULL,
  `transaction_id` varchar(255) DEFAULT NULL,
  `correlation_id` varchar(255) DEFAULT NULL,
  `result_transaction_id` varchar(255) DEFAULT NULL,
  `user_reference` varchar(255) DEFAULT NULL,
  `source_number` varchar(45) DEFAULT NULL,
  `target_number` varchar(45) DEFAULT NULL,
  `item_name` varchar(255) DEFAULT NULL,
  `amount` double(45,2) DEFAULT '0.00',
  `response_code` varchar(50) DEFAULT NULL,
  `status` varchar(50) DEFAULT NULL,
  `code_type` varchar(255) DEFAULT NULL,
  `description` varchar(300) DEFAULT NULL,
  `additional_info` varchar(255) DEFAULT NULL,
  `entry_date` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `reversed_on` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`payment_id`)
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
