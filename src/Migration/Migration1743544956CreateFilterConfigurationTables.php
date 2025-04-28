<?php

declare(strict_types=1);

namespace ITB\ITBConfigurableListingFilters\Migration;

use Doctrine\DBAL\Connection;
use Shopware\Core\Framework\Migration\MigrationStep;

class Migration1743544956CreateFilterConfigurationTables extends MigrationStep
{
    public function getCreationTimestamp(): int
    {
        return 1743544956;
    }

    public function update(Connection $connection): void
    {
        $sql = <<<SQL
CREATE TABLE IF NOT EXISTS `itb_lfc_checkbox` (
    `id` BINARY(16) NOT NULL,
    `sales_channel_id` BINARY(16) NULL,
    `dal_field` VARCHAR(255) NOT NULL,
    `enabled` TINYINT(1) NOT NULL,
    `position` INTEGER NULL,
    `twig_template` VARCHAR(255) NOT NULL,
    `created_at` DATETIME(3) NOT NULL,
    `updated_at` DATETIME(3) NULL,
    PRIMARY KEY (`id`),
    KEY `fk.itb_lfc_checkbox.sales_channel_id` (`sales_channel_id`),
    CONSTRAINT `fk.itb_lfc_checkbox.sales_channel_id` FOREIGN KEY (`sales_channel_id`) REFERENCES `sales_channel` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
SQL;
        $connection->executeStatement($sql);

        $sql = <<<SQL
CREATE TABLE IF NOT EXISTS `itb_lfc_checkbox_translation` (
    `itb_lfc_checkbox_id` BINARY(16) NOT NULL,
    `language_id` BINARY(16) NOT NULL,
    `display_name` VARCHAR(255) NOT NULL,
    `created_at` DATETIME(3) NOT NULL,
    `updated_at` DATETIME(3) NULL,
    PRIMARY KEY (`itb_lfc_checkbox_id`, `language_id`),
    CONSTRAINT `fk.itb_lfc_translation_checkbox.entity_id` FOREIGN KEY (`itb_lfc_checkbox_id`)
        REFERENCES `itb_lfc_checkbox` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
    CONSTRAINT `fk.itb_lfc_translation_checkbox.language_id` FOREIGN KEY (`language_id`)
        REFERENCES `language` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
SQL;
        $connection->executeStatement($sql);

        $sql = <<<SQL
CREATE TABLE IF NOT EXISTS `itb_lfc_multi_select` (
    `id` BINARY(16) NOT NULL,
    `sales_channel_id` BINARY(16) NULL,
    `dal_field` VARCHAR(255) NOT NULL,
    `enabled` TINYINT(1) NOT NULL,
    `position` INTEGER NULL,
    `twig_template` VARCHAR(255) NOT NULL,
    `sorting_order` VARCHAR(3) NOT NULL,
    `created_at` DATETIME(3) NOT NULL,
    `updated_at` DATETIME(3) NULL,
    PRIMARY KEY (`id`),
    KEY `fk.itb_lfc_multi_select.sales_channel_id` (`sales_channel_id`),
    CONSTRAINT `fk.itb_lfc_multi_select.sales_channel_id` FOREIGN KEY (`sales_channel_id`) REFERENCES `sales_channel` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
SQL;
        $connection->executeStatement($sql);

        $sql = <<<SQL
CREATE TABLE IF NOT EXISTS `itb_lfc_multi_select_translation` (
    `itb_lfc_multi_select_id` BINARY(16) NOT NULL,
    `language_id` BINARY(16) NOT NULL,
    `display_name` VARCHAR(255) NOT NULL,
    `allowed_elements` JSON NULL,
    `element_prefix` VARCHAR(255) NULL,
    `element_suffix` VARCHAR(255) NULL,
    `explicit_element_sorting` JSON NULL,
    `forbidden_elements` JSON NULL,
    `created_at` DATETIME(3) NOT NULL,
    `updated_at` DATETIME(3) NULL,
    PRIMARY KEY (`itb_lfc_multi_select_id`, `language_id`),
    CONSTRAINT `fk.itb_lfc_translation_multi_select.entity_id` FOREIGN KEY (`itb_lfc_multi_select_id`)
        REFERENCES `itb_lfc_multi_select` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
    CONSTRAINT `fk.itb_lfc_translation_multi_select.language_id` FOREIGN KEY (`language_id`)
        REFERENCES `language` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
SQL;
        $connection->executeStatement($sql);

        $sql = <<<SQL
CREATE TABLE IF NOT EXISTS `itb_lfc_range` (
    `id` BINARY(16) NOT NULL,
    `sales_channel_id` BINARY(16) NULL,
    `dal_field` VARCHAR(255) NOT NULL,
    `enabled` TINYINT(1) NOT NULL,
    `position` INTEGER NULL,
    `twig_template` VARCHAR(255) NOT NULL,
    `created_at` DATETIME(3) NOT NULL,
    `updated_at` DATETIME(3) NULL,
    PRIMARY KEY (`id`),
    KEY `fk.itb_lfc_range.sales_channel_id` (`sales_channel_id`),
    CONSTRAINT `fk.itb_lfc_range.sales_channel_id` FOREIGN KEY (`sales_channel_id`) REFERENCES `sales_channel` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
SQL;
        $connection->executeStatement($sql);

        $sql = <<<SQL
CREATE TABLE IF NOT EXISTS `itb_lfc_range_translation` (
    `itb_lfc_range_id` BINARY(16) NOT NULL,
    `language_id` BINARY(16) NOT NULL,
    `display_name` VARCHAR(255) NOT NULL,
    `unit` VARCHAR(255) NULL,
    `created_at` DATETIME(3) NOT NULL,
    `updated_at` DATETIME(3) NULL,
    PRIMARY KEY (`itb_lfc_range_id`, `language_id`),
    CONSTRAINT `fk.itb_lfc_translation_range.entity_id` FOREIGN KEY (`itb_lfc_range_id`)
        REFERENCES `itb_lfc_range` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
    CONSTRAINT `fk.itb_lfc_translation_range.language_id` FOREIGN KEY (`language_id`)
        REFERENCES `language` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
SQL;
        $connection->executeStatement($sql);

        $sql = <<<SQL
CREATE TABLE IF NOT EXISTS `itb_lfc_range_interval` (
    `id` BINARY(16) NOT NULL,
    `sales_channel_id` BINARY(16) NULL,
    `dal_field` VARCHAR(255) NOT NULL,
    `enabled` TINYINT(1) NOT NULL,
    `position` INTEGER NULL,
    `twig_template` VARCHAR(255) NOT NULL,
    `created_at` DATETIME(3) NOT NULL,
    `updated_at` DATETIME(3) NULL,
    PRIMARY KEY (`id`),
    KEY `fk.itb_lfc_range.sales_channel_id` (`sales_channel_id`),
    CONSTRAINT `fk.itb_lfc_range_interval.sales_channel_id` FOREIGN KEY (`sales_channel_id`) REFERENCES `sales_channel` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
SQL;
        $connection->executeStatement($sql);

        $sql = <<<SQL
CREATE TABLE IF NOT EXISTS `itb_lfc_range_interval_translation` (
    `itb_lfc_range_interval_id` BINARY(16) NOT NULL,
    `language_id` BINARY(16) NOT NULL,
    `display_name` VARCHAR(255) NOT NULL,
    `element_prefix` VARCHAR(255) NULL,
    `element_suffix` VARCHAR(255) NULL,
    `created_at` DATETIME(3) NOT NULL,
    `updated_at` DATETIME(3) NULL,
    PRIMARY KEY (`itb_lfc_range_interval_id`, `language_id`),
    CONSTRAINT `fk.itb_lfc_translation_range_interval.entity_id` FOREIGN KEY (`itb_lfc_range_interval_id`)
        REFERENCES `itb_lfc_range_interval` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
    CONSTRAINT `fk.itb_lfc_translation_range_interval.language_id` FOREIGN KEY (`language_id`)
        REFERENCES `language` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
SQL;
        $connection->executeStatement($sql);

        $sql = <<<SQL
CREATE TABLE IF NOT EXISTS `itb_lfc_range_interval_interval` (
    `id` BINARY(16) NOT NULL,
    `itb_lfc_range_interval_id` BINARY(16) NOT NULL,
    `min` INTEGER NULL,
    `max` INTEGER NULL,
    `position` INTEGER NOT NULL,
    `created_at` DATETIME(3) NOT NULL,
    `updated_at` DATETIME(3) NULL,
    PRIMARY KEY (`id`),
    CONSTRAINT `fk.itb_lfc_interval_range_interval.entity_id` FOREIGN KEY (`itb_lfc_range_interval_id`)
        REFERENCES `itb_lfc_range_interval` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
SQL;
        $connection->executeStatement($sql);
    }

    public function updateDestructive(Connection $connection): void
    {
    }
}
