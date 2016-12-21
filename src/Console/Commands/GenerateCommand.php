<?php

namespace Awjudd\JavaScriptLocalization\Console\Commands;

use File;
use Illuminate\Console\Command;

class GenerateCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'js-localize:generate';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Runs the JavaScript localization routine for all resource files.';

    /**
     * The source folder that contains all of the language resource files.
     *
     * @var        string
     */
    private $_source;

    /**
     * The destination folder where we will place the compiled resource files.
     *
     * @var        string
     */
    private $_destination;

    /**
     * The pattern which we should use for the resources.
     * 
     * @var        string
     */
    private $_pattern;

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct($sourceFolder = null, $destinationFolder = null, $pattern = null)
    {
        if(is_null($sourceFolder)) {
            $sourceFolder = config('js-localization.source');
        }

        $this->_source = $sourceFolder;

        if(is_null($destinationFolder)) {
            $destinationFolder = config('js-localization.destination');
        }

        $this->_destination = $destinationFolder;

        if(is_null($pattern)) {
            $pattern = config('js-localization.pattern');
        }

        $this->_pattern = $pattern;

        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        // Grab all of the directories
        $languages = collect(File::directories($this->_source));

        // Make sure that the folder exists
        if(!File::exists($this->_destination)) {
            File::makeDirectory($this->_destination);
        }

        // Loop through the list
        foreach($languages as $language) {
            // Build up the destination path
            $output = $this->fullFilePath($language);

            // Cycle through all of the resource files
            $files = File::allFiles($language);

            $data = [];

            // Cycle through the files
            foreach($files as $file) {
                $filePath = $file->getRealPath();

                $key = str_ireplace('/', '.', str_ireplace('.php', '', str_ireplace($language . '/', '', $filePath)));

                $resources = require $filePath;
                $data = array_add($data, $key, $resources);
            }

            $contents = File::get(__DIR__ . '/../../resources/templates/template.js');
            $contents = str_ireplace('{resource-data}', json_encode($data), $contents);

            File::put($output, $contents);
        }
    }

    public function fullFilePath($languageFolder)
    {
        $resourceCode = explode('/', $languageFolder);
        $resourceCode = end($resourceCode);

        return sprintf(
            '%s/%s',
            $this->_destination,
            str_ireplace(':locale:', $resourceCode, $this->_pattern)
        );
    }
}
