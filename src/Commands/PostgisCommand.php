<?php

namespace Ajthinking\LaravelEasyPostGIS\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputOption;

class PostgisCommand extends Command
{

    protected $name = 'postgis';

    /**
     * The command Data.
     *
     * @var CommandData
     */
    public $commandData;

    /**
     * @var Composer
     */
    public $composer;

    /**
     * Create a new command instance.
     */
    public function __construct()
    {
        parent::__construct();

        $this->composer = app()['composer'];
    }

    public function handle()
    {
        \Ajthinking\LaravelEasyPostGIS\enablePostGIS();
        $this->comment("PostGIS is enabled.");
        \Ajthinking\LaravelEasyPostGIS\dropTriggers();
        $this->comment("Dropped old triggers.");   
        $tables = \Ajthinking\LaravelEasyPostGIS\tables();
        foreach ($tables as $table) {
            $WKTColumns = [];
            $tableName = $table->tablename;        
            $columns = \Ajthinking\LaravelEasyPostGIS\columns($table);
            foreach ($columns as $column) {
                $columnName = $column->column_name;
                if(\Ajthinking\LaravelEasyPostGIS\isWKTColumn($columnName)) {
                    $WKTColumnName = $columnName;
                    $WKTColumns[] = $WKTColumnName;
                    
                    $geometryColumnName = \Ajthinking\LaravelEasyPostGIS\wktColumnNameToGeometryColumnName($columnName);
                    $geometryType = \Ajthinking\LaravelEasyPostGIS\getColumnGeometryType($columnName);
                    \Ajthinking\LaravelEasyPostGIS\addGeometryColumnIfNotExists($tableName,$geometryColumnName,$geometryType);
                } else {
                }
            }
            if(count($WKTColumns) > 0) {
                    \Ajthinking\LaravelEasyPostGIS\createTriggerFunction($tableName, $WKTColumns);
                    \Ajthinking\LaravelEasyPostGIS\createTrigger($tableName);
                    $this->info("Adding triggers for " . $tableName);
            }
        }
        $this->comment("Done!");        
    }
}