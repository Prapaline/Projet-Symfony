<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250209210658 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE user_favoris (user_id INT NOT NULL, vehicule_id INT NOT NULL, INDEX IDX_D13EDA38A76ED395 (user_id), INDEX IDX_D13EDA384A4A3511 (vehicule_id), PRIMARY KEY(user_id, vehicule_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE user_favoris ADD CONSTRAINT FK_D13EDA38A76ED395 FOREIGN KEY (user_id) REFERENCES user (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE user_favoris ADD CONSTRAINT FK_D13EDA384A4A3511 FOREIGN KEY (vehicule_id) REFERENCES vehicule (id) ON DELETE CASCADE');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE user_favoris DROP FOREIGN KEY FK_D13EDA38A76ED395');
        $this->addSql('ALTER TABLE user_favoris DROP FOREIGN KEY FK_D13EDA384A4A3511');
        $this->addSql('DROP TABLE user_favoris');
    }
}
