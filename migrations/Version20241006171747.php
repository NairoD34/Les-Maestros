<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20241006171747 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE admin (id INT AUTO_INCREMENT NOT NULL, roles JSON NOT NULL, nom VARCHAR(255) NOT NULL, prenom VARCHAR(255) NOT NULL, password VARCHAR(255) NOT NULL, email VARCHAR(255) NOT NULL, username VARCHAR(255) NOT NULL, UNIQUE INDEX UNIQ_880E0D76E7927C74 (email), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE adresse (id INT AUTO_INCREMENT NOT NULL, ville_id INT DEFAULT NULL, users_id INT NOT NULL, code_postal_id INT DEFAULT NULL, num_voie INT NOT NULL, rue VARCHAR(255) NOT NULL, complement VARCHAR(255) DEFAULT NULL, is_active TINYINT(1) NOT NULL, INDEX IDX_C35F0816A73F0036 (ville_id), INDEX IDX_C35F081667B3B43D (users_id), INDEX IDX_C35F0816B2B59251 (code_postal_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE categorie (id INT AUTO_INCREMENT NOT NULL, categorie_parente_id INT DEFAULT NULL, libelle VARCHAR(255) NOT NULL, description VARCHAR(255) NOT NULL, INDEX IDX_497DD6345CBD743C (categorie_parente_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE code_postal (id INT AUTO_INCREMENT NOT NULL, libelle VARCHAR(10) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE commande (id INT AUTO_INCREMENT NOT NULL, livraison_id INT DEFAULT NULL, paiement_id INT DEFAULT NULL, etat_id INT DEFAULT NULL, est_livre_id INT NOT NULL, est_facture_id INT NOT NULL, users_id INT NOT NULL, panier_id INT DEFAULT NULL, date_commande DATE NOT NULL COMMENT \'(DC2Type:date_immutable)\', prix_ttc_commande DOUBLE PRECISION NOT NULL, INDEX IDX_6EEAA67D8E54FB25 (livraison_id), INDEX IDX_6EEAA67D2A4C4478 (paiement_id), INDEX IDX_6EEAA67DD5E86FF (etat_id), INDEX IDX_6EEAA67DAC56B862 (est_livre_id), INDEX IDX_6EEAA67D8E569DDE (est_facture_id), INDEX IDX_6EEAA67D67B3B43D (users_id), UNIQUE INDEX UNIQ_6EEAA67DF77D927C (panier_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE departement (id INT AUTO_INCREMENT NOT NULL, region_id INT NOT NULL, nom VARCHAR(255) NOT NULL, numero_departement VARCHAR(255) NOT NULL, INDEX IDX_C1765B6398260155 (region_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE etat (id INT AUTO_INCREMENT NOT NULL, libelle VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE ligne_de_commande (id INT AUTO_INCREMENT NOT NULL, commande_id INT NOT NULL, nom_produit VARCHAR(255) NOT NULL, prix_produit DOUBLE PRECISION NOT NULL, taux_tva DOUBLE PRECISION NOT NULL, nombre_article INT NOT NULL, prix_total DOUBLE PRECISION NOT NULL, nom_utilisateur VARCHAR(255) NOT NULL, prenom_utilisateur VARCHAR(255) NOT NULL, email_utilisateur VARCHAR(255) NOT NULL, INDEX IDX_7982ACE682EA2E54 (commande_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE livraison (id INT AUTO_INCREMENT NOT NULL, libelle VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE paiement (id INT AUTO_INCREMENT NOT NULL, libelle VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE panier (id INT AUTO_INCREMENT NOT NULL, users_id INT DEFAULT NULL, INDEX IDX_24CC0DF267B3B43D (users_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE panier_produit (id INT AUTO_INCREMENT NOT NULL, produit_id INT DEFAULT NULL, panier_id INT DEFAULT NULL, quantite INT NOT NULL, INDEX IDX_D31F28A6F347EFB (produit_id), INDEX IDX_D31F28A6F77D927C (panier_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE photos (id INT AUTO_INCREMENT NOT NULL, categorie_id INT DEFAULT NULL, produit_id INT DEFAULT NULL, url_photo VARCHAR(255) NOT NULL, INDEX IDX_876E0D9BCF5E72D (categorie_id), INDEX IDX_876E0D9F347EFB (produit_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE produit (id INT AUTO_INCREMENT NOT NULL, tva_id INT NOT NULL, promotion_id INT DEFAULT NULL, categorie_id INT DEFAULT NULL, libelle VARCHAR(255) NOT NULL, description VARCHAR(255) NOT NULL, prix_ht DOUBLE PRECISION NOT NULL, INDEX IDX_29A5EC274D79775F (tva_id), INDEX IDX_29A5EC27139DF194 (promotion_id), INDEX IDX_29A5EC27BCF5E72D (categorie_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE promotion (id INT AUTO_INCREMENT NOT NULL, libelle VARCHAR(255) NOT NULL, taux_promotion DOUBLE PRECISION NOT NULL, code_promotion VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE region (id INT AUTO_INCREMENT NOT NULL, nom VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE tva (id INT AUTO_INCREMENT NOT NULL, taux_tva DOUBLE PRECISION NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE users (id INT AUTO_INCREMENT NOT NULL, nom VARCHAR(255) NOT NULL, prenom VARCHAR(255) NOT NULL, email VARCHAR(255) NOT NULL, roles JSON NOT NULL, password VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE ville (id INT AUTO_INCREMENT NOT NULL, departement_id INT NOT NULL, nom VARCHAR(255) NOT NULL, INDEX IDX_43C3D9C3CCF9E01E (departement_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE ville_code_postal (ville_id INT NOT NULL, code_postal_id INT NOT NULL, INDEX IDX_B5964877A73F0036 (ville_id), INDEX IDX_B5964877B2B59251 (code_postal_id), PRIMARY KEY(ville_id, code_postal_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE messenger_messages (id BIGINT AUTO_INCREMENT NOT NULL, body LONGTEXT NOT NULL, headers LONGTEXT NOT NULL, queue_name VARCHAR(190) NOT NULL, created_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', available_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', delivered_at DATETIME DEFAULT NULL COMMENT \'(DC2Type:datetime_immutable)\', INDEX IDX_75EA56E0FB7336F0 (queue_name), INDEX IDX_75EA56E0E3BD61CE (available_at), INDEX IDX_75EA56E016BA31DB (delivered_at), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE adresse ADD CONSTRAINT FK_C35F0816A73F0036 FOREIGN KEY (ville_id) REFERENCES ville (id)');
        $this->addSql('ALTER TABLE adresse ADD CONSTRAINT FK_C35F081667B3B43D FOREIGN KEY (users_id) REFERENCES users (id)');
        $this->addSql('ALTER TABLE adresse ADD CONSTRAINT FK_C35F0816B2B59251 FOREIGN KEY (code_postal_id) REFERENCES code_postal (id)');
        $this->addSql('ALTER TABLE categorie ADD CONSTRAINT FK_497DD6345CBD743C FOREIGN KEY (categorie_parente_id) REFERENCES categorie (id)');
        $this->addSql('ALTER TABLE commande ADD CONSTRAINT FK_6EEAA67D8E54FB25 FOREIGN KEY (livraison_id) REFERENCES livraison (id)');
        $this->addSql('ALTER TABLE commande ADD CONSTRAINT FK_6EEAA67D2A4C4478 FOREIGN KEY (paiement_id) REFERENCES paiement (id)');
        $this->addSql('ALTER TABLE commande ADD CONSTRAINT FK_6EEAA67DD5E86FF FOREIGN KEY (etat_id) REFERENCES etat (id)');
        $this->addSql('ALTER TABLE commande ADD CONSTRAINT FK_6EEAA67DAC56B862 FOREIGN KEY (est_livre_id) REFERENCES adresse (id)');
        $this->addSql('ALTER TABLE commande ADD CONSTRAINT FK_6EEAA67D8E569DDE FOREIGN KEY (est_facture_id) REFERENCES adresse (id)');
        $this->addSql('ALTER TABLE commande ADD CONSTRAINT FK_6EEAA67D67B3B43D FOREIGN KEY (users_id) REFERENCES users (id)');
        $this->addSql('ALTER TABLE commande ADD CONSTRAINT FK_6EEAA67DF77D927C FOREIGN KEY (panier_id) REFERENCES panier (id)');
        $this->addSql('ALTER TABLE departement ADD CONSTRAINT FK_C1765B6398260155 FOREIGN KEY (region_id) REFERENCES region (id)');
        $this->addSql('ALTER TABLE ligne_de_commande ADD CONSTRAINT FK_7982ACE682EA2E54 FOREIGN KEY (commande_id) REFERENCES commande (id)');
        $this->addSql('ALTER TABLE panier ADD CONSTRAINT FK_24CC0DF267B3B43D FOREIGN KEY (users_id) REFERENCES users (id)');
        $this->addSql('ALTER TABLE panier_produit ADD CONSTRAINT FK_D31F28A6F347EFB FOREIGN KEY (produit_id) REFERENCES produit (id)');
        $this->addSql('ALTER TABLE panier_produit ADD CONSTRAINT FK_D31F28A6F77D927C FOREIGN KEY (panier_id) REFERENCES panier (id)');
        $this->addSql('ALTER TABLE photos ADD CONSTRAINT FK_876E0D9BCF5E72D FOREIGN KEY (categorie_id) REFERENCES categorie (id)');
        $this->addSql('ALTER TABLE photos ADD CONSTRAINT FK_876E0D9F347EFB FOREIGN KEY (produit_id) REFERENCES produit (id)');
        $this->addSql('ALTER TABLE produit ADD CONSTRAINT FK_29A5EC274D79775F FOREIGN KEY (tva_id) REFERENCES tva (id)');
        $this->addSql('ALTER TABLE produit ADD CONSTRAINT FK_29A5EC27139DF194 FOREIGN KEY (promotion_id) REFERENCES promotion (id)');
        $this->addSql('ALTER TABLE produit ADD CONSTRAINT FK_29A5EC27BCF5E72D FOREIGN KEY (categorie_id) REFERENCES categorie (id)');
        $this->addSql('ALTER TABLE ville ADD CONSTRAINT FK_43C3D9C3CCF9E01E FOREIGN KEY (departement_id) REFERENCES departement (id)');
        $this->addSql('ALTER TABLE ville_code_postal ADD CONSTRAINT FK_B5964877A73F0036 FOREIGN KEY (ville_id) REFERENCES ville (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE ville_code_postal ADD CONSTRAINT FK_B5964877B2B59251 FOREIGN KEY (code_postal_id) REFERENCES code_postal (id) ON DELETE CASCADE');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE adresse DROP FOREIGN KEY FK_C35F0816A73F0036');
        $this->addSql('ALTER TABLE adresse DROP FOREIGN KEY FK_C35F081667B3B43D');
        $this->addSql('ALTER TABLE adresse DROP FOREIGN KEY FK_C35F0816B2B59251');
        $this->addSql('ALTER TABLE categorie DROP FOREIGN KEY FK_497DD6345CBD743C');
        $this->addSql('ALTER TABLE commande DROP FOREIGN KEY FK_6EEAA67D8E54FB25');
        $this->addSql('ALTER TABLE commande DROP FOREIGN KEY FK_6EEAA67D2A4C4478');
        $this->addSql('ALTER TABLE commande DROP FOREIGN KEY FK_6EEAA67DD5E86FF');
        $this->addSql('ALTER TABLE commande DROP FOREIGN KEY FK_6EEAA67DAC56B862');
        $this->addSql('ALTER TABLE commande DROP FOREIGN KEY FK_6EEAA67D8E569DDE');
        $this->addSql('ALTER TABLE commande DROP FOREIGN KEY FK_6EEAA67D67B3B43D');
        $this->addSql('ALTER TABLE commande DROP FOREIGN KEY FK_6EEAA67DF77D927C');
        $this->addSql('ALTER TABLE departement DROP FOREIGN KEY FK_C1765B6398260155');
        $this->addSql('ALTER TABLE ligne_de_commande DROP FOREIGN KEY FK_7982ACE682EA2E54');
        $this->addSql('ALTER TABLE panier DROP FOREIGN KEY FK_24CC0DF267B3B43D');
        $this->addSql('ALTER TABLE panier_produit DROP FOREIGN KEY FK_D31F28A6F347EFB');
        $this->addSql('ALTER TABLE panier_produit DROP FOREIGN KEY FK_D31F28A6F77D927C');
        $this->addSql('ALTER TABLE photos DROP FOREIGN KEY FK_876E0D9BCF5E72D');
        $this->addSql('ALTER TABLE photos DROP FOREIGN KEY FK_876E0D9F347EFB');
        $this->addSql('ALTER TABLE produit DROP FOREIGN KEY FK_29A5EC274D79775F');
        $this->addSql('ALTER TABLE produit DROP FOREIGN KEY FK_29A5EC27139DF194');
        $this->addSql('ALTER TABLE produit DROP FOREIGN KEY FK_29A5EC27BCF5E72D');
        $this->addSql('ALTER TABLE ville DROP FOREIGN KEY FK_43C3D9C3CCF9E01E');
        $this->addSql('ALTER TABLE ville_code_postal DROP FOREIGN KEY FK_B5964877A73F0036');
        $this->addSql('ALTER TABLE ville_code_postal DROP FOREIGN KEY FK_B5964877B2B59251');
        $this->addSql('DROP TABLE admin');
        $this->addSql('DROP TABLE adresse');
        $this->addSql('DROP TABLE categorie');
        $this->addSql('DROP TABLE code_postal');
        $this->addSql('DROP TABLE commande');
        $this->addSql('DROP TABLE departement');
        $this->addSql('DROP TABLE etat');
        $this->addSql('DROP TABLE ligne_de_commande');
        $this->addSql('DROP TABLE livraison');
        $this->addSql('DROP TABLE paiement');
        $this->addSql('DROP TABLE panier');
        $this->addSql('DROP TABLE panier_produit');
        $this->addSql('DROP TABLE photos');
        $this->addSql('DROP TABLE produit');
        $this->addSql('DROP TABLE promotion');
        $this->addSql('DROP TABLE region');
        $this->addSql('DROP TABLE tva');
        $this->addSql('DROP TABLE users');
        $this->addSql('DROP TABLE ville');
        $this->addSql('DROP TABLE ville_code_postal');
        $this->addSql('DROP TABLE messenger_messages');
    }
}
