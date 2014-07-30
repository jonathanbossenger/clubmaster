CREATE TABLE club_media_document (id INT AUTO_INCREMENT NOT NULL, user_id INT DEFAULT NULL, name VARCHAR(255) NOT NULL, path VARCHAR(255) NOT NULL, size INT NOT NULL, type VARCHAR(255) NOT NULL, created_at DATETIME NOT NULL, INDEX IDX_1BB91417A76ED395 (user_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB;
ALTER TABLE club_media_document ADD CONSTRAINT FK_1BB91417A76ED395 FOREIGN KEY (user_id) REFERENCES club_user_user (id);
ALTER TABLE club_shop_image ADD created_at DATETIME NOT NULL;
ALTER TABLE club_shop_product ADD image_id INT DEFAULT NULL;
ALTER TABLE club_shop_product ADD CONSTRAINT FK_AACDEC773DA5256D FOREIGN KEY (image_id) REFERENCES club_shop_image (id);
CREATE INDEX IDX_AACDEC773DA5256D ON club_shop_product (image_id);
ALTER TABLE club_booking_plan ADD status VARCHAR(255) NOT NULL;
ALTER TABLE club_media_document ADD priority INT NOT NULL;