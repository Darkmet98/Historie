<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20200607194637 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE text_visualizator (id INT AUTO_INCREMENT NOT NULL, project_id INT DEFAULT NULL, background LONGTEXT NOT NULL, font_size INT NOT NULL, line_height INT NOT NULL, top_position INT NOT NULL, left_position INT NOT NULL, INDEX IDX_8EC1A3D3166D1F9C (project_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE text_visualizator ADD CONSTRAINT FK_8EC1A3D3166D1F9C FOREIGN KEY (project_id) REFERENCES projects (id)');
        $this->addSql('ALTER TABLE po_file CHANGE project_id project_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE projects CHANGE branch branch VARCHAR(100) DEFAULT NULL');
        $this->addSql('ALTER TABLE releases CHANGE project_id project_id INT DEFAULT NULL');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('DROP TABLE text_visualizator');
        $this->addSql('ALTER TABLE po_file CHANGE project_id project_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE projects CHANGE branch branch VARCHAR(100) CHARACTER SET utf8mb4 DEFAULT \'NULL\' COLLATE `utf8mb4_unicode_ci`');
        $this->addSql('ALTER TABLE releases CHANGE project_id project_id INT DEFAULT NULL');
    }
}
