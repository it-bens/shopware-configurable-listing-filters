DOCKER_COMPOSE = docker compose
DEV_TOOLS_SERVICE = dev-tools
SHOPWARE_SERVICE = shopware

style-check:
	$(DOCKER_COMPOSE) run --rm $(DEV_TOOLS_SERVICE) php /home/php/.composer/vendor/bin/ecs check --config=ecs.php src tests

style-fix:
	$(DOCKER_COMPOSE) run --rm $(DEV_TOOLS_SERVICE) php /home/php/.composer/vendor/bin/ecs check --config=ecs.php --fix src tests

code-upgrade:
	$(DOCKER_COMPOSE) run --rm $(DEV_TOOLS_SERVICE) php /home/php/.composer/vendor/bin/rector process --config=rector.php src tests

static-analysis:
	$(DOCKER_COMPOSE) run --rm $(DEV_TOOLS_SERVICE) php /home/php/.composer/vendor/bin/phpstan analyze --configuration=phpstan-local.neon --memory-limit=-1 src tests

create-checkbox-filter-configuration:
	$(DOCKER_COMPOSE) exec -u www-data $(SHOPWARE_SERVICE) php bin/console itb:listing-filter:create-checkbox-filter-configuration 'isCloseout' 'Is Closeout' --enabled

create-multi-select-filter-configuration:
	$(DOCKER_COMPOSE) exec -u www-data $(SHOPWARE_SERVICE) php bin/console itb:listing-filter:create-multi-select-filter-configuration 'productNumber' 'Product number' --enabled

build-storefront:
	$(DOCKER_COMPOSE) exec -u www-data $(SHOPWARE_SERVICE) sed -i 's/npm install --prefer-offline --production/npm install --prefer-offline/g' ./bin/build-storefront.sh
	$(DOCKER_COMPOSE) exec -u www-data $(SHOPWARE_SERVICE) bin/build-storefront.sh

build-administration:
	$(DOCKER_COMPOSE) exec -u www-data $(SHOPWARE_SERVICE) sed -i 's/npm install --prefer-offline --production/npm install --prefer-offline/g' ./bin/build-administration.sh
	$(DOCKER_COMPOSE) exec -u www-data $(SHOPWARE_SERVICE) bin/build-administration.sh

watch-administration:
	$(DOCKER_COMPOSE) exec -u www-data $(SHOPWARE_SERVICE) sed -i 's/npm install --prefer-offline --production/npm install --prefer-offline/g' ./bin/watch-administration.sh
	$(DOCKER_COMPOSE) exec -u www-data $(SHOPWARE_SERVICE) bin/watch-administration.sh