<?php get_header(); ?>

    <div class="wrapper-sm">

        <?php if ( have_posts() ) : ?>

            <?php while ( have_posts() ) : the_post(); ?>

                <article <?php post_class(); ?>>

                    <?php $symbol = get_post_meta(get_the_ID(), '_mfsa_symbol', true); ?>

                    <header class="post-header">
                        <?php 
                            $companyLogo = has_post_thumbnail() ? get_the_post_thumbnail( get_the_id(), 'thumbnail', array('class' => 'd-inline-block') ) : false;

                            if ($companyLogo) {
                                echo $companyLogo;
                            }
                        ?>

                        <h1><?php the_title(); ?></h2>
                        <?php
                            if ($symbol) {
                                echo '<span class="ticker-tag">NASDAQ:'. $symbol .'</span>';
                            }
                        ?>
                    </header>

                    <?php the_content(); ?>

                    <div id="mfsa-stats"></div>

                    <?php 
                        $recommendations = get_posts(array(
                            'post_type' => 'stocks',
                            'meta_query' => array(
                                'relation' => 'AND',
                                'symbol_clause' => array(
                                    'key' => '_mfsa_symbol',
                                    'value' => $symbol,
                                    'compare' => '='
                                )
                            ),
                        ));
                    ?>

                    <?php if ($symbol && $recommendations) { ?>

                        <hr />
                        
                        <h2>Recommendations</h2>

                        <?php foreach ($recommendations as $post) { ?>
                            <?php 
                                $author = get_the_author();
                                $authorPermalink = get_author_posts_url( get_the_author_meta('ID') );
                            ?>
                            
                            <article class="post-component">
                                <h3 class="post-component_title"><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h3>
                                <?php the_excerpt(); ?>
                                <div class="post-component_meta post-meta">
                                    <span class="post-date"><?php echo get_the_date('l, M j, Y', $post->ID);?></span>
                                    <span class="post-author">
                                        <a href="<?php echo esc_url( $authorPermalink ); ?>">
                                            <?php echo $author; ?>
                                        </a>
                                    </span>
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
                                    'value' => $symbol,
                                    'compare' => '='
                                )
                            ),
                        ));
                    ?>

                    <?php if ($symbol && $news) { ?>
                        
                        <hr />

                        <h2>Other Coverage</h2>

                        <?php foreach ($news as $post) { ?>
                            <?php 
                                $author = get_the_author();
                                $authorPermalink = get_author_posts_url( get_the_author_meta('ID') );
                            ?>

                            <article class="post-component">
                                <h3 class="post-component_title"><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h3>
                                <?php the_excerpt(); ?>
                                <div class="post-component_meta post-meta">
                                    <span class="post-date"><?php echo get_the_date('l, M j, Y', $post->ID);?></span>
                                    <span class="post-author">
                                        <a href="<?php echo esc_url( $authorPermalink ); ?>">
                                            <?php echo $author; ?>
                                        </a>
                                    </span>
                                </div>
                            </article>
                        <?php } ?>

                    <?php } ?>

                </article>

            <?php endwhile; ?>

        <?php endif; ?>

    </div>

<?php get_footer(); ?>