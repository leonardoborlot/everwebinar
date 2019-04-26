# Everwebinar Application

## Prerequisites

- PHP 7.1 or higher
- MySQL database

## Install

- download app with command `git clone https://github.com/lorddesais/everwebinar.git`
- create .env file in application root and add line `API_KEY=secret_key_string`
- setup your MySQL connection and user credentials in app/config/local.neon
- run app/sql/everwebinar.sql to create DB 'everwebinar'

## Run

- in application root run command `php -S localhost:8010 -t www`
- go to URL 'http://localhost:8010/' and enjoy the app!