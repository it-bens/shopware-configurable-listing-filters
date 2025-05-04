ARG SHOPWARE_VERSION=''
FROM ghcr.io/dockware-mirror/dev:$SHOPWARE_VERSION

RUN set -ex; \
    sudo sed -i '/\[mysqld\]/a group_concat_max_len = 320000' /etc/mysql/my.cnf && \
    sudo sed -i '/\[mysqld\]/a sql_mode = "STRICT_TRANS_TABLES,NO_ZERO_IN_DATE,ERROR_FOR_DIVISION_BY_ZERO,NO_ENGINE_SUBSTITUTION"' /etc/mysql/my.cnf

RUN set -ex; \
    sudo mkdir /opt/phpstorm-coverage && \
    sudo chmod -R 777 /opt/phpstorm-coverage

# Remove sample plugin
RUN set -ex; \
    sudo service mysql start && \
    php bin/console plugin:uninstall DockwareSamplePlugin && \
    rm -rf custom/plugins/DockwareSamplePlugin && \
    php bin/console plugin:refresh && \
    php bin/console cache:clear

# Install dependencies required for static analysis and testing
RUN set -ex; \
    sudo service mysql start && \
    composer require shopware/conflicts:'0.1.22' --no-scripts && \
    composer config platform.php '8.1' && \
    composer update && \
    composer require --dev \
      phpstan/phpstan:'^1.12' \
      phpstan/phpstan-phpunit:'^1.4' \
      phpstan/phpstan-symfony:'^1.4' \
      phpunit/phpunit:'^10.5' \
      shopware/dev-tools:'^1.5.0' \
      zalas/phpunit-globals:'^4.0'

# Copy boot_end script into image
ADD ./docker/shopware/boot_end.sh /var/www/boot_end.sh