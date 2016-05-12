<h1>Sloth Invoices (v01 Alpha)</h1>

This is my first project built with Symfony 2.7<br>
This script allows you to create invoices.

<h2>Features:</h2>
<ul>
<li>Authentication for separate users</li>
<li>Separate profiles (if you have for example 2 companies)</li>
<li>Many clients with possibility to assign difference language and currency to each</li>
<li>Export to pdf</li>
</ul>

<h2>Installation:</h2>
After cloning repository you need to install it from project's root with composer.<br>
You will also need to setup frontend libraries with bower and setup config files at:<br>
app/config/parameters.yml (database)<br>
app/config/config.yml (assets_base_url)<br>
app/config/config_prod.yml (assets_base_url)<br>
and then update database schema with doctrine.<br>
The commands would be as follows (if you have composer, npm and bower in please):<br>
composer install<br>
bower install<br>
php app/console doctrine:schema:update --force<br>

You will also need <a href="http://wkhtmltopdf.org/" target="_blank">wkhtmltopdf</a> installed on your server.<br><br>

Grunt is used for merging sass files with bootstrap:<br>
cd web/assets/vendor/bootstrap; grunt watch<br><br>

&#35;TODO change frontend setup...<br><br>

You can check the working version of the project at: <a href="http://sloth-invoices.expandingweb.com/" target="_blank">http://sloth-invoices.expandingweb.com/</a>
