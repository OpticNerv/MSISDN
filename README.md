# MSISDN
MSISDN parsing software, with "complete" list of country call codes and MNOs

Application takes MSISDN as an input and returns MNO identifier, country dialling code, subscriber number and country identifier as defined with ISO 3166-1-alpha-2

What is included (/data):
* "complete" list of countries with their call codes in JSON format
* "complete" list of countries with MNO`s, attached to country call codes in JSON format and country identifiers defined with ISO 3166-1-alpha-2

Installation:
* make sure you have running PHP(tested on PHP 5.6 and 7.2) and a web server (such as Nginx, Apache), then just clone the whole repository

Test cases are loacted in "tests" folder and were done using PHPUnit 7.0.0. Run them by navigating to project root folder and running "phpunit tests/MsisdnTest.php" command in your command line.

Sources of data i used are: 
* https://countrycode.org/
* https://github.com/googlei18n/libphonenumber/tree/master/resources/carrier/en
