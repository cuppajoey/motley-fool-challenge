<?php get_header(); ?>

    <main id="site-content">

        <?php if ( have_posts() ) : ?>

            <?php while ( have_posts() ) : the_post(); ?>

                <article>
                    <h1><?php the_title(); ?></h2>
                    <span class=""><?php echo get_the_author(); ?></span>
                    <span class=""><?php echo get_the_date();?></span>
                    <?php the_content(); ?>
                </article>

            <?php endwhile; ?>

        <?php endif; ?>
        
    </main>

<?php get_footer(); ?>