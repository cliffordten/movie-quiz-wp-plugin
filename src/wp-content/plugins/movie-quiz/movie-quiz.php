<?php
/**
 * Plugin Name: Movie Quiz
 * Description: Movie Quiz plugin for wordpress
 * Author: Teneng Cliford (bisynerd) <cliffordteng5@gmail.com>
 * Author URI: https://github.com/cliffordten
 * Version: 1.0.0
 * Text Domain: movie-quiz
 */

if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly.
}

require_once plugin_dir_path(__FILE__) . 'includes/main.php';

function run_movie_quiz()
{
    $plugin = new Movie_Quiz();
    $plugin->run();
}

register_activation_hook(__FILE__, ['Movie_Quiz', 'activate']);
register_deactivation_hook(__FILE__, ['Movie_Quiz', 'deactivate']);

run_movie_quiz();
