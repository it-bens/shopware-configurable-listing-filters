FROM dockware/dev:6.5.8.16

RUN set -ex; \
    sudo sed -i '/\[mysqld\]/a group_concat_max_len = 320000' /etc/mysql/my.cnf && \
    sudo sed -i '/\[mysqld\]/a sql_mode = "STRICT_TRANS_TABLES,NO_ZERO_IN_DATE,ERROR_FOR_DIVISION_BY_ZERO,NO_ENGINE_SUBSTITUTION"' /etc/mysql/my.cnf

RUN set -ex; \
    composer config platform.php '8.1' && \
    composer update && \
    composer require shopware/dev-tools
