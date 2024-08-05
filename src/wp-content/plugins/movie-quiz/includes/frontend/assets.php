<?php

if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly.
}

class Movie_Quiz_Assets
{

    public function __construct()
    {
        require_once MOVIE_QUIZ_PATH . 'includes/templates/all.php';
    }

    public function display_quiz_info()
    {
        $template = new Movie_Quiz_Templates();

        return $template->render_quiz_template();

    }

    public function enqueue_scripts()
    {
        wp_enqueue_style(
            'movie-quiz',
            MOVIE_QUIZ_URL . 'css/styles.css',
            [],
            1,
            "all"
        );
        wp_enqueue_script(
            'movie-quiz',
            MOVIE_QUIZ_URL . 'js/main.js',
            ['jquery'],
            1,
            true
        );
        wp_localize_script(
            'movie-quiz',
            'movieQuiz',
            ['ajax_url' => admin_url('admin-ajax.php')]
        );
    }
}
