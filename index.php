<?php get_header(); ?>

    <main id="site-content">

        <?php if ( have_posts() ) : ?>

            <?php while ( have_posts() ) : the_post(); ?>

                <article>
                    <h2><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h2>
                    <div class="post-meta">
                        <span class="post-date"><?php echo get_the_date();?></span>
                        <span class="post-author"><?php echo 'by ' . get_the_author(); ?></span>
                    </div>
                    <p><?php the_excerpt(); ?></p>
                </article>

            <?php endwhile; ?>

        <?php endif; ?>
        
    </main>

<?php get_footer(); ?>