<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

final class Version20260425145300 extends AbstractMigration
{
    public function up(Schema $schema): void
    {
        if (!$schema->hasTable('mobile_sensor')) {
            return;
        }

        $table = $schema->getTable('mobile_sensor');

        if ($table->hasIndex('idx_mobile_sensor_last_seen_at')) {
            $this->addSql('ALTER TABLE `mobile_sensor` DROP INDEX `idx_mobile_sensor_last_seen_at`;');
        }

        if ($table->hasColumn('last_seen_at')) {
            $this->addSql('ALTER TABLE `mobile_sensor` DROP COLUMN `last_seen_at`;');
        }
    }

    public function down(Schema $schema): void
    {
        if (!$schema->hasTable('mobile_sensor')) {
            return;
        }

        $table = $schema->getTable('mobile_sensor');

        if (!$table->hasColumn('last_seen_at')) {
            $this->addSql('ALTER TABLE `mobile_sensor` ADD `last_seen_at` DATETIME NOT NULL;');
        }

        if (!$table->hasIndex('idx_mobile_sensor_last_seen_at')) {
            $this->addSql('ALTER TABLE `mobile_sensor` ADD INDEX `idx_mobile_sensor_last_seen_at` (`last_seen_at`);');
        }
    }
}
