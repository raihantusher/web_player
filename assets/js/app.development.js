
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

                                    +'</audio></td>'

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
