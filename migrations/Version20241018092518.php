<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20241018092518 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE inventaire CHANGE restaurant_id restaurant_id INT NOT NULL');
        $this->addSql('ALTER TABLE inventaire ADD CONSTRAINT FK_338920E0B1E7706E FOREIGN KEY (restaurant_id) REFERENCES restaurant (id)');
        $this->addSql('CREATE INDEX IDX_338920E0B1E7706E ON inventaire (restaurant_id)');
        $this->addSql('ALTER TABLE restaurant DROP user_id');
        $this->addSql('ALTER TABLE vins ADD restaurant_id INT NOT NULL, CHANGE id_restaurant id_restaurant VARCHAR(255) NOT NULL');
        $this->addSql('ALTER TABLE vins ADD CONSTRAINT FK_1A64B65CB1E7706E FOREIGN KEY (restaurant_id) REFERENCES restaurant (id)');
        $this->addSql('CREATE INDEX IDX_1A64B65CB1E7706E ON vins (restaurant_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE restaurant ADD user_id INT NOT NULL');
        $this->addSql('ALTER TABLE inventaire DROP FOREIGN KEY FK_338920E0B1E7706E');
        $this->addSql('DROP INDEX IDX_338920E0B1E7706E ON inventaire');
        $this->addSql('ALTER TABLE inventaire CHANGE restaurant_id restaurant_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE vins DROP FOREIGN KEY FK_1A64B65CB1E7706E');
        $this->addSql('DROP INDEX IDX_1A64B65CB1E7706E ON vins');
        $this->addSql('ALTER TABLE vins DROP restaurant_id, CHANGE id_restaurant id_restaurant INT DEFAULT NULL');
    }
}
