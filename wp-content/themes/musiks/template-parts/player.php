<?php if( get_theme_mod( 'hide-player' ) == 0 ){ ?>

<?php /*?>svnlabs<?php */?>
<link rel='stylesheet' id='musik-style-css'  href='<?php echo get_template_directory_uri(); ?>/lyrics.css?ver=4.7&rand=<?php echo rand(100, 3000); ?>' type='text/css' media='all' /> 

<footer class="footer nav-bar-fixed-bottom <?php echo esc_attr( get_theme_mod( 'player-bg-color', 'bg-info' ) ); ?>">
  <div id="jp_container">
    <div class="jp-type-single">
      <div  id="yt_player"></div>
    </div>
    <div class="jp-type-playlist">
      <div id="jplayer" class="jp-jplayer hide"></div>
      <div class="jp-gui">
        <div class="jp-video-play hide">
          <a class="jp-video-play-icon"><?php esc_html_e( 'play', 'musik' ); ?></a>
        </div>
        <div class="jp-interface">
          <div class="jp-controls">
            <?php /*?><div><a class="jp-previous"><i class="icon-control-rewind i-lg"></i></a></div><?php */  ?> <?php /*svnlabs*/ ?>
            <div>
              <a class="jp-play play-me" id="home-pg-data-id" data-id="<?php echo esc_attr( $post->ID ); ?>" <?php /*?>onClick="jQuery('#current_playlist').slideToggle();"<?php */?>><i class="icon-control-play i-2x"></i></a>  <?php /*?>svnlabs<?php */?>
              <a class="jp-pause hid" <?php /*?>onClick="jQuery('#current_playlist').slideToggle();"<?php */?>><i class="icon-control-pause i-2x"></i></a>
            </div>
            <?php /*?><div><a class="jp-next"><i class="icon-control-forward i-lg"></i></a></div><?php */ ?> <?php /*svnlabs*/ ?>
            <div class="hide"><a class="jp-stop"><i class="fa fa-stop"></i></a></div>
            <div style="display:none;"><a class="" data-toggle="dropdown" data-target="#playlist"><i class="icon-list"></i></a></div>
            <div class="jp-progress hidden-xs">
              <div class="jp-seek-bar lt">
                <div class="jp-play-bar dk">
                </div>
              </div>
              <div class="jp-title text-lt">
              </div>
            </div>
            <div class="hidden-xs hidden-sm jp-current-time text-xs text-muted"></div>
            <div class="hidden-xs hidden-sm jp-duration text-xs text-muted"></div>
            <div class="hidden-xs hidden-sm">
              <a class="jp-mute" title="<?php esc_html_e( 'mute', 'musik' ); ?>"><i class="icon-volume-2"></i></a>
              <a class="jp-unmute hid" title="<?php esc_html_e( 'unmute', 'musik' ); ?>"><i class="icon-volume-off"></i></a>
            </div>
            <div class="hidden-xs hidden-sm jp-volume">
              <div class="jp-volume-bar dk">
                <div class="jp-volume-bar-value lter"></div>
              </div>
            </div>
              <?php /*?>svnlabs<?php */
  
	$lyrics_font_size	= get_option("lyrics-popout-font-size");
	$lyrics_popout_height	= get_option("lyrics-popout-height");
	$lyrics_font_family	= get_option("lyrics-popout-font-family");				
	$lyrics_font_weight	= get_option("lyrics-popout-font-weight");				
	$lyrics_text_color	= get_option("lyrics-popout-text-color");
	$lyrics_text_color2	= get_option("lyrics-popout-text-color2");
	$lyrics_text_font	= get_option("lyrics-popout-text-font");
	$lyrics_text_font2	= get_option("lyrics-popout-text-font2");	
	$popout_window_color	= get_option("lyrics-popout-window-color");	
  
  
  ?>
            
            <style type="text/css">
			
				.my-new-list{				
				color:#000000;
				padding-left:10px; 
				overflow:auto; 
				width:100%;			
				}
			
				#current_playlist.open {
				bottom: 60px;
				border:5px solid #006633;  /*svnlabs-v2*/
				/*height: <?php //echo intval(str_replace("px", "", $lyrics_popout_height))+40; ?>px;*/
				width:100%;
				left:0px;
				/*left:30px;*/
				}
				
				/* 15 may 2017  svnlabs */
				
				/* 19 jun 2017  svnlabs */
				
				.lyrics_text_color{
				  color: <?php echo $lyrics_text_color; ?> !important;
				  font-size: <?php echo $lyrics_text_font; ?>;
				}
				
				.lyrics_text_color2{
				  color: <?php echo $lyrics_text_color2; ?> !important;
				  font-size: <?php echo $lyrics_text_font2; ?>;
				}
				
				/*.footer{
				 
				 min-height: 45px;
				 height: 45px;
                 padding: 0 15px;
	
				}*/
				
			
			</style>
            
            <script type="text/javascript">
			
			
			jQuery(document).ready(function(){
			
			if (!("ontouchstart" in document.documentElement)) {
             document.documentElement.className += " no-touch";
            }
 
               //alert( jQuery(window).height() * <?php //echo intval($lyrics_popout_height)/100; ?> );  
			   
			   var HH = jQuery(window).height() * <?php echo intval($lyrics_popout_height)/100; ?>;
			   
               //jQuery(".my-new-list").css({height: Math.round(HH)+"px"  });
			   //jQuery("#current_playlist.open").css({height: (Math.round(HH)+40)+"px" });
			   
			  jQuery(".my-new-list").css({height: (Math.round(HH)-40)+"px"  });
			  jQuery("#current_playlist.open").css({height: Math.round(HH)+"px" });
               
 
 			});
			
			
			jQuery(window).resize(function () {

	          //jQuery('#dns_enterprise').css({height: jQuery(window).height() - 212 });
			  
			  var HH = jQuery(window).height() * <?php echo intval($lyrics_popout_height)/100; ?>;
			  
			  //jQuery(".my-new-list").css({height: Math.round(HH)+"px"  });
			  //jQuery("#current_playlist.open").css({height: (Math.round(HH)+40)+"px" });
			  
			  jQuery(".my-new-list").css({height: (Math.round(HH)-40)+"px"  });
			  jQuery("#current_playlist.open").css({height: Math.round(HH)+"px" });
			  
			  //console.log( jQuery(window).height() );
	
           });
		   
		   
		   
		    ///svnlabs  19 jun 2017
			if(localStorage.getItem("currentSpeedIdx")!=null)
			{
			 var currentSpeedIdx = parseInt(localStorage.getItem("currentSpeedIdx"));
			}
			else
			{
			 var currentSpeedIdx = 0;
			 localStorage.setItem("currentSpeedIdx", 0);
			}

            /* 5 oct 2017 svnlabs  Lyrics box font size to have memory too like speed and volume */
            if(localStorage.getItem("currentFont")!=null)
			{
			 var currentFont = parseInt(localStorage.getItem("currentFont"));
			 var currentFont2 = parseInt(localStorage.getItem("currentFont2"));
			}
			else
			{
			 var currentFont = parseInt(jQuery('.lyrics_text_color').css('font-size'), 10);
			 localStorage.setItem("currentFont", parseInt(jQuery('.lyrics_text_color').css('font-size'), 10));

             var currentFont2 = parseInt(jQuery('.lyrics_text_color2').css('font-size'), 10);
			 localStorage.setItem("currentFont2", parseInt(jQuery('.lyrics_text_color2').css('font-size'), 10));

			}


			//var currentSpeedIdx = 0;  /// svnlabs
			/*var speeds = [ 0.5, 1, 1.5, 2, 2.5 ];*/
			
			// 1x, 0.7x, 0.5x, 2x, 1.5x
			var speeds = [ 1, 0.7, 0.5, 2, 1.5 ];
			
			
			// svnlabs 19 jun 2017  Setting Speed: default 
			jQuery(document).ready(function(){
			
			//alert(speeds[currentSpeedIdx] + 'x'); 
			jQuery("#jpSpeedControl").html( speeds[currentSpeedIdx] + 'x' );
			//setTimeout(function(){}, 1000);
			//jQuery("#jplayer").jPlayer("option","defaultPlaybackRate", speeds[currentSpeedIdx]);
			//jQuery("#jplayer").jPlayer("option","playbackRate", speeds[currentSpeedIdx]);


            /* 5 oct 2017 svnlabs  Lyrics box font size to have memory too like speed and volume */

            //alert(parseInt(localStorage.getItem("currentFont")));   

			//jQuery('.lyrics_text_color').css('font-size', parseInt(localStorage.getItem("currentFont")), 10 );
			//jQuery('.lyrics_text_color2').css('font-size', parseInt(localStorage.getItem("currentFont2")), 10 );

			console.log("Font: " + parseInt(localStorage.getItem("currentFont")));
			
			
			});
			
			//var speed = player.querySelector('.jpSpeedControl');
			
			function jpSpeedControl()
			{
			
			  	currentSpeedIdx = parseInt(currentSpeedIdx) + 1 < speeds.length ? parseInt(currentSpeedIdx) + 1 : 0;
				//audio.playbackRate = speeds[currentSpeedIdx];
				
				console.log(currentSpeedIdx);
								
				// Storing the data:
                localStorage.setItem("currentSpeedIdx", currentSpeedIdx);
				
				jQuery("#jplayer").jPlayer("option","playbackRate", speeds[currentSpeedIdx]);
				
				jQuery("#jpSpeedControl").html( speeds[currentSpeedIdx] + 'x' );
				 
			  
			  
			  
			   
			  
			}
			
			</script>
            
            
            <div id="jpSpeedControl" title="Speed" class="1" style="cursor:pointer;" onclick="jpSpeedControl();">1x</div>  <?php /*?>svnlabs<?php */?>
            
            <?php /*?>svnlabs-v2<?php */?>
            <div><strong><a href="javascript:void(0);" title="Increase Font" onclick="popoutFont('+');">&uarr;</a></strong>&nbsp;/&nbsp;<strong><a href="javascript:void(0);" title="Decrease Font" onclick="popoutFont('-');">&darr;</a></strong></div>
            
            
            <div id="hide2nd" title="Hide 2nd Lyrics" style="cursor:pointer;" onclick="jQuery('.lyrics_text_color2').toggle();"><img src="<?php echo get_template_directory_uri(); ?>/lyrics22.png" border="0" /></div>
            
            <div id="maximize" title="Maximize" style="cursor:pointer; display:none;" onclick="jQuery('#current_playlist').show();"><img src="<?php echo get_template_directory_uri(); ?>/open.gif" border="0" /></div>
            
            <?php /*?><div>
              <a class="jp-shuffle" title="<?php esc_html_e( 'shuffle', 'musik' ); ?>"><i class="icon-shuffle text-muted"></i></a>
              <a class="jp-shuffle-off hid" title="<?php esc_html_e( 'shuffle off', 'musik' ); ?>"><i class="icon-shuffle text-lt"></i></a>
            </div><?php */?> <?php /*svnlabs*/ ?>
            
            <div style="display:none;"> <?php /*?>svnlabs-v2<?php */?>
              <a class="jp-repeat" title="<?php _e( 'repeat', 'musik' ); ?>"><i class="icon-loop text-muted"></i></a>
              <a class="jp-repeat-off hid" title="<?php _e( 'repeat off', 'musik' ); ?>"><i class="icon-loop text-lt"></i></a>
            </div>
            <div class="hide">
              <a class="jp-full-screen" title="<?php esc_html_e( 'full screen', 'musik' ); ?>"><i class="fa fa-expand"></i></a>
              <a class="jp-restore-screen" title="<?php esc_html_e( 'restore screen', 'musik' ); ?>"><i class="fa fa-compress text-lt"></i></a>
            </div>
          </div>
        </div>
      </div>
      <div class="jp-playlist dropup" id="playlist">
        <ul class="dropdown-menu aside-xl dker">
          <!-- The method Playlist.displayPlaylist() uses this unordered list -->
          <li class="list-group-item"></li>
        </ul>
      </div>
      <div class="jp-no-solution hide">
        <span><?php esc_html_e( 'Update Required', 'musik' ); ?></span>
        <?php esc_html_e( 'To play the media you will need to either update your browser to a recent version or update your Flash plugin.', 'musik'); ?>
      </div>
    </div>
  </div>
  

  <div id="current_playlist" class="open" style="z-index:1000; position:fixed;">
                <div id="current_playlist_header" style="background-color:<?php echo $popout_window_color; ?>">
                    
                    <div title="Close" id="current_playlist_close" onClick="jQuery('#current_playlist').hide();  jQuery('#jplayer').jPlayer('stop'); setTimeout(function(){ }, 1000); "></div>
                    <div title="Close" id="current_playlist_minimize" onClick="jQuery('#current_playlist').hide(); jQuery('#maximize').show(); "></div>
                    
                    <div class="current_playlist_header_item" style="font-size:16px;">
					<?php /*?><img src="http://via.placeholder.com/150x35/4cb6cb/000000" border="0" /><?php */?>
					<?php /*?><strong><a href="javascript:void(0);" title="Increase Font" onclick="popoutFont('+');">&uarr;</a></strong> &nbsp;/&nbsp; <strong><a href="javascript:void(0);" title="Decrease Font" onclick="popoutFont('-');">&darr;</a></strong><?php */?></div>
                </div>
                
                
                    <?php /*?><div class="lrctext" style="overflow:auto; position:absolute; z-index:1000; margin-top:200px;"></div><?php */?>
                    <div class="my-new-list" style="font-family:<?php echo $lyrics_font_family; ?>; font-size:<?php echo $lyrics_font_size; ?>; font-weight:<?php echo $lyrics_font_weight; ?>; color:<?php echo $lyrics_text_color; ?>;">
                    
                    <?php /*?><li></li><?php */?>
                    
                    </div>
                    
            </div>
   <?php /*?>svnlabs<?php */?>    
   
