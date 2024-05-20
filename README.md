=> php artisan passport:install command. This command generates the encryption keys needed for OAuth2 authentication.

1. register user with user profile
2. login
3. post photo with caption
4. update user profile 
5. follow other user 


## Database Schema Design

## User
    id int primary key auto_increment
    username varchar(255) not null
    email varchar(255) not null
    passoword varchar(255) not null
    profile_pic varchar(255) not null
    bio varchar(255) not null
    create_at timestamp default current_timestamp,
    update_at timestamp default current_timestamp on update current_timestamp

## Post
    id int primary key auto_increment
    user_id int not null
    caption varchar(255) not null
    image varchar(255) not null
    
## Follow
    id int primary key auto_increment
    user_id int not null
    follow_id int not null
## Likes
    id int primary key auto_increment
    user_id int not null
    post_id int not null

### Comments
    id int primary key auto_increment
    user_id int not null
    post_id int not null
    comment varchar(255) not null

## Laravel passport Authentication
1. Install Laravel Passport
    ```bash 
    composer require laravel/passport -w
    //w that mean all dependecy
2. Run migrate
    ``bash
    php artisan migrate
3. Install passport
    ``bash
    php artisan passport:install
4. Add HasApiTokens trait to user model
    ``php 
    use Laravel\Passport\HasApiTokens;
5. Add Passport::route() to boot method in AuthServiceProvider

.....
