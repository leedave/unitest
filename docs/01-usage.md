# Using Unitest 

## What it does

Unitest generates PHPUnit test classes from your original classes. Each public 
method of your original class is tested in the test class. Against the will of 
"Unit Testing Best Practices", by default all test methods succeed and do not 
run your code. I decided to do it this way, because a default failure would 
kill your motivation to use unit tests at all. 

Still it does not make sense to test, if you do not edit these methods. 

## Magento2 integration

[You can use Unitest with Magento 2](02-magento2.md)

## Typo3 integration

[You can use Unitest with Typo3 version 8)](03-typo3.md)
