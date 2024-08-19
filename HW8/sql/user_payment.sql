-- auto-generated definition
create table user_payment
(
    id_user_payment   int auto_increment
        primary key,
    amount            int null,
    payment_timestamp int null,
    user_id           int not null,
    constraint user_id
        foreign key (user_id) references users (id_user)
            on update cascade on delete cascade
);

