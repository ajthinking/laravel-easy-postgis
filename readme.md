# Laravel Easy PostGIS

When you want geometry data types in your Laravel project. This package keeps the geometry support strictly on the database side, making it more easy to use together with other third party packages affecting your Models. On the laravel side only WKT-strings are used. For a more powerful and integrated approach, see https://github.com/njbarrett/laravel-postgis

## How it works

*Create your model and migration
    **When you want a geometry column, just create a text column with a trailing "_wkt". 
    **Example: "park_polygon_wkt"
*php artisan migrate
*php artisan postgisify
    **This will create a mirrored POLYGON column "park_polygon_geom"
    **It will also add trigger functions to your table
*Now, whenever you Insert or Update on your table, the database will make sure the _geom column is in sync with the "park_polygon_wkt"-column. 

## Install

composer require ajthinking/laravel-easy-postgis
...
...
TODO

## Configuration

Set the prefixes TODO.

## License

CC0 - do whatever you want.
