<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20210907133305 extends AbstractMigration
{
  public function getDescription(): string
  {
    return '';
  }

  public function up(Schema $schema): void
  {
    // this up() migration is auto-generated, please modify it to your needs
    $this->addSql('CREATE TABLE comment (id INT AUTO_INCREMENT NOT NULL, episode_id INT NOT NULL, user_id INT NOT NULL, comment LONGTEXT NOT NULL, rate INT DEFAULT NULL, INDEX IDX_9474526C362B62A0 (episode_id), INDEX IDX_9474526CA76ED395 (user_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
    $this->addSql('ALTER TABLE comment ADD CONSTRAINT FK_9474526C362B62A0 FOREIGN KEY (episode_id) REFERENCES episode (id)');
    $this->addSql('ALTER TABLE comment ADD CONSTRAINT FK_9474526CA76ED395 FOREIGN KEY (user_id) REFERENCES user (id)');
    $this->addSql('ALTER TABLE program ADD owner_id INT NOT NULL');
    $this->addSql('ALTER TABLE program ADD CONSTRAINT FK_92ED77847E3C61F9 FOREIGN KEY (owner_id) REFERENCES user (id)');
    $this->addSql('CREATE INDEX IDX_92ED77847E3C61F9 ON program (owner_id)');

  }

  public function down(Schema $schema): void
  {
    // this down() migration is auto-generated, please modify it to your needs
    $this->addSql('DROP TABLE comment');
    $this->addSql('ALTER TABLE program DROP FOREIGN KEY FK_92ED77847E3C61F9');
    $this->addSql('DROP INDEX IDX_92ED77847E3C61F9 ON program');
    $this->addSql('ALTER TABLE program DROP owner_id');

  }
}
