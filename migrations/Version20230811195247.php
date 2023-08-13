<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230811195247 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('DROP SEQUENCE content_id_seq CASCADE');
        $this->addSql('DROP SEQUENCE family_id_seq CASCADE');
        $this->addSql('DROP SEQUENCE hospitalization_id_seq CASCADE');
        $this->addSql('DROP SEQUENCE job_id_seq CASCADE');
        $this->addSql('DROP SEQUENCE notification_hospitalization_recommandation_id_seq CASCADE');
        $this->addSql('DROP SEQUENCE notification_job_user_id_seq CASCADE');
        $this->addSql('DROP SEQUENCE notification_recommendation_vaccine_id_seq CASCADE');
        $this->addSql('DROP SEQUENCE pathology_id_seq CASCADE');
        $this->addSql('DROP SEQUENCE recommendation_job_pathology_id_seq CASCADE');
        $this->addSql('DROP SEQUENCE recommendation_pathology_hospital_id_seq CASCADE');
        $this->addSql('DROP SEQUENCE type_id_seq CASCADE');
        $this->addSql('DROP SEQUENCE vaccine_id_seq CASCADE');
        $this->addSql('ALTER TABLE notification_hospitalization_recommandation DROP CONSTRAINT fk_6491e0c791bec254');
        $this->addSql('ALTER TABLE notification_hospitalization_recommandation DROP CONSTRAINT fk_6491e0c798e81baa');
        $this->addSql('ALTER TABLE hospitalization DROP CONSTRAINT fk_40cf0891103f94d4');
        $this->addSql('ALTER TABLE hospitalization DROP CONSTRAINT fk_40cf08913492c8a5');
        $this->addSql('ALTER TABLE content DROP CONSTRAINT fk_fec530a9aa5b841d');
        $this->addSql('ALTER TABLE notification_recommendation_vaccine DROP CONSTRAINT fk_6881c292103f94d4');
        $this->addSql('ALTER TABLE notification_recommendation_vaccine DROP CONSTRAINT fk_6881c292ce7dc311');
        $this->addSql('ALTER TABLE notification_job_user DROP CONSTRAINT fk_5f4f98ad613043ec');
        $this->addSql('ALTER TABLE notification_job_user DROP CONSTRAINT fk_5f4f98ad103f94d4');
        $this->addSql('ALTER TABLE recommendation_job_pathology DROP CONSTRAINT fk_359ea768dd2fc225');
        $this->addSql('ALTER TABLE recommendation_job_pathology DROP CONSTRAINT fk_359ea7683492c8a5');
        $this->addSql('ALTER TABLE recommendation_job_pathology DROP CONSTRAINT fk_359ea7682e61e4ca');
        $this->addSql('ALTER TABLE recommendation_pathology_hospital DROP CONSTRAINT fk_9d51f6fe3492c8a5');
        $this->addSql('ALTER TABLE recommendation_pathology_hospital DROP CONSTRAINT fk_9d51f6fe2e61e4ca');
        $this->addSql('DROP TABLE notification_hospitalization_recommandation');
        $this->addSql('DROP TABLE pathology');
        $this->addSql('DROP TABLE hospitalization');
        $this->addSql('DROP TABLE job');
        $this->addSql('DROP TABLE type');
        $this->addSql('DROP TABLE family');
        $this->addSql('DROP TABLE content');
        $this->addSql('DROP TABLE vaccine');
        $this->addSql('DROP TABLE notification_recommendation_vaccine');
        $this->addSql('DROP TABLE notification_job_user');
        $this->addSql('DROP TABLE recommendation_job_pathology');
        $this->addSql('DROP TABLE recommendation_pathology_hospital');
        $this->addSql('ALTER TABLE "user" DROP firstname');
        $this->addSql('ALTER TABLE "user" DROP lastname');
        $this->addSql('ALTER TABLE "user" DROP born');
        $this->addSql('ALTER TABLE "user" DROP gender');
        $this->addSql('ALTER TABLE "user" DROP phone');
        $this->addSql('ALTER TABLE "user" DROP type');
        $this->addSql('ALTER TABLE "user" ALTER password SET NOT NULL');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SCHEMA public');
        $this->addSql('CREATE SEQUENCE content_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE SEQUENCE family_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE SEQUENCE hospitalization_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE SEQUENCE job_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE SEQUENCE notification_hospitalization_recommandation_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE SEQUENCE notification_job_user_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE SEQUENCE notification_recommendation_vaccine_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE SEQUENCE pathology_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE SEQUENCE recommendation_job_pathology_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE SEQUENCE recommendation_pathology_hospital_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE SEQUENCE type_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE SEQUENCE vaccine_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE TABLE notification_hospitalization_recommandation (id INT NOT NULL, hospitalization_integer_id INT DEFAULT NULL, recommandation_pathology_hospital_integer_id INT DEFAULT NULL, notify BOOLEAN DEFAULT NULL, date_notification DATE DEFAULT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX idx_6491e0c798e81baa ON notification_hospitalization_recommandation (recommandation_pathology_hospital_integer_id)');
        $this->addSql('CREATE INDEX idx_6491e0c791bec254 ON notification_hospitalization_recommandation (hospitalization_integer_id)');
        $this->addSql('CREATE TABLE pathology (id INT NOT NULL, title VARCHAR(255) DEFAULT NULL, description TEXT DEFAULT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE TABLE hospitalization (id INT NOT NULL, user_integer_id INT DEFAULT NULL, pathology_integer_id INT DEFAULT NULL, entry_date DATE DEFAULT NULL, end_date DATE DEFAULT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX idx_40cf08913492c8a5 ON hospitalization (pathology_integer_id)');
        $this->addSql('CREATE INDEX idx_40cf0891103f94d4 ON hospitalization (user_integer_id)');
        $this->addSql('CREATE TABLE job (id INT NOT NULL, title VARCHAR(255) DEFAULT NULL, description TEXT DEFAULT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE TABLE type (id INT NOT NULL, title VARCHAR(255) DEFAULT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE TABLE family (id INT NOT NULL, groupe JSON DEFAULT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE TABLE content (id INT NOT NULL, type_integer_id INT DEFAULT NULL, title VARCHAR(255) DEFAULT NULL, description TEXT DEFAULT NULL, image_source VARCHAR(255) DEFAULT NULL, slug VARCHAR(128) DEFAULT NULL, created DATE DEFAULT NULL, updated DATE DEFAULT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX idx_fec530a9aa5b841d ON content (type_integer_id)');
        $this->addSql('CREATE UNIQUE INDEX uniq_fec530a9989d9b62 ON content (slug)');
        $this->addSql('CREATE TABLE vaccine (id INT NOT NULL, title VARCHAR(255) DEFAULT NULL, description TEXT DEFAULT NULL, slug VARCHAR(128) DEFAULT NULL, recommandation_age INT DEFAULT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE UNIQUE INDEX uniq_a7dd90b1989d9b62 ON vaccine (slug)');
        $this->addSql('CREATE TABLE notification_recommendation_vaccine (id INT NOT NULL, user_integer_id INT DEFAULT NULL, vaccine_integer_id INT DEFAULT NULL, notify BOOLEAN DEFAULT NULL, date_notification DATE DEFAULT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX idx_6881c292ce7dc311 ON notification_recommendation_vaccine (vaccine_integer_id)');
        $this->addSql('CREATE INDEX idx_6881c292103f94d4 ON notification_recommendation_vaccine (user_integer_id)');
        $this->addSql('CREATE TABLE notification_job_user (id INT NOT NULL, recommandation_job_pathology_integer_id INT DEFAULT NULL, user_integer_id INT DEFAULT NULL, notify BOOLEAN DEFAULT NULL, date_notification DATE DEFAULT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX idx_5f4f98ad103f94d4 ON notification_job_user (user_integer_id)');
        $this->addSql('CREATE INDEX idx_5f4f98ad613043ec ON notification_job_user (recommandation_job_pathology_integer_id)');
        $this->addSql('CREATE TABLE recommendation_job_pathology (id INT NOT NULL, job_integer_id INT DEFAULT NULL, pathology_integer_id INT DEFAULT NULL, content_integer_id INT DEFAULT NULL, age INT DEFAULT NULL, description TEXT DEFAULT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX idx_359ea7682e61e4ca ON recommendation_job_pathology (content_integer_id)');
        $this->addSql('CREATE INDEX idx_359ea7683492c8a5 ON recommendation_job_pathology (pathology_integer_id)');
        $this->addSql('CREATE INDEX idx_359ea768dd2fc225 ON recommendation_job_pathology (job_integer_id)');
        $this->addSql('CREATE TABLE recommendation_pathology_hospital (id INT NOT NULL, pathology_integer_id INT DEFAULT NULL, content_integer_id INT DEFAULT NULL, "interval" VARCHAR(255) DEFAULT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX idx_9d51f6fe2e61e4ca ON recommendation_pathology_hospital (content_integer_id)');
        $this->addSql('CREATE INDEX idx_9d51f6fe3492c8a5 ON recommendation_pathology_hospital (pathology_integer_id)');
        $this->addSql('ALTER TABLE notification_hospitalization_recommandation ADD CONSTRAINT fk_6491e0c791bec254 FOREIGN KEY (hospitalization_integer_id) REFERENCES hospitalization (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE notification_hospitalization_recommandation ADD CONSTRAINT fk_6491e0c798e81baa FOREIGN KEY (recommandation_pathology_hospital_integer_id) REFERENCES recommendation_pathology_hospital (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE hospitalization ADD CONSTRAINT fk_40cf0891103f94d4 FOREIGN KEY (user_integer_id) REFERENCES "user" (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE hospitalization ADD CONSTRAINT fk_40cf08913492c8a5 FOREIGN KEY (pathology_integer_id) REFERENCES pathology (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE content ADD CONSTRAINT fk_fec530a9aa5b841d FOREIGN KEY (type_integer_id) REFERENCES type (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE notification_recommendation_vaccine ADD CONSTRAINT fk_6881c292103f94d4 FOREIGN KEY (user_integer_id) REFERENCES "user" (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE notification_recommendation_vaccine ADD CONSTRAINT fk_6881c292ce7dc311 FOREIGN KEY (vaccine_integer_id) REFERENCES vaccine (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE notification_job_user ADD CONSTRAINT fk_5f4f98ad613043ec FOREIGN KEY (recommandation_job_pathology_integer_id) REFERENCES recommendation_job_pathology (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE notification_job_user ADD CONSTRAINT fk_5f4f98ad103f94d4 FOREIGN KEY (user_integer_id) REFERENCES "user" (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE recommendation_job_pathology ADD CONSTRAINT fk_359ea768dd2fc225 FOREIGN KEY (job_integer_id) REFERENCES job (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE recommendation_job_pathology ADD CONSTRAINT fk_359ea7683492c8a5 FOREIGN KEY (pathology_integer_id) REFERENCES pathology (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE recommendation_job_pathology ADD CONSTRAINT fk_359ea7682e61e4ca FOREIGN KEY (content_integer_id) REFERENCES content (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE recommendation_pathology_hospital ADD CONSTRAINT fk_9d51f6fe3492c8a5 FOREIGN KEY (pathology_integer_id) REFERENCES pathology (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE recommendation_pathology_hospital ADD CONSTRAINT fk_9d51f6fe2e61e4ca FOREIGN KEY (content_integer_id) REFERENCES content (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE "user" ADD firstname VARCHAR(255) NOT NULL');
        $this->addSql('ALTER TABLE "user" ADD lastname VARCHAR(255) NOT NULL');
        $this->addSql('ALTER TABLE "user" ADD born DATE DEFAULT NULL');
        $this->addSql('ALTER TABLE "user" ADD gender VARCHAR(255) DEFAULT NULL');
        $this->addSql('ALTER TABLE "user" ADD phone VARCHAR(255) DEFAULT NULL');
        $this->addSql('ALTER TABLE "user" ADD type VARCHAR(255) DEFAULT NULL');
        $this->addSql('ALTER TABLE "user" ALTER password DROP NOT NULL');
    }
}
