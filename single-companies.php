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

                    <?php 
                        $keyStats = mfsa_get_company_stats("SBUX");
                        $quote = mfsa_get_company_quote("SBUX");

                        $price = $quote[0]->price;
                        $change = $quote[0]->change;
                        $changesPercentage = $quote[0]->changesPercentage;
                        $range = $keyStats[0]->range;
                        $beta = $keyStats[0]->beta;
                        $avgVolume = $quote[0]->avgVolume;
                        $marketCap = $quote[0]->marketCap;
                        $lastDiv = $keyStats[0]->lastDiv;

                        // Price
                        // Price change
                        // Price change in percentage
                        // 52 week range
                        // Beta
                        // Volume Average
                        // Market Capitalisation
                        // Last Dividend (if any, otherwise display “N/A”)

                        // echo '<pre>';
                        // print_r($keyStats);
                        // print_r($quote);
                        // echo '</pre>';
                    ?>

                    <div class="table-grid">
                        <div class="table-cell">
                            <strong>Current Price: </strong>
                            <span><?php echo '$' . number_format($price, 2, '.', ','); ?></span>
                        </div>
                        <div class="table-cell">
                            <strong>Today's Change: </strong>
                            <span><?php echo '$' . number_format($change, 2, '.', ','); ?></span>
                        </div>
                        <div class="table-cell">
                            <strong>Change Percentage: </strong>
                            <span><?php echo number_format($changesPercentage, 2, '.', ',') . "%"; ?></span>
                        </div>
                        <div class="table-cell">
                            <strong>Yearly Range: </strong>
                            <span><?php echo $range; ?></span>
                        </div>
                        <div class="table-cell">
                            <strong>Beta: </strong>
                            <span><?php echo number_format($beta, 2, '.', ','); ?></span>
                        </div>
                        <div class="table-cell">
                            <strong>Average Volume: </strong>
                            <span><?php echo number_format($avgVolume, 2, '.', ','); ?></span>
                        </div>
                        <div class="table-cell">
                            <strong>Market Cap: </strong>
                            <span><?php echo '$' . number_format($marketCap, 2, '.', ','); ?></span>
                        </div>
                        <div class="table-cell">
                            <strong>Dividend: </strong>
                            <span><?php echo $lastDiv ? number_format($lastDiv, 2, '.', ',') : "N/A"; ?></span>
                        </div>
                    </div>
                </article>

            <?php endwhile; ?>

        <?php endif; ?>
        
    </main>

<?php get_footer(); ?>