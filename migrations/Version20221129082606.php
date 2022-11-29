<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20221129082606 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE blog ADD CONSTRAINT FK_C015514312469DE2 FOREIGN KEY (category_id) REFERENCES blog_category (id)');
        $this->addSql('ALTER TABLE product ADD CONSTRAINT FK_D34A04AD12469DE2 FOREIGN KEY (category_id) REFERENCES category (id)');
        $this->addSql('ALTER TABLE task ADD CONSTRAINT FK_527EDB25A76ED395 FOREIGN KEY (user_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE task ADD CONSTRAINT FK_527EDB2512469DE2 FOREIGN KEY (category_id) REFERENCES task_category (id)');
        $this->addSql('ALTER TABLE task_notification ADD CONSTRAINT FK_77C552E78DB60186 FOREIGN KEY (task_id) REFERENCES task (id)');
        $this->addSql('ALTER TABLE user ADD agreed_terms_at DATETIME DEFAULT NULL');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE product DROP FOREIGN KEY FK_D34A04AD12469DE2');
        $this->addSql('ALTER TABLE task DROP FOREIGN KEY FK_527EDB25A76ED395');
        $this->addSql('ALTER TABLE task DROP FOREIGN KEY FK_527EDB2512469DE2');
        $this->addSql('ALTER TABLE user DROP agreed_terms_at');
        $this->addSql('ALTER TABLE blog DROP FOREIGN KEY FK_C015514312469DE2');
        $this->addSql('ALTER TABLE task_notification DROP FOREIGN KEY FK_77C552E78DB60186');
    }
}
