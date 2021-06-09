
<?php

// remove /.. dots from file name [last parameter]
function remove_dots($str)
	{
		$str=str_replace("/..","",$str,$num);
		if($num==1)
			return $str;
		else
			return $str=str_replace("/.","",$str,$num);
	}

$exclude=$config["exclude"];
//https://stackoverflow.com/questions/20264737/php-list-directory-structure-and-exclude-some-directories
	$filter = function ($file, $key, $iterator) use ($exclude) {
	    if ($iterator->hasChildren() && !in_array($file->getFilename(), $exclude)) {
	        return true;
	    }
	    return $file->isFile();
	};


$directory = new \RecursiveDirectoryIterator($config['dir'],RecursiveDirectoryIterator::SKIP_DOTS);//inside dir here main
$iterator = new \RecursiveIteratorIterator(
	new RecursiveCallbackFilterIterator($directory, $filter)
);


//$reg="/[a-z\d\s+]?"+$_GET["q"]+"[a-z\d\s\.]/";

$files = [];

$dirs = [];

$q = $_GET['q'];

$id = 1;
foreach ($iterator as $info) {

			$c = $info->getPathname();// getPathname
			$address = remove_dots($c); // address to build url
			$c = str_replace("\\","/",$address);




			$name = explode("/",$c);// remove backslash from directory path
			$name = end($name);

			if (is_dir($c)) {

				if (preg_match("/[a-z\d\s+]?($q)[a-z\d\s\.]?/",$name,$match)) {

					$fi = new FilesystemIterator($c, FilesystemIterator::SKIP_DOTS);
					//$dir[]=["name"=>"Raihan"];
					$dirs = array_unique($dirs,SORT_REGULAR);
					$dirs[] = [
							"id" => $id,
							"loc" => $c,
							"name" => $name,
							"type" => "dir",
							"details" => iterator_count($fi)." Files" //count files

						];

					}// preg match here
				}

			if(is_file($c)){

				if(preg_match("/[a-z\d\s+]?($q)[a-z\d\s\.]?/",$name,$match) && !in_array($name,$config["exclude"])) {
					$files[]=
						[
							"id" =>$id,
							"loc" =>$c,
							"name" =>$name,
							"type" =>"file",
							"details" => $audio->details($c)
						];
				} // preg match here
			}
			$id++;
}

$json = [];

$files = array_unique($files, SORT_REGULAR);
$dirs = array_unique($dirs, SORT_REGULAR);

$folders = array_merge($dirs, []);

if (strcmp($_GET["type"], "folder") == 0) {
		$json = $folders;
}

if (strcmp($_GET["type"], "file")==0) {
		$json = $files;
}


 if( strcmp($_GET["type"], "filefolder") == 0) {
		$json = array_merge($folders,$files);
}


$json=json_encode($json);
echo $json;
