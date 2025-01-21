<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20241025092323 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE base_vins ADD notes_id INT NOT NULL, CHANGE description description VARCHAR(900) NOT NULL, CHANGE contact contact VARCHAR(255) NOT NULL');
        $this->addSql('ALTER TABLE base_vins ADD CONSTRAINT FK_8C0778ACFC56F556 FOREIGN KEY (notes_id) REFERENCES notes (id)');
        $this->addSql('CREATE INDEX IDX_8C0778ACFC56F556 ON base_vins (notes_id)');
        $this->addSql('ALTER TABLE cave CHANGE num_cave num_cave VARCHAR(255) NOT NULL');
        $this->addSql('ALTER TABLE inventaire CHANGE restaurant_id restaurant_id INT NOT NULL');
        $this->addSql('ALTER TABLE inventaire ADD CONSTRAINT FK_338920E0B1E7706E FOREIGN KEY (restaurant_id) REFERENCES restaurant (id)');
        $this->addSql('CREATE INDEX IDX_338920E0B1E7706E ON inventaire (restaurant_id)');
        $this->addSql('ALTER TABLE restaurant ADD CONSTRAINT FK_EB95123FA76ED395 FOREIGN KEY (user_id) REFERENCES user (id)');
        $this->addSql('CREATE INDEX IDX_EB95123FA76ED395 ON restaurant (user_id)');
        $this->addSql('ALTER TABLE vins ADD notes_id INT NOT NULL, CHANGE id_restaurant id_restaurant INT NOT NULL, CHANGE prix_achat_ht prix_achat_ht VARCHAR(255) NOT NULL, CHANGE prix_achat_ttc prix_achat_ttc VARCHAR(255) NOT NULL, CHANGE prix_vente_ht prix_vente_ht VARCHAR(255) NOT NULL, CHANGE prix_vente_ttc prix_vente_ttc VARCHAR(255) NOT NULL');
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
        $this->addSql('ALTER TABLE base_vins DROP notes_id, CHANGE description description VARCHAR(900) DEFAULT NULL, CHANGE contact contact VARCHAR(255) DEFAULT NULL');
        $this->addSql('ALTER TABLE restaurant DROP FOREIGN KEY FK_EB95123FA76ED395');
        $this->addSql('DROP INDEX IDX_EB95123FA76ED395 ON restaurant');
        $this->addSql('ALTER TABLE vins DROP FOREIGN KEY FK_1A64B65CB1E7706E');
        $this->addSql('ALTER TABLE vins DROP FOREIGN KEY FK_1A64B65CFC56F556');
        $this->addSql('DROP INDEX IDX_1A64B65CB1E7706E ON vins');
        $this->addSql('DROP INDEX IDX_1A64B65CFC56F556 ON vins');
        $this->addSql('ALTER TABLE vins DROP notes_id, CHANGE id_restaurant id_restaurant INT DEFAULT NULL, CHANGE prix_achat_ht prix_achat_ht VARCHAR(255) DEFAULT NULL, CHANGE prix_achat_ttc prix_achat_ttc VARCHAR(255) DEFAULT NULL, CHANGE prix_vente_ht prix_vente_ht VARCHAR(255) DEFAULT NULL, CHANGE prix_vente_ttc prix_vente_ttc VARCHAR(255) DEFAULT NULL');
        $this->addSql('ALTER TABLE cave CHANGE num_cave num_cave VARCHAR(255) DEFAULT NULL');
        $this->addSql('ALTER TABLE inventaire DROP FOREIGN KEY FK_338920E0B1E7706E');
        $this->addSql('DROP INDEX IDX_338920E0B1E7706E ON inventaire');
        $this->addSql('ALTER TABLE inventaire CHANGE restaurant_id restaurant_id INT DEFAULT NULL');
    }
}
