<!-- 
    Pebble Workout timer. By Fernando Trujano
    trujano@mit.edu
    v2.0. Lets users see and edit workouts online. 
--> 

<html>
    <head>
        <link rel="stylesheet" href="http://code.jquery.com/mobile/1.4.3/jquery.mobile-1.4.3.min.css" />
        <script src="http://code.jquery.com/jquery-1.9.1.min.js"></script>
        <script src="http://code.jquery.com/mobile/1.4.3/jquery.mobile-1.4.3.min.js"></script>
        <style>
            .error{ 
                text-align: center; 
                background-color: rgba(255, 0,0,.2);
                padding: 3px;
                border: 1px solid red;
                margin-bottom: 5px;
            }

            a:link {
                text-decoration: none;
             }

             .totaltime { 
                font-weight: 100;
             }

             .delete{ 
                background-color: #FFA6A6!important;
             }

            .success{ 
                background-color: #B5FDC6!important;
                padding: 3px;
                border: 1px solid #0A8000;
                margin-bottom: 5px;
             }

             .info{ 
                background-color: #D3DEFF!important;
                padding: 3px;
                border: 1px solid #0040FF;
                margin-bottom: 5px;
             }

             .add-move-to-existing,.delete-workout{ 
                padding:0;
                border:0;
                margin:0;
             }

             .li-btn { 
                margin:0;
             }

         </style> 
    </head>

    <body>
        <div data-role="page" id="main">

            <div data-role="content"> 
                <div class="error" id="update" hidden><strong> There is an update for this app available on the Pebble appstore. </strong> </div>
                <h2>Manage Workouts <a href="#info" data-transition="flip"><button data-inline="true" data-mini="true" data-icon="info"> FAQ </button></a></h2> 
                <div id="loading" class="info">Loading your workouts, please wait. </div> 
                <div data-role="collapsibleset" data-content-theme="a" data-iconpos="left" data-collapsed-icon="carat-d" data-expanded-icon="carat-u" id="workout-list"> 
                </div> 
                <a href="#add"><button data-inline="true" data-mini="true" data-icon="plus"> Add Workout </button></a> 
                <hr> 
                <fieldset class="ui-grid-a">
                    <div class="ui-block-a" >  <a href="#" data-rel="back"><button id = "cancel-btn" data-theme="d" data-icon="delete" > Cancel </button> </a> </div> 
                    <div class="ui-block-b" > <button id = "save-btn" class="ui-btn-active" data-icon="check"> Save</button>  </div> 
                </fieldset>
                <div class="error" id="main-error" hidden> Please add at least one workout to save </div>
                <div class="success" id="success" hidden> Changes saved! Please open this on your phone (from the pebble app) and click save to appy changes on Pebble </div> 
                <div class="info" id="bitly">Go to this link to make make changes from your computer! <br> <span id="bitly-link"></span>  </div> 
            </div> 

        </div> 

        <!-- FAQ Page --> 
        <style> 
            .ui-listview>li p, .ui-listview>.ui-li-static, .ui-listview>.ui-li-divider, .ui-listview>li>a.ui-btn{ 
                overflow: visible;
                white-space: normal;
            }
        </style>
        <div data-role="page" id="info">
            <div data-role="content"> 
                <h3> <a href="#main" data-transition="flip"><button data-inline="true" data-mini="true" data-icon="back"> Back</button></a> Frequently Asked Questions <small> Custom Workout Timer v2.0</small> </h1> 
                <ul data-role="listview"> 
                    <li> How do I add a workout? 
                        <p> On the Pebble app, find this app and click on the settings icon, then click on the "Add a workout button". Type in a workout title and add moves by using the sliders and textbox. You can add as many moves as you want.  When you are done, click on Add workout. Then click the blue "save" button. The workout should appear on your Pebble. </p>  </li> 
                    <li> How do I delete a workout? 
                        <p> Expand the workout you want to delete on the Online Workout Manager. Then click on the red "delete" button.  </p>  </li> 
                    <li>  Can I add a move to an exisiting workout?
                        <p> Yes, after expanding the workout click on the add move button and fill in the info.</p>  </li>
                    <li>  Can I edit moves after I add them? 
                        <p> Yes, after expanding the workout click on the move you'd like to edit. From there, you can change it's name, time or delete it. </p>  </li> 
                    <li>  Phone keyboards suck, can I do all of this from my computer? 
                        <p> Yes, you can manage all of your workouts from your computer! Go back to the main page, and there should be a link at the bottom. Go to this link on your computer, make the necesary changes and save. To apply the changes to the Pebble, simply go back to the workout manager from your phone and click save.  </p>  </li> 
                    <li>  How do I pause/resume a timer?
                        <p> While a timer is running, simply press the middle button to pause or resume. </p>  </li> 
                     <li> Can I suggest a new feature?
                        <p> Yes, please. That would be awesome. If you have any ideas to improve this app, or ideas for another app please email me at <a href="mailto:trujano.fernando@gmail.com?subject=App Suggestion"> trujano.fernando@gmail.com </a>  </p>  </li>                
                    <li> Something went wrong!
                        <p> That is not a question. If you think you found a bug, please <a href="mailto:trujano.fernando@gmail.com?subject=Custom Workout Timer bug!"> email </a> me the details. Check below for known issues and how to fix them.   </p>  </li>
                    <li> Who are you? 
                        <p> Hi, I am <a href="http://www.fernandotrujano.com"> Fernando Trujano </a>, a happy Pebble owner and college sophomore. If you like this app please give it a "heart" on the Pebble appstore.I smile everytime I see the count go up :)  </p>  </li> 
                </ul> 
                <hr> 
                <h2> Known Issues </h2> 
                <ul data-role="listview"> 
                    <li> Workout name changes to a number
                        <p><strong> Temporary Fix: </strong> Go to the workout manager and save your workouts back to Pebble. </p> </li> 
                    <li> Workout does not start
                        <p><strong> Temporary Fix: </strong> Wait a couple of seconds and try again. </p> </li> 
                </ul> 
            </div> 
        </div> 

        <!-- Add workout page --> 
        <div data-role="page" id="add">

            <div data-role="content"> 

                <h2>Add a custom timer </h2>  
            
                <form id="settings"> 
                        <label for="title">  Workout Title:</label>       <input type="text" required name="title" id="title" data-clear-btn="true"> <br>  
                        <hr>   
                        <label for="move-name">Add Move: </label> <input type="text" id="move-name" data-clear-btn="true"> 
                        <label for="minute-slider" class="">Minutes</label>
                        <input type="range" name="minute-slider" id="minute-slider" data-highlight="true" min="0" max="15" value="0" step="1">
                        <label for="second-slider" class="">Seconds</label>
                        <input type="range" name="second-slider" id="second-slider" data-highlight="true" min="0" max="59" value="0" step="1">
                </form> 

                <button  id="add-move" data-icon="plus"> Add Move</button> <br> 
                <div class="error" id="error1" hidden>Please enter a move title and time</div>
                <hr>

                <fieldset class="ui-grid-a">
                    <div class="ui-block-a" > <div id="titletext"> </div>  </div> 
                    <div class="ui-block-b" ><h3> <small class="totaltime" id="add-total-time"> </small> </h3>  </div> 
                </fieldset>

                
                <ol id="move-list" data-role="listview"> </ol>
                <br> 

                <fieldset class="ui-grid-a">
                    <div class="ui-block-a" > <a href="#main"> <button id = "add-workout-cancel-btn" data-theme="d" data-icon="delete"> Cancel </button> </a> </div> 
                    <div class="ui-block-b" > <button id = "add-workout-save-btn" class="ui-btn-active" data-icon="check"> Save</button> </div> 
                </fieldset>

                <div class="error" id="error-add-workout" hidden>Please enter a workout title and add at least one move</div>
            </div> 
        </div> <!-- End Add workout page --> 

        <!-- Edit Workout popup --> 
        <div data-role="popup" id="edit-popup" data-theme="a" class="ui-corner-all ui-content">
            <h3> <span id="popup-text"> </span> <button id = "edit-delete-btn" data-icon="delete" data-inline="true" data-mini="true" class="delete"> Delete Move</button> </h3> 
            <form> 

                <label>Name</label> 
                <input type="text" data-clear-btn="true" name="edit-title" id="edit-title" value="5">
                <label for="slider-2">Minutes</label> 
                <input type="range"  class="slider" id="edit-minute-slider" data-highlight="true" min="0" max="60" value="5"> 
                <label for="slider-2">Seconds</label>
                <input type="range" class="slider" id="edit-second-slider" data-highlight="true" min="0" max="59" value="5"> 
            </form> 
            
            <fieldset class="ui-grid-a">
                <div class="ui-block-a" >  <button id = "edit-cancel-btn" data-theme="d" data-icon="delete" > Cancel </button> </div> 
                <div class="ui-block-b" > <button id = "edit-save-btn" class="ui-btn-active" data-icon="check"> Done</button>  </div> 
            </fieldset>
            <div class = "error" id="popup-error" hidden> Please enter a valid title and time </div>   
        </div> <!-- End Edit Workout Popup --> 

    </body>

    <script> 
    //Add workout page scripts. 

        $(document).ready(function(){
            function resetAddWorkoutForm(){ 
                console.log("Resetting Add Workout Form...")
                //Reset Form
                workout = {};
                workout.moves = [];
                workout.title = ""; 
                $('#add-total-time').html('');
                $("#titletext").html("");
                $("#title").val("");
                $("#move-name").val("");
                $("#minute-slider").val("0"); 
                $("#minute-slider").slider("refresh"); 
                $("#second-slider").val("0"); 
                $("#second-slider").slider("refresh"); 
                $('#move-list').html('');
                $('#move-list').listview('refresh');
                $("#error1").fadeOut();
                $("#error-add-workout").fadeOut(); 

             }
            var workout = {};
            workout.moves= []; 
            var totalsecs = 0; 


            $("#title").change(function(){ 
                $("#titletext").html("<h3>" + $("#title").val()+ "</h3>")
            })

            $("#add-move").click(function(){       
                var move = $("#move-name").val();
                var mins = $("#minute-slider").val(); 
                var secs = $("#second-slider").val(); 
                console.log(move)
                if (move != "" && (mins > 0 || secs >0)) {          
                    var time = (parseInt(mins)*60)+parseInt(secs)
                    console.log(time); 
   
                    totalsecs += time; 
                    var timetext = timeText(time);
                    $("#move-list").append("<li>" + move +' for ' + timetext); 
                    console.log(workout); 
                    workout.moves.push([move, time]) // Add to workout

                    console.log("appended " + title)
                    console.log(workout)

                    $('#add-total-time').html("Total Time: "+timeText(totalsecs));
                    //Reset Values
                    $("#move-name").val("");
                    $("#minute-slider").val("0"); 
                    $("#minute-slider").slider("refresh"); 
                    $("#second-slider").val("0"); 
                    $("#second-slider").slider("refresh"); 
                    $('#move-list').listview('refresh');
                    $("#error1").fadeOut()
                }

                else { 
                    $("#error1").fadeIn()
                }              
            })
          
            $("#add-workout-save-btn").click(function() {

                var title = $("#title").val()
                while (title.slice(-1)==" ") title = title.slice(0,-1) //Don't allow trailing spaces

                var same_title = false; 
                $.each(json.workouts, function(index, workout){ 
                    if (title == workout.title) same_title=true
                })

                //Form Validation
                if (title == "") { //No title entered
                    $("#error-add-workout").fadeIn()
                    $("#error-add-workout").html("Please enter a title")
                } 

                else if (same_title){ //Title already exists
                    $("#error-add-workout").html("Workout title already exists. Please change.")
                    $("#error-add-workout").fadeIn()
                    } 

                else if (title.indexOf(',') > 0){ //Valid title name (no commas)
                    $("#error-add-workout").html("Plase don't use commas in your title");
                    $("#error-add-workout").fadeIn(); 
                }

                else if ($("#move-list").html() == " "){  //No moves entered
                    $("#error-add-workout").html('Please enter at least one move');
                    $("#error-add-workout").fadeIn();
                } 

                else {  //Looks good
                    totalsecs = 0;  
                    workout.title = title
                    console.log(workout); 
                    json.workouts.push(workout)
                    populateHTML(); 
                    window.location = '#main'
                    resetAddWorkoutForm(); 
                }   

            })

            $("#add-workout-cancel-btn").click(function(){ 
                resetAddWorkoutForm(); 
            })
        })

    </script> 

    <script>
        var json = {}
        function timeText(time){ 
            secs = time%60; 
            mins = (time-secs)/60; 
        //Format time to text nicely b/c grammar Nazis
            var mintext = "minutes";
            if (mins == 1) mintext = "minute";
            var sectext = "seconds";
            if (secs == 1) sectext = "second";
            if (mins > 0){ 
                    if (secs > 0) return  mins + " " + mintext + " and " + secs + " " + sectext +"."
                    else return  mins + " " + mintext +"."
                }
                else return secs + " " + sectext + "."
            return "Error"            
        }

        function populateHTML() { 
        
            $("#workout-list").html(''); //Reset div. Yes, I am redrawing everytime, could be optimized. 
            $.each(json.workouts, function(i, workout) {  //Add workouts
                    var maintotaltime = 0; 
                    var workout_title = workout.title.replace(/\s+/g, '-');
                    $.each(workout.moves, function(j, move){ maintotaltime += parseInt(move[1]) }) //Please optimize
                    $("#workout-list").append('<div data-role="collapsible" id="workoutcollapsible" > <h4>'+ workout.title + ' <span class="totaltime" style="float:right" id="total-time">'+ timeText(maintotaltime)+' </span>  </h4> <ul class="move-list" id="' + workout_title+'" data-role="listview">'); 
                    $.each(workout.moves, function(j, move){  //Add moves
                        $("#"+workout_title).append('<li data-icon="gear"> <a href="#edit-popup" id="'+ i + ','+j+ '" class="editlink" data-rel="popup" data-position-to="window" data-role="button"  data-transition="pop">'+move[0]+' <small class="totaltile"> for ' + timeText(parseInt(move[1]))+'</small>   <p class="ui-li-aside">Edit Move</p> </a> </li> ');
                        console.log(maintotaltime); 
                    })
                        $("#"+workout_title).append('<li><fieldset class="ui-grid-a"><div class="ui-block-a"><a href="#edit-popup" id="'+ i+ '" class="add-move-to-existing" data-rel="popup" data-position-to="window" data-role="button"  data-transition="pop"><button class="li-btn" data-mini="true" data-icon="plus" >Add Move</button></a> </div><div class="ui-block-b"><a href="#" id="'+ i+ '" class="delete-workout"><button data-mini="true" class="delete li-btn" data-icon="delete">Delete Workout</button></a> </div></fieldset></li>  ');
                        
                     $("#workout-list").append('</ul> </div>')//.trigger('create');  

                })
                //Refresh
                $('#workout-list').collapsibleset().collapsibleset('refresh')//.trigger('create'); ;

                $('.move-list').listview().listview('refresh').trigger('create'); ; 

        }



    //Main page scripts. 
        var parser = document.createElement('a');
        parser.href = document.URL; 
        var urlinfo = parser.search.slice(6).split(',') //Split url into array of info. Current Format [token,version]
        var token = urlinfo[0]; //Unique to Pebble account and this app!
        var version = urlinfo[1]; 
        //Open user file 
        var jsonstring = false

        $(document).ready(function(){ 
            // Array Remove - By John Resig (MIT Licensed)
            Array.prototype.remove = function(from, to) {
              var rest = this.slice((to || from) + 1 || this.length);
              this.length = from < 0 ? this.length + from : from;
              return this.push.apply(this, rest);
            };

            if (!version) document.location = "http://www.fernandotrujano.com/pebble/ver1.html";
            
            //Initialize Popup
            $( "#edit-popup" ).enhanceWithin().popup();
            $('.slider').slider();

            $.ajax({
              url:'read.php?token='+ token,
              complete: function (response) {
                if (response.responseText){
                    console.log("got a response! "); 
                    jsonstring = response.responseText.replace(/\\"/g, '"'); //Unescape escaped ""
                    json = $.parseJSON(jsonstring); 
                    $("#loading").hide(); 
                    //Populate HTML
                    populateHTML();  
                }
              },

              error: function () {
                  console.log("Something went wrong. Can't connect")
              },
            });


            $(document).on('click', '.editlink', function(){
                
                $("#edit-delete-btn").show(); 
                $( "#edit-popup" ).enhanceWithin().popup();
                $("#popup-text").html('<small> Edit Move </small> ')

                console.log("click"); 
                var id = this.id.replace(/id/,'').split(','); 
                console.log(id); 
                var move = json.workouts[id[0]].moves[id[1]]   

                $("#edit-title").val(move[0]); 
                $("#edit-minute-slider").val((move[1] - move[1]%60)/60); 
                $("#edit-second-slider").val(move[1]%60); 

                $("#edit-second-slider").slider("refresh"); 
                $("#edit-minute-slider").slider("refresh"); 


                $("#edit-save-btn").val(id[0]+','+id[1])
            })

            $(document).on('click', '.add-move-to-existing', function(){
               
                $("#edit-delete-btn").hide(); 
                $( "#edit-popup" ).enhanceWithin().popup();
                $("#popup-text").html('<small>Add Move</small>');

                console.log("click. Trying to add move"); 
                var id = this.id.replace(/id/,'')
                console.log(id); 
                $("#edit-title").val(''); 
                $("#edit-minute-slider").val(0); 
                $("#edit-second-slider").val(0); 
                $("#edit-second-slider").slider("refresh"); 
                $("#edit-minute-slider").slider("refresh"); 
                $("#edit-save-btn").val(id+','+json.workouts[id].moves.length)//Add new move to workout id[0]
            })

            $(document).on('click', '.delete-workout', function(){
                var id = this.id.replace(/id/,'')
                json.workouts.remove(parseInt(id)); 
                console.log("Removing workout with id: "+id)
                populateHTML(); 
            })


            $("#save-btn").click(function() { 
                if (json.workouts.length < 1) $("#main-error").fadeIn();

                else {
                    //Submit json file to server
                    $.ajax({
                      type: "POST",
                      url: 'save.php?token='+token,
                      data: {"data": JSON.stringify(json)},
                      success: function(data, textStatus, jqXHR){
                        console.log("Server received message"); 
                        console.log(textStatus, data, jqXHR); 
                        $("#success").fadeIn();
                        $("#main-error").hide();
                        document.location = "pebblejs://close#" +encodeURIComponent(JSON.stringify(json)) ; 
                        },

                      complete: function(){console.log("Send message to PHP")},
                      error: function(jqXHR, textStatus, errorThrown) {
                        console.log("Error: you are weak. ")
                        console.log(textStatus, errorThrown, jqXHR.responseText);
                        }
                    });

                    console.log("ajax funciton called")
                    console.log(json); 
                }               
            })

            $("#edit-save-btn").click(function(){ 
                //Edit json
                var id = $(this).val().split(','); 
                var title = $("#edit-title").val()
                console.log(title); 
                var mins = $("#edit-minute-slider").val(); 
                var secs = $("#edit-second-slider").val(); 
                if (title != "" && (mins > 0 || secs >0)) {          
                    var time = (parseInt(mins)*60)+parseInt(secs)       
                    json.workouts[id[0]].moves[id[1]] = [title , time]; 
                    console.log("Edited");   
                    populateHTML(); 
                    console.log(id)
                    $("#popup-error").fadeOut(); 
                    $("#edit-popup").popup('close');    
                }
                else $("#popup-error").fadeIn(); 
            })

            $("#edit-cancel-btn").click(function(){ 
                $("#popup-error").fadeOut(); 
                $("#edit-popup").popup('close');   
            })



            $("#edit-delete-btn").click(function(){ 
                var id = $("#edit-save-btn").val().split(',');
                //Deleting the last move --> Delete workout 
                if (json.workouts[id[0]].moves.length == 1)  json.workouts.remove(parseInt(id[0])); 
                else json.workouts[id[0]].moves.remove(parseInt(id[1])); 

                populateHTML(); 
                $("#edit-popup").popup('close');   
            })

            $("#cancel-btn").click(function() { 
                document.location = "pebblejs://close#" 
            }) 

            $.getJSON(
                "http://api.bitly.com/v3/shorten?callback=?", 
                { 
                    "format": "json",
                    "apiKey": "R_ceb591091d2c79f818d71577d992ae28",  //Anyone can see this from "Network" so no need to hide it :(. 
                    "login": "fertogo",
                    "longUrl": $(location).attr('href')
                },
                function(response)
                {
                    $("#bitly-link").html('<a href="'+response.data.url+'"">'+response.data.url+'</a>'); 
                }
    );
        })

    </script> 
</html>