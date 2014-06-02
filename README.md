# CIBlog

A blogging engine built using the [CodeIniter](http://ellislab.com/codeigniter) 
PHP framework.

## Installation

### Setting up the database

Create the user used for development and testing. Start the mysql console from
the terminal:

    $ mysql -u root

From the mysql console:

    mysql> CREATE USER 'dev'@'localhost'

Create the database that is used for the site, and set permissions for the dev
user to use the database.

    mysql> CREATE DATABASE blog;
    mysql> GRANT ALL privileges ON blog.* to dev@'%';
    mysql> GRANT ALL privileges ON blog.* to dev@localhost;

Run the script 'blog.sql' from the sql directory to create the database tables.

    $ mysql -u dev blog < sql/blog.sql

### Set the base site URL

Edit the file 'application/config/config.php', and set the value of the
$config['base_url'] variable to your domain name or IP address.

### URI Routing

Routes for the site are defined in 'config/routes.php'. URL rewriting needs to
be configured. On Ubuntu, mod_rewrite is enabled by running the following
command and re-starting apache:

    $ a2enmod rewrite
    $ service apache2 restart

To get the re-writing to work, edit the
'/etc/apache2/sites-enabled/000-default', or whatever file you used for this
site. The line 'AllowOverride All' was changed from 'AllowOverride None'.

    ...
    <Directory /var/www/>
      Options Indexes FollowSymLinks MultiViews
      AllowOverride All
      Order allow deny
      allow from all
    </Directory>
    ...

### Captcha

Captcha is used to validate comments from site visistors, and to filter out spam from robots.

Install the gd image library. On Ubuntu:

    $ apt-get install php5-gd

## Testing

Populate the site with sample postings:

    $ mysql -u dev blog < sql/insert.sql
    $ mysql -u dev blog < sql/insert_image.sql
