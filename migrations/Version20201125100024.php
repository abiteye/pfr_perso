<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20201125100024 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE profil_de_sortie (id INT AUTO_INCREMENT NOT NULL, libelle VARCHAR(100) NOT NULL, archivage TINYINT(1) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE apprenant ADD profil_de_sortie_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE apprenant ADD CONSTRAINT FK_C4EB462E65E0C4D3 FOREIGN KEY (profil_de_sortie_id) REFERENCES profil_de_sortie (id)');
        $this->addSql('CREATE INDEX IDX_C4EB462E65E0C4D3 ON apprenant (profil_de_sortie_id)');
        $this->addSql('ALTER TABLE user CHANGE photo photo LONGBLOB DEFAULT NULL');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE apprenant DROP FOREIGN KEY FK_C4EB462E65E0C4D3');
        $this->addSql('DROP TABLE profil_de_sortie');
        $this->addSql('DROP INDEX IDX_C4EB462E65E0C4D3 ON apprenant');
        $this->addSql('ALTER TABLE apprenant DROP profil_de_sortie_id');
        $this->addSql('ALTER TABLE user CHANGE photo photo LONGBLOB NOT NULL');
    }
}
