<?php

if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly.
}

class Movie_Quiz_Question_Answers
{
    public function __construct()
    {

        add_action('init', [$this, 'create_quiz_response_post_type']);
        add_filter('manage_' . Movie_Quiz_Constants::RESPONSE . '_posts_columns', [$this, 'create_custom_response_columns']);
        add_action('manage_' . Movie_Quiz_Constants::RESPONSE . '_posts_custom_column', [$this, 'fill_custom_response_columns'], 10, 2);
    }

    function create_quiz_response_post_type()
    {
        $labels = [
            'name' => 'Quiz Responses',
            'singular_name' => 'Quiz Response',
            'menu_name' => 'Quiz Response',
            'name_admin_bar' => 'Quiz Responses',
            'add_new' => 'New Quiz Responses',
            'add_new_item' => 'New Quiz Responses',
            'new_item' => 'New Quiz Responses',
            'edit_item' => 'Edit Quiz Responses',
            'view_item' => 'View Quiz Responses',
            'all_items' => 'All Quiz Responses',
            'search_items' => 'Search Quiz Responses',
            'not_found' => 'No Quiz Responses found.',
            'not_found_in_trash' => 'No Quiz Responses found in Trash.',
        ];

        $args = [
            'labels' => $labels,
            'public' => false,
            'menu_position' => 32,
            'show_ui' => true,
            'show_in_menu' => true,
            'has_archive' => true,
            'supports' => false,
            'capability_type' => 'post',
            'capabilities' => [
                'create_posts' => false,
            ],
            'menu_icon' => 'dashicons-welcome-learn-more',
        ];

        register_post_type(Movie_Quiz_Constants::RESPONSE, $args);
    }

    function create_custom_response_columns($columns)
    {


        $columns = [

            'cb' => $columns['cb'],
            'user_id' => __('User Id', 'movie-quiz'),
            'score' => __('User Score', 'movie-quiz'),
            'date' => 'Date',
            'answers' => __('User Responses', 'movie-quiz'),
        ];

        return $columns;
    }

    function fill_custom_response_columns($column, $post_id)
    {
        // Return meta data for individual posts on table

        switch ($column) {

            case 'user_id':
                echo esc_html(get_post_meta($post_id, 'user_id', true));
                break;

            case 'answers':
                $data = json_decode(get_post_meta($post_id, 'answers', true), true);
                if (!empty($data) && is_array($data)) {
                    // Start the HTML table
                    echo '<table border="1" cellspacing="0" cellpadding="5">';
                    echo '<thead>
                            <tr>
                                <th>Question ID</th>
                                <th>Question Title</th>
                                <th>User Answer</th>
                                <th>Status</th>
                            </tr>
                           </thead>';
                    echo '<tbody>';

                    // Iterate through each item in the array
                    foreach ($data as $item) {
                        echo '<tr>';
                        echo '<td>' . htmlspecialchars($item['question_id']) . '</td>';
                        echo '<td>' . htmlspecialchars($item['question_title']) . '</td>';
                        echo '<td>' . htmlspecialchars($item['answer']) . '</td>';
                        echo '<td>' . htmlspecialchars($item['status']) . '</td>';
                        echo '</tr>';
                    }

                    // End the HTML table
                    echo '</tbody>';
                    echo '</table>';
                } else {
                    echo $data;
                }
                break;

            case 'score':
                echo esc_html(get_post_meta($post_id, 'score', true));
                break;

        }
    }



}