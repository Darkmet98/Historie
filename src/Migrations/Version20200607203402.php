<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20200607203402 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE po_file CHANGE project_id project_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE projects ADD visualizator_id INT DEFAULT NULL, CHANGE branch branch VARCHAR(100) DEFAULT NULL');
        $this->addSql('ALTER TABLE projects ADD CONSTRAINT FK_5C93B3A43DE10F0 FOREIGN KEY (visualizator_id) REFERENCES text_visualizator (id)');
        $this->addSql('CREATE INDEX IDX_5C93B3A43DE10F0 ON projects (visualizator_id)');
        $this->addSql('ALTER TABLE releases CHANGE project_id project_id INT DEFAULT NULL');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE po_file CHANGE project_id project_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE projects DROP FOREIGN KEY FK_5C93B3A43DE10F0');
        $this->addSql('DROP INDEX IDX_5C93B3A43DE10F0 ON projects');
        $this->addSql('ALTER TABLE projects DROP visualizator_id, CHANGE branch branch VARCHAR(100) CHARACTER SET utf8mb4 DEFAULT \'NULL\' COLLATE `utf8mb4_unicode_ci`');
        $this->addSql('ALTER TABLE releases CHANGE project_id project_id INT DEFAULT NULL');
    }
}
