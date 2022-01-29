<?php get_header(); ?>

    <main id="site-content">

        <?php if ( have_posts() ) : ?>

            <?php while ( have_posts() ) : the_post(); ?>

                <article <?php post_class(); ?>>
                    <header>
                        <h1><?php the_title(); ?></h2>
                        
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
                        
                        <span class=""><?php echo get_the_author(); ?></span>
                        <span class=""><?php echo get_the_date();?></span>
                    </header>
                    
                    <?php the_content(); ?>
                </article>

            <?php endwhile; ?>

        <?php endif; ?>
        
    </main>

<?php get_footer(); ?>