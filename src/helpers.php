<?php

namespace Ajthinking\LaravelEasyPostGIS;

function sayHi() {
    echo "freedom!";
    return true;
}

function createTrigger($tableName) {
    $template = file_get_contents(database_path() . "/sql/create_trigger.stub");
    
    $variables = [
            '$TABLE_NAME$' => $tableName,
            '$TRIGGER_NAME$' => 'ajthinking_' . 'trigger_insert_or_update_on_' . $tableName,
            '$TRIGGER_FUNCTION_NAME$' => 'ajthinking_' . 'sync_geometry_columns_on_' . $tableName
    ];
    $filledTemplate = fill_template($variables, $template); 
    \DB::statement($filledTemplate);
    return true;
}

function createTriggerFunction($tableName, $WKTColumns) {
    $template = file_get_contents(database_path() . "/sql/create_trigger_function.stub");
    //$template = file_get_contents(database_path() . "/sql/easy_trigger_function.stub");
    $process_columns_sub_template = file_get_contents(database_path() . "/sql/process_columns.stub");
    $processColumns = '';
    foreach ($WKTColumns as $WKTColumn) {
        $variables = [
            '$WKT_COLUMN$' => $WKTColumn,            
            '$GEOMETRY_COLUMN$' => wktColumnNameToGeometryColumnName($WKTColumn)
        ];
        $processColumns = $processColumns . fill_template($variables, $process_columns_sub_template);    
    }

    $variables = [
            '$PROCESS_COLUMNS$' => $processColumns,            
            '$TRIGGER_FUNCTION_NAME$' => 'ajthinking_' . 'sync_geometry_columns_on_' . $tableName
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
    // TODO READ FROM CONFIG!
    return ends_with($columnName,'_wkt');
}

function wktColumnNameToGeometryColumnName($columnName) {
    return str_replace('_wkt','_geom',$columnName);
}

function getColumnGeometryType() {
    return 'POLYGON';
}

function dropTriggers() {
    $template = file_get_contents(database_path() . "/sql/drop_triggers.stub");
    
    $variables = [
            '$NAMING_PREFIX$' => 'ajthinking_'
    ];
    $filledTemplate = fill_template($variables, $template);
    \DB::statement($filledTemplate);
    \DB::select('SELECT drop_triggers();');   
    return true;
}

function addGeometryColumnIfNotExists($tableName,$geometryColumnName,$geometryType) {
    $template = file_get_contents(database_path() . "/sql/add_geometry_column.stub");
    
    $variables = [
            '$TABLE_NAME$' => $tableName,            
            '$GEOMETRY_COLUMN$' => $geometryColumnName,
            '$GEOMETRY_TYPE$' => $geometryType
    ];
    $filledTemplate = fill_template($variables, $template); 
    \DB::statement($filledTemplate);    
}