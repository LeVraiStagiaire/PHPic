# PHPic

## Description

PHPic is a PHP gallery application that allows you to easily create and manage your own photo gallery.

## Requirements

- PHP 7.4 or higher
- LDAP extension (see [PHP documentation](https://www.php.net/manual/en/ldap.installation.php) for installation instructions)
- Imagick extension (see [PHP documentation](https://www.php.net/manual/en/imagick.installation.php) for installation instructions

## Installation

To install the project, follow these steps:

1. Clone the repository in your webserver directory: `git clone https://github.com/LeVraiStagiaire/PHPic.git`
2. Configre your webserver (described below for Apache)
3. Navigate the installation page: `http://yourdomain.com/install.php`
4. Follow the instructions on the page and enjoy!

## Apache configuration

To configure Apache, you need to create a virtual host. Here is an example of a virtual host configuration:

```apache
<VirtualHost *:80>
    ServerName yourdomain.com
    DocumentRoot /path/to/phpic

    <Directory /path/to/phpic/public>
        AllowOverride All
        Require all granted
    </Directory>
</VirtualHost>
```