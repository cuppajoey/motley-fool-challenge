<?php 
/**
 * This template is used for a single stock recommendation post
 *
 */
?>
<?php get_header(); ?>

    <div class="wrapper-sm">

        <?php if ( have_posts() ) : ?>

            <?php while ( have_posts() ) : the_post(); ?>

                <article <?php post_class(); ?>>
                    <header>
                        <h1 class="post-title"><?php the_title(); ?></h2>
                        
                        <div class="post-meta">
                            <span class="post-date"><?php echo get_the_date();?></span>
                            <span class="post-author"><?php echo 'by ' . get_the_author(); ?></span>

                            <?php 
                                $exchange = get_post_meta(get_the_ID(), '_mfsa_exchange', true); 
                                $symbol = get_post_meta(get_the_ID(), '_mfsa_symbol', true); 
                                $companyPermalink = $symbol ? get_link_to_company_profile($symbol) : false;

                                if ($companyPermalink) {
                                    echo '<a href="' .$companyPermalink. '">' . $exchange. ':' . $symbol . '</a>';
                                }
                            ?>
                        </div>
                    </header>
                    
                    <?php the_content(); ?>
                </article>

            <?php endwhile; ?>

        <?php endif; ?>

    </div>

<?php get_footer(); ?>