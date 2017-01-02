# Laravel Easy PostGIS

When you want geometry data types in your Laravel project. This package keeps the geometry support strictly on the database side, making it more easy to use together with other third party packages affecting your Models. On the laravel side only WKT-strings are used. For a more powerful and integrated approach, see https://github.com/njbarrett/laravel-postgis

## How it works

* Create your model and migration
 * When you want a geometry column, just create a text column with geometry type and a trailing "_wkt". 
 * Examples: "park_polygon_wkt", "tree_point_wkt", "path_linestring_wkt"
* php artisan migrate
* php artisan postgisify
 * This will create a mirrored geometry column for instance "park_polygon_geom"
 * It will also add trigger functions to your table
* Now, whenever you Insert or Update on your table, the database will make sure any _geom column is in sync with the corresponding _wkt-column. 

## Install

composer require ajthinking/laravel-easy-postgis
...
...
TODO

## Configuration

Set the prefixes TODO.

## License

CC0 - do whatever you want.
