<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20220418222303 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE acao_rejeitada (id INT AUTO_INCREMENT NOT NULL, acao_id INT NOT NULL, codigo VARCHAR(255) NOT NULL, motivo VARCHAR(255) DEFAULT NULL, UNIQUE INDEX UNIQ_787B8A1760DA4051 (acao_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE acao_rejeitada ADD CONSTRAINT FK_787B8A1760DA4051 FOREIGN KEY (acao_id) REFERENCES acao (id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('DROP TABLE acao_rejeitada');
    }
}
