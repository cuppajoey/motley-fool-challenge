<!DOCTYPE html>
<html lang="en" dir="ltr">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <link rel="preconnect" href="https://fonts.googleapis.com"> 
        <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin> 
        <link href="https://fonts.googleapis.com/css2?family=Poppins:ital,wght@0,400;0,700;1,400;1,700&display=swap" rel="stylesheet">

        <?php wp_head(); ?>
    </head>
    <body <?php body_class(); ?>>
        <header class="site-header">
            <div class="wrapper">
                <div class="site-branding">
                    <a href="<?php echo site_url(); ?>">
                        <img src="<?php echo get_template_directory_uri() . "/assets/img/motley-fool-sa-logo.png"?>" alt="Stock Advisor Logo by Motley Fool">
                    </a>
                </div>
                <?php
                    wp_nav_menu(
                    array(
                        'theme_location' => 'main-menu',
                        'container' => 'nav',
                        'container_id' => '',
                        'container_class' => 'primary-nav',
                        )
                    );
                ?>
            </div>
        </header>
        <main id="site-content">
    