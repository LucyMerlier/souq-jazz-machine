# SOUQ' JAZZ MACHINE - WEBSITE

## INDEX

* [Description](#description)
* [Todo](#todo)
* [Prerequisites](#prerequisites)
* [Installation](#installation)
* [Usage](#usage)
* [Credits](#credits)
* [Acknowledgements](#acknowledgements)

## DESCRIPTION

This project is a website created for the "Souq' Jazz Machine" big band. Its goals are :

* on one hand to propose a beautiful showcase website presenting the band and displaying photographs as well as information regarding the next concerts, 
* and on the other hand to have a minimalistic but practical "administration" interface for the band members to collect information about possible concerts, sheet music, as well as current and potential partners.

This web application has been developped using some cool frameworks and tools like [Symfony](https://symfony.com/) and [Bootstrap](https://getbootstrap.com/). You can find a more exhaustive list in the [acknowledgements section](#acknowledgements).

## PREREQUISITES

To install this project, you will need to have some packages installed on your machine. Here is the recommended setup :

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
* use the `.env` file as a template to create a `.env.local` file (which should never be versionned by Git), and fill the `MAILER_DSN`, `MAIL_ADDRESS` and `DATABASE_URL` lines with the appropriate information
    * note : the `DATABASE_URL` variable should contain the connection information of a user that has `CREATE/DROP DATABASE`, `CREATE/DROP TABLE`, `INSERT INTO`, `UPDATE`, `DELETE` and `SELECT` rights on the given database, and you may need to create that user and grant it those rights beforehand
* run `bin/console doctrine:database:create` to create your database with the informations that you wrote in `.env.local`
* run `bin/console doctrine:migrations:migrate` to create the tables structure of the database
* run `bin/console doctrine:fixtures:load` to fill the database with fictive information
* run `symfony server:start` to launch your PHP Symfony server
* open your preferred web browser and go to `localhost:8000`

## USAGE

This website is divided in two major parts : a "public" section that anyone has access to, and a "private" section that is only accessible by using one of the band member's credentials (email + password). For each part of the website, you will find a list of associated functionalities below.

### PUBLIC ("SHOWCASE") FUNCTIONALITIES

This part of the application lets visitors access various pages, mainly by using the navigation bar at the top of the viewport :

* `/` : a home page with a welcoming message on a banner, the last 6 news articles that have been posted, and details concernaing the next concert of the band, if there is any scheduled
* `/le-big-band` : a page displaying a couple of paragraphs presenting the band's history, and a section presenting the band members sorted by instrument, with their profile picture (or by default, a random picture of a cat from [placekitten](https://placekitten.com/)), their name, and optionally their pseudonym and / or associated catchphrase
* `/galerie` : a page displaying a selection of photographs of the band
* `/calendrier` : a page displaying all the band's concerts to come, with a description and prices
* `/contact` : a page displaying a contact form that sends an email to the configured address (`MAIL_ADDRESS` environment variable) when properly submitted

On each of those pages, we can also find common a footer at the bottom of the document, containing the same links as in the navigation bar in the "Plan du site" section, but also a "Liens utiles" section that contains links to some useful pages :

* `/mentions-legales` : a page displaying the legal notice of the website
* `/credits` : a page displaying the project's credits
* `/contact?bugReporting=true` : this directs to the contact page, except the "Sujet" and "Votre message" fields are pre-filled to make it easier to report bugs

Also, if the band is looking for new members, visitors may be prompted to join the band with a small message box at the bottom of the screen. Visitors can then either dismiss the message, or click on the offer that they are interested in, after which they will be directed to the `/repondre-a-une-annonce/{id}` route, where `{id}` is the offer's id. They can then send an application to the band via the form displayed on this page to explain why they want to be part of the band.

### PRIVATE ("ADMIN") FUNCTIONALITIES

This part of the application is only accessible after login in by using one of the band members credentials (email and password) via the `/connexion` route. Here, band members can do a number of actions, such as manage their potential and future concerts, their sheet music, or the gallery of photopgraphs.

Also, some of those functionalities are only accessible to users with a certain role. Here is the roles' hierarchy :

* ROLE_USER (default)
* ROLE_ADMIN > ROLE_USER
* ROLE_SUPERADMIN > ROLE_ADMIN

Here is a list of functionalities that authenticated band members have access to, mainly by using the navigation bar at the top of the viewport :

* `/admin` : a home page displaying information concerning the next concert, as well as the proposed concerts if they have not indicated yet if they are gonna be available
* `/admin/calendrier` :
  * [ROLE_USER] : a page that displays information concerning proposed, future, and past concerts, and lets them indicate if they will be available for one of the proposed concerts by clicking the "Je serai là!" or "Pas dispo :(" buttons; users can also propose a concert date by clicking the "Proposer une date" button
  * [ROLE_ADMIN] : administrators can also validate, edit, and delete a concert by clicking one of the appropriate buttons that are only visible to them
* `/admin/partitions` : a page from which athenticated users can manage the band's sheet music
* `/admin/tous-les-membres` :
  * [ROLE_USER] : a page that displays the band member's useful information
  * [ROLE_ADMIN] : administrators can also have access to the `/admin/nouvel-utilisateur` route by clicking the "Ajouter un nouveau membre" button that is only visible to them : on this page, they can create a new account, and an email will be sent to the email address that was specified in the form for the person that owns the address to confirm their registration
  * [ROLE_SUPERADMIN] : the superadministrator is the only user that has sufficient credentials to delete users, as well as grant or revoke administration rights
* `/admin/tous-les-partenaires` : a page from which athenticated users can manage the band's partners' informations (friends of the band, former members, *etc...*)
* `/admin/alums-photos` :
  * [ROLE_USER] : a page from which athenticated users can manage the band's photographs
  * [ROLE_ADMIN] : administrators can also manage the photographs' visibility on the public part of the application (via the `/galerie` route), by going to a specific photo album's page at `/admin/voir-les-photos/{id}`; where `{id}` is the id of a photo album
* `/admin/mes-informations` :
  * [ROLE_USER] : a page from which authenticated users can manage their personnal information, as well as delete their account
  * [ROLE_SUPERADMIN] : as the role of the superadministrator is supposed to be unique, the superadministrator can not delete their account right away, and needs to give away their superadministrator rights to another user beforehand, by clicking the "Céder superadmin" button that is visible to them on the `/admin/tous-les-membres` route
* `/deconnexion` : this route allows authenticated users to log out, and redirects them to the public home page at the `/` route

For administrators, an extra "Admin" dropdown menu appears in the navigation bar, that lets them access some more functionalities :

* `/admin/envoyer-un-email` [ROLE_ADMIN] : a page from which administrators can send an email to all or some members of the band
* `/admin/gestion-des-actus` [ROLE_ADMIN] : a page from which administrators can manage the news articles of the band
* `/admin/gestion-du-contenu` [ROLE_ADMIN] : a page from which administrators can manage the some of the content of the public part of the website, such as the "Notre histoire" section of the `/le-big-band` page, or the legal notice of the website
* `/admin/gestion-des-instruments` [ROLE_ADMIN] : a page from which administrators can manage the musical instruments played by the band members
* `/admin/gestion-des-petites-annonces` [ROLE_ADMIN] : a page from which administrators can indicate if the band is looking for new members


## CREDITS

This website has been created by [Lucy MERLIER](https://www.linkedin.com/in/lucymerlier/).

## ACKNOWLEDGEMENTS

This website has been developped using a vast array of amazing technological tools, such as :

* [PHP](https://www.php.net/)
* [Symfony](https://symfony.com/)
* [Twig](https://twig.symfony.com/)
* [HTML](https://developer.mozilla.org/fr/docs/Web/HTML)
* [Bootstrap](https://getbootstrap.com/)
* [Sass](https://sass-lang.com/)
* [Doctrine](https://www.doctrine-project.org/projects/orm.html)
* [MySQL](https://www.mysql.com/fr/)
* [Composer](https://getcomposer.org/)
* [NodeJS](https://nodejs.org/en/)
* [Yarn](https://yarnpkg.com/)
* [JavaScript](https://developer.mozilla.org/fr/docs/Web/JavaScript)
* [GrumPHP](https://github.com/phpro/grumphp)
* [PHPCS](https://github.com/squizlabs/PHP_CodeSniffer)
* [PHPStan](https://phpstan.org/user-guide/getting-started)
* [PHPMD](https://phpmd.org/)
* [PHPMND](https://github.com/povils/phpmnd)
* [PHPCPD](https://github.com/sebastianbergmann/phpcpd)
* [TwigCS](https://github.com/friendsoftwig/twigcs)
* [ESLint](https://eslint.org/)
* [Git](https://git-scm.com/)
* [GitHub](https://github.com/)
* [CKEditor](https://github.com/FriendsOfSymfony/FOSCKEditorBundle)
* [VichUploader](https://github.com/dustin10/VichUploaderBundle)
* [Placekitten](https://placekitten.com/)
* [Font Awesome](https://fontawesome.com/)
* [AOS](https://michalsnik.github.io/aos/)
* [VSCodium](https://vscodium.com/)
* [Mailtrap](https://mailtrap.io/)

I also want to thank the [Wild Code School](https://www.wildcodeschool.com/fr-FR), and especially [Romain Clair](https://www.linkedin.com/in/romain-clair-88679376/), [Magali Guignard](https://www.linkedin.com/in/magali-guignard-2aa863169/), [Sylvain Blondeau](https://www.linkedin.com/in/sylvain-blondeau/), and [Éléonore Bourguignon d'Herbigny](https://www.linkedin.com/in/el%C3%A9onore-bourguignon-d%E2%80%99herbigny-0654a5187/) for having accompagnied me during my first year discovering the world of web developpement.
