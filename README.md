# Snowtricks

## Introduction
This project is a community website about snow tricks build with Symfony & Doctrine.
It's the 6th OpenClassRooms PHP/Symfony Developer project.

To run this project and load all its dependencies on your local machine, you need to have [Composer](https://getcomposer.org/) and [npm (Node.js package manager)](https://nodejs.org/en/).
Assets are managed by [WebPack Encore](https://github.com/symfony/webpack-encore), a simpler way to integrate Webpack into your application, developed by Symfony. Read documentation [here](https://symfony.com/doc/current/frontend.html)

## Dependencies
This project uses:
- [FOSUserBundle](https://github.com/FriendsOfSymfony/FOSUserBundle) for user management
- [StofDoctrineExtensionsBundle](https://github.com/stof/StofDoctrineExtensionsBundle) for sluggable fields
- [TwigExtensions](https://github.com/twigphp/Twig-extensions) that do not belong to Twig core
	- Twig_Extensions_Extension_Array for shuffle functionality
	- Twig_Extensions_Extension_Text for truncate functionality
- [Symfony/Yaml](https://github.com/symfony/yaml) to dump YAML files - used to parse fixtures YAML files
- [IvoryCKEditorBundle](https://github.com/egeloen/IvoryCKEditorBundle) for CKEditor integration
Those dependencies are included in composer.json.

This project also uses:
- [WebPack Encore](https://github.com/symfony/webpack-encore) for assets management
- [bootstrap-sass](https://github.com/twbs/bootstrap-sass) Bootstrap SASS library
- [sass-loader](https://github.com/webpack-contrib/sass-loader) to compile your SCSS files to CSS
- [jQuery](https://github.com/jquery/jquery) autoloaded by WebPack Encore
- [lightgallery](https://github.com/sachinchoolur/lightGallery) for trick pictures gallery
Those dependencies are included in package.json

## Installation
1. Clone this repository on your local machine by using this command line in your folder `git clone https://github.com/bhalexx/snowtricks.git`.
2. In project folder open a new terminal window and execute command line `composer install`.
3. Execute command line `php bin/console assets:install web` to install Ivory CKEditor assets.
4. Then execute command line `npm install` to install node modules for assets management.
5. Rename `snowtricks/app/config/parameters.yml.dist` in `snowtricks/app/config/parameters.dist` and edit database parameters with yours.
6. Execute command line `php bin/console doctrine:database:create`.
7. Now that your database is created, you have to create tables with command line `php bin/console doctrine:schema:update --force`.
##### Your project is ready to be run!
##### I can hear you saying: "Wait... I don't want to create families and tricks one by one...". Don't worry!
8. Run `php bin/console snowtricks:fixtures:load` and wait until it's done. Now you have a website full of families and tricks!
9. Enjoy!

<!-- ## Structure
This Symfony project contains 3 bundles:
- CoreBundle
- SnowTricksBundle
- UserBundle -->