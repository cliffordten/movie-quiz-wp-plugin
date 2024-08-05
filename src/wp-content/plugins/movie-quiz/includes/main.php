<?php

if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly.
}

class Movie_Quiz
{

    public function __construct()
    {

        $this->define_plugin_constants();
        $this->load_dependencies();
        $this->load_assets();
        $this->define_api_hooks();
    }

    private function define_plugin_constants()
    {
        define('MOVIE_QUIZ_PATH', plugin_dir_path(dirname(__FILE__)));
        define('MOVIE_QUIZ_URL', plugin_dir_url(dirname(__FILE__)));
    }

    private function load_dependencies()
    {
        require_once MOVIE_QUIZ_PATH . 'includes/utils/libraries.php';
        require_once MOVIE_QUIZ_PATH . 'includes/admin/movie-quiz-configs.php';
        require_once MOVIE_QUIZ_PATH . 'includes/admin/manage-quiz-questions.php';
        require_once MOVIE_QUIZ_PATH . 'includes/admin/manage-answered-quizzes.php';
        require_once MOVIE_QUIZ_PATH . 'includes/api/routes.php';
        require_once MOVIE_QUIZ_PATH . 'includes/frontend/assets.php';
    }

    private function load_assets()
    {
        $plugin_assets = new Movie_Quiz_Assets();

        add_shortcode('movie_quiz', [$plugin_assets, 'display_quiz_info']);
        add_action('wp_enqueue_scripts', [$plugin_assets, 'enqueue_scripts']);
    }

    private function define_api_hooks()
    {
        $plugin_api = new Movie_Quiz_API_Routes();

        add_action('rest_api_init', [$plugin_api, 'register_routes']);
    }

    public function run()
    {
        new Movie_Quiz_Libraries();
        new Movie_Quiz_Admin_Configs();
        new Manage_Movie_Quiz_Question();
        new Movie_Quiz_Question_Answers();
    }

    public static function activate()
    {
        // global $wpdb;
        // $charset_collate = $wpdb->get_charset_collate();

        // $quiz_table = $wpdb->prefix . 'movie_quiz';
        // $quiz_history_table = $wpdb->prefix . 'movie_quiz_history';

        // $sql = "CREATE TABLE $quiz_table (
        //     id mediumint(9) NOT NULL AUTO_INCREMENT,
        //     question text NOT NULL,
        //     image_url varchar(255) DEFAULT '' NOT NULL,
        //     answers text NOT NULL,
        //     correct_answer varchar(255) NOT NULL,
        //     difficulty varchar(10) NOT NULL,
        //     PRIMARY KEY (id)
        // ) $charset_collate;

        // CREATE TABLE $quiz_history_table (
        //     id mediumint(9) NOT NULL AUTO_INCREMENT,
        //     user_id bigint(20) NOT NULL,
        //     quiz_data text NOT NULL,
        //     score int NOT NULL,
        //     created_at datetime DEFAULT CURRENT_TIMESTAMP NOT NULL,
        //     PRIMARY KEY (id)
        // ) $charset_collate;";

        // require_once (ABSPATH . 'wp-admin/includes/upgrade.php');
        // dbDelta($sql);
    }

    public static function deactivate()
    {
        // Optional: Clean up on deactivation
    }
}
