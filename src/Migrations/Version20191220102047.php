<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20191220102047 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE consultation (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(255) NOT NULL, description LONGTEXT NOT NULL, shift LONGTEXT NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE data_main CHANGE temperature temperature DOUBLE PRECISION DEFAULT NULL, CHANGE humidity humidity DOUBLE PRECISION DEFAULT NULL, CHANGE pressure pressure DOUBLE PRECISION DEFAULT NULL, CHANGE pm25 pm25 DOUBLE PRECISION DEFAULT NULL, CHANGE pm10 pm10 DOUBLE PRECISION DEFAULT NULL');
        $this->addSql('ALTER TABLE device CHANGE description description VARCHAR(255) DEFAULT NULL');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('DROP TABLE consultation');
        $this->addSql('ALTER TABLE data_main CHANGE temperature temperature DOUBLE PRECISION DEFAULT \'NULL\', CHANGE humidity humidity DOUBLE PRECISION DEFAULT \'NULL\', CHANGE pressure pressure DOUBLE PRECISION DEFAULT \'NULL\', CHANGE pm25 pm25 DOUBLE PRECISION DEFAULT \'NULL\', CHANGE pm10 pm10 DOUBLE PRECISION DEFAULT \'NULL\'');
        $this->addSql('ALTER TABLE device CHANGE description description VARCHAR(255) CHARACTER SET utf8mb4 DEFAULT \'NULL\' COLLATE `utf8mb4_unicode_ci`');
    }
}
