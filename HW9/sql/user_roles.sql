-- auto-generated definition
create table user_roles
(
    id      int auto_increment
        primary key,
    role    varchar(15) not null,
    user_id int         not null,
    constraint user_roles_users_id_user_fk
        foreign key (user_id) references users (id_user)
            on update cascade on delete cascade
);


INSERT INTO application1.user_roles (id, role, user_id) VALUES (1, 'admin', 5);