<script type="text/javascript">

function popoutFont(s)
{

  console.log(s);

  if(s=="+")
  {
    jQuery('.my-new-list').css('font-size', parseInt(jQuery('.my-new-list').css('font-size'), 10) + 1 );
	jQuery('.lyrics_text_color').css('font-size', parseInt(jQuery('.lyrics_text_color').css('font-size'), 10) + 1 );
	jQuery('.lyrics_text_color2').css('font-size', parseInt(jQuery('.lyrics_text_color2').css('font-size'), 10) + 1 );
  }
   	
  if(s=="-")
  {
    jQuery('.my-new-list').css('font-size', parseInt(jQuery('.my-new-list').css('font-size'), 10) - 1 );	
    jQuery('.lyrics_text_color').css('font-size', parseInt(jQuery('.lyrics_text_color').css('font-size'), 10) - 1 );
	jQuery('.lyrics_text_color2').css('font-size', parseInt(jQuery('.lyrics_text_color2').css('font-size'), 10) - 1 );
  }


  localStorage.setItem("currentFont", parseInt(jQuery('.lyrics_text_color').css('font-size'), 10));

  localStorage.setItem("currentFont2", parseInt(jQuery('.lyrics_text_color2').css('font-size'), 10));

}

</script>        
  
  
</footer>
<?php } ?>