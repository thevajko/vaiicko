CREATE TABLE `likes` (
                         `id` int(11) NOT NULL AUTO_INCREMENT,
                         `post_id` int(11) DEFAULT NULL,
                         `liker` varchar(200) NOT NULL,
                         PRIMARY KEY (`id`),
                         KEY `Likes_posts_id_fk` (`post_id`),
                         CONSTRAINT `Likes_posts_id_fk` FOREIGN KEY (`post_id`) REFERENCES `posts` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;