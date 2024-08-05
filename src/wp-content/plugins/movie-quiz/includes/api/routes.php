<?php

if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly.
}


class Movie_Quiz_API_Routes
{

    public function __construct()
    {

        require_once MOVIE_QUIZ_PATH . 'includes/api/methods.php';

    }

    public function register_routes()
    {

        $api_methods = new Movie_Quiz_API_Methods();

        register_rest_route(
            'api/movie-quiz',
            '/submit',
            [
                'permission_callback' => function (WP_REST_Request $request) {
                    return true;
                },
                'methods' => 'POST',
                'callback' => [$api_methods, 'submit_answers'],
            ]
        );


        register_rest_route(
            'api/movie-quiz',
            '/history/(?P<user_id>[\w-]+)',
            [
                'permission_callback' => function (WP_REST_Request $request) {
                    return true;
                },
                'methods' => 'GET',
                'callback' => [$api_methods, 'get_history'],
            ]
        );
    }

}
