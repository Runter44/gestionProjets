<?php

namespace DoctrineMigrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
class Version20180425065543 extends AbstractMigration
{
    /**
     * @param Schema $schema
     */
    public function up(Schema $schema)
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE tache_type DROP FOREIGN KEY FK_FEED336FC18272');
        $this->addSql('DROP TABLE projet_type');
        $this->addSql('DROP TABLE tache_type');
    }

    /**
     * @param Schema $schema
     */
    public function down(Schema $schema)
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE projet_type (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(255) NOT NULL COLLATE utf8mb4_unicode_ci, slug VARCHAR(255) NOT NULL COLLATE utf8mb4_unicode_ci, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE tache_type (tache_id INT NOT NULL, projet_id INT NOT NULL, INDEX IDX_FEED336FD2235D39 (tache_id), INDEX IDX_FEED336FC18272 (projet_id), PRIMARY KEY(tache_id, projet_id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('ALTER TABLE tache_type ADD CONSTRAINT FK_FEED336FC18272 FOREIGN KEY (projet_id) REFERENCES projet_type (id)');
        $this->addSql('ALTER TABLE tache_type ADD CONSTRAINT FK_FEED336FD2235D39 FOREIGN KEY (tache_id) REFERENCES tache (id)');
    }
}
