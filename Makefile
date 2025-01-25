.PHONY: all clean build

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

help: ## Show this help message
	@echo
	@echo 'PHAR Builder. Build PHAR files easily.'
	@echo 'usage: make [options]'
	@echo
	@echo 'options:'
	@egrep '^(.+)\:\ ##\ (.+)' $(MAKEFILE_LIST) | sed 's/Makefile://' | column -t -s '##'
	@echo
