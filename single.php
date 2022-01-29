<?php get_header(); ?>

    <main id="site-content">

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

                            <?php 
                                if ($symbol) {
                                    
                                    $keyStats = mfsa_get_company_stats("SBUX");

                                    $logoURL = $keyStats[0]->image;
                                    $companyName = $keyStats[0]->companyName;
                                    $exchangeShortName = $keyStats[0]->exchangeShortName;
                                    $description = $keyStats[0]->description;
                                    $sector = $keyStats[0]->sector;
                                    $website = $keyStats[0]->website;

                                    // echo '<pre>';
                                    // print_r($keyStats);
                                    // print_r($quote);
                                    // echo '</pre>';
                                }
                            ?>
                        </div>
                    </header>
                    
                    <?php the_content(); ?>
                </article>
                
                <?php if ($symbol) { ?>
                    <?php 
                        $keyStats = mfsa_get_company_stats("SBUX");

                        $logoURL = $keyStats[0]->image;
                        $companyName = $keyStats[0]->companyName;
                        $exchangeShortName = $keyStats[0]->exchangeShortName;
                        $description = $keyStats[0]->description;
                        $sector = $keyStats[0]->sector;
                        $website = $keyStats[0]->website;
                    ?>

                    <aside class="company-stats">
                        <div class="stats-title">
                            <img src="<?php echo $logoURL; ?>" />
                            <span class="heading-size-4"><?php echo $companyName; ?></span>
                        </div>
                        <p class="stats-description">
                            <?php echo mfsa_truncate_content($description); ?>
                        </p>
                        <div class="stats-meta">
                            <span><strong>Exchange:</strong> <?php echo $exchangeShortName; ?></span>
                            <span><strong>Industry:</strong> <?php echo $sector; ?></span>
                            <span><strong>Website:</strong> <a href="<?php echo $website; ?>">Visit <?php echo $companyName; ?></a></span>
                        </div>
                    </aside>
                <?php } ?>

            <?php endwhile; ?>

        <?php endif; ?>
        
    </main>

<?php get_footer(); ?>