# Envirovue Assessment

This repo contains an assessment for a Senior Laravel Developer as outlined in [this Notion document](https://www.notion.so/Assessment-for-Senior-Laravel-Developer-24864f24b84c80f5968fc7ec8604126c).

To run it, you will need PHP, Composer, andl either Node and NPM or Bun. I believe we discussed that Envirovue uses Valet but as I didn't have access to a Mac, I couldn't test the setup environment there. However, these are the versions I used in case it helps:

```
PHP 8.4
Composer 2.8.3
Node 22.15.0
NPM 10.9.2
```
Once all of that is completed, please clone the repo using your preferred method and then run the following commands:

```
composer install
cp .env-example .env
php artisan key:generate
php artisan migrate --seed
npm install
composer run dev
```

Seeding is optional but it will save some time with user management. 

You should now be able to see the site on your localhost. For ease of use, I have removed most clutter from the home page and only left a simple message and the auth routes. I have also removed the Dashboard and set up equivalent redirects to the users.index route.

In the interest of time, I have used the starter kit's existing layouts (with slight modifications).

In the same vein, other concessions have been made. For instance, the users.index route returns _all_ users. In a production environment, it would instead use pagination or some other similar type of filtering.

Moreover, authorisation policies and user roles have not been created so any authentication user can complete any of the actions defined in the UserController route, such as deleting and editing users.