<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20191120151810 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE categories_website (id INT AUTO_INCREMENT NOT NULL, slug VARCHAR(255) NOT NULL, category_slug_id INT NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE website_posts (id INT AUTO_INCREMENT NOT NULL, category_id_id INT NOT NULL, posts VARCHAR(255) NOT NULL, INDEX IDX_DF370A369777D11E (category_id_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('ALTER TABLE website_posts ADD CONSTRAINT FK_DF370A369777D11E FOREIGN KEY (category_id_id) REFERENCES categories_website (id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE website_posts DROP FOREIGN KEY FK_DF370A369777D11E');
        $this->addSql('DROP TABLE categories_website');
        $this->addSql('DROP TABLE website_posts');
    }
}
