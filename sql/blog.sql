DROP TABLE IF EXISTS news_thumbnail;
DROP TABLE IF EXISTS news_comment; 
DROP TABLE IF EXISTS header_image;
DROP TABLE IF EXISTS news_image;
DROP TABLE IF EXISTS news;
DROP TABLE IF EXISTS captcha;

CREATE TABLE news (
    id INT(11) NOT NULL AUTO_INCREMENT,
    news_title VARCHAR(128) NOT NULL,
    news_date DATE NOT NULL,
    news_text TEXT NOT NULL,
    slug VARCHAR(128) NOT NULL,
    PRIMARY KEY (id),
    KEY (slug)
) ENGINE=INNODB;

CREATE TABLE news_thumbnail (
    id INT(11) NOT NULL AUTO_INCREMENT,
    news_id INT(11) NOT NULL,
    image_name VARCHAR(30) NOT NULL,
    PRIMARY KEY (id),
    INDEX (news_id),
    FOREIGN KEY (news_id) REFERENCES news (id)
) ENGINE=INNODB;

CREATE TABLE news_image (
    id INT(11) NOT NULL AUTO_INCREMENT,
    news_id INT(11) NOT NULL,
    image_id INT(11) NOT NULL, -- references the {image} tags
    image_name VARCHAR(30) NOT NULL,
    PRIMARY KEY (id),
    INDEX (news_id),
    INDEX (image_id),
    FOREIGN KEY (news_id) REFERENCES news (id)
) ENGINE=INNODB;

CREATE TABLE header_image (
    news_id INT(11) NOT NULL,
    news_image_id INT(11) NOT NULL,
    PRIMARY KEY (news_id, news_image_id),
    FOREIGN KEY (news_id) REFERENCES news (id),
    FOREIGN KEY (news_image_id) REFERENCES news_image (id)
) ENGINE=INNODB;

CREATE TABLE news_comment (
    id INT(11) NOT NULL AUTO_INCREMENT,
    comment_timestamp TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    comment_email VARCHAR(128),
    comment_url VARCHAR(128),
    comment_text VARCHAR(1024) NOT NULL,
    comment_name VARCHAR(30) NOT NULL,
    news_id INT(11) NOT NULL,
    ip_address VARCHAR(20),
    PRIMARY KEY (id),
    INDEX (news_id),
    FOREIGN KEY (news_id) REFERENCES news (id)
) ENGINE=INNODB;

CREATE TABLE captcha (
    captcha_id BIGINT(13) UNSIGNED NOT NULL AUTO_INCREMENT,
    captcha_time int(10) UNSIGNED NOT NULL,
    ip_address VARCHAR(16) DEFAULT '0' NOT NULL,
    word VARCHAR(20) NOT NULL,
    PRIMARY KEY `captcha_id` (`captcha_id`),
    KEY `word` (`word`)
) ENGINE=INNODB;
