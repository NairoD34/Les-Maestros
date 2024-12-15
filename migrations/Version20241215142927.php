<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20241215142927 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE adress DROP FOREIGN KEY FK_C35F0816B2B59251');
        $this->addSql('CREATE TABLE city_zipcode (city_id INT NOT NULL, zipcode_id INT NOT NULL, INDEX IDX_E844BF908BAC62AF (city_id), INDEX IDX_E844BF90E4C7FA21 (zipcode_id), PRIMARY KEY(city_id, zipcode_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE zipcode (id INT AUTO_INCREMENT NOT NULL, title VARCHAR(10) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE city_zipcode ADD CONSTRAINT FK_E844BF908BAC62AF FOREIGN KEY (city_id) REFERENCES city (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE city_zipcode ADD CONSTRAINT FK_E844BF90E4C7FA21 FOREIGN KEY (zipcode_id) REFERENCES zipcode (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE city_zip_code DROP FOREIGN KEY FK_B5964877A73F0036');
        $this->addSql('ALTER TABLE city_zip_code DROP FOREIGN KEY FK_B5964877B2B59251');
        $this->addSql('DROP TABLE city_zip_code');
        $this->addSql('DROP TABLE zip_code');
        $this->addSql('ALTER TABLE admin CHANGE lastname lastname VARCHAR(255) NOT NULL, CHANGE firstname firstname VARCHAR(255) NOT NULL');
        $this->addSql('ALTER TABLE adress CHANGE number number INT NOT NULL, CHANGE street street VARCHAR(255) NOT NULL');
        $this->addSql('ALTER TABLE adress ADD CONSTRAINT FK_5CECC7BEE4C7FA21 FOREIGN KEY (zipcode_id) REFERENCES zipcode (id)');
        $this->addSql('ALTER TABLE adress RENAME INDEX idx_c35f0816a73f0036 TO IDX_5CECC7BE8BAC62AF');
        $this->addSql('ALTER TABLE adress RENAME INDEX idx_c35f081667b3b43d TO IDX_5CECC7BE67B3B43D');
        $this->addSql('ALTER TABLE adress RENAME INDEX idx_c35f0816b2b59251 TO IDX_5CECC7BEE4C7FA21');
        $this->addSql('ALTER TABLE cart RENAME INDEX idx_24cc0df267b3b43d TO IDX_BA388B767B3B43D');
        $this->addSql('ALTER TABLE cart_product CHANGE quantity quantity INT NOT NULL');
        $this->addSql('ALTER TABLE cart_product RENAME INDEX idx_d31f28a6f347efb TO IDX_2890CCAA4584665A');
        $this->addSql('ALTER TABLE cart_product RENAME INDEX idx_d31f28a6f77d927c TO IDX_2890CCAA1AD5CDBF');
        $this->addSql('ALTER TABLE category CHANGE title title VARCHAR(255) NOT NULL');
        $this->addSql('ALTER TABLE category RENAME INDEX idx_497dd6345cbd743c TO IDX_64C19C1796A8F92');
        $this->addSql('ALTER TABLE city CHANGE county_id county_id INT NOT NULL, CHANGE name name VARCHAR(255) NOT NULL');
        $this->addSql('ALTER TABLE city RENAME INDEX idx_43c3d9c3ccf9e01e TO IDX_2D5B023485E73F45');
        $this->addSql('ALTER TABLE county CHANGE name name VARCHAR(255) NOT NULL, CHANGE ZIP zip VARCHAR(255) NOT NULL');
        $this->addSql('ALTER TABLE county RENAME INDEX idx_c1765b6398260155 TO IDX_58E2FF2598260155');
        $this->addSql('ALTER TABLE delivery CHANGE title title VARCHAR(255) NOT NULL');
        $this->addSql('ALTER TABLE order_line CHANGE order_id order_id INT NOT NULL, CHANGE product_name product_name VARCHAR(255) NOT NULL, CHANGE product_price product_price DOUBLE PRECISION NOT NULL, CHANGE tax_rate tax_rate DOUBLE PRECISION NOT NULL, CHANGE quantity quantity INT NOT NULL, CHANGE total_price total_price DOUBLE PRECISION NOT NULL, CHANGE user_lastname user_lastname VARCHAR(255) NOT NULL, CHANGE user_firstname user_firstname VARCHAR(255) NOT NULL, CHANGE user_email user_email VARCHAR(255) NOT NULL');
        $this->addSql('ALTER TABLE order_line RENAME INDEX idx_7982ace682ea2e54 TO IDX_9CE58EE18D9F6D38');
        $this->addSql('ALTER TABLE orders CHANGE delivered_id delivered_id INT NOT NULL, CHANGE billed_id billed_id INT NOT NULL, CHANGE order_date order_date DATE NOT NULL COMMENT \'(DC2Type:date_immutable)\', CHANGE ti_order_price ti_order_price DOUBLE PRECISION NOT NULL');
        $this->addSql('ALTER TABLE orders RENAME INDEX idx_6eeaa67d8e54fb25 TO IDX_E52FFDEE12136921');
        $this->addSql('ALTER TABLE orders RENAME INDEX idx_6eeaa67d2a4c4478 TO IDX_E52FFDEE4C3A3BB');
        $this->addSql('ALTER TABLE orders RENAME INDEX idx_6eeaa67dd5e86ff TO IDX_E52FFDEE5D83CC1');
        $this->addSql('ALTER TABLE orders RENAME INDEX idx_6eeaa67dac56b862 TO IDX_E52FFDEEC3D4ABB7');
        $this->addSql('ALTER TABLE orders RENAME INDEX idx_6eeaa67d8e569dde TO IDX_E52FFDEE148E6BE7');
        $this->addSql('ALTER TABLE orders RENAME INDEX idx_6eeaa67d67b3b43d TO IDX_E52FFDEE67B3B43D');
        $this->addSql('ALTER TABLE orders RENAME INDEX uniq_6eeaa67df77d927c TO UNIQ_E52FFDEE1AD5CDBF');
        $this->addSql('ALTER TABLE payment CHANGE title title VARCHAR(255) NOT NULL');
        $this->addSql('ALTER TABLE photos RENAME INDEX idx_876e0d9bcf5e72d TO IDX_876E0D912469DE2');
        $this->addSql('ALTER TABLE photos RENAME INDEX idx_876e0d9f347efb TO IDX_876E0D94584665A');
        $this->addSql('ALTER TABLE product ADD audio VARCHAR(255) DEFAULT NULL, CHANGE tax_rate_id tax_rate_id INT NOT NULL, CHANGE title title VARCHAR(255) NOT NULL, CHANGE tax_free_price tax_free_price DOUBLE PRECISION NOT NULL');
        $this->addSql('ALTER TABLE product RENAME INDEX idx_29a5ec274d79775f TO IDX_D34A04ADFDD13F95');
        $this->addSql('ALTER TABLE product RENAME INDEX idx_29a5ec27139df194 TO IDX_D34A04ADA4522A07');
        $this->addSql('ALTER TABLE product RENAME INDEX idx_29a5ec27bcf5e72d TO IDX_D34A04AD12469DE2');
        $this->addSql('ALTER TABLE region CHANGE name name VARCHAR(255) NOT NULL');
        $this->addSql('ALTER TABLE sales CHANGE title title VARCHAR(255) NOT NULL, CHANGE sales_rate sales_rate DOUBLE PRECISION NOT NULL, CHANGE sales_code sales_code VARCHAR(255) NOT NULL');
        $this->addSql('ALTER TABLE state CHANGE title title VARCHAR(255) NOT NULL');
        $this->addSql('ALTER TABLE tax_rate CHANGE tax_rate tax_rate DOUBLE PRECISION NOT NULL');
        $this->addSql('ALTER TABLE users CHANGE lastname lastname VARCHAR(255) NOT NULL, CHANGE firstname firstname VARCHAR(255) NOT NULL');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE adress DROP FOREIGN KEY FK_5CECC7BEE4C7FA21');
        $this->addSql('CREATE TABLE city_zip_code (city_id INT NOT NULL, ZIP_code_id INT NOT NULL, INDEX IDX_B5964877A73F0036 (city_id), INDEX IDX_B5964877B2B59251 (ZIP_code_id), PRIMARY KEY(city_id, ZIP_code_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('CREATE TABLE zip_code (id INT AUTO_INCREMENT NOT NULL, title VARCHAR(255) CHARACTER SET utf8mb4 DEFAULT NULL COLLATE `utf8mb4_unicode_ci`, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('ALTER TABLE city_zip_code ADD CONSTRAINT FK_B5964877A73F0036 FOREIGN KEY (city_id) REFERENCES city (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE city_zip_code ADD CONSTRAINT FK_B5964877B2B59251 FOREIGN KEY (ZIP_code_id) REFERENCES zip_code (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE city_zipcode DROP FOREIGN KEY FK_E844BF908BAC62AF');
        $this->addSql('ALTER TABLE city_zipcode DROP FOREIGN KEY FK_E844BF90E4C7FA21');
        $this->addSql('DROP TABLE city_zipcode');
        $this->addSql('DROP TABLE zipcode');
        $this->addSql('ALTER TABLE admin CHANGE lastname lastname VARCHAR(25) DEFAULT NULL, CHANGE firstname firstname VARCHAR(50) DEFAULT NULL');
        $this->addSql('ALTER TABLE adress CHANGE number number INT DEFAULT NULL, CHANGE street street VARCHAR(240) DEFAULT NULL');
        $this->addSql('ALTER TABLE adress ADD CONSTRAINT FK_C35F0816B2B59251 FOREIGN KEY (zipcode_id) REFERENCES zip_code (id)');
        $this->addSql('ALTER TABLE adress RENAME INDEX idx_5cecc7be8bac62af TO IDX_C35F0816A73F0036');
        $this->addSql('ALTER TABLE adress RENAME INDEX idx_5cecc7bee4c7fa21 TO IDX_C35F0816B2B59251');
        $this->addSql('ALTER TABLE adress RENAME INDEX idx_5cecc7be67b3b43d TO IDX_C35F081667B3B43D');
        $this->addSql('ALTER TABLE cart RENAME INDEX idx_ba388b767b3b43d TO IDX_24CC0DF267B3B43D');
        $this->addSql('ALTER TABLE cart_product CHANGE quantity quantity INT DEFAULT NULL');
        $this->addSql('ALTER TABLE cart_product RENAME INDEX idx_2890ccaa4584665a TO IDX_D31F28A6F347EFB');
        $this->addSql('ALTER TABLE cart_product RENAME INDEX idx_2890ccaa1ad5cdbf TO IDX_D31F28A6F77D927C');
        $this->addSql('ALTER TABLE category CHANGE title title VARCHAR(240) DEFAULT NULL');
        $this->addSql('ALTER TABLE category RENAME INDEX idx_64c19c1796a8f92 TO IDX_497DD6345CBD743C');
        $this->addSql('ALTER TABLE city CHANGE county_id county_id INT DEFAULT NULL, CHANGE name name VARCHAR(240) DEFAULT NULL');
        $this->addSql('ALTER TABLE city RENAME INDEX idx_2d5b023485e73f45 TO IDX_43C3D9C3CCF9E01E');
        $this->addSql('ALTER TABLE county CHANGE name name VARCHAR(240) DEFAULT NULL, CHANGE zip ZIP VARCHAR(3) DEFAULT NULL');
        $this->addSql('ALTER TABLE county RENAME INDEX idx_58e2ff2598260155 TO IDX_C1765B6398260155');
        $this->addSql('ALTER TABLE delivery CHANGE title title VARCHAR(240) DEFAULT NULL');
        $this->addSql('ALTER TABLE order_line CHANGE order_id order_id INT DEFAULT NULL, CHANGE product_name product_name VARCHAR(240) DEFAULT NULL, CHANGE product_price product_price DOUBLE PRECISION DEFAULT NULL, CHANGE tax_rate tax_rate DOUBLE PRECISION DEFAULT NULL, CHANGE quantity quantity INT DEFAULT NULL, CHANGE total_price total_price DOUBLE PRECISION DEFAULT NULL, CHANGE user_lastname user_lastname VARCHAR(240) DEFAULT NULL, CHANGE user_firstname user_firstname VARCHAR(240) DEFAULT NULL, CHANGE user_email user_email VARCHAR(240) DEFAULT NULL');
        $this->addSql('ALTER TABLE order_line RENAME INDEX idx_9ce58ee18d9f6d38 TO IDX_7982ACE682EA2E54');
        $this->addSql('ALTER TABLE orders CHANGE delivered_id delivered_id INT DEFAULT NULL, CHANGE billed_id billed_id INT DEFAULT NULL, CHANGE order_date order_date DATE DEFAULT NULL, CHANGE ti_order_price ti_order_price DOUBLE PRECISION DEFAULT NULL');
        $this->addSql('ALTER TABLE orders RENAME INDEX idx_e52ffdee12136921 TO IDX_6EEAA67D8E54FB25');
        $this->addSql('ALTER TABLE orders RENAME INDEX idx_e52ffdee5d83cc1 TO IDX_6EEAA67DD5E86FF');
        $this->addSql('ALTER TABLE orders RENAME INDEX idx_e52ffdee67b3b43d TO IDX_6EEAA67D67B3B43D');
        $this->addSql('ALTER TABLE orders RENAME INDEX idx_e52ffdeec3d4abb7 TO IDX_6EEAA67DAC56B862');
        $this->addSql('ALTER TABLE orders RENAME INDEX uniq_e52ffdee1ad5cdbf TO UNIQ_6EEAA67DF77D927C');
        $this->addSql('ALTER TABLE orders RENAME INDEX idx_e52ffdee4c3a3bb TO IDX_6EEAA67D2A4C4478');
        $this->addSql('ALTER TABLE orders RENAME INDEX idx_e52ffdee148e6be7 TO IDX_6EEAA67D8E569DDE');
        $this->addSql('ALTER TABLE payment CHANGE title title VARCHAR(255) DEFAULT NULL');
        $this->addSql('ALTER TABLE photos RENAME INDEX idx_876e0d94584665a TO IDX_876E0D9F347EFB');
        $this->addSql('ALTER TABLE photos RENAME INDEX idx_876e0d912469de2 TO IDX_876E0D9BCF5E72D');
        $this->addSql('ALTER TABLE product DROP audio, CHANGE tax_rate_id tax_rate_id INT DEFAULT NULL, CHANGE title title VARCHAR(255) DEFAULT NULL, CHANGE tax_free_price tax_free_price DOUBLE PRECISION DEFAULT NULL');
        $this->addSql('ALTER TABLE product RENAME INDEX idx_d34a04ada4522a07 TO IDX_29A5EC27139DF194');
        $this->addSql('ALTER TABLE product RENAME INDEX idx_d34a04adfdd13f95 TO IDX_29A5EC274D79775F');
        $this->addSql('ALTER TABLE product RENAME INDEX idx_d34a04ad12469de2 TO IDX_29A5EC27BCF5E72D');
        $this->addSql('ALTER TABLE region CHANGE name name VARCHAR(255) DEFAULT NULL');
        $this->addSql('ALTER TABLE sales CHANGE title title VARCHAR(255) DEFAULT NULL, CHANGE sales_rate sales_rate VARCHAR(255) DEFAULT NULL, CHANGE sales_code sales_code VARCHAR(255) DEFAULT NULL');
        $this->addSql('ALTER TABLE state CHANGE title title VARCHAR(255) DEFAULT NULL');
        $this->addSql('ALTER TABLE tax_rate CHANGE tax_rate tax_rate DOUBLE PRECISION DEFAULT NULL');
        $this->addSql('ALTER TABLE users CHANGE lastname lastname VARCHAR(255) DEFAULT NULL, CHANGE firstname firstname VARCHAR(255) DEFAULT NULL');
    }
}
