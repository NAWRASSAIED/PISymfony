<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20231121200427 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE messenger_messages (id BIGINT AUTO_INCREMENT NOT NULL, body LONGTEXT NOT NULL, headers LONGTEXT NOT NULL, queue_name VARCHAR(190) NOT NULL, created_at DATETIME NOT NULL, available_at DATETIME NOT NULL, delivered_at DATETIME DEFAULT NULL, INDEX IDX_75EA56E0FB7336F0 (queue_name), INDEX IDX_75EA56E0E3BD61CE (available_at), INDEX IDX_75EA56E016BA31DB (delivered_at), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE facture DROP FOREIGN KEY facture_ibfk_1');
        $this->addSql('DROP INDEX numRes ON facture');
        $this->addSql('ALTER TABLE facture ADD idreservation INT DEFAULT NULL, DROP numRes, CHANGE date_paiement date_paiement VARCHAR(250) DEFAULT NULL');
        $this->addSql('ALTER TABLE facture ADD CONSTRAINT FK_FE866410840939FA FOREIGN KEY (idreservation) REFERENCES reservations (idreservation)');
        $this->addSql('CREATE INDEX IDX_FE866410840939FA ON facture (idreservation)');
        $this->addSql('ALTER TABLE reservations CHANGE CinClient cinclient INT DEFAULT NULL, CHANGE nomClient nomclient VARCHAR(150) NOT NULL, CHANGE dateDebut dateDebut DATE DEFAULT NULL, CHANGE dateFin dateFin DATE DEFAULT NULL, CHANGE mode_paiement mode_paiement VARCHAR(250) NOT NULL');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('DROP TABLE messenger_messages');
        $this->addSql('ALTER TABLE facture DROP FOREIGN KEY FK_FE866410840939FA');
        $this->addSql('DROP INDEX IDX_FE866410840939FA ON facture');
        $this->addSql('ALTER TABLE facture ADD numRes INT NOT NULL, DROP idreservation, CHANGE date_paiement date_paiement VARCHAR(254) NOT NULL');
        $this->addSql('ALTER TABLE facture ADD CONSTRAINT facture_ibfk_1 FOREIGN KEY (numRes) REFERENCES reservations (idReservation) ON UPDATE CASCADE ON DELETE CASCADE');
        $this->addSql('CREATE INDEX numRes ON facture (numRes)');
        $this->addSql('ALTER TABLE reservations CHANGE cinclient CinClient INT NOT NULL, CHANGE nomclient nomClient VARCHAR(254) NOT NULL, CHANGE dateDebut dateDebut DATE NOT NULL, CHANGE dateFin dateFin DATE NOT NULL, CHANGE mode_paiement mode_paiement VARCHAR(254) NOT NULL');
    }
}
