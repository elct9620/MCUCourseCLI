MCUCourseCLI
============

Ming Chuan University Course command line tools for developer.

**This project is still under developing.**

## Requirement

1. PHP >= 5.3.7
2. Composer

## Install

1. clone repo from github `git clone git@github.com:elct9620/MCUCourseCLI.git`
2. `cd MCUCourseCLI`
3. `compoer install` (You should install composer)
4. place this code to your .profile file `alias mcucli="php YOUR_CLONE_PATH/bin/MCUCourseCLI.php"`

## Usage

Just type `mcucli` or `php YOUR_CLONE_PATH/bin/MCUCourseCLI.php` to get help

## Config File

It can help you change default setting like database driver, name.

At your work directory create a file named `.mcuConfig`.

Example:

```
[database]
DBConnection[driver]=sqlite
DBConnection[database]=mcu.sqlite
migrationTable=migrations
```

### Support Options

* DBConnection
  * driver - support SQLite, MySQL, Postgres, SQL Server
  * database
  * host
  * username
  * password
  * charset
  * collation
  * prefix
* migrationTable - migrations table name