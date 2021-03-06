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
    protected $signature = 'js-localize:generate {locale?}';

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
        $languages = collect(File::directories($this->_source))
                        ->filter(function($index, $value) {
                            $locale = $this->argument('locale');

                            return is_null($locale) ? true : preg_match('/' . $locale . '$/', $value) == 1;
                        });

        // Make sure that the folder exists
        if(!File::exists($this->_destination)) {
            // It didn't exist, so make it
            File::makeDirectory($this->_destination);
        }

        // Loop through the list
        foreach($languages as $language) {
            // Build up the destination path
            $output = $this->fullFilePath($language);

            // Write out details
            $this->info(sprintf('Processing Resource: %s', $this->locale($language)));

            // Cycle through all of the resource files
            $files = File::allFiles($language);

            $data = [];

            // Cycle through the files
            foreach($files as $file) {
                $filePath = $file->getRealPath();

                // Strip out un-necessary actions
                $key = str_ireplace('/', '.', str_ireplace('.php', '', str_ireplace($language . '/', '', $filePath)));

                $resources = require $filePath;
                $data = array_add($data, $key, $resources);
            }

            // Grab the template
            $contents = File::get(__DIR__ . '/../../resources/templates/template.js');
            $contents = str_ireplace('{resource-data}', json_encode($data), $contents);

            // Write out the details
            File::put($output, $contents);
        }
    }

    /**
     * Derives the full file name for the 
     *
     * @param      string  $languageFolder  The language folder
     *
     * @return     string  ( description_of_the_return_value )
     */
    public function fullFilePath($languageFolder)
    {
        return sprintf(
            '%s/%s',
            $this->_destination,
            str_ireplace(':locale:', $this->locale($languageFolder), $this->_pattern)
        );
    }

    /**
     * Derives thep locale based on the folder that it is in.
     *
     * @param      string  $languageFolder  The language folder
     *
     * @return     string  The locale
     */
    public function locale($languageFolder)
    {
        $resourceCode = explode('/', $languageFolder);
        return end($resourceCode);
    }
}
