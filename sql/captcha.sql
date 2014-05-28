DROP TABLE captcha;

CREATE TABLE captcha (
    captcha_id BIGINT(13) UNSIGNED NOT NULL AUTO_INCREMENT,
    captcha_time INT(10) UNSIGNED NOT NULL,
    ip_address VARCHAR(16) DEFAULT '0' NOT NULL,
    word VARCHAR(20) NOT NULL,
    PRIMARY KEY `captcha_id` (`captcha_id`),
    KEY `word` (`word`)
);
