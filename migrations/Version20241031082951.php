<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20241031082951 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE base_vins DROP id_basevins, CHANGE description description VARCHAR(900) NOT NULL, CHANGE contact contact VARCHAR(255) NOT NULL, CHANGE notes_id notes_id INT NOT NULL');
        $this->addSql('ALTER TABLE base_vins ADD CONSTRAINT FK_8C0778ACFC56F556 FOREIGN KEY (notes_id) REFERENCES notes (id)');
        $this->addSql('CREATE INDEX IDX_8C0778ACFC56F556 ON base_vins (notes_id)');
        $this->addSql('ALTER TABLE cave CHANGE num_cave num_cave VARCHAR(255) NOT NULL');
        $this->addSql('ALTER TABLE inventaire CHANGE restaurant_id restaurant_id INT NOT NULL, CHANGE date_enregistrement date_enregistrement DATETIME NOT NULL');
        $this->addSql('ALTER TABLE inventaire ADD CONSTRAINT FK_338920E0B1E7706E FOREIGN KEY (restaurant_id) REFERENCES restaurant (id)');
        $this->addSql('CREATE INDEX IDX_338920E0B1E7706E ON inventaire (restaurant_id)');
        $this->addSql('ALTER TABLE `like` ADD basevin_id INT NOT NULL, ADD user_id INT NOT NULL, ADD liked TINYINT(1) NOT NULL');
        $this->addSql('CREATE INDEX IDX_AC6340B38D71BB1F ON `like` (basevin_id)');
        $this->addSql('CREATE INDEX IDX_AC6340B3A76ED395 ON `like` (user_id)');
        $this->addSql('CREATE INDEX IDX_11BA68CA76ED395 ON notes (user_id)');
        $this->addSql('ALTER TABLE restaurant CHANGE user_id user_id INT NOT NULL');
        $this->addSql('ALTER TABLE restaurant ADD CONSTRAINT FK_EB95123FA76ED395 FOREIGN KEY (user_id) REFERENCES user (id)');
        $this->addSql('CREATE INDEX IDX_EB95123FA76ED395 ON restaurant (user_id)');
        $this->addSql('ALTER TABLE star CHANGE pseudo pseudo VARCHAR(255) NOT NULL, CHANGE star star VARCHAR(255) DEFAULT NULL');
        $this->addSql('ALTER TABLE vins CHANGE id_restaurant id_restaurant INT NOT NULL, CHANGE prix_achat_ht prix_achat_ht VARCHAR(255) NOT NULL, CHANGE prix_achat_ttc prix_achat_ttc VARCHAR(255) NOT NULL, CHANGE prix_vente_ht prix_vente_ht VARCHAR(255) NOT NULL, CHANGE prix_vente_ttc prix_vente_ttc VARCHAR(255) NOT NULL, CHANGE notes_id notes_id INT NOT NULL');
        $this->addSql('ALTER TABLE vins ADD CONSTRAINT FK_1A64B65CB1E7706E FOREIGN KEY (restaurant_id) REFERENCES restaurant (id)');
        $this->addSql('ALTER TABLE vins ADD CONSTRAINT FK_1A64B65CFC56F556 FOREIGN KEY (notes_id) REFERENCES notes (id)');
        $this->addSql('CREATE INDEX IDX_1A64B65CB1E7706E ON vins (restaurant_id)');
        $this->addSql('CREATE INDEX IDX_1A64B65CFC56F556 ON vins (notes_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE base_vins DROP FOREIGN KEY FK_8C0778ACFC56F556');
        $this->addSql('DROP INDEX IDX_8C0778ACFC56F556 ON base_vins');
        $this->addSql('ALTER TABLE base_vins ADD id_basevins INT DEFAULT NULL, CHANGE notes_id notes_id INT DEFAULT NULL, CHANGE description description VARCHAR(900) DEFAULT NULL, CHANGE contact contact VARCHAR(255) DEFAULT NULL');
        $this->addSql('ALTER TABLE vins DROP FOREIGN KEY FK_1A64B65CB1E7706E');
        $this->addSql('ALTER TABLE vins DROP FOREIGN KEY FK_1A64B65CFC56F556');
        $this->addSql('DROP INDEX IDX_1A64B65CB1E7706E ON vins');
        $this->addSql('DROP INDEX IDX_1A64B65CFC56F556 ON vins');
        $this->addSql('ALTER TABLE vins CHANGE notes_id notes_id INT DEFAULT NULL, CHANGE id_restaurant id_restaurant INT DEFAULT NULL, CHANGE prix_achat_ht prix_achat_ht VARCHAR(255) DEFAULT NULL, CHANGE prix_achat_ttc prix_achat_ttc VARCHAR(255) DEFAULT NULL, CHANGE prix_vente_ht prix_vente_ht VARCHAR(255) DEFAULT NULL, CHANGE prix_vente_ttc prix_vente_ttc VARCHAR(255) DEFAULT NULL');
        $this->addSql('ALTER TABLE cave CHANGE num_cave num_cave VARCHAR(255) DEFAULT NULL');
        $this->addSql('ALTER TABLE notes DROP FOREIGN KEY FK_11BA68CA76ED395');
        $this->addSql('DROP INDEX IDX_11BA68CA76ED395 ON notes');
        $this->addSql('ALTER TABLE `like` DROP FOREIGN KEY FK_AC6340B38D71BB1F');
        $this->addSql('ALTER TABLE `like` DROP FOREIGN KEY FK_AC6340B3A76ED395');
        $this->addSql('DROP INDEX IDX_AC6340B38D71BB1F ON `like`');
        $this->addSql('DROP INDEX IDX_AC6340B3A76ED395 ON `like`');
        $this->addSql('ALTER TABLE `like` DROP basevin_id, DROP user_id, DROP liked');
        $this->addSql('ALTER TABLE star CHANGE pseudo pseudo INT NOT NULL, CHANGE star star INT DEFAULT NULL');
        $this->addSql('ALTER TABLE inventaire DROP FOREIGN KEY FK_338920E0B1E7706E');
        $this->addSql('DROP INDEX IDX_338920E0B1E7706E ON inventaire');
        $this->addSql('ALTER TABLE inventaire CHANGE restaurant_id restaurant_id INT DEFAULT NULL, CHANGE date_enregistrement date_enregistrement DATETIME DEFAULT NULL');
        $this->addSql('ALTER TABLE restaurant DROP FOREIGN KEY FK_EB95123FA76ED395');
        $this->addSql('DROP INDEX IDX_EB95123FA76ED395 ON restaurant');
        $this->addSql('ALTER TABLE restaurant CHANGE user_id user_id INT DEFAULT NULL');
    }
}
