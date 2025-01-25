<h1 align="center">Phar Skeleton</h1>

<p align="center">
    <a href="https://www.php.net/">
        <img src="https://img.shields.io/badge/PHP-8.2-blue.svg?style=flat&logo=php&logoColor=white&logoWidth=20" alt="PHP 8.2">
    </a>
    <a href="https://github.com/mpernia/phar-skeleton/blob/main/LICENSE">
        <img src="https://img.shields.io/badge/License-MIT-green.svg" alt="License MIT">
    </a>
    <a href="#">
        <img src="https://img.shields.io/badge/Version-1.0.0-orange.svg" alt="Version 1.0.0">
    </a>
</p>

**Phar Skeleton** is a simple starter project for building a `.phar` package in PHP.  
This repository provides a basic structure to create, compile, and distribute your PHP application as a PHAR archive.

## Contents

- [Requirements](#requirements)
- [Features](#features)
- [Project Structure](#project-structure)
- [Getting Started](#getting-started)
  - [Environment Variables](#environment-variables)
- [Usage](#usage)
  - [Build with Makefile](#build-with-makefile)
  - [Build with Composer](#build-with-composer)
- [Run or distribute the PHAR file](#run-or-distribute-the-phar-file)
- [Error Handling](#error-handling)
- [Contributing](#contributing)
- [License](#license)

## Requirements

- PHP 8.2 or higher
- PHP `phar.readonly` setting must be disabled
- `make` command available in your system

## Features

- Basic Composer configuration (type = "project").
- PSR-4 autoloading support.
- Simple build script example (`src/Example.php`).
- Example CLI script (`build/app.phar`).


## Project Structure

```
.
├── .env               # Environment variables
├── Makefile           # Build automation
├── README.md          # This file
├── bin/
│   └── builder.php    # PHAR builder script
└── src/
    ├── index.php      # Main application file
    └── ...            # Other PHP source files
```


## Getting Started

1. **Install** this skeleton (via Composer or by downloading/cloning this repository).
2. **Customize** your package:
   - Update `composer.json` with your details.
   - Modify `src/` files to include your application logic.
   - Edit a file `.env` to set environment variables.
        ```env
        BUILD_DIR=build
        PHAR_NAME=app.phar
        ```
3. **Build** your PHAR:
   - Run `composer compile` or directly `make`.
   - This will generate a `app.phar` file in the project.
   - The PHAR file will be created in the specified build directory (default: `./build/app.phar`).
4. **Run** or test your PHAR:
   - Run `php build/app.phar` or `build/app.phar` to execute the PHAR file.

### Environment Variables

- `BUILD_DIR`: Directory where the PHAR file will be created
- `PHAR_NAME`: Name of the output PHAR file

## Usage

### Build with Makefile

The project includes a Makefile with several commands to help you build the PHAR file:

```bash
# Clean and build (recommended)
make all

# Only clean the build directory
make clean

# Only build the PHAR file
make build

# Show available commands
make help
```

### Build with Composer

You can also use Composer to build the PHAR file:

```bash
composer compile
```

## Run or distribute the PHAR file

- You can run the PHAR file directly:

    ```bash
    php build/app.phar
    ```

    Or:

    ```bash
    php build/app.phar
    ```

- You can also distribute the PHAR file to other machines or environments, so they can run the application without needing the full source.

## Error Handling

- If the `.env` file is missing or incomplete, the build process will show an error message with the required variables
- Build errors will be displayed with appropriate error messages and exit codes

## Contributing

Feel free to open issues or submit pull requests if you have any suggestions or improvements. 

1. Fork the repository
2. Create your feature branch
3. Commit your changes
4. Push to the branch
5. Create a new Pull Request

## License

MIT License. See the [LICENSE](LICENSE) file for more details.
