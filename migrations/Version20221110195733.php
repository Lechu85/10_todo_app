<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20221110195733 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE task ADD description LONGTEXT DEFAULT NULL, ADD status SMALLINT NOT NULL, ADD created_at DATETIME NOT NULL, ADD updated_at DATETIME DEFAULT NULL, ADD deleted TINYINT(1) DEFAULT NULL, ADD prioryty SMALLINT DEFAULT NULL, ADD pinned TINYINT(1) DEFAULT NULL, ADD done_at DATETIME DEFAULT NULL, ADD dony_by_user INT DEFAULT NULL, ADD remind VARCHAR(50) DEFAULT NULL, ADD aaaaa VARCHAR(255) DEFAULT NULL');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE task DROP description, DROP status, DROP created_at, DROP updated_at, DROP deleted, DROP prioryty, DROP pinned, DROP done_at, DROP dony_by_user, DROP remind, DROP aaaaa');
    }
}
