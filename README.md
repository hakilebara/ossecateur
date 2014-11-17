# ossecateur

This README outlines the details of collaborating on this Ember application.

 a Web Front End for OSSEC that handles alerts visualisation and acknowledgement.
 ossecateur is based on OSSEC 2.8

## Prerequisites

You will need the following things properly installed on your computer.

* [Git](http://git-scm.com/)
* [Node.js](http://nodejs.org/) (with NPM) and [Bower](http://bower.io/)
* [Apache](http://www.apache.org) and [PHP](http://php.net) (>= 5.3.0)
* [MySQL](http://www.mysql.com)


## Running / Development'
* `git clone https://github.com/fsoumare/ossecateur.git`
* `cd ossecateur`
* `npm install`
* `bower install`

Copy a folder called ossecateur at the root of your web server
* `mkdir /var/www/html/ossecateur`
* `ln -s /var/www/html/ossecateur/api api`

Add the ossecateur's MySQL tables to your OSSEC database
* `mysql ossec < api/ossecateur-tables.schema`
 
If you do not have an OSSEC database yet, you can use the sample db provided
* `mysqladmin create ossec`
* `mysql ossec < api/ossecateur.sql`

Configure PHP authentation to to your OSSEC database
* in `api/index.php` edit the line `pdo = new PDO("mysql:dbname=ossec", "root");`

* `ember server --proxy http://localhost`
* Visit your app at http://localhost:4200.


### Running Tests

* `ember test`
* `ember test --server`

### Building/Deploying
* `ember build --environment production`
* `cp -a dist/* /var/www/html/ossecateur/`

## Further Reading / Useful Links

* ember: http://emberjs.com/
* ember-cli: http://www.ember-cli.com/
* Development Browser Extensions
  * [ember inspector for chrome](https://chrome.google.com/webstore/detail/ember-inspector/bmdblncegkenkacieihfhpjfppoconhi)
  * [ember inspector for firefox](https://addons.mozilla.org/en-US/firefox/addon/ember-inspector/)

