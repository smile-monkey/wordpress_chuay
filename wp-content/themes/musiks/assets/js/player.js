+function ($) {
  $(document).ready(function(){
    var storage  = $.localStorage,
        //playlist = storage.get('playlist') || [],
        
		playlist = [],
		
		setting  = storage.get('setting') || {},
        bundle   = false;

    var player   = new jPlayerPlaylist(
      {
        jPlayer: "#jplayer",
        cssSelectorAncestor: "#jp_container"
      },
      playlist,
      {
        playlistOptions: {
          enableRemoveControls: true
        },
        loop: setting.repeat,
        swfPath: "/wp-content/themes/musiks/js/jPlayer",
        supplied: "webmv, webma, ogv, oga, m4v, m4a, mp3",
        smoothPlayBar: true,
        keyEnabled: true,
        audioFullScreen: false
      }
    );

    var ytPlayer = new YTPlayer({
        jPlayer: "#jplayer"
    }, player, setting);

    $('#jplayer').bind($.jPlayer.event.ready, function() {
      if( playlist.length && setting.currentIndex > -1 ){
          player.select(setting.currentIndex);
          
          // mobile does not have the autoplay feature
          $('html').hasClass('touch') && (setting.play = false);
          
          $(this).jPlayer("playHead", setting.percent);
          setting.play && $(this).jPlayer("play", setting.currentTime);
          setting.volume && $(this).jPlayer( "volume", setting.volume );
          setting.shuffle && player.shuffle(true);
          updateDisplay();
      }
      setupListener();
    });

    $(document).on('click', '#playlist .dropdown-menu', function(e){
        e.stopPropagation();
    });

    // setup Listener
    function setupListener(){
      $('#jplayer').bind($.jPlayer.event.timeupdate, function(event){
        setting.currentTime = event.jPlayer.status.currentTime;
        setting.duration = event.jPlayer.status.duration;
        setting.percent = event.jPlayer.status.currentPercentAbsolute;
        setting.currentIndex = player.current;
        setting.shuffle = player.shuffled;
        updateSetting();
		
		//var pppp = event.jPlayer.status.duration * event.jPlayer.status.currentPercentAbsolute;
		
		/* svnlabs */
		
/*		var pppp = Math.floor(setting.currentTime);
		
		console.log( pppp );
		
		jQuery(".my-new-list").find("span").removeClass('speaking');;
		
		if( jQuery("#IDs"+parseInt(pppp)).html() )
		{
		 jQuery("#IDs"+parseInt(pppp)).addClass('speaking');
		}*/
		
		/* svnlabs */
		
		
      })
      .bind($.jPlayer.event.pause, function(event){
        setting.play = false;
        updateSetting();
        updateDisplay();
        $('body').removeClass('is-seeking');
      })
      .bind($.jPlayer.event.play, function(){
        setting.play = true;
        updateSetting();
        updateDisplay();
      })
      .bind($.jPlayer.event.repeat, function(event){
        setting.repeat = event.jPlayer.options.loop;
        updateSetting();
      })
      .bind($.jPlayer.event.volumechange, function(event){
        setting.volume = event.jPlayer.options.volume;
        updateSetting();
      })
      .bind($.jPlayer.event.playing, function(event){
        $('body').removeClass('is-seeking');
      })
	  .bind($.jPlayer.event.timeupdate, function(event){
        //$('body').removeClass('is-seeking');
		
		/*svnlabs*/
		
		var pppp = Math.floor(setting.currentTime);
		
		//console.log( "current = " + pppp );
		
		//jQuery(".my-new-list").find("span").removeClass('speaking');
		
		//console.log( jQuery("#IDs"+parseInt(pppp)).html() );
		
		var procLine = jQuery("#IDs"+parseInt(pppp)).text();    //// for strip tags  text()
		
		//console.log( "O:" + procLine + "!" );
			
		//if( procLine!=null && procLine.length > 6 )  //// synchronize and highlight HTML text to audio  /////  ignore jump by <br>
		
		
		if( procLine!=null && procLine.length > 3 )
		{
			
		 //console.log("L: "+procLine.length);
			
		 jQuery(".my-new-list").find("div").removeClass('speaking');
		 
		 jQuery("#IDs"+parseInt(pppp)).addClass('speaking');
		 //jQuery("#IDs"+parseInt(pppp)).focus();
		 
		 //jQuery.scrollTo("#IDs"+parseInt(pppp));
		 
		 var prevsc = jQuery("#IDs"+parseInt(pppp));  //// svnlabs .... show highlight in middle 
		 
		 
		 //jQuery(".my-new-list").scrollTo("#IDs"+parseInt(pppp),{duration:'fast', offsetTop : '30px'});
		 //jQuery(".my-new-list").scrollTo(prevsc, {offset: {top:20px, left:0} });
		 
		 //jQuery(".my-new-list").scrollTo("#IDs"+parseInt(pppp),{offsetTop : '200px'});
		 
		 jQuery(".my-new-list").scrollTo(prevsc);
		 
		 //jQuery(".my-new-list").scrollTo(prevsc);
		 //jQuery("#IDs"+parseInt(pppp)).animate({marginTop :'50px'}, 500);
		 
		 //jQuery(".my-new-list").scrollTo(prevsc);  /// autoscroll autofocus 
		 
		 //console.log( jQuery(".jp-current-time").html() +" - "+jQuery(".jp-duration").html()  );
		 
		 //console.log( setting.currentTime +" - "+ setting.duration +" - "+ setting.percent );
		 
		 console.log( Math.round(setting.percent) );
		 
		 //ScrollDIV( (Math.round(setting.percent)) / 100 );
		 
//		 if(Math.round(setting.percent)>20)
	//	 ScrollDIV_1(Math.round(setting.percent)*10);
	
	
	//if ($myValue >= $minValue && $myValue <= $maxValue)


/* 11 may 2017 */
/*		 if(Math.round(setting.percent)>=20 && Math.round(setting.percent) <= 25)
		  ScrollDIV_1(200);
		  
		 if(Math.round(setting.percent)>=30 && Math.round(setting.percent) <= 35)
		  ScrollDIV_1(300); 
		  
		 if(Math.round(setting.percent)>=40  && Math.round(setting.percent) <= 45)
		  ScrollDIV_1(400);
		  
		 if(Math.round(setting.percent)>=50 && Math.round(setting.percent) <= 55)
		  ScrollDIV_1(500); 
		  
		 if(Math.round(setting.percent)>=60  && Math.round(setting.percent) <= 65)
		  ScrollDIV_1(600);
		  
		 if(Math.round(setting.percent)>=70  && Math.round(setting.percent) <= 75)
		  ScrollDIV_1(700);
		  
		 if(Math.round(setting.percent)>=80  && Math.round(setting.percent) <= 85)
		  ScrollDIV_1(1000);*/
		 
		  //jQuery("#IDsfocus").scrollTo("#IDs"+parseInt(pppp));
		  
		 
		 //doScroll( Math.round(setting.percent) / 100 );
		 
		 
		 //jQuery(".my-new-list").animate({scrollTop: jQuery("#IDs"+parseInt(pppp)).position().top}, 800, 'swing');  //svnlabs   /// Auto Focus
		}
		
 
		
		/*svnlabs*/
		
		
      })
      .bind($.jPlayer.event.waiting, function(event){
        $('body').addClass('is-seeking');
      })
      .bind($.jPlayer.event.seeking, function(event){
        $('body').addClass('is-seeking');
      })
      .bind($.jPlayer.event.seeked, function(event){
        $('body').removeClass('is-seeking');
      })
      .bind($.jPlayer.event.setmedia, function(){
        var media = $('#jplayer').find('audio, video');
        if(playlist[player.current] && media){
          media.attr('title', ( playlist[player.current]['title'].replace(/<(?:.|\n)*?>/gm, '') ) );
          media.attr('poster', ( playlist[player.current]['poster'] ) );
        }
      })
      ;

      // remove item from player gui
      $(document).on('click', '.jp-playlist-item-remove', function(e){
        window.setTimeout(updatePlaylist, 500);
      });
      
    }

    // bind click on play-me element.
    $(document).on('click', '.play-me', function(e){
												 
	//jQuery('#current_playlist').slideToggle(); //svnlabs	
	
	/* svnlabs   prevent making playlist */
	//jQuery('.jp-playlist-item-remove').click();
	
 
/* svnlabs   prevent making playlist  have only Current item  */
$('ul.dropdown-menu li').each(function (i) {

        //var index = $(this).index();
        //var text = $(this).text();
        //var value = $(this).attr('value');
        //alert('Index is: ' + index + ' and text is ' + text + ' and Value ' + value);
		
		if( $(this).attr('class') == "jp-playlist-current" )
		{
			console.log( "In here" );
						
		}
		else
		{
		  //jQuery('.jp-playlist-item-remove').click();	
		  $(this).remove()
		  console.log( "Out here" );
		  
		}
		
		  
		
    });

								
												 
      e.stopPropagation();
      var id = $(this).attr("data-id");
	  
	  jQuery('#home-pg-data-id').attr("data-id", id);  /// set last played id
	  
	  //alert(id);
	  
	  // svnlabs
	  
	  if (!Date.now) {
        Date.now = function() { return new Date().getTime(); };
      }
	  
	  var currentTime = Date.now() || +new Date();
	  
	  jQuery(".my-new-list").html( "" );
	  
	    var noCache = Date();
	  
		//jQuery.getJSON( lycUrl + "/wp-content/plugins/easy-digital-downloads/assets/lyrics/"+id+".json?rand="+currentTime, { "noCache": noCache }, function( data ) {
		
		jQuery.getJSON( lycUrl + "/lyrics/"+id+".json?rand="+currentTime, function( data ) {
		var items = [];
		
		
		var datalen = Object.keys(data).length;
		//var dataloop = 0;
		//console.log(  data );
		
		jQuery.each( data, function( key, val ) {
		 
		 
		 //console.log( key + "," + val );							
         //items.push( '<span id="'+ key +'"  data-start="'+ data[key][1] +'" data-stop="400">'+ data[key][0] +'</span><br>' );
		 //items.push( '<span id="IDs'+ data[key][1] +'" name="IDs'+ data[key][1] +'"  data-start="'+ data[key][1] +'" data-stop="400">'+ data[key][0]+'<br>'+data[key][4] +'</span><br>' );
		 
		 var xbr = '';
		 
		 //console.log( "4=>" + data[key][4] );
		 
		 if(data[key][4]=="" || data[key][4]==null)
		 {
		  var xbr = '';
		  var key4 = '';
		 }
		 else
		 {
		  var xbr = '<br>';
		  var key4 = '<span class="lyrics_text_color2">' + data[key][4] + '</span>';  //// 15 may 2017  svnlabs
		  //var key4 = data[key][4];
		 }
		 
		 items.push( '<div class="lyrics_text_color" id="IDs'+ data[key][1] +'" name="IDs'+ data[key][1] +'"  data-start="'+ data[key][1] +'" data-stop="400">'+ data[key][0] + xbr + key4 +'</div>' );
		 
		 //if( Math.round(datalen / 2) == dataloop)
		  //items.push( '<span id="IDsfocus"></span>' );
		 
		 //dataloop++;
		 
		//items.push( "<li id='" + key + "'>" + val + "</li>" );
		});
		
		var lyrics_text = items.join( " " );
		

		jQuery(".my-new-list").html( lyrics_text + "<br>" );
		
		//console.log( lyrics_text );
		
		/*$( "<ul/>", {
		"class": "my-new-list",
		html: items.join( "" )
		}).appendTo( "body" );*/
		
		});
	  
	  // svnlabs
	  
	  
	  
      var i = inObj(id, playlist);
      if( i == -1){
        $.ajax({
           type : "get",
           dataType : "json",
           url : ajax_object.ajaxurl,
           data : {action: "get_media", id : id},
           async: false,
           success: function(obj) {
              if(obj.length == 1){
                player.add( obj[0] );
                player.play(-1);
                updatePlaylist();
              }else if(obj.length > 1){
                player.setPlaylist(obj);
                player.play(0);
                updatePlaylist();
              }
           }
        });
      }else{
        if( player.current == i ){
          setting.play ? player.pause() : player.play();
        } else {
          player.play( i );
        }
      }
    });

    // update ui
    function updateDisplay(){
      $('.play-me').removeClass('active');
      if( playlist[player.current] ){
        var current = $('a[data-id='+playlist[player.current]['id']+']'+', '+'a[data-id='+playlist[player.current]['ids']+']');
        setting.play ? current.addClass('active') : current.removeClass('active');
      }
    }

    $( document ).on( "pjaxEnd", function() {
      updateDisplay();
    });

    // update setting 
    function updateSetting(){
      storage.set( 'setting', setting );
    }

    // update playlist
    function updatePlaylist(){
      updateDisplay();
      playlist = player.playlist;
      storage.set( 'playlist', playlist );
    }

    // check exist
    function inObj(id, list) {
        var i;
        for (i = 0; i < list.length; i++) {
            if ( (list[i]['id'] == id) || (list[i]['ids'] == id) ) {
                return i;
            }
        }
        return -1;
    }

  });
}(jQuery);


function ScrollDIV(per)
{

var contentTop = jQuery('.my-new-list').offset().top;
contentTop = parseInt(contentTop - (contentTop * per)); 

console.log( contentTop );

jQuery('.my-new-list').animate({ scrollTop: contentTop }, 1000);
  //return false;

}


function ScrollDIV_1(per)
{

jQuery('.my-new-list').animate({ scrollTop: per }, 1000);
  //return false;

}



function doScroll(per){
	console.log( per );
   jQuery('.my-new-list').scrollTop(jQuery('.my-new-list').offset().top - per);
}


function doScroll_1(per){
	console.log( per );
   jQuery('.my-new-list').scrollTop(jQuery('.my-new-list').scrollTop() + per);
}
