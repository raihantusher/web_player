<?php

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

$config=[];
$config["dir"]='.';

$config['exclude'] = [

            "api.php", "assets", "index.php", "libs",  "config.php",
            ".git", "LICENSE", "README.md",
          ];

#$config["current_url"]="http://alhidaaya.com/swahili/duaa_adhkaar";
$config["current_url"]="http://localhost/web_player";

?>
