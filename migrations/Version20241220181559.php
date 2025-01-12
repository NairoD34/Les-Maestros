<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */

final class Version20241220181559 extends AbstractMigration

{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE message (id INT AUTO_INCREMENT NOT NULL, firstname VARCHAR(255) NOT NULL, lastname VARCHAR(255) NOT NULL, email VARCHAR(255) NOT NULL, message LONGTEXT NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE messenger_messages (id BIGINT AUTO_INCREMENT NOT NULL, body LONGTEXT NOT NULL, headers LONGTEXT NOT NULL, queue_name VARCHAR(190) NOT NULL, created_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', available_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', delivered_at DATETIME DEFAULT NULL COMMENT \'(DC2Type:datetime_immutable)\', INDEX IDX_75EA56E0FB7336F0 (queue_name), INDEX IDX_75EA56E0E3BD61CE (available_at), INDEX IDX_75EA56E016BA31DB (delivered_at), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE adress DROP FOREIGN KEY FK_5CECC7BE8BAC62AF');
        $this->addSql('ALTER TABLE adress DROP FOREIGN KEY FK_5CECC7BE67B3B43D');
        $this->addSql('ALTER TABLE adress DROP FOREIGN KEY FK_5CECC7BEE4C7FA21');
        $this->addSql('ALTER TABLE cart DROP FOREIGN KEY FK_BA388B767B3B43D');
        $this->addSql('ALTER TABLE cart_product DROP FOREIGN KEY FK_2890CCAA4584665A');
        $this->addSql('ALTER TABLE cart_product DROP FOREIGN KEY FK_2890CCAA1AD5CDBF');
        $this->addSql('ALTER TABLE category DROP FOREIGN KEY FK_64C19C1796A8F92');
        $this->addSql('ALTER TABLE city DROP FOREIGN KEY FK_2D5B023485E73F45');
        $this->addSql('ALTER TABLE city_zipcode DROP FOREIGN KEY FK_E844BF908BAC62AF');
        $this->addSql('ALTER TABLE city_zipcode DROP FOREIGN KEY FK_E844BF90E4C7FA21');
        $this->addSql('ALTER TABLE county DROP FOREIGN KEY FK_58E2FF2598260155');
        $this->addSql('ALTER TABLE order_line DROP FOREIGN KEY FK_9CE58EE18D9F6D38');
        $this->addSql('ALTER TABLE orders DROP FOREIGN KEY FK_E52FFDEE12136921');
        $this->addSql('ALTER TABLE orders DROP FOREIGN KEY FK_E52FFDEE4C3A3BB');
        $this->addSql('ALTER TABLE orders DROP FOREIGN KEY FK_E52FFDEE5D83CC1');
        $this->addSql('ALTER TABLE orders DROP FOREIGN KEY FK_E52FFDEEC3D4ABB7');
        $this->addSql('ALTER TABLE orders DROP FOREIGN KEY FK_E52FFDEE148E6BE7');
        $this->addSql('ALTER TABLE orders DROP FOREIGN KEY FK_E52FFDEE67B3B43D');
        $this->addSql('ALTER TABLE orders DROP FOREIGN KEY FK_E52FFDEE1AD5CDBF');
        $this->addSql('ALTER TABLE photos DROP FOREIGN KEY FK_876E0D912469DE2');
        $this->addSql('ALTER TABLE photos DROP FOREIGN KEY FK_876E0D94584665A');
        $this->addSql('ALTER TABLE product DROP FOREIGN KEY FK_D34A04ADFDD13F95');
        $this->addSql('ALTER TABLE product DROP FOREIGN KEY FK_D34A04ADA4522A07');
        $this->addSql('ALTER TABLE product DROP FOREIGN KEY FK_D34A04AD12469DE2');
        $this->addSql('DROP TABLE admin');
        $this->addSql('DROP TABLE adress');
        $this->addSql('DROP TABLE cart');
        $this->addSql('DROP TABLE cart_product');
        $this->addSql('DROP TABLE category');
        $this->addSql('DROP TABLE city');
        $this->addSql('DROP TABLE city_zipcode');
        $this->addSql('DROP TABLE county');
        $this->addSql('DROP TABLE delivery');
        $this->addSql('DROP TABLE message');
        $this->addSql('DROP TABLE order_line');
        $this->addSql('DROP TABLE orders');
        $this->addSql('DROP TABLE payment');
        $this->addSql('DROP TABLE photos');
        $this->addSql('DROP TABLE product');
        $this->addSql('DROP TABLE region');
        $this->addSql('DROP TABLE sales');
        $this->addSql('DROP TABLE state');
        $this->addSql('DROP TABLE tax_rate');
        $this->addSql('DROP TABLE users');
        $this->addSql('DROP TABLE zipcode');
        $this->addSql('DROP TABLE messenger_messages');
    }
}
