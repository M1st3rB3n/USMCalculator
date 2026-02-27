<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20260227230514 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE categorie (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, nom VARCHAR(255) NOT NULL)');
        $this->addSql('CREATE TABLE categorie_annee (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, annee INTEGER NOT NULL, categorie_id INTEGER NOT NULL, CONSTRAINT FK_2BEA281FBCF5E72D FOREIGN KEY (categorie_id) REFERENCES categorie (id) NOT DEFERRABLE INITIALLY IMMEDIATE)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_2BEA281FDE92C5CF ON categorie_annee (annee)');
        $this->addSql('CREATE INDEX IDX_2BEA281FBCF5E72D ON categorie_annee (categorie_id)');
        $this->addSql('CREATE TABLE club (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, nom VARCHAR(255) NOT NULL, ville VARCHAR(255) DEFAULT NULL, logo VARCHAR(255) DEFAULT NULL)');
        $this->addSql('CREATE TABLE competition (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, nom VARCHAR(255) NOT NULL, date DATETIME NOT NULL)');
        $this->addSql('CREATE TABLE element_artistique (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, nom VARCHAR(255) NOT NULL, score DOUBLE PRECISION DEFAULT NULL, qo_e DOUBLE PRECISION DEFAULT NULL)');
        $this->addSql('CREATE TABLE element_technique (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, nom VARCHAR(255) NOT NULL, famille VARCHAR(255) DEFAULT NULL, score DOUBLE PRECISION DEFAULT NULL, qo_e DOUBLE PRECISION DEFAULT NULL)');
        $this->addSql('CREATE TABLE engagement (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, patineuse_id INTEGER NOT NULL, epreuve_id INTEGER NOT NULL, CONSTRAINT FK_D86F0141AD2DD0CB FOREIGN KEY (patineuse_id) REFERENCES patineuse (id) NOT DEFERRABLE INITIALLY IMMEDIATE, CONSTRAINT FK_D86F0141AB990336 FOREIGN KEY (epreuve_id) REFERENCES epreuve (id) NOT DEFERRABLE INITIALLY IMMEDIATE)');
        $this->addSql('CREATE INDEX IDX_D86F0141AD2DD0CB ON engagement (patineuse_id)');
        $this->addSql('CREATE INDEX IDX_D86F0141AB990336 ON engagement (epreuve_id)');
        $this->addSql('CREATE TABLE epreuve (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, nom VARCHAR(255) NOT NULL, categorie_id INTEGER NOT NULL, niveau_id INTEGER NOT NULL, competition_id INTEGER DEFAULT NULL, CONSTRAINT FK_D6ADE47FBCF5E72D FOREIGN KEY (categorie_id) REFERENCES categorie (id) NOT DEFERRABLE INITIALLY IMMEDIATE, CONSTRAINT FK_D6ADE47FB3E9C81 FOREIGN KEY (niveau_id) REFERENCES niveau (id) NOT DEFERRABLE INITIALLY IMMEDIATE, CONSTRAINT FK_D6ADE47F7B39D312 FOREIGN KEY (competition_id) REFERENCES competition (id) NOT DEFERRABLE INITIALLY IMMEDIATE)');
        $this->addSql('CREATE INDEX IDX_D6ADE47FBCF5E72D ON epreuve (categorie_id)');
        $this->addSql('CREATE INDEX IDX_D6ADE47FB3E9C81 ON epreuve (niveau_id)');
        $this->addSql('CREATE INDEX IDX_D6ADE47F7B39D312 ON epreuve (competition_id)');
        $this->addSql('CREATE TABLE epreuve_element_technique (epreuve_id INTEGER NOT NULL, element_technique_id INTEGER NOT NULL, PRIMARY KEY (epreuve_id, element_technique_id), CONSTRAINT FK_968F9F28AB990336 FOREIGN KEY (epreuve_id) REFERENCES epreuve (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE, CONSTRAINT FK_968F9F28B747B22B FOREIGN KEY (element_technique_id) REFERENCES element_technique (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE)');
        $this->addSql('CREATE INDEX IDX_968F9F28AB990336 ON epreuve_element_technique (epreuve_id)');
        $this->addSql('CREATE INDEX IDX_968F9F28B747B22B ON epreuve_element_technique (element_technique_id)');
        $this->addSql('CREATE TABLE epreuve_element_artistique (epreuve_id INTEGER NOT NULL, element_artistique_id INTEGER NOT NULL, PRIMARY KEY (epreuve_id, element_artistique_id), CONSTRAINT FK_FC888C26AB990336 FOREIGN KEY (epreuve_id) REFERENCES epreuve (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE, CONSTRAINT FK_FC888C2692DE26B9 FOREIGN KEY (element_artistique_id) REFERENCES element_artistique (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE)');
        $this->addSql('CREATE INDEX IDX_FC888C26AB990336 ON epreuve_element_artistique (epreuve_id)');
        $this->addSql('CREATE INDEX IDX_FC888C2692DE26B9 ON epreuve_element_artistique (element_artistique_id)');
        $this->addSql('CREATE TABLE niveau (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, nom VARCHAR(255) NOT NULL)');
        $this->addSql('CREATE TABLE note_artistique (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, note DOUBLE PRECISION DEFAULT NULL, patineuse_id INTEGER NOT NULL, element_artistique_id INTEGER NOT NULL, epreuve_id INTEGER NOT NULL, CONSTRAINT FK_A800C8EDAD2DD0CB FOREIGN KEY (patineuse_id) REFERENCES patineuse (id) NOT DEFERRABLE INITIALLY IMMEDIATE, CONSTRAINT FK_A800C8ED92DE26B9 FOREIGN KEY (element_artistique_id) REFERENCES element_artistique (id) NOT DEFERRABLE INITIALLY IMMEDIATE, CONSTRAINT FK_A800C8EDAB990336 FOREIGN KEY (epreuve_id) REFERENCES epreuve (id) NOT DEFERRABLE INITIALLY IMMEDIATE)');
        $this->addSql('CREATE INDEX IDX_A800C8EDAD2DD0CB ON note_artistique (patineuse_id)');
        $this->addSql('CREATE INDEX IDX_A800C8ED92DE26B9 ON note_artistique (element_artistique_id)');
        $this->addSql('CREATE INDEX IDX_A800C8EDAB990336 ON note_artistique (epreuve_id)');
        $this->addSql('CREATE TABLE note_technique (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, note DOUBLE PRECISION DEFAULT NULL, patineuse_id INTEGER NOT NULL, element_technique_id INTEGER NOT NULL, epreuve_id INTEGER NOT NULL, CONSTRAINT FK_C09C7DDEAD2DD0CB FOREIGN KEY (patineuse_id) REFERENCES patineuse (id) NOT DEFERRABLE INITIALLY IMMEDIATE, CONSTRAINT FK_C09C7DDEB747B22B FOREIGN KEY (element_technique_id) REFERENCES element_technique (id) NOT DEFERRABLE INITIALLY IMMEDIATE, CONSTRAINT FK_C09C7DDEAB990336 FOREIGN KEY (epreuve_id) REFERENCES epreuve (id) NOT DEFERRABLE INITIALLY IMMEDIATE)');
        $this->addSql('CREATE INDEX IDX_C09C7DDEAD2DD0CB ON note_technique (patineuse_id)');
        $this->addSql('CREATE INDEX IDX_C09C7DDEB747B22B ON note_technique (element_technique_id)');
        $this->addSql('CREATE INDEX IDX_C09C7DDEAB990336 ON note_technique (epreuve_id)');
        $this->addSql('CREATE TABLE patineuse (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, nom VARCHAR(255) NOT NULL, prenom VARCHAR(255) NOT NULL, annee_de_naissance INTEGER NOT NULL, niveau_id INTEGER DEFAULT NULL, club_id INTEGER DEFAULT NULL, CONSTRAINT FK_7C3D06B0B3E9C81 FOREIGN KEY (niveau_id) REFERENCES niveau (id) NOT DEFERRABLE INITIALLY IMMEDIATE, CONSTRAINT FK_7C3D06B061190A32 FOREIGN KEY (club_id) REFERENCES club (id) NOT DEFERRABLE INITIALLY IMMEDIATE)');
        $this->addSql('CREATE INDEX IDX_7C3D06B0B3E9C81 ON patineuse (niveau_id)');
        $this->addSql('CREATE INDEX IDX_7C3D06B061190A32 ON patineuse (club_id)');
        $this->addSql('CREATE TABLE user (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, email VARCHAR(180) NOT NULL, roles CLOB NOT NULL, password VARCHAR(255) NOT NULL)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_IDENTIFIER_EMAIL ON user (email)');
        $this->addSql('CREATE TABLE messenger_messages (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, body CLOB NOT NULL, headers CLOB NOT NULL, queue_name VARCHAR(190) NOT NULL, created_at DATETIME NOT NULL, available_at DATETIME NOT NULL, delivered_at DATETIME DEFAULT NULL)');
        $this->addSql('CREATE INDEX IDX_75EA56E0FB7336F0E3BD61CE16BA31DBBF396750 ON messenger_messages (queue_name, available_at, delivered_at, id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('DROP TABLE categorie');
        $this->addSql('DROP TABLE categorie_annee');
        $this->addSql('DROP TABLE club');
        $this->addSql('DROP TABLE competition');
        $this->addSql('DROP TABLE element_artistique');
        $this->addSql('DROP TABLE element_technique');
        $this->addSql('DROP TABLE engagement');
        $this->addSql('DROP TABLE epreuve');
        $this->addSql('DROP TABLE epreuve_element_technique');
        $this->addSql('DROP TABLE epreuve_element_artistique');
        $this->addSql('DROP TABLE niveau');
        $this->addSql('DROP TABLE note_artistique');
        $this->addSql('DROP TABLE note_technique');
        $this->addSql('DROP TABLE patineuse');
        $this->addSql('DROP TABLE user');
        $this->addSql('DROP TABLE messenger_messages');
    }
}
