<?php

return [
    /**
     * The path the the resource files that we will be processing.
     * 
     * @var string
     */
    'source' => resource_path('lang'),

    /**
     * The path to where the finalized resource file will be stored.
     * 
     * @var string
     */
    'destination' => storage_path('lang'),
];