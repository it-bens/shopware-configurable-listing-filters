#!/bin/bash

cd /var/www/html && sudo -u www-data composer require it-bens/configurable-listing-filters
cd /var/www/html && sudo -u www-data php bin/console plugin:refresh
cd /var/www/html && sudo -u www-data php bin/console plugin:install ITBConfigurableListingFilters --activate
cd /var/www/html && sudo -u www-data php bin/console cache:clear