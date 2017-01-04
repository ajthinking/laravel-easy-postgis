<?php

namespace Ajthinking\LaravelEasyPostGIS;

function createTrigger($tableName) {    
    $template = file_get_contents(__DIR__ . "/../sql/create_trigger.stub");
    
    $variables = [
            '$TABLE_NAME$' => $tableName,
            '$TRIGGER_NAME$' => config('postgis.database_prefix') . 'trigger_insert_or_update_on_' . $tableName,
            '$TRIGGER_FUNCTION_NAME$' => config('postgis.database_prefix') . 'sync_geometry_columns_on_' . $tableName
    ];
    $filledTemplate = fill_template($variables, $template); 
    \DB::statement($filledTemplate);
    return true;
}

function createTriggerFunction($tableName, $WKTColumns) {
    $template = file_get_contents(__DIR__ . "/../sql/create_trigger_function.stub");    
    $process_columns_sub_template = file_get_contents(__DIR__ . "/../sql/process_columns.stub");
    $processColumns = '';
    foreach ($WKTColumns as $WKTColumn) {
        $variables = [
            '$WKT_COLUMN$' => $WKTColumn,            
            '$GEOMETRY_COLUMN$' => wktColumnNameToGeometryColumnName($WKTColumn),
            '$SRID$'            => config('postgis.srid')
        ];
        $processColumns = $processColumns . fill_template($variables, $process_columns_sub_template);    
    }

    $variables = [
            '$PROCESS_COLUMNS$' => $processColumns,            
            '$TRIGGER_FUNCTION_NAME$' => config('postgis.database_prefix') . 'sync_geometry_columns_on_' . $tableName
    ];
    $filledTemplate = fill_template($variables, $template);
    \DB::statement($filledTemplate);    
    return true;
}

function fill_template($variables, $template)
{
    foreach ($variables as $variable => $value) {
        $template = str_replace($variable, $value, $template);
    }

    return $template;
}

function isWKTColumn($columnName) {
    return ends_with($columnName,config('postgis.column_indicator.wkt'));
}

function wktColumnNameToGeometryColumnName($columnName) {
    return str_replace(config('postgis.column_indicator.wkt'),config('postgis.column_indicator.geometry'),$columnName);
}

function getColumnGeometryType($columnName) {
    $indicators = config('postgis.geometry_indicators');    
    foreach ($indicators as $key => $value) {
        if (strpos($columnName, $key) !== false) {
            return $value;
        }
    }
    // Default
    return "POINT";
}

function dropTriggers() {
    $template = file_get_contents(__DIR__ . "/../sql/drop_triggers.stub");
    
    $variables = [
            '$NAMING_PREFIX$' => config('postgis.database_prefix'),
            '$SCHEMA$'        => config('postgis.schema')
    ];
    $filledTemplate = fill_template($variables, $template);
    \DB::statement($filledTemplate);
    \DB::select('SELECT drop_triggers();');   
    return true;
}

function addGeometryColumnIfNotExists($tableName,$geometryColumnName,$geometryType) {
    $template = file_get_contents(__DIR__ . "/../sql/add_geometry_column.stub");
    
    $variables = [
            '$TABLE_NAME$' => $tableName,            
            '$GEOMETRY_COLUMN$' => $geometryColumnName,
            '$GEOMETRY_TYPE$' => $geometryType
    ];
    $filledTemplate = fill_template($variables, $template); 
    \DB::statement($filledTemplate);    
}