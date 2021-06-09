<?php


include "libs/audioInfo.php";

$audio = new RTinfo();

// get configuration
include "config.php";

// assinging default directory
$dir = $config["dir"];


$search_type = "";
$search_q = "";


// detect search or dir/file get request

$detect_search = 0;

/*
* an important block get directory from url
* if url  not contains dir then use $config["dir"]
*/
if (isset($_GET['dir'])) {
  $dir = $_GET['dir'];
}

if(isset($_GET['type']) && isset($_GET['q'])) {
	$detect_search = 1 ;

	$search_type = $_GET["type"];
	$search_q = $_GET["q"];
}

// above $_GETs handle all the get reuests

//exact location on server
$temp_dir=__DIR__."\\".$dir."\\";

$temp_dir = $dir;


$list = scandir($temp_dir);//scanning the directory
$list = array_slice($list, 2); // remove dots
$list = array_values(array_diff($list, $config["exclude"]));
//print_r($list);
$len = count($list); // get length list

$content = []; // all file and folder list

for($i=0; $i<$len; $i++) {

	$content_name = $list[$i]; // name assigned from file and folder name
	$current_content_location = $temp_dir.'/'.$content_name;  // we need to get content location also

	if(is_dir($current_content_location)){
		//bellow $fi to get file numbers
		$fi = new FilesystemIterator($current_content_location, FilesystemIterator::SKIP_DOTS);
		$content[]=[
			"id" => $i, // nothing but a uid temporary
		    "name" => $content_name, // name of the content
			"type" => "dir", // set type dir
			"loc" => $current_content_location, //location
			"details" => iterator_count($fi)." Files" //count files
		];
	}


	if(is_file($current_content_location)){

		$content[] = [
			"id"=> $i,
			"name"=> $content_name,
			"type"=> "file",
			"loc"=> $current_content_location,
			"details"=> $audio->details($current_content_location)
		];
	}
}



//header("Access-Control-Allow-Origin: *");
//header("Set-Cookie", "SameSite=None");
//print_r($content);

if($detect_search == 1){
   require "libs/dir-tree.php";
}
else if(isset($_GET["file"])){
	$actual_link = "http://$_SERVER[HTTP_HOST]";
	echo $config["current_url"].'/'.$_GET["file"];
} else {
	//header('Content-Type: application/json');
	echo json_encode($content);
}
