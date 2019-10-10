<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\DBALException;
use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20191010141827 extends AbstractMigration
{
    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        try {
            $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');
        } catch (DBALException $e) {
        }

        $this->addSql('CREATE TABLE data_main (id INT AUTO_INCREMENT NOT NULL, device_id INT NOT NULL, temperature DOUBLE PRECISION NOT NULL, humidity DOUBLE PRECISION NOT NULL, pressure DOUBLE PRECISION NOT NULL, pm25 DOUBLE PRECISION NOT NULL, pm10 DOUBLE PRECISION NOT NULL, created_at DATETIME NOT NULL, updated_at DATETIME NOT NULL, INDEX IDX_467068694A4C7D4 (device_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE device (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(30) NOT NULL, description VARCHAR(255) DEFAULT NULL, ip_address VARCHAR(16) NOT NULL, active TINYINT(1) NOT NULL, created_at DATETIME NOT NULL, updated_at DATETIME NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('ALTER TABLE data_main ADD CONSTRAINT FK_467068694A4C7D4 FOREIGN KEY (device_id) REFERENCES device (id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        try {
            $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');
        } catch (DBALException $e) {
        }

        $this->addSql('ALTER TABLE data_main DROP FOREIGN KEY FK_467068694A4C7D4');
        $this->addSql('DROP TABLE data_main');
        $this->addSql('DROP TABLE device');
    }
}
