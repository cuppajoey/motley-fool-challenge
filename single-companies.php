<?php get_header(); ?>

    <main id="site-content" class="company-page">

        <?php if ( have_posts() ) : ?>

            <?php while ( have_posts() ) : the_post(); ?>

                <article <?php post_class(); ?>>
                    <header class="post-header">
                        <?php 
                            $companyLogo = has_post_thumbnail() ? get_the_post_thumbnail( get_the_id(), 'thumbnail', array('class' => 'd-inline-block') ) : false;

                            if ($companyLogo) {
                                echo $companyLogo;
                            }
                        ?>

                        <h1><?php the_title(); ?></h2>
                        <span class="ticker-tag">NASDAQ:SBUX</span>
                    </header>
                    <?php the_content(); ?>
                </article>

            <?php endwhile; ?>

        <?php endif; ?>
        
    </main>

<?php get_footer(); ?>