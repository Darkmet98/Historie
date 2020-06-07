<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20200607202701 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE text_visualizator DROP FOREIGN KEY FK_8EC1A3D3166D1F9C');
        $this->addSql('DROP INDEX IDX_8EC1A3D3166D1F9C ON text_visualizator');
        $this->addSql('ALTER TABLE text_visualizator DROP project_id');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE text_visualizator ADD project_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE text_visualizator ADD CONSTRAINT FK_8EC1A3D3166D1F9C FOREIGN KEY (project_id) REFERENCES projects (id)');
        $this->addSql('CREATE INDEX IDX_8EC1A3D3166D1F9C ON text_visualizator (project_id)');
    }
}
