<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20241215144659 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE admin (id INT AUTO_INCREMENT NOT NULL, roles JSON NOT NULL, lastname VARCHAR(255) NOT NULL, firstname VARCHAR(255) NOT NULL, password VARCHAR(255) NOT NULL, email VARCHAR(255) NOT NULL, username VARCHAR(255) NOT NULL, UNIQUE INDEX UNIQ_880E0D76E7927C74 (email), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE adress (id INT AUTO_INCREMENT NOT NULL, city_id INT DEFAULT NULL, users_id INT NOT NULL, zipcode_id INT DEFAULT NULL, number INT NOT NULL, street VARCHAR(255) NOT NULL, complement VARCHAR(255) DEFAULT NULL, is_active TINYINT(1) NOT NULL, INDEX IDX_5CECC7BE8BAC62AF (city_id), INDEX IDX_5CECC7BE67B3B43D (users_id), INDEX IDX_5CECC7BEE4C7FA21 (zipcode_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE cart (id INT AUTO_INCREMENT NOT NULL, users_id INT DEFAULT NULL, INDEX IDX_BA388B767B3B43D (users_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE cart_product (id INT AUTO_INCREMENT NOT NULL, product_id INT DEFAULT NULL, cart_id INT DEFAULT NULL, quantity INT NOT NULL, INDEX IDX_2890CCAA4584665A (product_id), INDEX IDX_2890CCAA1AD5CDBF (cart_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE category (id INT AUTO_INCREMENT NOT NULL, parent_category_id INT DEFAULT NULL, title VARCHAR(255) NOT NULL, description VARCHAR(255) NOT NULL, INDEX IDX_64C19C1796A8F92 (parent_category_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE city (id INT AUTO_INCREMENT NOT NULL, county_id INT NOT NULL, name VARCHAR(255) NOT NULL, INDEX IDX_2D5B023485E73F45 (county_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE city_zipcode (city_id INT NOT NULL, zipcode_id INT NOT NULL, INDEX IDX_E844BF908BAC62AF (city_id), INDEX IDX_E844BF90E4C7FA21 (zipcode_id), PRIMARY KEY(city_id, zipcode_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE county (id INT AUTO_INCREMENT NOT NULL, region_id INT NOT NULL, name VARCHAR(255) NOT NULL, zip VARCHAR(255) NOT NULL, INDEX IDX_58E2FF2598260155 (region_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE delivery (id INT AUTO_INCREMENT NOT NULL, title VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE order_line (id INT AUTO_INCREMENT NOT NULL, order_id INT NOT NULL, product_name VARCHAR(255) NOT NULL, product_price DOUBLE PRECISION NOT NULL, tax_rate DOUBLE PRECISION NOT NULL, quantity INT NOT NULL, total_price DOUBLE PRECISION NOT NULL, user_lastname VARCHAR(255) NOT NULL, user_firstname VARCHAR(255) NOT NULL, user_email VARCHAR(255) NOT NULL, INDEX IDX_9CE58EE18D9F6D38 (order_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE orders (id INT AUTO_INCREMENT NOT NULL, delivery_id INT DEFAULT NULL, payment_id INT DEFAULT NULL, state_id INT DEFAULT NULL, delivered_id INT NOT NULL, billed_id INT NOT NULL, users_id INT NOT NULL, cart_id INT DEFAULT NULL, order_date DATE NOT NULL COMMENT \'(DC2Type:date_immutable)\', ti_order_price DOUBLE PRECISION NOT NULL, INDEX IDX_E52FFDEE12136921 (delivery_id), INDEX IDX_E52FFDEE4C3A3BB (payment_id), INDEX IDX_E52FFDEE5D83CC1 (state_id), INDEX IDX_E52FFDEEC3D4ABB7 (delivered_id), INDEX IDX_E52FFDEE148E6BE7 (billed_id), INDEX IDX_E52FFDEE67B3B43D (users_id), UNIQUE INDEX UNIQ_E52FFDEE1AD5CDBF (cart_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE payment (id INT AUTO_INCREMENT NOT NULL, title VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE photos (id INT AUTO_INCREMENT NOT NULL, category_id INT DEFAULT NULL, product_id INT DEFAULT NULL, url_photo VARCHAR(255) NOT NULL, INDEX IDX_876E0D912469DE2 (category_id), INDEX IDX_876E0D94584665A (product_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE product (id INT AUTO_INCREMENT NOT NULL, tax_rate_id INT NOT NULL, sales_id INT DEFAULT NULL, category_id INT DEFAULT NULL, title VARCHAR(255) NOT NULL, description VARCHAR(255) NOT NULL, tax_free_price DOUBLE PRECISION NOT NULL, audio VARCHAR(255) DEFAULT NULL, INDEX IDX_D34A04ADFDD13F95 (tax_rate_id), INDEX IDX_D34A04ADA4522A07 (sales_id), INDEX IDX_D34A04AD12469DE2 (category_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE region (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE sales (id INT AUTO_INCREMENT NOT NULL, title VARCHAR(255) NOT NULL, sales_rate DOUBLE PRECISION NOT NULL, sales_code VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE state (id INT AUTO_INCREMENT NOT NULL, title VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE tax_rate (id INT AUTO_INCREMENT NOT NULL, tax_rate DOUBLE PRECISION NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE users (id INT AUTO_INCREMENT NOT NULL, lastname VARCHAR(255) NOT NULL, firstname VARCHAR(255) NOT NULL, email VARCHAR(255) NOT NULL, roles JSON NOT NULL, password VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE zipcode (id INT AUTO_INCREMENT NOT NULL, title VARCHAR(10) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE messenger_messages (id BIGINT AUTO_INCREMENT NOT NULL, body LONGTEXT NOT NULL, headers LONGTEXT NOT NULL, queue_name VARCHAR(190) NOT NULL, created_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', available_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', delivered_at DATETIME DEFAULT NULL COMMENT \'(DC2Type:datetime_immutable)\', INDEX IDX_75EA56E0FB7336F0 (queue_name), INDEX IDX_75EA56E0E3BD61CE (available_at), INDEX IDX_75EA56E016BA31DB (delivered_at), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE adress ADD CONSTRAINT FK_5CECC7BE8BAC62AF FOREIGN KEY (city_id) REFERENCES city (id)');
        $this->addSql('ALTER TABLE adress ADD CONSTRAINT FK_5CECC7BE67B3B43D FOREIGN KEY (users_id) REFERENCES users (id)');
        $this->addSql('ALTER TABLE adress ADD CONSTRAINT FK_5CECC7BEE4C7FA21 FOREIGN KEY (zipcode_id) REFERENCES zipcode (id)');
        $this->addSql('ALTER TABLE cart ADD CONSTRAINT FK_BA388B767B3B43D FOREIGN KEY (users_id) REFERENCES users (id)');
        $this->addSql('ALTER TABLE cart_product ADD CONSTRAINT FK_2890CCAA4584665A FOREIGN KEY (product_id) REFERENCES product (id)');
        $this->addSql('ALTER TABLE cart_product ADD CONSTRAINT FK_2890CCAA1AD5CDBF FOREIGN KEY (cart_id) REFERENCES cart (id)');
        $this->addSql('ALTER TABLE category ADD CONSTRAINT FK_64C19C1796A8F92 FOREIGN KEY (parent_category_id) REFERENCES category (id)');
        $this->addSql('ALTER TABLE city ADD CONSTRAINT FK_2D5B023485E73F45 FOREIGN KEY (county_id) REFERENCES county (id)');
        $this->addSql('ALTER TABLE city_zipcode ADD CONSTRAINT FK_E844BF908BAC62AF FOREIGN KEY (city_id) REFERENCES city (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE city_zipcode ADD CONSTRAINT FK_E844BF90E4C7FA21 FOREIGN KEY (zipcode_id) REFERENCES zipcode (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE county ADD CONSTRAINT FK_58E2FF2598260155 FOREIGN KEY (region_id) REFERENCES region (id)');
        $this->addSql('ALTER TABLE order_line ADD CONSTRAINT FK_9CE58EE18D9F6D38 FOREIGN KEY (order_id) REFERENCES orders (id)');
        $this->addSql('ALTER TABLE orders ADD CONSTRAINT FK_E52FFDEE12136921 FOREIGN KEY (delivery_id) REFERENCES delivery (id)');
        $this->addSql('ALTER TABLE orders ADD CONSTRAINT FK_E52FFDEE4C3A3BB FOREIGN KEY (payment_id) REFERENCES payment (id)');
        $this->addSql('ALTER TABLE orders ADD CONSTRAINT FK_E52FFDEE5D83CC1 FOREIGN KEY (state_id) REFERENCES state (id)');
        $this->addSql('ALTER TABLE orders ADD CONSTRAINT FK_E52FFDEEC3D4ABB7 FOREIGN KEY (delivered_id) REFERENCES adress (id)');
        $this->addSql('ALTER TABLE orders ADD CONSTRAINT FK_E52FFDEE148E6BE7 FOREIGN KEY (billed_id) REFERENCES adress (id)');
        $this->addSql('ALTER TABLE orders ADD CONSTRAINT FK_E52FFDEE67B3B43D FOREIGN KEY (users_id) REFERENCES users (id)');
        $this->addSql('ALTER TABLE orders ADD CONSTRAINT FK_E52FFDEE1AD5CDBF FOREIGN KEY (cart_id) REFERENCES cart (id)');
        $this->addSql('ALTER TABLE photos ADD CONSTRAINT FK_876E0D912469DE2 FOREIGN KEY (category_id) REFERENCES category (id)');
        $this->addSql('ALTER TABLE photos ADD CONSTRAINT FK_876E0D94584665A FOREIGN KEY (product_id) REFERENCES product (id)');
        $this->addSql('ALTER TABLE product ADD CONSTRAINT FK_D34A04ADFDD13F95 FOREIGN KEY (tax_rate_id) REFERENCES tax_rate (id)');
        $this->addSql('ALTER TABLE product ADD CONSTRAINT FK_D34A04ADA4522A07 FOREIGN KEY (sales_id) REFERENCES sales (id)');
        $this->addSql('ALTER TABLE product ADD CONSTRAINT FK_D34A04AD12469DE2 FOREIGN KEY (category_id) REFERENCES category (id)');
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
