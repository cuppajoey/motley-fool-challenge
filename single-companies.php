<?php 
/**
 * This template is used for a single company post.
 * It displays the post content, related stock recommendation, and related news
 * 
 * Company stats are injected via Javascript /assets/js/app.js
 *
 */
?>

<?php get_header(); ?>

    <div class="wrapper-sm">

        <?php while ( have_posts() ) : the_post(); ?>

            <article <?php post_class(); ?>>

                <?php 
                    $exchange = get_post_meta(get_the_ID(), '_mfsa_exchange', true); 
                    $symbol = get_post_meta(get_the_ID(), '_mfsa_symbol', true); 
                ?>

                <header class="post-header">
                    <?php 
                        $companyLogo = has_post_thumbnail() ? 
                                        get_the_post_thumbnail( get_the_id(), 'thumbnail', array('class' => 'd-inline-block') ) : 
                                        '<img src="'.get_template_directory_uri().'/assets/img/SBUX.png" />';

                        if ($companyLogo) {
                            echo $companyLogo;
                        }
                    ?>

                    <h1><?php the_title(); ?></h2>
                    <?php
                        if ($symbol) {
                            echo '<span class="ticker-tag">' . $exchange . ":" . $symbol .'</span>';
                        }
                    ?>
                </header>

                <?php the_content(); ?>

                <div id="mfsa-stats"></div>

            </article>

        <?php endwhile; ?>

        <?php wp_reset_postdata(); ?>

        <?php if ($symbol) { ?>

            <?php if (! get_query_var('paged')) { ?>
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

                <?php if ($recommendations) { ?>

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

            <?php } ?>
            
            <?php 
                $newsArgs = array(
                    'post_type' => 'post',
                    'meta_query' => array(
                        'relation' => 'AND',
                        'symbol_clause' => array(
                            'key' => '_mfsa_symbol',
                            'value' => $symbol,
                            'compare' => '='
                        )
                    ),
                    'paged' => get_query_var('paged')
                );

                $news = new WP_Query($newsArgs);
            ?>

            <?php if ($news->have_posts()) { ?>
                
                <hr />

                <h2>Other Coverage</h2>

                <?php while ($news->have_posts()) { $news->the_post(); ?>
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

                <?php if ($news->found_posts > 10) { ?>
            
                    <div class="pagination-block">
                        <?php 
                            echo paginate_links( array(
                                'current' => max( 1, $paged ),
                                'total' => $news->max_num_pages
                            ) );
                        ?>
                    </div>

                <?php } ?>

            <?php } ?>

            <?php wp_reset_postdata(); ?>
        
        <?php } ?>

    </div>

<?php get_footer(); ?>