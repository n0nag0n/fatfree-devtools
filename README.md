# FatFree DevTools

This is a package that can aid the creation and management of your Fat-Free projects.




## Usage Video

I created a basic guide to help those that want to get started with this DevTools package.

[![Fat-Free DevTools](https://img.youtube.com/vi/2kU5S-byTwI/0.jpg)](https://youtu.be/2kU5S-byTwI)

[https://youtu.be/2kU5S-byTwI](https://youtu.be/2kU5S-byTwI)




## Installation

Installation is through Composer. If you don't have Composer, run the following command.

```
curl -s http://getcomposer.org/installer | php
```



### Globally (recommended)

```shell
composer global require n0nag0n/fatfree-devtools

# or sudo if you run into permissions issues because it's configured for restricted directories
sudo composer global require n0nag0n/fatfree-devtools
```



### Locally

```shell
composer require n0nag0n/fatfree-devtools --dev
```




## Usage

Basic usage is provided via the bin that is installed with Composer. Most of the default installations of Composer will use `vendor/bin/` and the root directory for bin files if you've installed this locally.

Otherwise, globally installations can just type `fatfree`. Make sure to place the `~/.composer/vendor/bin` directory in your `PATH` so the `fatfree` executable can be located by your system. As such, the remainder of this readme will only use `fatfree` instead of `vendor/bin/fatfree`.



### Web interface

There is a web interface that can be used instead of usage through CLI. It is recommended to use the web interface for beginners. To start up the web interface run the following command.

```shell
fatfree serve admin
```

Then follow the instructions output in your console for where to navigate to in your browser for the DevTools to display.




### Commands

This is a list of commands currently being used in the devtools helper.


#### Serve

This will serve your project using the PHP built-in web server.

```shell
fatfree serve
```


#### Clear Cache

This will clear your temp cache for you.

```shell
fatfree cache clear
```


#### Help

Provides a simple help dialog.

```shell
fatfree help
```


#### Version

Outputs the version.

```shell
fatfree version
```
