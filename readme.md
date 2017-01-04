# Laravel Easy PostGIS

When you want geometry data types in your Laravel project. This package keeps the geometry support strictly on the database side, making it more easy to use together with other third party packages affecting your Models. On the laravel side only WKT-strings are used. For a more powerful and integrated approach, see https://github.com/njbarrett/laravel-postgis

## Install
    composer require ajthinking/laravel-easy-postgis
Add the service provider to providers array in config/app.php
    Ajthinking\LaravelEasyPostGIS\LaravelEasyPostGISServiceProvider::class, 

## How it works

* In your migration, create a text field as $name$_$geometry$_wkt. Example: "park_polygon_wkt"
* php artisan migrate
* php artisan postgisify

This will create a mirrored geometry column for instance "park_polygon_geom". It will also add trigger functions to your table.
Now, whenever you Insert or Update on your table, the database will make sure any _geom column is in sync with the corresponding _wkt-column.

## Configuration

Set the prefixes TODO.

## License

CC0 - do whatever you want.