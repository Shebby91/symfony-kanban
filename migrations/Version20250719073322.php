<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250719073322 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE board (id INT AUTO_INCREMENT NOT NULL, owner_id INT NOT NULL, name VARCHAR(100) NOT NULL, description VARCHAR(255) DEFAULT NULL, created_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', INDEX IDX_58562B477E3C61F9 (owner_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE card (id INT AUTO_INCREMENT NOT NULL, created_by_id INT NOT NULL, lane_id INT NOT NULL, title VARCHAR(150) NOT NULL, description VARCHAR(255) DEFAULT NULL, position INT NOT NULL, created_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', INDEX IDX_161498D3B03A8386 (created_by_id), INDEX IDX_161498D3A128F72F (lane_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE card_assignment (id INT AUTO_INCREMENT NOT NULL, card_id INT NOT NULL, user_id INT NOT NULL, assigned_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', INDEX IDX_5CB7D9B44ACC9A20 (card_id), INDEX IDX_5CB7D9B4A76ED395 (user_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE card_label (id INT AUTO_INCREMENT NOT NULL, card_id INT NOT NULL, label_id INT NOT NULL, INDEX IDX_3693A12E4ACC9A20 (card_id), INDEX IDX_3693A12E33B92F39 (label_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE comment (id INT AUTO_INCREMENT NOT NULL, card_id INT NOT NULL, user_id INT NOT NULL, content LONGTEXT NOT NULL, created_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', INDEX IDX_9474526C4ACC9A20 (card_id), INDEX IDX_9474526CA76ED395 (user_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE label (id INT AUTO_INCREMENT NOT NULL, board_id INT NOT NULL, name VARCHAR(50) NOT NULL, color VARCHAR(7) NOT NULL, INDEX IDX_EA750E8E7EC5785 (board_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE lane (id INT AUTO_INCREMENT NOT NULL, board_id INT NOT NULL, title VARCHAR(50) NOT NULL, position INT NOT NULL, INDEX IDX_DF07E54EE7EC5785 (board_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE user (id INT AUTO_INCREMENT NOT NULL, username VARCHAR(80) NOT NULL, email VARCHAR(100) NOT NULL, password VARCHAR(255) NOT NULL, created_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE messenger_messages (id BIGINT AUTO_INCREMENT NOT NULL, body LONGTEXT NOT NULL, headers LONGTEXT NOT NULL, queue_name VARCHAR(190) NOT NULL, created_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', available_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', delivered_at DATETIME DEFAULT NULL COMMENT \'(DC2Type:datetime_immutable)\', INDEX IDX_75EA56E0FB7336F0 (queue_name), INDEX IDX_75EA56E0E3BD61CE (available_at), INDEX IDX_75EA56E016BA31DB (delivered_at), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE board ADD CONSTRAINT FK_58562B477E3C61F9 FOREIGN KEY (owner_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE card ADD CONSTRAINT FK_161498D3B03A8386 FOREIGN KEY (created_by_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE card ADD CONSTRAINT FK_161498D3A128F72F FOREIGN KEY (lane_id) REFERENCES lane (id)');
        $this->addSql('ALTER TABLE card_assignment ADD CONSTRAINT FK_5CB7D9B44ACC9A20 FOREIGN KEY (card_id) REFERENCES card (id)');
        $this->addSql('ALTER TABLE card_assignment ADD CONSTRAINT FK_5CB7D9B4A76ED395 FOREIGN KEY (user_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE card_label ADD CONSTRAINT FK_3693A12E4ACC9A20 FOREIGN KEY (card_id) REFERENCES card (id)');
        $this->addSql('ALTER TABLE card_label ADD CONSTRAINT FK_3693A12E33B92F39 FOREIGN KEY (label_id) REFERENCES label (id)');
        $this->addSql('ALTER TABLE comment ADD CONSTRAINT FK_9474526C4ACC9A20 FOREIGN KEY (card_id) REFERENCES card (id)');
        $this->addSql('ALTER TABLE comment ADD CONSTRAINT FK_9474526CA76ED395 FOREIGN KEY (user_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE label ADD CONSTRAINT FK_EA750E8E7EC5785 FOREIGN KEY (board_id) REFERENCES board (id)');
        $this->addSql('ALTER TABLE lane ADD CONSTRAINT FK_DF07E54EE7EC5785 FOREIGN KEY (board_id) REFERENCES board (id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE board DROP FOREIGN KEY FK_58562B477E3C61F9');
        $this->addSql('ALTER TABLE card DROP FOREIGN KEY FK_161498D3B03A8386');
        $this->addSql('ALTER TABLE card DROP FOREIGN KEY FK_161498D3A128F72F');
        $this->addSql('ALTER TABLE card_assignment DROP FOREIGN KEY FK_5CB7D9B44ACC9A20');
        $this->addSql('ALTER TABLE card_assignment DROP FOREIGN KEY FK_5CB7D9B4A76ED395');
        $this->addSql('ALTER TABLE card_label DROP FOREIGN KEY FK_3693A12E4ACC9A20');
        $this->addSql('ALTER TABLE card_label DROP FOREIGN KEY FK_3693A12E33B92F39');
        $this->addSql('ALTER TABLE comment DROP FOREIGN KEY FK_9474526C4ACC9A20');
        $this->addSql('ALTER TABLE comment DROP FOREIGN KEY FK_9474526CA76ED395');
        $this->addSql('ALTER TABLE label DROP FOREIGN KEY FK_EA750E8E7EC5785');
        $this->addSql('ALTER TABLE lane DROP FOREIGN KEY FK_DF07E54EE7EC5785');
        $this->addSql('DROP TABLE board');
        $this->addSql('DROP TABLE card');
        $this->addSql('DROP TABLE card_assignment');
        $this->addSql('DROP TABLE card_label');
        $this->addSql('DROP TABLE comment');
        $this->addSql('DROP TABLE label');
        $this->addSql('DROP TABLE lane');
        $this->addSql('DROP TABLE user');
        $this->addSql('DROP TABLE messenger_messages');
    }
}
