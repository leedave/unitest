# Using Unitest in Magento 2

Either copy the webloader from the webloader folder to your Magento2 /pub/ folder 
and run it via Browser, or use the bash command 

```bash
vendor/bin/unitest unitest.json
```

There is an example for your json file under 
vendor/leedch/unitest/examples/magento.json

## Running Tests

Copy the phpunit.xml example for magento to your magento root folder and run:

```bash
vendor/bin/phpunit -c phpunit.xml
```
