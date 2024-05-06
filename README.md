## Book-API

**API functionality**:

Created with plain PHP without frameworks.

**App requires** .env with:
JWTKEY, REFRESH_LIFE_TIME, DB_URL

The program is hosted on www.heroku.com, _but is not currently running._

### Deploying steps in Heroku

1) Install Heroku CLI
2) heroku login
3) Procfile - web: vendor/bin/heroku-php-apache2 public/ (or else)
4) heroku create

Heroku requires composer.json and composer.lock. Vendor should be deleted.

### To deploy new version on Heroku
````
git add <.>
````
````
git commit -m "..."
````
````
git push heroku master
````

### To execute PostgreSQL

Add .sql file with tables using this command
````
heroku pg:psql --app <app name> < <sql file source>
````
### To run Heroku bash
````
heroku run bash --app <app name>
````
### Heroku logs
````
heroku logs --tail
````