<?php

namespace Framework\Config;

use Framework\Exceptions\ConfigFileNotFoundException;

class Config
{
    public function __construct(array $configs = []) 
    {
        foreach($configs as $config) {
            $config = str_replace('-', '_', $config);
            $configDefault[$config] = $this->loadConfig($config);
        }

        $configOverride = $this->loadConfigOverride();

        foreach($configDefault as $key => $value) {
            if($configOverride && array_key_exists($key, $configOverride)) {
                $this->{$key} = $configOverride[$key];
            } else {
                $this->{$key} = $value;
            }
        }
    }
    
    private function loadConfig($file) {
        $filePath = DIR_CONFIG . '/' . $file . '.php';
        
        if(!file_exists($filePath)) {
            throw new ConfigFileNotFoundException('Could not load config file path: ' . $filePath);
        }
        
        return include_once $filePath;
    }

    private function loadConfigOverride()
    {
        $filePath = DIR_CONFIG . '/environment/' . APP_ENV . '.php';

        if(file_exists($filePath)) {
            return include_once $filePath;
        }
    }
}