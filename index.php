<?php get_header(); ?>

    <main id="site-content">

        <?php if ( have_posts() ) : ?>

            <?php while ( have_posts() ) : the_post(); ?>

                <?php 
                    $postType = get_post_type(); 
                    $author = get_the_author();
                    $authorPermalink = get_author_posts_url( get_the_author_meta('ID') );
                ?>

                <article class="post-component">
                    <div class="post-component_meta post-meta">
                        <span class="post-date"><?php echo get_the_date();?></span>
                        <span class="post-author">
                            <a href="<?php echo esc_url( $authorPermalink ); ?>">
                                <?php echo 'by ' . $author; ?>
                            </a>
                        </span>
                        <?php if ($postType === 'stocks') { ?>
                            <span class="post-type">Stock Recommendation</span>
                        <?php } else { ?>
                            <span class="post-type">News</span>
                        <?php } ?>
                    </div>
                    <h2 class="post-component_title"><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h2>
                    <?php the_excerpt(); ?>
                </article>

            <?php endwhile; ?>

        <?php endif; ?>
        
    </main>

<?php get_footer(); ?>