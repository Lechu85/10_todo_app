<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20221123085458 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        //$this->addSql('ALTER TABLE task DROP remind');
        $this->addSql('ALTER TABLE task_notification ADD CONSTRAINT FK_77C552E78DB60186 FOREIGN KEY (task_id) REFERENCES task (id)');
        $this->addSql('CREATE INDEX IDX_77C552E78DB60186 ON task_notification (task_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        //$this->addSql('ALTER TABLE task ADD remind VARCHAR(50) DEFAULT NULL');
        $this->addSql('ALTER TABLE task_notification DROP FOREIGN KEY FK_77C552E78DB60186');
        $this->addSql('DROP INDEX IDX_77C552E78DB60186 ON task_notification');
    }
}
