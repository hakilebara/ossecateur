# Ossec-manager

This README outlines the details of collaborating on this Ember application.

 a Web Front End for OSSEC that handles alerts visualisation and acknowledgement. 

## Prerequisites

You will need the following things properly installed on your computer.

* [Git](http://git-scm.com/)
* [Node.js](http://nodejs.org/) (with NPM) and [Bower](http://bower.io/)
* [Apache](http://www.apache.org) and [PHP](http://php.net) (>= 5.3.0)
* [MySQL](http://www.mysql.com)


## Running / Development
* `git clone https://github.com/fsoumare/ossecateur.git`
* `cd ossecateur`
* `npm install`
* `bower install`

* mkdir /var/www/html/ossecateur
* ln -s /var/www/html/ossecateur/api api

* `ember server --proxy http://localhost`
* Visit your app at http://localhost:4200.


### Running Tests

* `ember test`
* `ember test --server`

### Building

* `ember build` (development)
* `ember build --environment production` (production)

### Deploying

Work in progress

## Further Reading / Useful Links

* ember: http://emberjs.com/
* ember-cli: http://www.ember-cli.com/
* Development Browser Extensions
  * [ember inspector for chrome](https://chrome.google.com/webstore/detail/ember-inspector/bmdblncegkenkacieihfhpjfppoconhi)
  * [ember inspector for firefox](https://addons.mozilla.org/en-US/firefox/addon/ember-inspector/)

