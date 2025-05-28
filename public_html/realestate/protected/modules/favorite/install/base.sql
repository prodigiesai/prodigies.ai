CREATE TABLE `{dbPrefix}favorite` (
  `user_id` int(11) NOT NULL,
  `model_name` varchar(255) NOT NULL DEFAULT '',
  `model_id` int(11) NOT NULL,
  `date_created` datetime NOT NULL,
  KEY `user_id` (`user_id`),
  KEY `model_id` (`model_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;