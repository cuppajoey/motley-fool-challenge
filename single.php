<?php 
/**
 * This template is used for a single standard post
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

                            <?php $symbol = get_post_meta(get_the_ID(), '_mfsa_symbol', true); ?>
                            <?php 
                                if ($symbol) { 
                                    $findCompanyPage = get_posts(
                                        array(
                                            'post_type' => 'companies',
                                            'meta_query' => array(
                                                'relation' => 'AND',
                                                'symbol_clause' => array(
                                                    'key' => '_mfsa_symbol',
                                                    'value' => $symbol,
                                                    'compare' => '='
                                                )
                                            ),
                                        )
                                    );
                                    $companyPageID = $findCompanyPage ? $findCompanyPage[0]->ID : 0;
                                    $companyPageLink = $companyPageID > 0 ? get_permalink($companyPageID) : '#';
                                
                                    if ($companyPageID) {
                                        echo '<a href="' .$companyPageLink. '">NASDAQ:' . $symbol . '</a>';
                                    }
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