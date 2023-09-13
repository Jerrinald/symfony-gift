<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230825184437 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SEQUENCE booking_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE TABLE booking (id INT NOT NULL, user_booking_id INT NOT NULL, list_gift_id INT DEFAULT NULL, gift_id INT NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX IDX_E00CEDDEA457DA8C ON booking (user_booking_id)');
        $this->addSql('CREATE INDEX IDX_E00CEDDE4BE6878E ON booking (list_gift_id)');
        $this->addSql('CREATE INDEX IDX_E00CEDDE97A95A83 ON booking (gift_id)');
        $this->addSql('ALTER TABLE booking ADD CONSTRAINT FK_E00CEDDEA457DA8C FOREIGN KEY (user_booking_id) REFERENCES "user" (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE booking ADD CONSTRAINT FK_E00CEDDE4BE6878E FOREIGN KEY (list_gift_id) REFERENCES list_gift (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE booking ADD CONSTRAINT FK_E00CEDDE97A95A83 FOREIGN KEY (gift_id) REFERENCES gift (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SCHEMA public');
        $this->addSql('DROP SEQUENCE booking_id_seq CASCADE');
        $this->addSql('ALTER TABLE booking DROP CONSTRAINT FK_E00CEDDEA457DA8C');
        $this->addSql('ALTER TABLE booking DROP CONSTRAINT FK_E00CEDDE4BE6878E');
        $this->addSql('ALTER TABLE booking DROP CONSTRAINT FK_E00CEDDE97A95A83');
        $this->addSql('DROP TABLE booking');
    }
}
