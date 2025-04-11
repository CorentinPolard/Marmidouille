<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250408075243 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql(<<<'SQL'
            ALTER TABLE ingredient_quantity ADD ingredient_id INT DEFAULT NULL, ADD recipe_id INT DEFAULT NULL
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE ingredient_quantity ADD CONSTRAINT FK_EDF546B8933FE08C FOREIGN KEY (ingredient_id) REFERENCES ingredient (id)
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE ingredient_quantity ADD CONSTRAINT FK_EDF546B859D8A214 FOREIGN KEY (recipe_id) REFERENCES recipe (id)
        SQL);
        $this->addSql(<<<'SQL'
            CREATE INDEX IDX_EDF546B8933FE08C ON ingredient_quantity (ingredient_id)
        SQL);
        $this->addSql(<<<'SQL'
            CREATE INDEX IDX_EDF546B859D8A214 ON ingredient_quantity (recipe_id)
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE step ADD recipe_id INT DEFAULT NULL
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE step ADD CONSTRAINT FK_43B9FE3C59D8A214 FOREIGN KEY (recipe_id) REFERENCES recipe (id)
        SQL);
        $this->addSql(<<<'SQL'
            CREATE INDEX IDX_43B9FE3C59D8A214 ON step (recipe_id)
        SQL);
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql(<<<'SQL'
            ALTER TABLE ingredient_quantity DROP FOREIGN KEY FK_EDF546B8933FE08C
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE ingredient_quantity DROP FOREIGN KEY FK_EDF546B859D8A214
        SQL);
        $this->addSql(<<<'SQL'
            DROP INDEX IDX_EDF546B8933FE08C ON ingredient_quantity
        SQL);
        $this->addSql(<<<'SQL'
            DROP INDEX IDX_EDF546B859D8A214 ON ingredient_quantity
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE ingredient_quantity DROP ingredient_id, DROP recipe_id
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE step DROP FOREIGN KEY FK_43B9FE3C59D8A214
        SQL);
        $this->addSql(<<<'SQL'
            DROP INDEX IDX_43B9FE3C59D8A214 ON step
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE step DROP recipe_id
        SQL);
    }
}
