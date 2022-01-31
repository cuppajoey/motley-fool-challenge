<?php get_header(); ?>

    <div class="wrapper-sm">

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

                    <div id="mfsa-stats"></div>

                    <?php 
                        // $keyStats = mfsa_get_company_stats("SBUX");
                        // $quote = mfsa_get_company_quote("SBUX");

                        // $price = $quote[0]->price;
                        // $change = $quote[0]->change;
                        // $changesPercentage = $quote[0]->changesPercentage;
                        // $range = $keyStats[0]->range;
                        // $beta = $keyStats[0]->beta;
                        // $avgVolume = $quote[0]->avgVolume;
                        // $marketCap = $quote[0]->marketCap;
                        // $lastDiv = $keyStats[0]->lastDiv;

                        // echo '<pre>';
                        // print_r($keyStats);
                        // print_r($quote);
                        // echo '</pre>';
                    ?>

                    <?php 
                        $recommendations = get_posts(array(
                            'post_type' => 'stocks',
                            'meta_query' => array(
                                'relation' => 'AND',
                                'symbol_clause' => array(
                                    'key' => '_mfsa_symbol',
                                    'value' => 'SBUX',
                                    'compare' => '='
                                )
                            ),
                        ));
                    ?>

                    <?php if ($recommendations) { ?>
                        <h2>Recommendations</h2>
                        <?php foreach ($recommendations as $post) { ?>
                            <article class="post-component">
                                <h3 class="post-component_title"><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h3>
                                <?php the_excerpt(); ?>
                                <div class="post-component_meta post-meta">
                                    <span class="post-date"><?php echo get_the_date('l, M j, Y', $post->ID);?></span>
                                    <span class="author"><?php echo get_the_author($post->ID); ?></span>
                                </div>
                            </article>
                        <?php } ?>
                    <?php } ?>
                    
                    <?php 
                        $news = get_posts(array(
                            'post_type' => 'post',
                            'meta_query' => array(
                                'relation' => 'AND',
                                'symbol_clause' => array(
                                    'key' => '_mfsa_symbol',
                                    'value' => 'SBUX',
                                    'compare' => '='
                                )
                            ),
                        ));
                    ?>

                    <?php if ($news) { ?>
                        <h2>Other Coverage</h2>
                        <?php foreach ($news as $post) { ?>
                            <article class="post-component">
                                <h3 class="post-component_title"><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h3>
                                <?php the_excerpt(); ?>
                                <div class="post-component_meta post-meta">
                                    <span class="post-date"><?php echo get_the_date('l, M j, Y', $post->ID);?></span>
                                    <span class="author"><?php echo get_the_author($post->ID); ?></span>
                                </div>
                            </article>
                        <?php } ?>
                    <?php } ?>
                </article>

            <?php endwhile; ?>

        <?php endif; ?>

    </div>

<?php get_footer(); ?>