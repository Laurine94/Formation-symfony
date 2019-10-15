<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20191014133939 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'postgresql', 'Migration can only be executed safely on \'postgresql\'.');

        $this->addSql('CREATE SEQUENCE role_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE TABLE role (id INT NOT NULL, title VARCHAR(255) NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE TABLE role_utilisateur (role_id INT NOT NULL, utilisateur_id INT NOT NULL, PRIMARY KEY(role_id, utilisateur_id))');
        $this->addSql('CREATE INDEX IDX_2F4B3B3AD60322AC ON role_utilisateur (role_id)');
        $this->addSql('CREATE INDEX IDX_2F4B3B3AFB88E14F ON role_utilisateur (utilisateur_id)');
        $this->addSql('ALTER TABLE role_utilisateur ADD CONSTRAINT FK_2F4B3B3AD60322AC FOREIGN KEY (role_id) REFERENCES role (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE role_utilisateur ADD CONSTRAINT FK_2F4B3B3AFB88E14F FOREIGN KEY (utilisateur_id) REFERENCES utilisateur (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'postgresql', 'Migration can only be executed safely on \'postgresql\'.');

        $this->addSql('CREATE SCHEMA public');
        $this->addSql('ALTER TABLE role_utilisateur DROP CONSTRAINT FK_2F4B3B3AD60322AC');
        $this->addSql('DROP SEQUENCE role_id_seq CASCADE');
        $this->addSql('DROP TABLE role');
        $this->addSql('DROP TABLE role_utilisateur');
    }
}
