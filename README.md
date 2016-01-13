<h1>Sloth Invoices</h1>

This is my first project built with Symfony 2.7
This script allows you to create invoices.

<h2>Features:</h2>
<ul>
<li>Authentication for separate users</li>
<li>Separate profiles (if you have for example 2 companies)</li>
<li>Many clients with possibility to assign difference language and currency to each</li>
<li>Export to pdf</li>
</ul>

<h2>Installation:</h2>
After cloning repository you need to install it from project's root with composer.
You will also need to setup frontend libraries with bower and setup config files at:
app/config/parameters.yml (database)
app/config/config.yml (assets_base_url)
app/config/config_prod.yml (assets_base_url)
and then update database schema with doctrine.
The commands would be as follows (if you have composer, npm and bower in please):
composer install
bower install
php app/console doctrine:schema:update --force

You will also need <a href="http://wkhtmltopdf.org/" target="_blank">wkhtmltopdf</a> installed on your server.

Grunt is used for merging sass files with bootstrap:
cd web/assets/vendor/bootstrap; grunt watch

&#35;TODO change frontend setup...

You can check the working version of the project at: http://invoice.expandingweb.com/
