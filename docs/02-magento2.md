# Using Unitest in Magento 2

Either copy the webloader from the webloader folder to your Magento2 /pub/ folder 
and run it via Browser, or use the bash command 

One of many ugly features in Magento 2 is that it still uses PHPUnit 4.2, instead
of the more modern version ^6.2 that support namespaces. 

So don not forget to add the *oldversion* flag to ensure Magento 2 compatibility. 

```bash
vendor/bin/unitest unitest.json oldversion
```

There is an example for your json file under 
vendor/leedch/unitest/examples/magento.json


You can also generate your tests into a zip using the webloader located at
`vendor/leedch/unitest/webloaders/magento.php`
Just copy above file into your `pub` or `docroot` folder depending on your 
server setup

## If Magento 2 gets bitchy

It can often be a problem, if your classes refer to classes that Magento only 
generates in runtime. If so you can pre-compile these in Magento using the 
following bash command

```bash
php bin/magento setup:di:compile
```

## Running Tests

Copy the phpunit.xml example for magento to your magento root folder and run:

```bash
vendor/bin/phpunit -c phpunit.xml
```
