<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250320125137 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE collector (id INT AUTO_INCREMENT NOT NULL, user_billing_item_id INT NOT NULL, tariff_id INT NOT NULL, uid VARCHAR(36) NOT NULL, INDEX IDX_CEDBF54C6B9DB506 (user_billing_item_id), INDEX IDX_CEDBF54C92348FD2 (tariff_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE collector_data (id INT AUTO_INCREMENT NOT NULL, collector_id INT NOT NULL, amount DOUBLE PRECISION DEFAULT NULL, timestamp INT NOT NULL, INDEX IDX_8C8F0B4670BAFFE (collector_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE tariff (id INT AUTO_INCREMENT NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE collector ADD CONSTRAINT FK_CEDBF54C6B9DB506 FOREIGN KEY (user_billing_item_id) REFERENCES user_billing_data (id)');
        $this->addSql('ALTER TABLE collector ADD CONSTRAINT FK_CEDBF54C92348FD2 FOREIGN KEY (tariff_id) REFERENCES tariff (id)');
        $this->addSql('ALTER TABLE collector_data ADD CONSTRAINT FK_8C8F0B4670BAFFE FOREIGN KEY (collector_id) REFERENCES collector (id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE collector DROP FOREIGN KEY FK_CEDBF54C6B9DB506');
        $this->addSql('ALTER TABLE collector DROP FOREIGN KEY FK_CEDBF54C92348FD2');
        $this->addSql('ALTER TABLE collector_data DROP FOREIGN KEY FK_8C8F0B4670BAFFE');
        $this->addSql('DROP TABLE collector');
        $this->addSql('DROP TABLE collector_data');
        $this->addSql('DROP TABLE tariff');
    }
}
