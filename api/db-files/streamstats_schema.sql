DROP DATABASE IF EXISTS streamstats;
CREATE DATABASE streamstats;
USE streamstats;

CREATE TABLE users
(
    USERID          INT(10) UNSIGNED    NOT NULL PRIMARY KEY AUTO_INCREMENT,
    TWITCHUSERID    VARCHAR(255)        NOT NULL UNIQUE KEY,
    email           VARCHAR(255)        NOT NULL,
    username        VARCHAR(255)        NOT NULL,
    access_token    VARCHAR(255)        NOT NULL,
    refresh_token   VARCHAR(255)        NOT NULL
);

CREATE TABLE games
(
    GAMEID          INT(10) UNSIGNED    NOT NULL PRIMARY KEY AUTO_INCREMENT,
    TWITCHGAMEID    VARCHAR(255)        NOT NULL UNIQUE KEY,
    game_name       VARCHAR(255)        NOT NULL,
    last_seen       DATETIME            NOT NULL DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE streams
(
    STREAMID        INT(10) UNSIGNED    NOT NULL PRIMARY KEY AUTO_INCREMENT,
    TWITCHSTREAMID  VARCHAR(255)        NOT NULL UNIQUE KEY,
    GAMEID          INT(10) UNSIGNED    NULL,
    stream_title    VARCHAR(255)        NOT NULL DEFAULT '',
    viewers         INT(10) UNSIGNED    NOT NULL DEFAULT 0,
    date_started    DATETIME            NOT NULL DEFAULT CURRENT_TIMESTAMP,
    last_seen       DATETIME            NOT NULL DEFAULT CURRENT_TIMESTAMP,

    CONSTRAINT fk_streams_GAMEID
        FOREIGN KEY (GAMEID)
        REFERENCES games(GAMEID)
        ON DELETE CASCADE
);

CREATE TABLE tags
(
    TAGID       INT(10) UNSIGNED       NOT NULL PRIMARY KEY AUTO_INCREMENT,
    TWITCHTAGID VARCHAR(255)           NOT NULL UNIQUE KEY,
    name        VARCHAR(255)           NOT NULL,
    description TEXT                   NOT NULL,
    last_seen   DATETIME               NOT NULL
);

CREATE TABLE stream_tags
(
    STREAMTAGID  INT(10) UNSIGNED NOT NULL PRIMARY KEY AUTO_INCREMENT,
    TAGID        INT(10) UNSIGNED NOT NULL,
    STREAMID     INT(10) UNSIGNED NOT NULL,

    CONSTRAINT fk_stream_tags_TAGID
        FOREIGN KEY (TAGID)
        REFERENCES tags(TAGID)
        ON DELETE CASCADE,

    CONSTRAINT fk_stream_tags_STREAMID
        FOREIGN KEY (STREAMID)
        REFERENCES streams(STREAMID)
        ON DELETE CASCADE
);

CREATE TABLE users_following_streams
(
    FOLLOWINGID     INT(10) UNSIGNED    NOT NULL PRIMARY KEY AUTO_INCREMENT,
    USERID          INT(10) UNSIGNED    NOT NULL,
    STREAMID        INT(10) UNSIGNED    NOT NULL,

    CONSTRAINT fk_user_following_USERID
        FOREIGN KEY (USERID)
        REFERENCES users(USERID)
        ON DELETE CASCADE,

    CONSTRAINT fk_user_following_STREAMID
        FOREIGN KEY (STREAMID)
        REFERENCES streams(STREAMID)
        ON DELETE CASCADE
);