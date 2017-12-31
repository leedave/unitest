# Using Unitest in Typo3

## Prerequisites

It is possible, that the Typo3 PHPUnit package needs installing before you can 
run tests. You may also need to set an env variable for phpunit to work

```bash
composer require typo3/testing-framework
export TYPO3_PATH_ROOT='/home/vagrant/public_html/myproject.com/web/'
```

There is an example phpunit.xml file for Typo3 Installations (ver. 8+) in this 
package. Copy it to your projects root folder. 

## Generating Tests

Typo3 has a rather complex logic on autoloading. But Unitest doesn't need much 
autoloading, if it can read the required classes it is enough. 

There is an example for your json file under 
vendor/leedch/unitest/examples/typo3.json

There is an example you can use as autoload file at, just edit it to your needs
vendor/leedch/unitest/customautoload.php.dist

For Typo3 I recommend using the web loader, rather than the bash script. 
Just copy the example from the webloader folder to your /web/ folder and run it 
from your browser. 

