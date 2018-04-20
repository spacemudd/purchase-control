# Asset Manager

Work in progress.

# Requirements

1. Sqlite3 driver for indexing and searching

## Amiri font installed on server. 

- On Windows, the files can be obtained and installed from https://fonts.google.com/specimen/Amiri?selection.family=Amiri
- On Linux:
	1. `sudo apt-get install fonts-hosny-amiri`
	2. Make sure the it's installed by running `fc-list` to output all the font names.

## OpenSSL LibSSL for PDF generating

Linux Ubuntu 14.04

`sudo apt-get install openssl=1.0.1f-1ubuntu2.22`
`sudo apt-get install libssl-dev=1.0.1f-1ubuntu2.22`

# Installing

1. `composer install`
2. `php artisan key:generate`

# Deploying

1. `php artisan route:cache`
2. `npm run prod`

# Troubleshooting

Sqlite3 is required, if on Linux, just run 

	`sudo apt-get install php7.0-sqlite3`
