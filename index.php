<?php

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

require "config.php";

// empty breadcrumbs or declaration of breadcurmbs
$breadcrumbs=[];
// empty dirs or declr of dirs
$dirs=[];

// if file exists in url then
if (isset($_GET['file'])){
   $dirs=explode('/',$_GET['file']);
} // if dir exist in url
else if(isset($_GET['dir'])){
  $dirs=explode('/',$_GET['dir']);
//  $dirs=array_pop($dirs);
}else{
  // if noting is found then default config
  $dirs=[$config["dir"]];
}


$len = count($dirs);

$url=$config["dir"];

  $breadcrumbs[]=[
    "url"=>$config["dir"],
    "name"=>"Home",
    "status"=>""
  ];

    for ($i=1;$i<$len;$i++){

      $url.='/'.$dirs[$i];


      $breadcrumbs[]=[
        "url"=>$url,
        "name"=>$dirs[$i],
        "status"=>""
      ];
    }




$breadcrumbs[$len-1]["status"]="active";

/*
if($dirs[0]==".." || $dirs[1]=="..")
  die("Suspicious activity!!");
  */
?>





<!doctype html>
<html lang="en">
  <head>
    <!-- Required meta tags -->
    <link rel="shortcut icon" href="http://www.alhidaaya.com/sw/files/favicon.ico" type="image/vnd.microsoft.icon" />
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

        <title><?=(($len>1)?$dirs[$len-1]:'Home')?> | Alhidaaya.Com</title>

     <!-- Bootstrap CSS -->
    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="./assets/css/bootstrap.min.css" integrity="sha384-9aIt2nRpC12Uk9gS9baDl411NQApFmC26EwAOH8WgZl5MYYxFfc+NcPb1dKGj7Sk" crossorigin="anonymous">

    <link rel="stylesheet" href="./assets/css/plyr.css" />

    <!-- Social Media Metadata -->
   <meta property="og:title" content="<?=(($len>1)?$dirs[$len-1]:'Home')?>" />
   <meta property="og:type" content="article" />

   <meta property="og:image" content="http://www.alhidaaya.com/sw/sites/all/themes/corporateclean/images/logo.png" />


    <style>
    @import url("https://fonts.googleapis.com/css2?family=Ubuntu:wght@500&display=swap");
        body {
          font-family: "Ubuntu", sans-serif;
          background-color: #FEFFFF;
        }

        @media screen and (max-width: 480px) {
          body {
            font-size: 15px;
          }

          input[type=text] {
            width: 100px;
          }

          #srcType {
            width: 100px !important;
          }

          #srcBtn {
            margin-top: -16px !important;
          }
        }
    </style>

  </head>
  <body>



<div class="container">
        <div class="row mt-5">
          <div class="col-12 text-center">
           <a href="<?=$config['current_url']?>"> <img  class="img-fluid"src="http://www.alhidaaya.com/sw/sites/all/themes/corporateclean/images/logo.png" /></a>
          </div>
        </div>
        <hr/>
        <div class="row">

            <!-- search form finished -->
            <div class="col-12 my-3">

                  <form class="form-inline d-flex justify-content-end">
                      <div class="form-group">
                        <label for="">Search:</label>
                      </div>
                      <div class="form-group">
                        <select class="form-control" id="srcType" >
                          <option value="file">file</option>
                          <option value="folder">folder</option>
                          <option value="filefolder">file + folder</option>
                        </select>
                      </div>

                      <div class="form-group ml-1">
                        <input class="form-control" type="text" id="q" name="">
                      </div>
                      <button class="btn btn-primary ml-1" id="srcBtn">Search </button>
                </form>
            </div><!-- search form finished -->

            <!-- breadcrumbs-->
            <div class="col-12">
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb mr-auto" id="bcrumb">
                            <?php foreach($breadcrumbs as $bs): ?>
                                <?php if($bs["status"]=="active"){?>
                                <li class="breadcrumb-item <?=$bs['status']?>" aria-current="page"><?=$bs['name']?></li>
                                <?php }else { ?>
                                    <li class="breadcrumb-item" ><a href="?dir=<?=$bs['url']?>"><?=$bs['name']?></a></li>
                            <?php }

                             endforeach;?>
                   </ol>
                </nav>
            </div>

            <!-- redirect according to folder list -->
            <div class="col-12 mb-2">

                      <form class="form-inline d-flex justify-content-start">
                          <div class="form-group">
                              <select class="form-control" id="folder_selection">
                                  <option value="#" >---Select---</option>
                              </select>
                          </div>
                      </form>
            </div>
            <!-- redirect according to folder list finished -->

        </div>

        <div class="row justify-content-center">
                  <div class="col-8 col-md-6">
                  <!-- Go to www.addthis.com/dashboard to customize your tools -->
                        <div class="addthis_inline_share_toolbox"></div>
                  </div>

                  <div class="col-12">
                      <table class="table table-striped" id="list">
                          <tbody ></tbody>
                      </table>
                  </div>
        </div>

