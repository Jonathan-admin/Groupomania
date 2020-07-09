# Groupomania web application

Groupomania is a social network that allows users to share their contents between different collaborators. 
The goal is to improve the working conditions of everyone within the company. The web application was developed in php via the symfony framework. As the association php and mysql is popular, 
the database used for this project will therefore be mysql. 

To summarize:
* Application infrastructure: Symfony 5 (php)
* Templates: HTML and twig
* Page styles: SCSS
* Dynamism and functionality: JavaScript / JQuery

## Requirement

Symfony 5 is a new version, it requires the local installation of the latest versions of php and composer on the computer for the application to work properly. Before continuing, make sure that:
* Php is well installed and the version is 7.2 or higher. `php -v` displays the installed version.
* Composer is installed and the version is 1.10.1 or higher. `composer -v` displays the current version.
* The symfony 5 cli must be installed to use the web server.
  * The executable for windows is available on the internet  
  * On linux, execute the command `wget https://get.symfony.com/cli/installer -O - | bash`
  * On MAC OS, it 's the command `curl -sS https://get.symfony.com/cli/installer | bash`

## Installation 

Start by cloning the project into one of the folders of your choice. Then open your favorite code editor and go to the root of your project.
Now perform the following procedure to set up the social network in localhost on your computer.
1. Framework and project components installation (this command can take several minutes depending on the OS used)
`composer install`
2. At the database line of the .env file, configure its access and the name of the database:  
`DATABASE_URL=mysql://root:@127.0.0.1:3306/groupomania`  
The example above allows you to connect to the database without a password as root in localhost. The name of the base is groupomania.


**With symfony CLI**

3. Launch the command `php bin/console doctrine:database:create` to create the database according to the parameters defined in the .env file
4. Then execute the command `php bin/console doctrine:migrations:migrate` to create the tables and the relations of the database
5. The files representing the state of the database must be perfectly identical to its actual state. To verify this, you need to ask symfony if there are any differences.
The command `php bin/console make:migration` indicates whether you should launch the command to remove the differences.
If there is, rerun `php bin/console doctrine:migrations:migrate`.
6. Finally launch the last command `symfony server:start`to start the web server.

The application is now functional at the following address:  
[http://127.0.0.1:8000](http://127.0.0.1:8000)  
(Don't forget to start mysql services if you have them before!)


## Inserting the dataset

To insert a dataset in BDD and play with the application:  
Launch the command `php bin/console doctrine:fixtures:load`.
Users with different roles, contents, likes and comments will be inserted.

There are 10 users with the password [UserNnieme]Km@pieds.
Integrated accounts:
* 1st user: generic deletion account, he can do nothing except delete accounts.
* Peer users are administrators
* Odd users are normal users.

























