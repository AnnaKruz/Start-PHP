-- auto-generated definition
create table users
(
    id_user                 int auto_increment
        primary key,
    user_name               varchar(45)  null,
    user_lastname           varchar(45)  null,
    user_birthday_timestamp int          null,
    login                   varchar(20)  not null,
    hash_password           varchar(100) not null,
    token                   varchar(255) null
)
    charset = utf8;


INSERT INTO application1.users (id_user, user_name, user_lastname, user_birthday_timestamp, login, hash_password, token) VALUES (5, 'Admin', 'Adminov', 1719792000, 'admin', '$2y$10$2XBi9.hcJGrUIJ5G9fLY6Owcy8z5bVIk8QXk3HdMQ0886lu.yus5y', '9afafacad1c8a5ab07d0768bb3952d3f6ed13995d01f2000d82b2a3c98bfff1c');
INSERT INTO application1.users (id_user, user_name, user_lastname, user_birthday_timestamp, login, hash_password, token) VALUES (7, 'Валерий', 'Панов', 587865600, 'ValeriiPanov', '$2y$10$3Z9UuLHuh75hmB3utIruf..c/GO6n6dfl23gmmHx0QEP/PRpyofiO', null);
INSERT INTO application1.users (id_user, user_name, user_lastname, user_birthday_timestamp, login, hash_password, token) VALUES (8, 'User', 'Userov', 1721001600, 'user', '$2y$10$3S/vGIMdr9HFZZ4Efc.5/uFevFxBc0ncg7o2F7JplUtmvTmlwmaIq', null);
INSERT INTO application1.users (id_user, user_name, user_lastname, user_birthday_timestamp, login, hash_password, token) VALUES (9, 'NewUser', 'NewUserov', 1719792000, 'new', '$2y$10$LDR4JZdjV2EWi0kEnc71Me45HdGvm25IaDfM4SAx3Z3/gtnylIOee', null);
INSERT INTO application1.users (id_user, user_name, user_lastname, user_birthday_timestamp, login, hash_password, token) VALUES (10, 'вмывм', 'ывмывм', 1721088000, 'вмыв', '$2y$10$UETOticTViryNFlIw3OlD.dtczOjaFpTv0eqBS5QNpGvUDf9MfTWS', '106919fb78a44cac58e6c7e3036058e1b968882b97168b2ce2561151d4216a17');
INSERT INTO application1.users (id_user, user_name, user_lastname, user_birthday_timestamp, login, hash_password, token) VALUES (11, 'ddg', 'bnbdfsdfn', 1722211200, 'test', '$2y$10$0J6i.SKzaVgdN8HD8HNIQO6mMUYJP4rwzcwmYoE.mi9NcXzKSKuSa', '93eb4cf68911eb816df390c6382a66978a1a427d7c9f9b68f6db9a78d610d95b');
