<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20200511201415 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE projects_has_pofiles DROP FOREIGN KEY PoFileIdFk');
        $this->addSql('ALTER TABLE projects_has_pofiles DROP FOREIGN KEY ProjectIdFk');
        $this->addSql('ALTER TABLE projects_has_releases DROP FOREIGN KEY ProjectsIdFk');
        $this->addSql('ALTER TABLE projects_has_releases DROP FOREIGN KEY ReleasesIdFk');
        $this->addSql('CREATE TABLE user (id INT AUTO_INCREMENT NOT NULL, email VARCHAR(180) NOT NULL, roles JSON NOT NULL, password VARCHAR(255) NOT NULL, UNIQUE INDEX UNIQ_8D93D649E7927C74 (email), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('DROP TABLE pofile');
        $this->addSql('DROP TABLE projects');
        $this->addSql('DROP TABLE projects_has_pofiles');
        $this->addSql('DROP TABLE projects_has_releases');
        $this->addSql('DROP TABLE releases');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE pofile (Id INT AUTO_INCREMENT NOT NULL, Name VARCHAR(60) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_general_ci`, Path TEXT CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_general_ci`, Entries LONGTEXT CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_bin`, Position INT NOT NULL, PRIMARY KEY(Id)) DEFAULT CHARACTER SET utf8 COLLATE `utf8_unicode_ci` ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('CREATE TABLE projects (Id INT AUTO_INCREMENT NOT NULL, Name VARCHAR(50) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_general_ci`, Description VARCHAR(60) CHARACTER SET utf8mb4 DEFAULT \'NULL\' COLLATE `utf8mb4_general_ci`, Icon TEXT CHARACTER SET utf8mb4 DEFAULT NULL COLLATE `utf8mb4_general_ci`, Repository TEXT CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_general_ci`, Branch VARCHAR(50) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_general_ci`, PRIMARY KEY(Id)) DEFAULT CHARACTER SET utf8 COLLATE `utf8_unicode_ci` ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('CREATE TABLE projects_has_pofiles (ProjectId INT NOT NULL, PoFileId INT NOT NULL, INDEX PoFileIdFk (PoFileId), INDEX IDX_729EC9D6EE62E254 (ProjectId), PRIMARY KEY(ProjectId, PoFileId)) DEFAULT CHARACTER SET utf8 COLLATE `utf8_unicode_ci` ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('CREATE TABLE projects_has_releases (ProjectId INT NOT NULL, ReleasesId INT NOT NULL, INDEX ReleasesIdFk (ReleasesId), INDEX IDX_AB02BFEE62E254 (ProjectId), PRIMARY KEY(ProjectId, ReleasesId)) DEFAULT CHARACTER SET utf8 COLLATE `utf8_unicode_ci` ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('CREATE TABLE releases (Id INT AUTO_INCREMENT NOT NULL, Version VARCHAR(30) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_general_ci`, Changelog TEXT CHARACTER SET utf8mb4 DEFAULT NULL COLLATE `utf8mb4_general_ci`, File TEXT CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_general_ci`, Md5 TEXT CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_general_ci`, PRIMARY KEY(Id)) DEFAULT CHARACTER SET utf8 COLLATE `utf8_unicode_ci` ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('ALTER TABLE projects_has_pofiles ADD CONSTRAINT PoFileIdFk FOREIGN KEY (PoFileId) REFERENCES pofile (Id)');
        $this->addSql('ALTER TABLE projects_has_pofiles ADD CONSTRAINT ProjectIdFk FOREIGN KEY (ProjectId) REFERENCES projects (Id)');
        $this->addSql('ALTER TABLE projects_has_releases ADD CONSTRAINT ProjectsIdFk FOREIGN KEY (ProjectId) REFERENCES projects (Id)');
        $this->addSql('ALTER TABLE projects_has_releases ADD CONSTRAINT ReleasesIdFk FOREIGN KEY (ReleasesId) REFERENCES releases (Id)');
        $this->addSql('DROP TABLE user');
    }
}
