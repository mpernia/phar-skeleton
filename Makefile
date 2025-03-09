.PHONY: all clean build run stop check-index

$(shell cp -n docker-compose.yml.dist docker-compose.yml || true)
$(shell cp -n .env.dist .env || true)
include .env
export

ifndef BUILD_DIR
    $(error BUILD_DIR is not defined in .env file)
endif
ifndef PHAR_NAME
    $(error PHAR_NAME is not defined in .env file)
endif

all: ## Clean and build (default)
	$(MAKE) clean
	$(MAKE) build

clean: ## Clean build directory
	@echo "Cleaning build directory..."
	@rm -rf $(BUILD_DIR)
	@mkdir -p $(BUILD_DIR)
	@chmod 777 $(BUILD_DIR)

build: ## Build phar file
	@echo "Building $(PHAR_NAME)..."
	@php -d phar.readonly=0 bin/builder.php || exit 1
	@if [ -f "$(BUILD_DIR)/$(PHAR_NAME)" ]; then \
		chmod +x "$(BUILD_DIR)/$(PHAR_NAME)"; \
		echo "Build completed: $(BUILD_DIR)/$(PHAR_NAME)"; \
	else \
		echo "Error: Could not create phar file"; \
		exit 1; \
	fi

check-index:
	@if [ ! -f public/index.php ]; then \
		mkdir -p public; \
		echo "<?php\n\necho '<h1>It works!</h1>';\n\n?>" > public/index.php; \
	else \
		echo "public/index.php already exists"; \
	fi

run: ## Run docker-compose up
	docker-compose up -d

stop: ## Run docker-compose down
	docker-compose down

docker-build: ## Run docker-compose build
	$(MAKE) check-index
	docker-compose build

dir-app: ## Access the app directory in the container
	docker-compose exec app bash

docker-composer-install: ## Run docker-compose build inside the container
	docker-compose run --rm composer install

docker-build-app: ## Build the PHAR file inside the container
	docker-compose exec app make build

help: ## Show this help message
	@echo
	@echo 'PHAR Builder. Build PHAR files easily.'
	@echo 'usage: make [options]'
	@echo
	@echo 'options:'
	@egrep '^(.+)\:\ ##\ (.+)' $(MAKEFILE_LIST) | sed 's/Makefile://' | column -t -s '##'
	@echo
