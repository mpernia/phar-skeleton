# Release Notes

## Version 1.0.0 - Initial Release 🚀

**Release Date:** January 25, 2025

A lightweight and flexible skeleton for building PHAR packages in PHP. This initial release provides a solid foundation for creating distributable PHP applications as PHAR archives.

### Key Features

- 📦 Simple and intuitive project structure
- 🛠️ Automated build process using Makefile
- ⚙️ Environment-based configuration
- 🔄 Composer integration for easy dependency management
- 🐛 Built-in error handling and debugging support
- 📝 Comprehensive documentation

### Technical Details

- Requires PHP 8.2 or higher
- PSR-4 autoloading support
- MIT License
- Configurable build output directory and PHAR filename
- Command-line interface ready

### Getting Started

Check out our [README.md](README.md) for detailed installation and usage instructions.

## Version 1.0.1 - Minor Update 📈

**Release Date:** March 09, 2025

A minor update that introduces several improvements and new features, including enhancements to the Makefile and Docker commands.

### Key Features

- New `check-index` command in the Makefile
- Improved `public/index.php` with a header message
- Configurable application port via the `.env` file
- Enhanced Docker commands for easier development

### Technical Details

- Created `docker-compose.yml` file
- Improved `.env.dist` file with English comments
- New `docker-composer-install` and `docker-build-app` commands

### Release Notes for Version 1.0.1

#### Changes Introduced:

- Added a new `check-index` command in the Makefile to ensure `public/index.php` exists, creating it if necessary.
- Configured the application port to be set via the `.env` file, allowing for easier configuration.
- Added comments in English to the `.env.dist` file to describe the purpose of each variable.
- Created `docker-compose.yml.dist` file.
- Added `run` command to start the application container.
- Added `stop` command to stop the application container.
- Added `docker-build` command to build the application container.
- Added `dir-app` command to open a bash shell in the application container.
- Added `docker-composer-install` command to run `composer install` inside the Composer container.
- Added `docker-build-app` command to execute `make build` inside the application container.
