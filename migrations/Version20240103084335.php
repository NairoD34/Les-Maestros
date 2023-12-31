<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240103084335 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE code_postal CHANGE libelle libelle VARCHAR(10) DEFAULT NULL');
        $this->addSql('ALTER TABLE ligne_de_commande ADD nom_utilisateur VARCHAR(255) NOT NULL, ADD prenom_utilisateur VARCHAR(255) NOT NULL, ADD email_utilisateur VARCHAR(255) NOT NULL');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE code_postal CHANGE libelle libelle VARCHAR(10) DEFAULT NULL');
        $this->addSql('ALTER TABLE ligne_de_commande DROP nom_utilisateur, DROP prenom_utilisateur, DROP email_utilisateur');
    }
}
