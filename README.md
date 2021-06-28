# SOUQ' JAZZ MACHINE - WEBSITE

## INDEX

* [Description](#description)
* [Prerequisites](#prerequisites)
* [Installation](#installation)
* [Usage](#usage)
* [Credits](#credits)

## DESCRIPTION

This project is a website created for the "Souq' Jazz Machine" big band. Its goals are :

* on one hand to propose a beautiful showcase website presenting the band and displaying photographs as well as information regarding the next (and previous) concerts, 
* and on the other hand to have a minimalistic but practical "administration" interface for the band members to collect information about possible concerts, sheet music, as well as current and potential partners.

This web application has been developped using some cool frameworks and tools like [Symfony](https://symfony.com/) and [Bootstrap](https://getbootstrap.com/). You can find a more exhaustive list in the [credits section](#credits).

## PREREQUISITES

To install this project, you will need to have some packages installed on your machine. Here is the minimal recommended setup :

* [PHP 7.4.*](https://www.php.net/downloads.php#v7.4.20) (check by running `php -v` in your console)
* [Composer 2.*](https://getcomposer.org/) (check by running `composer --version` in your console)
* [node 14.*](https://nodejs.org/en/) (check by running `node -v` in your console)
* [Yarn 1.*](https://yarnpkg.com/) (check by running `yarn -v` in your console)
* [MySQL 8.0.*](https://www.mysql.com/fr/) (check by running `mysql --version` in your console)
* [Git 2.*](https://git-scm.com/) (check by running `git --version` in your console)
* You will also need a test SMTP connection, which you can configure using tools like [Mailtrap](https://mailtrap.io/)

Please note that you may also need to install other packages to fully make everything work together (like `php-mysql`).

## INSTALLATION

If your machine follows all the prerequisites, then you can just follow the instructions below to install the project in a dev environment:

* run `git clone {REPO_ADDRESS} {YOUR_CHOSEN_FOLDER_NAME}` in your console to fetch the repository from [GitHub](https://github.com/LucyMerlier/souq-jazz-machine)
* run `cd {YOUR_CHOSEN_FOLDER_NAME}` to move into the folder where the project has been downloaded
* run `composer install` to download and install PHP dependencies
* run `yarn install` to download and install JS dependencies
* run `yarn encore dev` to build assets
* use the `.env` file as a template to create a `.env.local` file (which should never be versionned by Git), and fill the `MAILER_DSN`, `MAIL_TO` and `DATABASE_URL` lines with the appropriate information
    * note : the `DATABASE_URL` variable should contain the connection information of a user that has `CREATE/DROP DATABASE`, `CREATE/DROP TABLE`, `INSERT INTO`, `UPDATE`, `DELETE` and `SELECT` rights on the given database, and you may need to create that user and grant it those rights beforehand
* run `bin/console doctrine:database:create` to create your database with the informations that you wrote in `.env.local`
* run `bin/console doctrine:migrations:migrate` to create the tables structure of the database
* run `bin/console doctrine:fixtures:load` to fill the database with fictive information
* run `symfony server:start` to launch you PHP Symfony server
* open your preferred web browser and go to `localhost:8000`

## USAGE

TODO

## CREDITS

TODO
