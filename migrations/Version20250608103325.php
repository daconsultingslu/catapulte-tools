<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250608103325 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql(<<<'SQL'
            CREATE TABLE answer (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(255) NOT NULL, right_answer TINYINT(1) DEFAULT NULL, type VARCHAR(255) NOT NULL, value DOUBLE PRECISION DEFAULT NULL, survey_question_id INT DEFAULT NULL, qcm_question_id INT DEFAULT NULL, INDEX IDX_DADD4A25A6DF29BA (survey_question_id), INDEX IDX_DADD4A25EA58905F (qcm_question_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4
        SQL);
        $this->addSql(<<<'SQL'
            CREATE TABLE brand (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4
        SQL);
        $this->addSql(<<<'SQL'
            CREATE TABLE event (id INT AUTO_INCREMENT NOT NULL, name LONGTEXT NOT NULL, start_date DATETIME NOT NULL, end_date DATETIME NOT NULL, language VARCHAR(2) NOT NULL, brand_id INT NOT NULL, INDEX IDX_3BAE0AA744F5D008 (brand_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4
        SQL);
        $this->addSql(<<<'SQL'
            CREATE TABLE group_event (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4
        SQL);
        $this->addSql(<<<'SQL'
            CREATE TABLE group_events_sessions (group_event_id INT NOT NULL, session_id INT NOT NULL, INDEX IDX_D9C798C978C7A4F4 (group_event_id), INDEX IDX_D9C798C9613FECDF (session_id), PRIMARY KEY(group_event_id, session_id)) DEFAULT CHARACTER SET utf8mb4
        SQL);
        $this->addSql(<<<'SQL'
            CREATE TABLE level (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(255) NOT NULL, tetris_tool_id INT DEFAULT NULL, INDEX IDX_9AEACC13CA6BB11F (tetris_tool_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4
        SQL);
        $this->addSql(<<<'SQL'
            CREATE TABLE number_plate (id INT AUTO_INCREMENT NOT NULL, brand VARCHAR(255) NOT NULL, model VARCHAR(255) NOT NULL, number_plate VARCHAR(255) NOT NULL, trial_tool_id INT NOT NULL, INDEX IDX_CAD754938CB2E660 (trial_tool_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4
        SQL);
        $this->addSql(<<<'SQL'
            CREATE TABLE qcmuser_data (id INT AUTO_INCREMENT NOT NULL, is_right_answered TINYINT(1) DEFAULT NULL, count_answers INT NOT NULL, time INT NOT NULL, user_data_id INT NOT NULL, qcm_question_id INT NOT NULL, INDEX IDX_186EDF096FF8BF36 (user_data_id), INDEX IDX_186EDF09EA58905F (qcm_question_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4
        SQL);
        $this->addSql(<<<'SQL'
            CREATE TABLE qcmuser_data_qcmanswer (qcmuser_data_id INT NOT NULL, qcmanswer_id INT NOT NULL, INDEX IDX_35ACC6DCC53A6B28 (qcmuser_data_id), INDEX IDX_35ACC6DC652F28A4 (qcmanswer_id), PRIMARY KEY(qcmuser_data_id, qcmanswer_id)) DEFAULT CHARACTER SET utf8mb4
        SQL);
        $this->addSql(<<<'SQL'
            CREATE TABLE qrcode (id INT AUTO_INCREMENT NOT NULL, token VARCHAR(255) NOT NULL, displayed_name VARCHAR(255) NOT NULL, session_id INT DEFAULT NULL, user_id INT DEFAULT NULL, INDEX IDX_A4FF23EC613FECDF (session_id), UNIQUE INDEX UNIQ_A4FF23ECA76ED395 (user_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4
        SQL);
        $this->addSql(<<<'SQL'
            CREATE TABLE question (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(255) NOT NULL, can_be_skipped TINYINT(1) NOT NULL, type VARCHAR(255) NOT NULL, used_in_export_calculation TINYINT(1) DEFAULT NULL, survey_tool_id INT DEFAULT NULL, qcm_tool_id INT DEFAULT NULL, INDEX IDX_B6F7494ECB570177 (survey_tool_id), INDEX IDX_B6F7494E5DC6F987 (qcm_tool_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4
        SQL);
        $this->addSql(<<<'SQL'
            CREATE TABLE self_evaluation_criteria (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(255) NOT NULL, self_evaluation_tool_id INT DEFAULT NULL, INDEX IDX_3A617F2422E37492 (self_evaluation_tool_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4
        SQL);
        $this->addSql(<<<'SQL'
            CREATE TABLE self_evaluation_user_data (id INT AUTO_INCREMENT NOT NULL, note INT NOT NULL, step VARCHAR(255) NOT NULL, user_data_id INT NOT NULL, self_evaluation_criteria_id INT NOT NULL, INDEX IDX_714296296FF8BF36 (user_data_id), INDEX IDX_71429629CB171FC1 (self_evaluation_criteria_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4
        SQL);
        $this->addSql(<<<'SQL'
            CREATE TABLE session (id INT AUTO_INCREMENT NOT NULL, start_date DATETIME NOT NULL, end_date DATETIME NOT NULL, place VARCHAR(255) NOT NULL, event_id INT NOT NULL, INDEX IDX_D044D5D471F7E88B (event_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4
        SQL);
        $this->addSql(<<<'SQL'
            CREATE TABLE signature_user_data (id INT AUTO_INCREMENT NOT NULL, type INT NOT NULL, signature LONGTEXT DEFAULT NULL, ip VARCHAR(45) DEFAULT NULL, is_off TINYINT(1) DEFAULT NULL, reason VARCHAR(255) DEFAULT NULL, created DATETIME NOT NULL, signature_tool_id INT NOT NULL, user_data_id INT NOT NULL, session_id INT NOT NULL, INDEX IDX_6BA750368587BD34 (signature_tool_id), INDEX IDX_6BA750366FF8BF36 (user_data_id), INDEX IDX_6BA75036613FECDF (session_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4
        SQL);
        $this->addSql(<<<'SQL'
            CREATE TABLE survey_user_data (id INT AUTO_INCREMENT NOT NULL, comment LONGTEXT DEFAULT NULL, user_data_id INT NOT NULL, survey_question_id INT DEFAULT NULL, survey_answer_id INT DEFAULT NULL, INDEX IDX_7224FD876FF8BF36 (user_data_id), INDEX IDX_7224FD87A6DF29BA (survey_question_id), INDEX IDX_7224FD87F650A2A (survey_answer_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4
        SQL);
        $this->addSql(<<<'SQL'
            CREATE TABLE tetris_user_data (id INT AUTO_INCREMENT NOT NULL, score INT NOT NULL, tetris_tool_id INT DEFAULT NULL, user_data_id INT DEFAULT NULL, INDEX IDX_1461AE98CA6BB11F (tetris_tool_id), INDEX IDX_1461AE986FF8BF36 (user_data_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4
        SQL);
        $this->addSql(<<<'SQL'
            CREATE TABLE theme (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(255) NOT NULL, logo VARCHAR(255) NOT NULL, updated_at DATETIME NOT NULL, write_color VARCHAR(255) DEFAULT NULL, background_color VARCHAR(255) DEFAULT NULL, brand_id INT DEFAULT NULL, UNIQUE INDEX UNIQ_9775E70844F5D008 (brand_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4
        SQL);
        $this->addSql(<<<'SQL'
            CREATE TABLE tool (id INT AUTO_INCREMENT NOT NULL, name LONGTEXT DEFAULT NULL, event_id INT DEFAULT NULL, type VARCHAR(255) NOT NULL, trainer VARCHAR(255) DEFAULT NULL, hours VARCHAR(255) DEFAULT NULL, is_multi_signatures TINYINT(1) DEFAULT NULL, is_with_discharge TINYINT(1) DEFAULT NULL, baseline VARCHAR(255) DEFAULT NULL, INDEX IDX_20F33ED171F7E88B (event_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4
        SQL);
        $this->addSql(<<<'SQL'
            CREATE TABLE trial_user_data (id INT AUTO_INCREMENT NOT NULL, ip VARCHAR(45) DEFAULT NULL, created DATETIME NOT NULL, updated DATETIME DEFAULT NULL, trial_tool_id INT DEFAULT NULL, user_data_id INT NOT NULL, number_plate_id INT NOT NULL, INDEX IDX_49C9492F8CB2E660 (trial_tool_id), INDEX IDX_49C9492F6FF8BF36 (user_data_id), INDEX IDX_49C9492F5F741C74 (number_plate_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4
        SQL);
        $this->addSql(<<<'SQL'
            CREATE TABLE `user` (id INT AUTO_INCREMENT NOT NULL, username VARCHAR(180) NOT NULL, roles JSON NOT NULL, password VARCHAR(255) NOT NULL, firstname VARCHAR(255) DEFAULT NULL, lastname VARCHAR(255) DEFAULT NULL, token LONGTEXT DEFAULT NULL, concession_code VARCHAR(255) DEFAULT NULL, details VARCHAR(255) DEFAULT NULL, expiration_date DATE DEFAULT NULL, is_active TINYINT(1) NOT NULL, group_event_id INT DEFAULT NULL, INDEX IDX_8D93D64978C7A4F4 (group_event_id), UNIQUE INDEX UNIQ_IDENTIFIER_USERNAME (username), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4
        SQL);
        $this->addSql(<<<'SQL'
            CREATE TABLE user_data (id INT AUTO_INCREMENT NOT NULL, user_id INT NOT NULL, UNIQUE INDEX UNIQ_D772BFAAA76ED395 (user_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4
        SQL);
        $this->addSql(<<<'SQL'
            CREATE TABLE word (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(255) NOT NULL, right_answer TINYINT(1) DEFAULT NULL, level_id INT DEFAULT NULL, INDEX IDX_C3F175115FB14BA7 (level_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4
        SQL);
        $this->addSql(<<<'SQL'
            CREATE TABLE word_cloud_user_data (id INT AUTO_INCREMENT NOT NULL, word VARCHAR(255) NOT NULL, word_cloud_tool_id INT NOT NULL, user_data_id INT NOT NULL, INDEX IDX_7ACA11EAC9928B60 (word_cloud_tool_id), INDEX IDX_7ACA11EA6FF8BF36 (user_data_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE answer ADD CONSTRAINT FK_DADD4A25A6DF29BA FOREIGN KEY (survey_question_id) REFERENCES question (id)
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE answer ADD CONSTRAINT FK_DADD4A25EA58905F FOREIGN KEY (qcm_question_id) REFERENCES question (id)
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE event ADD CONSTRAINT FK_3BAE0AA744F5D008 FOREIGN KEY (brand_id) REFERENCES brand (id)
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE group_events_sessions ADD CONSTRAINT FK_D9C798C978C7A4F4 FOREIGN KEY (group_event_id) REFERENCES group_event (id) ON DELETE CASCADE
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE group_events_sessions ADD CONSTRAINT FK_D9C798C9613FECDF FOREIGN KEY (session_id) REFERENCES session (id) ON DELETE CASCADE
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE level ADD CONSTRAINT FK_9AEACC13CA6BB11F FOREIGN KEY (tetris_tool_id) REFERENCES tool (id)
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE number_plate ADD CONSTRAINT FK_CAD754938CB2E660 FOREIGN KEY (trial_tool_id) REFERENCES tool (id)
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE qcmuser_data ADD CONSTRAINT FK_186EDF096FF8BF36 FOREIGN KEY (user_data_id) REFERENCES user_data (id)
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE qcmuser_data ADD CONSTRAINT FK_186EDF09EA58905F FOREIGN KEY (qcm_question_id) REFERENCES question (id)
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE qcmuser_data_qcmanswer ADD CONSTRAINT FK_35ACC6DCC53A6B28 FOREIGN KEY (qcmuser_data_id) REFERENCES qcmuser_data (id) ON DELETE CASCADE
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE qcmuser_data_qcmanswer ADD CONSTRAINT FK_35ACC6DC652F28A4 FOREIGN KEY (qcmanswer_id) REFERENCES answer (id) ON DELETE CASCADE
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE qrcode ADD CONSTRAINT FK_A4FF23EC613FECDF FOREIGN KEY (session_id) REFERENCES session (id)
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE qrcode ADD CONSTRAINT FK_A4FF23ECA76ED395 FOREIGN KEY (user_id) REFERENCES `user` (id)
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE question ADD CONSTRAINT FK_B6F7494ECB570177 FOREIGN KEY (survey_tool_id) REFERENCES tool (id)
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE question ADD CONSTRAINT FK_B6F7494E5DC6F987 FOREIGN KEY (qcm_tool_id) REFERENCES tool (id)
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE self_evaluation_criteria ADD CONSTRAINT FK_3A617F2422E37492 FOREIGN KEY (self_evaluation_tool_id) REFERENCES tool (id)
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE self_evaluation_user_data ADD CONSTRAINT FK_714296296FF8BF36 FOREIGN KEY (user_data_id) REFERENCES user_data (id)
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE self_evaluation_user_data ADD CONSTRAINT FK_71429629CB171FC1 FOREIGN KEY (self_evaluation_criteria_id) REFERENCES self_evaluation_criteria (id)
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE session ADD CONSTRAINT FK_D044D5D471F7E88B FOREIGN KEY (event_id) REFERENCES event (id)
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE signature_user_data ADD CONSTRAINT FK_6BA750368587BD34 FOREIGN KEY (signature_tool_id) REFERENCES tool (id)
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE signature_user_data ADD CONSTRAINT FK_6BA750366FF8BF36 FOREIGN KEY (user_data_id) REFERENCES user_data (id)
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE signature_user_data ADD CONSTRAINT FK_6BA75036613FECDF FOREIGN KEY (session_id) REFERENCES session (id)
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE survey_user_data ADD CONSTRAINT FK_7224FD876FF8BF36 FOREIGN KEY (user_data_id) REFERENCES user_data (id)
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE survey_user_data ADD CONSTRAINT FK_7224FD87A6DF29BA FOREIGN KEY (survey_question_id) REFERENCES question (id)
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE survey_user_data ADD CONSTRAINT FK_7224FD87F650A2A FOREIGN KEY (survey_answer_id) REFERENCES answer (id)
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE tetris_user_data ADD CONSTRAINT FK_1461AE98CA6BB11F FOREIGN KEY (tetris_tool_id) REFERENCES tool (id)
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE tetris_user_data ADD CONSTRAINT FK_1461AE986FF8BF36 FOREIGN KEY (user_data_id) REFERENCES user_data (id)
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE theme ADD CONSTRAINT FK_9775E70844F5D008 FOREIGN KEY (brand_id) REFERENCES brand (id)
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE tool ADD CONSTRAINT FK_20F33ED171F7E88B FOREIGN KEY (event_id) REFERENCES event (id)
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE trial_user_data ADD CONSTRAINT FK_49C9492F8CB2E660 FOREIGN KEY (trial_tool_id) REFERENCES tool (id)
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE trial_user_data ADD CONSTRAINT FK_49C9492F6FF8BF36 FOREIGN KEY (user_data_id) REFERENCES user_data (id)
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE trial_user_data ADD CONSTRAINT FK_49C9492F5F741C74 FOREIGN KEY (number_plate_id) REFERENCES number_plate (id)
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE `user` ADD CONSTRAINT FK_8D93D64978C7A4F4 FOREIGN KEY (group_event_id) REFERENCES group_event (id)
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE user_data ADD CONSTRAINT FK_D772BFAAA76ED395 FOREIGN KEY (user_id) REFERENCES `user` (id)
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE word ADD CONSTRAINT FK_C3F175115FB14BA7 FOREIGN KEY (level_id) REFERENCES level (id)
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE word_cloud_user_data ADD CONSTRAINT FK_7ACA11EAC9928B60 FOREIGN KEY (word_cloud_tool_id) REFERENCES tool (id)
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE word_cloud_user_data ADD CONSTRAINT FK_7ACA11EA6FF8BF36 FOREIGN KEY (user_data_id) REFERENCES user_data (id)
        SQL);
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql(<<<'SQL'
            ALTER TABLE answer DROP FOREIGN KEY FK_DADD4A25A6DF29BA
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE answer DROP FOREIGN KEY FK_DADD4A25EA58905F
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE event DROP FOREIGN KEY FK_3BAE0AA744F5D008
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE group_events_sessions DROP FOREIGN KEY FK_D9C798C978C7A4F4
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE group_events_sessions DROP FOREIGN KEY FK_D9C798C9613FECDF
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE level DROP FOREIGN KEY FK_9AEACC13CA6BB11F
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE number_plate DROP FOREIGN KEY FK_CAD754938CB2E660
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE qcmuser_data DROP FOREIGN KEY FK_186EDF096FF8BF36
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE qcmuser_data DROP FOREIGN KEY FK_186EDF09EA58905F
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE qcmuser_data_qcmanswer DROP FOREIGN KEY FK_35ACC6DCC53A6B28
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE qcmuser_data_qcmanswer DROP FOREIGN KEY FK_35ACC6DC652F28A4
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE qrcode DROP FOREIGN KEY FK_A4FF23EC613FECDF
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE qrcode DROP FOREIGN KEY FK_A4FF23ECA76ED395
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE question DROP FOREIGN KEY FK_B6F7494ECB570177
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE question DROP FOREIGN KEY FK_B6F7494E5DC6F987
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE self_evaluation_criteria DROP FOREIGN KEY FK_3A617F2422E37492
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE self_evaluation_user_data DROP FOREIGN KEY FK_714296296FF8BF36
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE self_evaluation_user_data DROP FOREIGN KEY FK_71429629CB171FC1
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE session DROP FOREIGN KEY FK_D044D5D471F7E88B
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE signature_user_data DROP FOREIGN KEY FK_6BA750368587BD34
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE signature_user_data DROP FOREIGN KEY FK_6BA750366FF8BF36
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE signature_user_data DROP FOREIGN KEY FK_6BA75036613FECDF
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE survey_user_data DROP FOREIGN KEY FK_7224FD876FF8BF36
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE survey_user_data DROP FOREIGN KEY FK_7224FD87A6DF29BA
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE survey_user_data DROP FOREIGN KEY FK_7224FD87F650A2A
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE tetris_user_data DROP FOREIGN KEY FK_1461AE98CA6BB11F
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE tetris_user_data DROP FOREIGN KEY FK_1461AE986FF8BF36
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE theme DROP FOREIGN KEY FK_9775E70844F5D008
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE tool DROP FOREIGN KEY FK_20F33ED171F7E88B
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE trial_user_data DROP FOREIGN KEY FK_49C9492F8CB2E660
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE trial_user_data DROP FOREIGN KEY FK_49C9492F6FF8BF36
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE trial_user_data DROP FOREIGN KEY FK_49C9492F5F741C74
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE `user` DROP FOREIGN KEY FK_8D93D64978C7A4F4
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE user_data DROP FOREIGN KEY FK_D772BFAAA76ED395
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE word DROP FOREIGN KEY FK_C3F175115FB14BA7
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE word_cloud_user_data DROP FOREIGN KEY FK_7ACA11EAC9928B60
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE word_cloud_user_data DROP FOREIGN KEY FK_7ACA11EA6FF8BF36
        SQL);
        $this->addSql(<<<'SQL'
            DROP TABLE answer
        SQL);
        $this->addSql(<<<'SQL'
            DROP TABLE brand
        SQL);
        $this->addSql(<<<'SQL'
            DROP TABLE event
        SQL);
        $this->addSql(<<<'SQL'
            DROP TABLE group_event
        SQL);
        $this->addSql(<<<'SQL'
            DROP TABLE group_events_sessions
        SQL);
        $this->addSql(<<<'SQL'
            DROP TABLE level
        SQL);
        $this->addSql(<<<'SQL'
            DROP TABLE number_plate
        SQL);
        $this->addSql(<<<'SQL'
            DROP TABLE qcmuser_data
        SQL);
        $this->addSql(<<<'SQL'
            DROP TABLE qcmuser_data_qcmanswer
        SQL);
        $this->addSql(<<<'SQL'
            DROP TABLE qrcode
        SQL);
        $this->addSql(<<<'SQL'
            DROP TABLE question
        SQL);
        $this->addSql(<<<'SQL'
            DROP TABLE self_evaluation_criteria
        SQL);
        $this->addSql(<<<'SQL'
            DROP TABLE self_evaluation_user_data
        SQL);
        $this->addSql(<<<'SQL'
            DROP TABLE session
        SQL);
        $this->addSql(<<<'SQL'
            DROP TABLE signature_user_data
        SQL);
        $this->addSql(<<<'SQL'
            DROP TABLE survey_user_data
        SQL);
        $this->addSql(<<<'SQL'
            DROP TABLE tetris_user_data
        SQL);
        $this->addSql(<<<'SQL'
            DROP TABLE theme
        SQL);
        $this->addSql(<<<'SQL'
            DROP TABLE tool
        SQL);
        $this->addSql(<<<'SQL'
            DROP TABLE trial_user_data
        SQL);
        $this->addSql(<<<'SQL'
            DROP TABLE `user`
        SQL);
        $this->addSql(<<<'SQL'
            DROP TABLE user_data
        SQL);
        $this->addSql(<<<'SQL'
            DROP TABLE word
        SQL);
        $this->addSql(<<<'SQL'
            DROP TABLE word_cloud_user_data
        SQL);
    }
}
