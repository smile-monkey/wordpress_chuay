<?php
/**
 * Downloads Category Template
*/

get_header(); ?>

<section class="<?php if( get_theme_mod( 'hide-player' ) == 0 ){ echo "w-f-md";} ?>" id="ajax-container">
    <section class="hbox stretch">
        <aside class="aside bg-light dk">
            <section class="vbox">
                <section class="scrollable hover hidden-xs" id="genres">

                    <?php the_widget( 'music_term_widget', 'taxonomy=download_category&display=list&widget=false&hide_empty=on&show_count=on' );?>
                      
                      <?php echo '<ul class="m-t-n-xxs nav"><li>&nbsp;</li><li>&nbsp;</li><li>&nbsp;</li><li>&nbsp;</li></ul>'; ?>
        
                    <?php /* svnlabs 6 sep 2017 Music difficulty */
                     the_widget( 'music_term_widget', 'taxonomy=difficulty_category&display=list&widget=false&hide_empty=on&show_count=on' );?>

                </section>
            </section>
        </aside>
        <section>
            <section class="vbox">
                <section class="scrollable padder-lg" id="tracks">
                    <a href="#" class="btn btn-link visible-xs pull-right m-t" data-toggle="class:show" data-target="#genres">
                      <i class="icon-list"></i>
                    </a>
                    <h1 class="font-thin h2 m-t m-b"><?php single_term_title(); ?></h1>
                    
                    <?php if ( have_posts() ) : ?>

                        <div class="row row-sm">
                            <?php while ( have_posts() ) : the_post(); ?>
                                <div class="col-xs-6 col-sm-4 col-md-3 col-lg-2">
                                    <?php get_template_part( 'template-parts/content', 'download' ); ?>
                                </div>
                            <?php endwhile; ?>
                        </div>
                        
                        <?php get_template_part( 'template-parts/pagination'); ?>

                    <?php else: ?>
                        
                        <?php get_template_part( 'template-parts/content', 'none' ); ?>
                        
                    <?php endif; ?>
                </section>
            </section>
        </section>

<aside class="aside aside-md bg-light dk">
<section class="vbox">    
<section class="scrollable wrapper hover" id="home-sidebar">
<div id="music_term_widget-2" class="widget-1 widget-odd widget_music_term_widget widget"><h4 class="widget-title">Top teachers</h4>

<?php //dynamic_sidebar( 'artist-sidebar' ); ?>



<?php /* svnlabs 6 sep 2017 Music difficulty */
        the_widget( 'music_term_widget', 'taxonomy=download_artist&display=list&widget=false&hide_empty=on&show_count=on' );?>


</div>
</section>
</section>
</aside>



    </section>



</section>


<?php get_template_part( 'template-parts/player' ); ?>





<?php get_footer( );  ?>
