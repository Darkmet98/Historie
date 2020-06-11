<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20200611094148 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE po_file (id INT AUTO_INCREMENT NOT NULL, project_id INT DEFAULT NULL, name VARCHAR(60) NOT NULL, path LONGTEXT NOT NULL, entries LONGTEXT NOT NULL COMMENT \'(DC2Type:json)\', position INT NOT NULL, INDEX IDX_9F3E03CC166D1F9C (project_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE projects (id INT AUTO_INCREMENT NOT NULL, visualizator_id INT DEFAULT NULL, name VARCHAR(50) NOT NULL, description VARCHAR(100) NOT NULL, icon LONGTEXT DEFAULT NULL, repository LONGTEXT DEFAULT NULL, branch VARCHAR(100) DEFAULT NULL, INDEX IDX_5C93B3A43DE10F0 (visualizator_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE projects_user (projects_id INT NOT NULL, user_id INT NOT NULL, INDEX IDX_B38D6A811EDE0F55 (projects_id), INDEX IDX_B38D6A81A76ED395 (user_id), PRIMARY KEY(projects_id, user_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE releases (id INT AUTO_INCREMENT NOT NULL, project_id INT DEFAULT NULL, version VARCHAR(50) NOT NULL, changelog LONGTEXT NOT NULL, file LONGTEXT NOT NULL, md5 LONGTEXT NOT NULL, INDEX IDX_7896E4D1166D1F9C (project_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE text_visualizator (id INT AUTO_INCREMENT NOT NULL, background LONGTEXT NOT NULL, font_size INT NOT NULL, line_height INT NOT NULL, top_position INT NOT NULL, left_position INT NOT NULL, name VARCHAR(100) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE user (id INT AUTO_INCREMENT NOT NULL, email VARCHAR(180) NOT NULL, roles LONGTEXT NOT NULL COMMENT \'(DC2Type:json)\', password VARCHAR(255) NOT NULL, UNIQUE INDEX UNIQ_8D93D649E7927C74 (email), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE po_file ADD CONSTRAINT FK_9F3E03CC166D1F9C FOREIGN KEY (project_id) REFERENCES projects (id)');
        $this->addSql('ALTER TABLE projects ADD CONSTRAINT FK_5C93B3A43DE10F0 FOREIGN KEY (visualizator_id) REFERENCES text_visualizator (id)');
        $this->addSql('ALTER TABLE projects_user ADD CONSTRAINT FK_B38D6A811EDE0F55 FOREIGN KEY (projects_id) REFERENCES projects (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE projects_user ADD CONSTRAINT FK_B38D6A81A76ED395 FOREIGN KEY (user_id) REFERENCES user (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE releases ADD CONSTRAINT FK_7896E4D1166D1F9C FOREIGN KEY (project_id) REFERENCES projects (id)');

        //Add user
        $this->addSql('insert into user (id, email, roles, password) values (1, \'admin@historie.com\', \'["ROLE_ADMIN"]\', \'NS5RUln5aXo0PnABmHXxt19y50cIQK44WV8wUNNLxyVqfIS7iVXMIvDsu5U3y4BH22Hoqpsj9tOH8Nl2JvUZRg==\')');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE po_file DROP FOREIGN KEY FK_9F3E03CC166D1F9C');
        $this->addSql('ALTER TABLE projects_user DROP FOREIGN KEY FK_B38D6A811EDE0F55');
        $this->addSql('ALTER TABLE releases DROP FOREIGN KEY FK_7896E4D1166D1F9C');
        $this->addSql('ALTER TABLE projects DROP FOREIGN KEY FK_5C93B3A43DE10F0');
        $this->addSql('ALTER TABLE projects_user DROP FOREIGN KEY FK_B38D6A81A76ED395');
        $this->addSql('DROP TABLE po_file');
        $this->addSql('DROP TABLE projects');
        $this->addSql('DROP TABLE projects_user');
        $this->addSql('DROP TABLE releases');
        $this->addSql('DROP TABLE text_visualizator');
        $this->addSql('DROP TABLE user');
    }
}
