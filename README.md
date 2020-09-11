# FatFree Developer Tools
This is a package that can aid the creation and management of your FatFree projects.

## Installation
Installation is through composer

### Globally (recommended)
```
composer global require n0nag0n/fatfree-devtools
# or sudo if you run into permissions issues
sudo composer global require n0nag0n/fatfree-devtools
```

### Locally
```
composer require n0nag0n/fatfree-devtools --dev
```

## Usage
Basic usage is provided via the bin that is installed with composer. Most of the default installations of composer will use `vendor/bin/` and the root directory for bin files if you've installed this locally. Otherwise globally installations can just type `fatfree-devtools` and it should be in your `PATH` variable already. As such, the remainder of this readme will only use `fatfree-devtools` instead of `vendor/bin/fatfree-devtools`

### Webtools
There is a web interface that can be used instead of usage through CLI. It is recommended to use the web interface for beginners. To start up the web interface run the following command.
```
fatfree-devtools serve admin
```
Then follow the instructions output in your console for where to navigate to in your browser for the devtools to display.

### Commands
This is a list of commands currently being used in the devtools helper.

#### Serve
This will serve your project using the PHP built in webserver
```
fatfree-devtools serve
```

#### Clear Cache
This will clear your temp cache for you.
```
fatfree-devtools serve
```

#### Help
Provides a simple help dialog.
```
fatfree-devtools help
```

#### Version
Outputs the version
```
fatfree-devtools version
```