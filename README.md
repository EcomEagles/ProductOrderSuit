# Mage2 Module Codilar ProductOrderSuit

    ``codilar/module-productordersuit``

 - [Main Functionalities](#markdown-header-main-functionalities)
 - [Installation](#markdown-header-installation)
 - [Specifications](#markdown-header-specifications)
 - [Adobe Commerce platform compatibility](#Adobe-Commerce-platform-compatibility)


## Main Functionalities
A necessary solution to completely manage product and process orders

## Installation
\* = in production please use the `--keep-generated` option

### Type 1: Zip file

 - Unzip the zip file in `app/code/Codilar`
 - Enable the module by running `php bin/magento module:enable Codilar_ProductOrderSuit`
 - Apply database updates by running `php bin/magento setup:upgrade`\*
 - Flush the cache by running `php bin/magento cache:flush`

### Type 2: Composer

 - Make the module available in a composer repository for example:
    - private repository `repo.magento.com`
    - public repository `packagist.org`
    - public github repository as vcs
 - Add the composer repository to the configuration by running `composer config repositories.repo.magento.com composer https://repo.magento.com/`
 - Install the module composer by running `composer require codilar/module-productordersuit`
 - enable the module by running `php bin/magento module:enable Codilar_ProductOrderSuit`
 - apply database updates by running `php bin/magento setup:upgrade`\*
 - Flush the cache by running `php bin/magento cache:flush`

## Specifications

 - Controller
	- frontend > suit/index/questions

 - Controller
	- adminhtml > productordersuit/index/index


## Adobe Commerce platform compatibility
Adobe Community Edition: 2.4.6 (current)





