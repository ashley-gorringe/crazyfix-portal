SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";

CREATE TABLE `customer` (
  `customer_id` int(11) NOT NULL,
  `title` varchar(6) NOT NULL,
  `first_name` varchar(50) NOT NULL,
  `last_name` varchar(50) NOT NULL,
  `email_address` varchar(100) NOT NULL,
  `password` tinytext NOT NULL,
  `address_line_1` varchar(100) NOT NULL,
  `address_line_2` varchar(100) NOT NULL,
  `postcode` varchar(8) NOT NULL,
  `phone` varchar(13) NOT NULL,
  `avatar_hash` tinytext NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

CREATE TABLE `customer_vehicle` (
  `customer_vehicle_id` int(11) NOT NULL,
  `customer_id` int(11) NOT NULL,
  `vehicle_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

CREATE TABLE `invoice` (
  `invoice_id` int(11) NOT NULL,
  `issue_date` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `due_date` datetime NOT NULL,
  `paid_date` datetime DEFAULT NULL,
  `total_items` int(11) NOT NULL DEFAULT '0',
  `total_labour` int(11) NOT NULL DEFAULT '0',
  `total_vat` int(11) NOT NULL DEFAULT '0',
  `total_payable` int(11) NOT NULL DEFAULT '0',
  `discount_percentage` int(3) NOT NULL DEFAULT '0',
  `service_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
DELIMITER $$
CREATE TRIGGER `calculate_prices_insert` BEFORE INSERT ON `invoice` FOR EACH ROW BEGIN
  IF (SELECT COUNT(*) FROM service_item WHERE service_id = NEW.service_id) > 0 THEN
    SET NEW.total_items = (SELECT SUM(sub_total) FROM service_item WHERE service_id = NEW.service_id);
  END IF;
  IF (SELECT COUNT(*) FROM labour WHERE service_id = NEW.service_id) > 0 THEN
    SET NEW.total_labour = 6000 * (SELECT SUM(hours) FROM labour WHERE service_id = NEW.service_id);
  END IF;
  SET NEW.total_vat = (NEW.total_items + NEW.total_labour) * 0.2;
  SET NEW.total_payable = NEW.total_items + NEW.total_labour + NEW.total_vat;
END
$$
DELIMITER ;

CREATE TABLE `item` (
  `item_id` int(11) NOT NULL,
  `name` varchar(50) NOT NULL,
  `price` int(11) NOT NULL,
  `is_original` int(1) NOT NULL DEFAULT '0',
  `product_code` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

INSERT INTO `item` (`item_id`, `name`, `price`, `is_original`, `product_code`) VALUES
(1, 'TurboCharge Boost Kit', 39999, 1, '9U6T-JD6K-8F4G'),
(2, 'ProSport Performance Muffler', 19999, 1, 'V2YH-P1ZT-L8EK'),
(3, 'BrakeMax High-Performance Brake Pads', 8999, 1, 'G7FJ-Y6H9-X9L8'),
(4, 'FlexiCoil Suspension Springs', 24999, 0, '1D0Q-F6TJ-K7NL'),
(5, 'SuperFlow Cold Air Intake', 17999, 1, '8B6S-E9WL-P3XK'),
(6, 'DynaBolt Exhaust Headers', 32999, 1, '3N7J-H4YK-L1V6'),
(7, 'MegaSpark Ignition Coils', 14999, 0, 'X4R7-D5N9-H0J1'),
(8, 'ApexRide Shock Absorbers', 29999, 1, 'C8K9-J6H3-F2N0'),
(9, 'TitanDrive Transmission Fluid', 2499, 0, 'A2W3-M7D1-Y9J5'),
(10, 'PowerDrive High Torque Starter', 14999, 1, 'P8Z6-N2F7-H1L0');

CREATE TABLE `labour` (
  `labour_id` int(11) NOT NULL,
  `description` tinytext NOT NULL,
  `hours` int(4) NOT NULL,
  `service_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

CREATE TABLE `login_token` (
  `login_token_id` int(11) NOT NULL,
  `customer_id` int(11) NOT NULL,
  `token` varchar(60) NOT NULL,
  `device_info` tinytext NOT NULL,
  `created_datetime` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `latest_activity` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

CREATE TABLE `service` (
  `service_id` int(11) NOT NULL,
  `start_date` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `complete_date` datetime DEFAULT NULL,
  `customer_id` int(11) NOT NULL,
  `vehicle_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
CREATE TABLE `service_invoice` (
`invoice_id` int(11)
,`issue_date` datetime
,`due_date` datetime
,`paid_date` datetime
,`total_items` int(11)
,`total_labour` int(11)
,`total_vat` int(11)
,`total_payable` int(11)
,`discount_percentage` int(3)
,`customer_title` varchar(6)
,`customer_first_name` varchar(50)
,`customer_last_name` varchar(50)
,`vehicle_make` varchar(50)
,`vehicle_model` varchar(50)
,`vehicle_registration` varchar(7)
);
CREATE TABLE `service_invoice_items` (
`invoice_id` int(11)
,`item_name` varchar(50)
,`product_code` varchar(50)
,`item_price` int(11)
,`is_original` int(1)
,`item_quantity` int(2)
,`item_sub_total` int(11)
);
CREATE TABLE `service_invoice_labour` (
`invoice_id` int(11)
,`labour_description` tinytext
,`labour_hours` int(4)
);

CREATE TABLE `service_item` (
  `service_item_id` int(11) NOT NULL,
  `quantity` int(2) NOT NULL,
  `sub_total` int(11) NOT NULL DEFAULT '0',
  `item_id` int(11) NOT NULL,
  `service_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
DELIMITER $$
CREATE TRIGGER `calculate_sub_total_insert` BEFORE INSERT ON `service_item` FOR EACH ROW BEGIN
SET NEW.sub_total = NEW.quantity * (SELECT price FROM item WHERE item_id = NEW.item_id);
END
$$
DELIMITER ;
DELIMITER $$
CREATE TRIGGER `calculate_sub_total_update` BEFORE UPDATE ON `service_item` FOR EACH ROW BEGIN
SET NEW.sub_total = NEW.quantity * (SELECT price FROM item WHERE item_id = NEW.item_id);
END
$$
DELIMITER ;

CREATE TABLE `vehicle` (
  `vehicle_id` int(11) NOT NULL,
  `make` varchar(50) NOT NULL,
  `model` varchar(50) NOT NULL,
  `registration` varchar(7) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
DROP TABLE IF EXISTS `service_invoice`;

CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `service_invoice`  AS SELECT `invoice`.`invoice_id` AS `invoice_id`, `invoice`.`issue_date` AS `issue_date`, `invoice`.`due_date` AS `due_date`, `invoice`.`paid_date` AS `paid_date`, `invoice`.`total_items` AS `total_items`, `invoice`.`total_labour` AS `total_labour`, `invoice`.`total_vat` AS `total_vat`, `invoice`.`total_payable` AS `total_payable`, `invoice`.`discount_percentage` AS `discount_percentage`, `customer`.`title` AS `customer_title`, `customer`.`first_name` AS `customer_first_name`, `customer`.`last_name` AS `customer_last_name`, `vehicle`.`make` AS `vehicle_make`, `vehicle`.`model` AS `vehicle_model`, `vehicle`.`registration` AS `vehicle_registration` FROM (((`invoice` join `service` on((`invoice`.`service_id` = `service`.`service_id`))) join `customer` on((`service`.`customer_id` = `customer`.`customer_id`))) join `vehicle` on((`service`.`vehicle_id` = `vehicle`.`vehicle_id`)))  ;
DROP TABLE IF EXISTS `service_invoice_items`;

CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `service_invoice_items`  AS SELECT `invoice`.`invoice_id` AS `invoice_id`, `item`.`name` AS `item_name`, `item`.`product_code` AS `product_code`, `item`.`price` AS `item_price`, `item`.`is_original` AS `is_original`, `service_item`.`quantity` AS `item_quantity`, `service_item`.`sub_total` AS `item_sub_total` FROM ((`service_item` join `invoice` on((`service_item`.`service_id` = `invoice`.`invoice_id`))) join `item` on((`service_item`.`item_id` = `item`.`item_id`)))  ;
DROP TABLE IF EXISTS `service_invoice_labour`;

CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `service_invoice_labour`  AS SELECT `invoice`.`invoice_id` AS `invoice_id`, `labour`.`description` AS `labour_description`, `labour`.`hours` AS `labour_hours` FROM (`labour` join `invoice` on((`labour`.`service_id` = `invoice`.`invoice_id`)))  ;


ALTER TABLE `customer`
  ADD PRIMARY KEY (`customer_id`);

ALTER TABLE `customer_vehicle`
  ADD PRIMARY KEY (`customer_vehicle_id`),
  ADD KEY `customer_id` (`customer_id`),
  ADD KEY `vehicle_id` (`vehicle_id`);

ALTER TABLE `invoice`
  ADD PRIMARY KEY (`invoice_id`),
  ADD KEY `service_id` (`service_id`);

ALTER TABLE `item`
  ADD PRIMARY KEY (`item_id`);

ALTER TABLE `labour`
  ADD PRIMARY KEY (`labour_id`),
  ADD KEY `service_id` (`service_id`);

ALTER TABLE `login_token`
  ADD PRIMARY KEY (`login_token_id`),
  ADD KEY `customer_id` (`customer_id`);

ALTER TABLE `service`
  ADD PRIMARY KEY (`service_id`),
  ADD KEY `customer_id` (`customer_id`),
  ADD KEY `vehicle_id` (`vehicle_id`);

ALTER TABLE `service_item`
  ADD PRIMARY KEY (`service_item_id`),
  ADD KEY `service_id` (`service_id`),
  ADD KEY `item_id` (`item_id`);

ALTER TABLE `vehicle`
  ADD PRIMARY KEY (`vehicle_id`);


ALTER TABLE `customer`
  MODIFY `customer_id` int(11) NOT NULL AUTO_INCREMENT;

ALTER TABLE `customer_vehicle`
  MODIFY `customer_vehicle_id` int(11) NOT NULL AUTO_INCREMENT;

ALTER TABLE `invoice`
  MODIFY `invoice_id` int(11) NOT NULL AUTO_INCREMENT;

ALTER TABLE `item`
  MODIFY `item_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

ALTER TABLE `labour`
  MODIFY `labour_id` int(11) NOT NULL AUTO_INCREMENT;

ALTER TABLE `login_token`
  MODIFY `login_token_id` int(11) NOT NULL AUTO_INCREMENT;

ALTER TABLE `service`
  MODIFY `service_id` int(11) NOT NULL AUTO_INCREMENT;

ALTER TABLE `service_item`
  MODIFY `service_item_id` int(11) NOT NULL AUTO_INCREMENT;

ALTER TABLE `vehicle`
  MODIFY `vehicle_id` int(11) NOT NULL AUTO_INCREMENT;


ALTER TABLE `customer_vehicle`
  ADD CONSTRAINT `customer_vehicle_ibfk_1` FOREIGN KEY (`customer_id`) REFERENCES `customer` (`customer_id`) ON DELETE CASCADE,
  ADD CONSTRAINT `customer_vehicle_ibfk_2` FOREIGN KEY (`vehicle_id`) REFERENCES `vehicle` (`vehicle_id`) ON DELETE CASCADE;

ALTER TABLE `invoice`
  ADD CONSTRAINT `invoice_ibfk_1` FOREIGN KEY (`service_id`) REFERENCES `service` (`service_id`) ON DELETE CASCADE;

ALTER TABLE `labour`
  ADD CONSTRAINT `labour_ibfk_1` FOREIGN KEY (`service_id`) REFERENCES `service` (`service_id`) ON DELETE CASCADE;

ALTER TABLE `login_token`
  ADD CONSTRAINT `login_token_ibfk_1` FOREIGN KEY (`customer_id`) REFERENCES `customer` (`customer_id`) ON DELETE CASCADE ON UPDATE CASCADE;

ALTER TABLE `service`
  ADD CONSTRAINT `service_ibfk_1` FOREIGN KEY (`customer_id`) REFERENCES `customer` (`customer_id`) ON DELETE CASCADE,
  ADD CONSTRAINT `service_ibfk_2` FOREIGN KEY (`vehicle_id`) REFERENCES `vehicle` (`vehicle_id`) ON DELETE CASCADE;

ALTER TABLE `service_item`
  ADD CONSTRAINT `service_item_ibfk_1` FOREIGN KEY (`service_id`) REFERENCES `service` (`service_id`) ON DELETE CASCADE,
  ADD CONSTRAINT `service_item_ibfk_2` FOREIGN KEY (`item_id`) REFERENCES `item` (`item_id`) ON DELETE CASCADE;
COMMIT;
