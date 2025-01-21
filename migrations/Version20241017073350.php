<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20241017073350 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE base_vins (id INT AUTO_INCREMENT NOT NULL, couleur VARCHAR(255) NOT NULL, type VARCHAR(255) NOT NULL, appellation VARCHAR(255) NOT NULL, nom_producteur VARCHAR(255) NOT NULL, domaine VARCHAR(255) DEFAULT NULL, nom_vin VARCHAR(255) DEFAULT NULL, cl VARCHAR(255) NOT NULL, millesime VARCHAR(255) NOT NULL, date_creation VARCHAR(255) NOT NULL, date_modification VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE cave (id INT AUTO_INCREMENT NOT NULL, id_restaurant VARCHAR(255) NOT NULL, code_vin VARCHAR(255) NOT NULL, num_cave VARCHAR(255) NOT NULL, colonne VARCHAR(255) NOT NULL, ligne VARCHAR(255) NOT NULL, nb_colonne VARCHAR(255) NOT NULL, nb_ligne VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE inventaire (id INT AUTO_INCREMENT NOT NULL, id_restaurant VARCHAR(255) NOT NULL, code_vin VARCHAR(255) NOT NULL, qts VARCHAR(255) NOT NULL, date_enregistrement VARCHAR(255) NOT NULL, date_modification VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE restaurant (id INT AUTO_INCREMENT NOT NULL, id_users VARCHAR(255) NOT NULL, nom_restaurant VARCHAR(255) NOT NULL, adresse VARCHAR(255) NOT NULL, code_postale VARCHAR(255) NOT NULL, ville VARCHAR(255) NOT NULL, telephone VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE user (id INT AUTO_INCREMENT NOT NULL, pseudo VARCHAR(180) NOT NULL, roles JSON NOT NULL, password VARCHAR(255) NOT NULL, nom VARCHAR(255) NOT NULL, prenom VARCHAR(255) NOT NULL, email VARCHAR(255) NOT NULL, UNIQUE INDEX UNIQ_8D93D64986CC499D (pseudo), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE vins (id INT AUTO_INCREMENT NOT NULL, id_base VARCHAR(255) NOT NULL, couleur VARCHAR(255) NOT NULL, type VARCHAR(255) NOT NULL, appellation VARCHAR(255) NOT NULL, nom_producteur VARCHAR(255) NOT NULL, domaine VARCHAR(255) DEFAULT NULL, nom_vin VARCHAR(255) DEFAULT NULL, cl VARCHAR(255) NOT NULL, millesime VARCHAR(255) NOT NULL, code_vin VARCHAR(255) NOT NULL, prix_achat_ht VARCHAR(255) NOT NULL, prix_achat_ttc VARCHAR(255) NOT NULL, prix_vente_ht VARCHAR(255) NOT NULL, prix_vente_ttc VARCHAR(255) NOT NULL, date_creation VARCHAR(255) NOT NULL, date_modification VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE messenger_messages (id BIGINT AUTO_INCREMENT NOT NULL, body LONGTEXT NOT NULL, headers LONGTEXT NOT NULL, queue_name VARCHAR(190) NOT NULL, created_at DATETIME NOT NULL, available_at DATETIME NOT NULL, delivered_at DATETIME DEFAULT NULL, INDEX IDX_75EA56E0FB7336F0 (queue_name), INDEX IDX_75EA56E0E3BD61CE (available_at), INDEX IDX_75EA56E016BA31DB (delivered_at), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('DROP TABLE base_vins');
        $this->addSql('DROP TABLE cave');
        $this->addSql('DROP TABLE inventaire');
        $this->addSql('DROP TABLE restaurant');
        $this->addSql('DROP TABLE user');
        $this->addSql('DROP TABLE vins');
        $this->addSql('DROP TABLE messenger_messages');
    }
}
