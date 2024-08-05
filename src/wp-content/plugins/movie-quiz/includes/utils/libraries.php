<?php

if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly.
}

class Movie_Quiz_Libraries
{
    public function __construct()
    {

        if (file_exists(MOVIE_QUIZ_PATH . 'vendor/autoload.php')) {
            require_once MOVIE_QUIZ_PATH . 'vendor/autoload.php';
        } else {
            add_action('admin_notices', function () {
                echo '<div class="notice notice-error"><p>Carbon Fields not found. Please run <code>composer install</code>.</p></div>';
            });
        }

        require_once MOVIE_QUIZ_PATH . 'includes/utils/constants.php';
        require_once MOVIE_QUIZ_PATH . 'includes/utils/user-info.php';
        require_once MOVIE_QUIZ_PATH . 'includes/utils/quiz-config.php';
        add_action('after_setup_theme', [$this, 'load_dependencies']);

    }


    function load_dependencies()
    {
        \Carbon_Fields\Carbon_Fields::boot();
    }
}