<?php

declare(strict_types=1);

namespace rinhaBackend\Migrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230805152240 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'fist migration';
    }

    public function up(Schema $schema): void
    {
        $this->addSql("
            CREATE TABLE IF NOT EXISTS `people` (
                `uuid` VARCHAR(32) NOT NULL,
                `nickname` VARCHAR(32) NOT NULL,
                `name` VARCHAR(100) NOT NULL,
                `birthdate` VARCHAR(10) NOT NULL,
                `stack` LONGTEXT,
                PRIMARY KEY (`uuid`),
                UNIQUE (`nickname`)
            )
        ");

    }

    public function down(Schema $schema): void
    {
        $this->throwIrreversibleMigrationException('Irreversible Migration');

    }
}
