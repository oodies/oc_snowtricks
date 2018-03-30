# A COMMUNITY SITE ABOUT SNOW TRICKS

------------------------------------------------------------------------------
Project number 6 of OpenClassrroms "Developpeur d'application PHP / Symfony" cursus

The objective of this project is to create a community site about snow tricks using the Symfony framework without using an existing Bundle

## Code quality

[![Codacy Badge](https://api.codacy.com/project/badge/Grade/eba77799d7164300a2954d2d30b40eef)](https://www.codacy.com/app/sebastien.chomy/oc_snowtricks?utm_source=github.com&amp;utm_medium=referral&amp;utm_content=sebastien-chomy/oc_snowtricks&amp;utm_campaign=Badge_Grade)
## Demonstration

Preview example : [http://snowtricks.oodie.fr](http://snowtricks.oodie.fr)
## Installation

### 1 - Download or clone the repository git
``
git clone https://github.com/sebastien-chomy/oc_snowtricks.git my_project
`` 

### 2 - Download dependencies
from **/my_project/**
``
composer install
`` 
Before you start using Composer, you must first install it on your system.
https://getcomposer.org/

### 4 - Download dependencies for frontend
From **/my_project/**
```
yarn install
```
Before you start using Yarn, you must first install it on your system.
https://yarnpkg.com/fr/docs/install

### 5 - Create database
From **/my_project/**
```
php bin/console doctrine:database:create
```

### 6 - Create schema
From **/my_project/**
```
php bin/console doctrine:schema:create
```
OR
```
php bin/console doctrine:schema:update --force
```

### 7 - Fixtures of data
From **/my_project/**
```
php bin/console doctrine:fixtures:load
```

### 8 - Preparation
From **/my_project/**
```
php bin/console cache:clear --env=prod 
```

### 8 - Run
From **/my_project/**
```
PHP -S localhost:8080
```
and from your browser
Production version
```
http://localhost:8080/web/app.php
```
OR
Development version 
```
http://localhost:8080/web/app_dev.php
```

### Users
To access the application's various features

User | login | Password
---- | ----- | --------
Author of comments | author | 12345
Blogger | blogger | 12345
Administrator| admin | 12345

  
  

