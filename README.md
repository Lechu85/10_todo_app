# Testowy projekt ToDo App :)

Mała aplikacja ToDo App :)  tworzona w celach nauki Symfony i związanych z tym technologii.

## Setup

If you've just downloaded the code, congratulations!!

To get it working, follow these steps:

**Download Composer dependencies**

Make sure you have [Composer installed](https://getcomposer.org/download/)
and then run:

```
composer install
```

You may alternatively need to run `php composer.phar install`, depending
on how you installed Composer.

## Setup the Database

For MySQL database I use docker. Please install docker on your computer or config database connection in `.env` or `.env.local` file 

To stard MySQL on docker run command
```
docker compose up
```

Now create the database & tables!
And make some initial data with Fixtures. 

```
php bin/console doctrine:database:create
php bin/console doctrine:migrations:migrate
php bin/console doctrine:fixtures:load
```

If you get an error that the database exists, that should
be ok. But if you have problems, completely drop the
database (`doctrine:database:drop --force`) and try again.

## Start the built-in web server

You can use Nginx or Apache, but Symfony's local web server
works even better.


To install the Symfony local web server, follow
"Downloading the Symfony client" instructions found
here: https://symfony.com/download - you only need to do this
once on your system.

Then, to start the web server, open a terminal, move into the
project, and run:

```
symfony serve
```
or
```
symfony serv -d
```

(If this is your first time using this command, you may see an
error that you need to run `symfony server:ca:install` first).

Now check out the site at `https://localhost:8000`

Have fun!





## Webpack setup: 
```
yarn install
```

And for watching changes use: 

```
yarn watch
```



## Have Ideas, Feedback or an Issue?

If you have suggestions or questions, let me know. Please feel free to
open an issue on this repository or send me email.

## Thanks. Leszek 