SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";

CREATE TABLE `client` (
  `client_id` int(11) NOT NULL,
  `team_id` int(11) NOT NULL,
  `client_slug` varchar(32) NOT NULL,
  `client_name` tinytext NOT NULL,
  `contact_email` tinytext NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

CREATE TABLE `domain` (
  `domain_id` int(11) NOT NULL,
  `domain_slug` varchar(32) NOT NULL,
  `team_id` int(11) NOT NULL,
  `domain_name` tinytext NOT NULL,
  `dns_a` tinytext,
  `dns_ns` tinytext
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

CREATE TABLE `domain_site` (
  `domain_site_id` int(11) NOT NULL,
  `domain_id` int(11) NOT NULL,
  `site_id` int(11) NOT NULL,
  `issue_dns` int(1) NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

CREATE TABLE `server` (
  `server_id` int(11) NOT NULL,
  `team_id` int(11) NOT NULL,
  `server_slug` varchar(32) NOT NULL,
  `server_name` tinytext NOT NULL,
  `ip_address` tinytext NOT NULL,
  `provider` tinytext NOT NULL,
  `login_link` tinytext NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

CREATE TABLE `site` (
  `site_id` int(11) NOT NULL,
  `site_slug` varchar(32) NOT NULL,
  `team_id` int(11) NOT NULL,
  `server_id` int(11) NOT NULL,
  `client_id` int(11) DEFAULT NULL,
  `site_name` tinytext NOT NULL,
  `primary_domain_id` int(11) NOT NULL,
  `updated_datetime` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `created_datetime` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `issue_flag` int(1) NOT NULL DEFAULT '0',
  `issue_dns` int(1) NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

CREATE TABLE `team` (
  `team_id` int(11) NOT NULL,
  `name` tinytext NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

CREATE TABLE `user` (
  `user_id` int(11) NOT NULL,
  `team_id` int(11) NOT NULL,
  `email` tinytext NOT NULL,
  `name` tinytext NOT NULL,
  `password` tinytext NOT NULL,
  `avatar_hash` varchar(64) NOT NULL,
  `level` int(1) NOT NULL DEFAULT '0',
  `theme` int(1) NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

CREATE TABLE `user_token` (
  `user_token_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `token` varchar(60) NOT NULL,
  `device_info` tinytext NOT NULL,
  `created_datetime` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `latest_activity` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8;


ALTER TABLE `client`
  ADD PRIMARY KEY (`client_id`),
  ADD KEY `client_team_id` (`team_id`);

ALTER TABLE `domain`
  ADD PRIMARY KEY (`domain_id`),
  ADD KEY `domain_team_id` (`team_id`);

ALTER TABLE `domain_site`
  ADD PRIMARY KEY (`domain_site_id`),
  ADD KEY `domain_id` (`domain_id`),
  ADD KEY `site_id` (`site_id`);

ALTER TABLE `server`
  ADD PRIMARY KEY (`server_id`),
  ADD KEY `server_team_id` (`team_id`);

ALTER TABLE `site`
  ADD PRIMARY KEY (`site_id`),
  ADD KEY `site_team_id` (`team_id`),
  ADD KEY `site_server_id` (`server_id`),
  ADD KEY `site_client_id` (`client_id`);

ALTER TABLE `team`
  ADD PRIMARY KEY (`team_id`);

ALTER TABLE `user`
  ADD PRIMARY KEY (`user_id`),
  ADD KEY `user_team_id` (`team_id`);

ALTER TABLE `user_token`
  ADD PRIMARY KEY (`user_token_id`),
  ADD KEY `token_user_id` (`user_id`);


ALTER TABLE `client`
  MODIFY `client_id` int(11) NOT NULL AUTO_INCREMENT;

ALTER TABLE `domain`
  MODIFY `domain_id` int(11) NOT NULL AUTO_INCREMENT;

ALTER TABLE `domain_site`
  MODIFY `domain_site_id` int(11) NOT NULL AUTO_INCREMENT;

ALTER TABLE `server`
  MODIFY `server_id` int(11) NOT NULL AUTO_INCREMENT;

ALTER TABLE `site`
  MODIFY `site_id` int(11) NOT NULL AUTO_INCREMENT;

ALTER TABLE `team`
  MODIFY `team_id` int(11) NOT NULL AUTO_INCREMENT;

ALTER TABLE `user`
  MODIFY `user_id` int(11) NOT NULL AUTO_INCREMENT;

ALTER TABLE `user_token`
  MODIFY `user_token_id` int(11) NOT NULL AUTO_INCREMENT;


ALTER TABLE `client`
  ADD CONSTRAINT `client_ibfk_1` FOREIGN KEY (`team_id`) REFERENCES `team` (`team_id`) ON DELETE CASCADE ON UPDATE CASCADE;

ALTER TABLE `domain`
  ADD CONSTRAINT `domain_ibfk_1` FOREIGN KEY (`team_id`) REFERENCES `team` (`team_id`) ON DELETE CASCADE ON UPDATE CASCADE;

ALTER TABLE `domain_site`
  ADD CONSTRAINT `domain_site_ibfk_1` FOREIGN KEY (`domain_id`) REFERENCES `domain` (`domain_id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `domain_site_ibfk_2` FOREIGN KEY (`site_id`) REFERENCES `site` (`site_id`) ON DELETE CASCADE ON UPDATE CASCADE;

ALTER TABLE `server`
  ADD CONSTRAINT `server_ibfk_1` FOREIGN KEY (`team_id`) REFERENCES `team` (`team_id`) ON DELETE CASCADE ON UPDATE CASCADE;

ALTER TABLE `site`
  ADD CONSTRAINT `site_ibfk_1` FOREIGN KEY (`team_id`) REFERENCES `team` (`team_id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `site_ibfk_2` FOREIGN KEY (`server_id`) REFERENCES `server` (`server_id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `site_ibfk_3` FOREIGN KEY (`client_id`) REFERENCES `client` (`client_id`) ON DELETE CASCADE ON UPDATE CASCADE;

ALTER TABLE `user`
  ADD CONSTRAINT `user_ibfk_1` FOREIGN KEY (`team_id`) REFERENCES `team` (`team_id`) ON DELETE CASCADE ON UPDATE CASCADE;

ALTER TABLE `user_token`
  ADD CONSTRAINT `user_token_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `user` (`user_id`) ON DELETE CASCADE ON UPDATE CASCADE;
COMMIT;
