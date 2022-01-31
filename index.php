<?php get_header(); ?>

    <div class="wrapper-sm">

        <?php if ( is_post_type_archive('stocks') ) { ?>    

            <h1>Stock Recommendations</h1>

        <?php } else { ?>    

            <h1>Latest News</h1>

        <?php } ?>

        <?php if ( have_posts() ) : ?>

            <?php while ( have_posts() ) : the_post(); ?>

                <?php 
                    $postType = get_post_type(); 
                    $author = get_the_author();
                    $authorPermalink = get_author_posts_url( get_the_author_meta('ID') );
                ?>

                <article class="post-component">
                    <h2 class="post-component_title"><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h2>

                    <?php the_excerpt(); ?>

                    <div class="post-component_meta post-meta">
                        <span class="post-date"><?php echo get_the_date();?></span>
                        <span class="post-author">
                            <a href="<?php echo esc_url( $authorPermalink ); ?>">
                                <?php echo 'by ' . $author; ?>
                            </a>
                        </span>
                    </div>
                </article>

            <?php endwhile; ?>

            <div class="pagination-block">
                <?php the_posts_pagination(); ?>
            </div>


        <?php endif; ?>
    
    </div>

<?php get_footer(); ?>