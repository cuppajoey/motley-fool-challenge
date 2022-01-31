<?php get_header(); ?>

    <div class="wrapper-sm">

        <?php if ( is_post_type_archive('stocks') ) { ?>    

            <h1 class="archive-title">Stock Recommendations</h1>

        <?php } else { ?>    

            <h1 class="archive-title">Latest News</h1>

        <?php } ?>

        <?php if ( have_posts() ) : ?>

            <?php while ( have_posts() ) : the_post(); ?>

                <?php 
                    $postType = get_post_type(); 
                    $author = get_the_author();
                ?>

                <article class="post-component">
                    <h2 class="post-component_title"><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h2>

                    <?php the_excerpt(); ?>

                    <div class="post-component_meta post-meta">
                        <span class="post-date"><?php echo get_the_date();?></span>
                        <span class="post-author"><?php echo $author; ?></span>
                        
                        <?php 
                            if ($postType === 'stocks') {
                                $exchange = get_post_meta(get_the_ID(), '_mfsa_exchange', true); 
                                $symbol = get_post_meta(get_the_ID(), '_mfsa_symbol', true); 

                                echo '<strong>' . $exchange . ':' . $symbol . '</strong>';
                            }
                        ?>
                    </div>
                </article>

            <?php endwhile; ?>

            <div class="pagination-block">
                <?php the_posts_pagination(); ?>
            </div>


        <?php endif; ?>
    
    </div>

<?php get_footer(); ?>