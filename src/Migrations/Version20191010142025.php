<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20191010142025 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE data_main CHANGE temperature temperature DOUBLE PRECISION DEFAULT NULL, CHANGE humidity humidity DOUBLE PRECISION DEFAULT NULL, CHANGE pressure pressure DOUBLE PRECISION DEFAULT NULL, CHANGE pm25 pm25 DOUBLE PRECISION DEFAULT NULL, CHANGE pm10 pm10 DOUBLE PRECISION DEFAULT NULL');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE data_main CHANGE temperature temperature DOUBLE PRECISION NOT NULL, CHANGE humidity humidity DOUBLE PRECISION NOT NULL, CHANGE pressure pressure DOUBLE PRECISION NOT NULL, CHANGE pm25 pm25 DOUBLE PRECISION NOT NULL, CHANGE pm10 pm10 DOUBLE PRECISION NOT NULL');
    }
}
