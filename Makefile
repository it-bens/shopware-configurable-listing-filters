DOCKER_COMPOSE = docker compose
DEV_TOOLS_SERVICE = dev-tools
SHOPWARE_SERVICE = shopware

style-check:
	composer style-check

style-fix:
	composer style-fix

code-upgrade:
	composer code-upgrade

static-analysis:
	$(DOCKER_COMPOSE) exec shopware bash -c "cd custom/plugins/ITBConfigurableListingFilters && ../../../vendor/bin/phpstan analyze -c ./phpstan.neon"

build-storefront:
	$(DOCKER_COMPOSE) exec -u www-data $(SHOPWARE_SERVICE) sed -i 's/npm install --prefer-offline --production/npm install --prefer-offline/g' ./bin/build-storefront.sh
	$(DOCKER_COMPOSE) exec -u www-data $(SHOPWARE_SERVICE) bin/build-storefront.sh

build-administration:
	$(DOCKER_COMPOSE) exec -u www-data $(SHOPWARE_SERVICE) sed -i 's/npm install --prefer-offline --production/npm install --prefer-offline/g' ./bin/build-administration.sh
	$(DOCKER_COMPOSE) exec -u www-data $(SHOPWARE_SERVICE) bin/build-administration.sh

watch-administration:
	$(DOCKER_COMPOSE) exec -u www-data $(SHOPWARE_SERVICE) sed -i 's/npm install --prefer-offline --production/npm install --prefer-offline/g' ./bin/watch-administration.sh
	$(DOCKER_COMPOSE) exec -u www-data $(SHOPWARE_SERVICE) bin/watch-administration.sh