</div> <!-- container  -->




    <!-- Optional JavaScript -->
    <!-- jQuery first, then Popper.js, then Bootstrap JS -->
    <script src="./assets/js/jquery.min.js"></script>
    <script src="./assets/js/popper.min.js"></script>
    <script src="./assets/js/bootstrap.min.js" ></script>
    <script src="./assets/js/lodash.min.js"></script>

    <script src="./assets/js/plyr.js"></script>
    <script >

    (function($) {
    $.QueryString = (function(paramsArray) {
        let params = {};

        for (let i = 0; i < paramsArray.length; ++i)
        {
            let param = paramsArray[i]
                .split('=', 2);

            if (param.length !== 2)
                continue;

            params[param[0]] = decodeURIComponent(param[1].replace(/\+/g, " "));
        }

        return params;
    })(window.location.search.substr(1).split('&'))
})(jQuery);



$( document ).ready(function() {
  // Handler for .ready() called.
  // get url query param as object
  var qs = $.QueryString;
  //console.log(qs);
  if(qs["q"]){
    $("#bcrumb").empty();
    $("#bcrumb").html(
      '<li class="breadcrumb-item" ><a href="<?=$config["current_url"]?>">Home</a></li>'
    );

  }


  $('#folder_selection').change(function() {
                // set the window's location property to the value of the option the user has selected
                window.location =$(this).val();
    });


  $("#srcBtn").click(function(e){
    e.preventDefault();
     // get selection value
      var selectedType = $("#srcType").children("option:selected").val();
      //console.log(selectedType);
      var q=$("#q").val();
      //console.log(q);
      var param='?type='+selectedType+'&q='+q;
      window.location=param;

  });

  function isValidUrl(string) {
    try {
      new URL(string);
    } catch (_) {
      return false;
    }

    return true;
  }



   $.get("api.php",qs)

    .done(function(response){
      // contents are combination for folder and file sorted
       let contents=[];

       // hold list of files
       let files=[];

       //hold list of folders
       let folders=[];

          //Try to get tbody first with jquery children. works faster!
          var tbody = $('#list').children('tbody');

        //Then if no tbody just select your table
        var table = tbody.length ? tbody : $('#list');

        if (isValidUrl(response)){
                  data=response;
                 // console.log(data)

                  table.append(
                              '<tr>'
                              + '<td><audio id="player" controls>'
                                    +'<source src="'+data+'" type="audio/mp3" />'

                                    +'</audio>'
                              +'</td>'

                    );
                    const player = new Plyr('#player',{
                       autoplay:true,
                       resetOnEnd:true
                    });

        }
        else{
          // start
                  data=JSON.parse(response);
                  data=_.uniqBy(data,'loc');



                  // bellow loops separate files and folders
                  for(i=0;i<data.length;i++){

                      if (data[i].type === "file"){

                        //console.log(data[i]);
                        files.push(data[i]);

                      }else if(data[i].type === "dir"){

                          folders.push(data[i]);
                          $("#folder_selection").append(   $('<option></option>').val('?dir='+data[i].loc).html(data[i].name) );

                      }
                    }

                  // combile file and folder list and assign into content
                  contents=[...files,...folders];
                  //contents=_.uniqBy(contents,'loc');

                  if(contents.length==0){
                    table.append(
                                '<tr>'
                                + '<td><h6 class="text-center"> No Result Found </h6> </td>'
                                +'</tr>');
                  }

                for(i=0;i<contents.length; i++){
                  //console.log(data[i]);
                    //Add row

                    if(qs['q']==="undefined")
                        contents[i].details="";

                    if (contents[i].type==="dir"){

                        table.append(
                              '<tr>'
                              +'<td><img src="./assets/img/folder-icon.png"/> '
                              +'<a href="'+'?dir='+contents[i].loc+'">'+contents[i].name+' </a></td>'
                              +'<td class="float-right">'+contents[i].details+'</td>'
                              +'</tr>');
                    }else
                    {
                      table.append(
                              '<tr>'
                              +'<td><img src="./assets/img/speaker-16.png"/> '
                              +'<a href="'+'?file='+contents[i].loc+'">'+contents[i].name+' </a></td>'
                              +'<td class="float-right">'+contents[i].details+'</td>'
                              +'</tr>');
                    }

                }


            //end

          }// response type logic finished here
      }); // get here
  });

    </script>



  <script type="text/javascript" src="//s7.addthis.com/js/300/addthis_widget.js#pubid=ra-4f5a3f82558e9f0e"></script>
  </body>
</html>
