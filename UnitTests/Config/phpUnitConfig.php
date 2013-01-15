<?php
class phpUnitConfig
{
    public function __construct() 
    {
    }
    
    public function getConfig()
    {
        return array(
            "lwdb"          => array("user" => "root",
                                     "pass" => "",
                                     "host" => "localhost",
                                     "db"   => "fab_test"),
            
            "plugins"       => "C:/xampp/htdocs/c38/contentory/c_server/plugins/",
            
            "plugin_path"   => array("lw" => "C:/xampp/htdocs/c38/contentory/c_server/modules/lw/")
        );
    }
}