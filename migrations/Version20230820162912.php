<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230820162912 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SEQUENCE gift_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE SEQUENCE list_gift_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE TABLE gift (id INT NOT NULL, user_gift_id INT NOT NULL, namename VARCHAR(255) NOT NULL, price DOUBLE PRECISION DEFAULT NULL, url_purchase VARCHAR(255) DEFAULT NULL, created_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, updated_at TIMESTAMP(0) WITHOUT TIME ZONE DEFAULT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX IDX_A47C990DCFA44489 ON gift (user_gift_id)');
        $this->addSql('COMMENT ON COLUMN gift.created_at IS \'(DC2Type:datetime_immutable)\'');
        $this->addSql('COMMENT ON COLUMN gift.updated_at IS \'(DC2Type:datetime_immutable)\'');
        $this->addSql('CREATE TABLE list_gift (id INT NOT NULL, user_list_id INT NOT NULL, title VARCHAR(255) NOT NULL, description VARCHAR(255) DEFAULT NULL, is_private BOOLEAN DEFAULT NULL, opening_date DATE DEFAULT NULL, closing_date DATE DEFAULT NULL, created_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, updated_at TIMESTAMP(0) WITHOUT TIME ZONE DEFAULT NULL, theme VARCHAR(255) DEFAULT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX IDX_E1B246E465A30881 ON list_gift (user_list_id)');
        $this->addSql('COMMENT ON COLUMN list_gift.created_at IS \'(DC2Type:datetime_immutable)\'');
        $this->addSql('COMMENT ON COLUMN list_gift.updated_at IS \'(DC2Type:datetime_immutable)\'');
        $this->addSql('CREATE TABLE list_gift_gift (list_gift_id INT NOT NULL, gift_id INT NOT NULL, PRIMARY KEY(list_gift_id, gift_id))');
        $this->addSql('CREATE INDEX IDX_1BCD673B4BE6878E ON list_gift_gift (list_gift_id)');
        $this->addSql('CREATE INDEX IDX_1BCD673B97A95A83 ON list_gift_gift (gift_id)');
        $this->addSql('ALTER TABLE gift ADD CONSTRAINT FK_A47C990DCFA44489 FOREIGN KEY (user_gift_id) REFERENCES "user" (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE list_gift ADD CONSTRAINT FK_E1B246E465A30881 FOREIGN KEY (user_list_id) REFERENCES "user" (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE list_gift_gift ADD CONSTRAINT FK_1BCD673B4BE6878E FOREIGN KEY (list_gift_id) REFERENCES list_gift (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE list_gift_gift ADD CONSTRAINT FK_1BCD673B97A95A83 FOREIGN KEY (gift_id) REFERENCES gift (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SCHEMA public');
        $this->addSql('DROP SEQUENCE gift_id_seq CASCADE');
        $this->addSql('DROP SEQUENCE list_gift_id_seq CASCADE');
        $this->addSql('ALTER TABLE gift DROP CONSTRAINT FK_A47C990DCFA44489');
        $this->addSql('ALTER TABLE list_gift DROP CONSTRAINT FK_E1B246E465A30881');
        $this->addSql('ALTER TABLE list_gift_gift DROP CONSTRAINT FK_1BCD673B4BE6878E');
        $this->addSql('ALTER TABLE list_gift_gift DROP CONSTRAINT FK_1BCD673B97A95A83');
        $this->addSql('DROP TABLE gift');
        $this->addSql('DROP TABLE list_gift');
        $this->addSql('DROP TABLE list_gift_gift');
    }
}
