<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20220427233555 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE acao_rejeitada DROP INDEX UNIQ_787B8A1760DA4051, ADD INDEX IDX_787B8A1760DA4051 (acao_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE acao_rejeitada DROP INDEX IDX_787B8A1760DA4051, ADD UNIQUE INDEX UNIQ_787B8A1760DA4051 (acao_id)');
    }
}
