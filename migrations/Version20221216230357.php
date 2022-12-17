<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20221216230357 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE acao (id INT AUTO_INCREMENT NOT NULL, codigo VARCHAR(255) NOT NULL, nome VARCHAR(255) NOT NULL, preco DOUBLE PRECISION NOT NULL, liquidez DOUBLE PRECISION NOT NULL, margem_ebit DOUBLE PRECISION NOT NULL, ev_ebit DOUBLE PRECISION NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE acao_rejeitada (id INT AUTO_INCREMENT NOT NULL, user_id INT NOT NULL, acao_id INT NOT NULL, motivo VARCHAR(255) DEFAULT NULL, INDEX IDX_787B8A17A76ED395 (user_id), INDEX IDX_787B8A1760DA4051 (acao_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE carteira (id INT AUTO_INCREMENT NOT NULL, acao_id INT NOT NULL, user_id INT NOT NULL, quantidade INT NOT NULL, preco_medio NUMERIC(10, 2) NOT NULL, INDEX IDX_307D688160DA4051 (acao_id), INDEX IDX_307D6881A76ED395 (user_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE star (id INT AUTO_INCREMENT NOT NULL, user_id INT NOT NULL, acao_id INT NOT NULL, INDEX IDX_C9DB5A14A76ED395 (user_id), INDEX IDX_C9DB5A1460DA4051 (acao_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE tipo_transacao (id INT NOT NULL, nome VARCHAR(6) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE transacao (id INT AUTO_INCREMENT NOT NULL, tipo_id INT NOT NULL, usuario_id INT DEFAULT NULL, acao_id INT NOT NULL, data DATETIME NOT NULL, valor NUMERIC(10, 2) NOT NULL, quantidade INT NOT NULL, INDEX IDX_6C9E60CEA9276E6C (tipo_id), INDEX IDX_6C9E60CEDB38439E (usuario_id), INDEX IDX_6C9E60CE60DA4051 (acao_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE user (id INT AUTO_INCREMENT NOT NULL, email VARCHAR(180) NOT NULL, roles JSON NOT NULL, password VARCHAR(255) NOT NULL, name VARCHAR(255) NOT NULL, UNIQUE INDEX UNIQ_8D93D649E7927C74 (email), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE acao_rejeitada ADD CONSTRAINT FK_787B8A17A76ED395 FOREIGN KEY (user_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE acao_rejeitada ADD CONSTRAINT FK_787B8A1760DA4051 FOREIGN KEY (acao_id) REFERENCES acao (id)');
        $this->addSql('ALTER TABLE carteira ADD CONSTRAINT FK_307D688160DA4051 FOREIGN KEY (acao_id) REFERENCES acao (id)');
        $this->addSql('ALTER TABLE carteira ADD CONSTRAINT FK_307D6881A76ED395 FOREIGN KEY (user_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE star ADD CONSTRAINT FK_C9DB5A14A76ED395 FOREIGN KEY (user_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE star ADD CONSTRAINT FK_C9DB5A1460DA4051 FOREIGN KEY (acao_id) REFERENCES acao (id)');
        $this->addSql('ALTER TABLE transacao ADD CONSTRAINT FK_6C9E60CEA9276E6C FOREIGN KEY (tipo_id) REFERENCES tipo_transacao (id)');
        $this->addSql('ALTER TABLE transacao ADD CONSTRAINT FK_6C9E60CEDB38439E FOREIGN KEY (usuario_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE transacao ADD CONSTRAINT FK_6C9E60CE60DA4051 FOREIGN KEY (acao_id) REFERENCES acao (id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE acao_rejeitada DROP FOREIGN KEY FK_787B8A17A76ED395');
        $this->addSql('ALTER TABLE acao_rejeitada DROP FOREIGN KEY FK_787B8A1760DA4051');
        $this->addSql('ALTER TABLE carteira DROP FOREIGN KEY FK_307D688160DA4051');
        $this->addSql('ALTER TABLE carteira DROP FOREIGN KEY FK_307D6881A76ED395');
        $this->addSql('ALTER TABLE star DROP FOREIGN KEY FK_C9DB5A14A76ED395');
        $this->addSql('ALTER TABLE star DROP FOREIGN KEY FK_C9DB5A1460DA4051');
        $this->addSql('ALTER TABLE transacao DROP FOREIGN KEY FK_6C9E60CEA9276E6C');
        $this->addSql('ALTER TABLE transacao DROP FOREIGN KEY FK_6C9E60CEDB38439E');
        $this->addSql('ALTER TABLE transacao DROP FOREIGN KEY FK_6C9E60CE60DA4051');
        $this->addSql('DROP TABLE acao');
        $this->addSql('DROP TABLE acao_rejeitada');
        $this->addSql('DROP TABLE carteira');
        $this->addSql('DROP TABLE star');
        $this->addSql('DROP TABLE tipo_transacao');
        $this->addSql('DROP TABLE transacao');
        $this->addSql('DROP TABLE user');
    }
}
