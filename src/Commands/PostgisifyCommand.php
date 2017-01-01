<?php

namespace Ajthinking\LaravelEasyPostGIS\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputOption;

class PostgisifyCommand extends Command
{

    protected $name = 'postgisify';

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
        Log::info('Postgisify was called from Ajthinkings package LaravelEasyPostGIS!');        
    }
}