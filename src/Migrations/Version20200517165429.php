<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20200517165429 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE pofile (Id INT AUTO_INCREMENT NOT NULL, Name VARCHAR(60) NOT NULL, Path TEXT NOT NULL, Entries LONGTEXT NOT NULL, Position INT NOT NULL, PRIMARY KEY(Id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE projects (Id INT AUTO_INCREMENT NOT NULL, Name VARCHAR(50) NOT NULL, Description VARCHAR(60) DEFAULT \'NULL\', Icon TEXT DEFAULT NULL, Repository TEXT NOT NULL, Branch VARCHAR(50) NOT NULL, PRIMARY KEY(Id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE projects_has_pofiles (ProjectId INT NOT NULL, PoFileId INT NOT NULL, INDEX IDX_729EC9D6EE62E254 (ProjectId), INDEX IDX_729EC9D65451FC93 (PoFileId), PRIMARY KEY(ProjectId, PoFileId)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE projects_has_releases (ProjectId INT NOT NULL, ReleasesId INT NOT NULL, INDEX IDX_AB02BFEE62E254 (ProjectId), INDEX IDX_AB02BF5AA4EA8B (ReleasesId), PRIMARY KEY(ProjectId, ReleasesId)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE projects_has_users (ProjectId INT NOT NULL, UserId INT NOT NULL, INDEX IDX_36B67268EE62E254 (ProjectId), INDEX IDX_36B67268631A48FA (UserId), PRIMARY KEY(ProjectId, UserId)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE releases (Id INT AUTO_INCREMENT NOT NULL, Version VARCHAR(30) NOT NULL, Changelog TEXT DEFAULT NULL, File TEXT NOT NULL, Md5 TEXT NOT NULL, PRIMARY KEY(Id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE user (id INT AUTO_INCREMENT NOT NULL, email VARCHAR(180) NOT NULL, roles LONGTEXT NOT NULL COMMENT \'(DC2Type:json)\', password VARCHAR(255) NOT NULL, UNIQUE INDEX UNIQ_8D93D649E7927C74 (email), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE projects_has_pofiles ADD CONSTRAINT FK_729EC9D6EE62E254 FOREIGN KEY (ProjectId) REFERENCES projects (Id)');
        $this->addSql('ALTER TABLE projects_has_pofiles ADD CONSTRAINT FK_729EC9D65451FC93 FOREIGN KEY (PoFileId) REFERENCES pofile (Id)');
        $this->addSql('ALTER TABLE projects_has_releases ADD CONSTRAINT FK_AB02BFEE62E254 FOREIGN KEY (ProjectId) REFERENCES projects (Id)');
        $this->addSql('ALTER TABLE projects_has_releases ADD CONSTRAINT FK_AB02BF5AA4EA8B FOREIGN KEY (ReleasesId) REFERENCES releases (Id)');
        $this->addSql('ALTER TABLE projects_has_users ADD CONSTRAINT FK_36B67268EE62E254 FOREIGN KEY (ProjectId) REFERENCES projects (Id)');
        $this->addSql('ALTER TABLE projects_has_users ADD CONSTRAINT FK_36B67268631A48FA FOREIGN KEY (UserId) REFERENCES user (id)');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE projects_has_pofiles DROP FOREIGN KEY FK_729EC9D65451FC93');
        $this->addSql('ALTER TABLE projects_has_pofiles DROP FOREIGN KEY FK_729EC9D6EE62E254');
        $this->addSql('ALTER TABLE projects_has_releases DROP FOREIGN KEY FK_AB02BFEE62E254');
        $this->addSql('ALTER TABLE projects_has_users DROP FOREIGN KEY FK_36B67268EE62E254');
        $this->addSql('ALTER TABLE projects_has_releases DROP FOREIGN KEY FK_AB02BF5AA4EA8B');
        $this->addSql('ALTER TABLE projects_has_users DROP FOREIGN KEY FK_36B67268631A48FA');
        $this->addSql('DROP TABLE pofile');
        $this->addSql('DROP TABLE projects');
        $this->addSql('DROP TABLE projects_has_pofiles');
        $this->addSql('DROP TABLE projects_has_releases');
        $this->addSql('DROP TABLE projects_has_users');
        $this->addSql('DROP TABLE releases');
        $this->addSql('DROP TABLE user');
    }
}
