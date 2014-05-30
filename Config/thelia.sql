
# This is a fix for InnoDB in MySQL >= 4.1.x
# It "suspends judgement" for fkey relationships until are tables are set.
SET FOREIGN_KEY_CHECKS = 0;

-- ---------------------------------------------------------------------
-- related_content
-- ---------------------------------------------------------------------

DROP TABLE IF EXISTS `related_content`;

CREATE TABLE `related_content`
(
    `content_id` INTEGER NOT NULL,
    `related_content_id` INTEGER NOT NULL,
    `created_at` DATETIME,
    `updated_at` DATETIME,
    PRIMARY KEY (`content_id`,`related_content_id`),
    INDEX `idx_content_id` (`content_id`),
    INDEX `idx_related_content_id` (`related_content_id`),
    CONSTRAINT `fk_content_id`
        FOREIGN KEY (`content_id`)
        REFERENCES `content` (`id`)
        ON UPDATE RESTRICT
        ON DELETE CASCADE,
    CONSTRAINT `fk_related_content_content_id`
        FOREIGN KEY (`related_content_id`)
        REFERENCES `content` (`id`)
        ON UPDATE RESTRICT
        ON DELETE CASCADE
) ENGINE=InnoDB;

# This restores the fkey checks, after having unset them earlier
SET FOREIGN_KEY_CHECKS = 1;
