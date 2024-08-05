<?php

if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly.
}

use Carbon_Fields\Container;
use Carbon_Fields\Field;

class Manage_Movie_Quiz_Question
{
    public function __construct()
    {

        add_action('init', [$this, 'create_quiz_questions_post_type']);
        add_action('carbon_fields_register_fields', [$this, 'attach_quiz_question_fields']);

    }

    function create_quiz_questions_post_type()
    {
        $labels = [
            'name' => 'Quiz Questions',
            'singular_name' => 'Quiz Question',
            'menu_name' => 'Quiz Questions',
            'name_admin_bar' => 'Quiz Question',
            'add_new' => 'New Quiz Question',
            'add_new_item' => 'New Quiz Question',
            'new_item' => 'New Quiz Question',
            'edit_item' => 'Edit Quiz Question',
            'view_item' => 'View Quiz Question',
            'all_items' => 'All Quiz Questions',
            'search_items' => 'Search Quiz Questions',
            'not_found' => 'No Quiz Questions found.',
            'not_found_in_trash' => 'No Quiz Questions found in Trash.',
        ];

        $args = [
            'labels' => $labels,
            'public' => false,
            'show_ui' => true,
            'menu_position' => 31,
            'show_in_menu' => true,
            'has_archive' => true,
            'supports' => ['title'],
            'menu_icon' => 'dashicons-welcome-learn-more',
        ];

        register_post_type(Movie_Quiz_Constants::QUESTION_INFO, $args);
    }

    function attach_quiz_question_fields()
    {
        Container::make('post_meta', __('Quiz Question Details'))
            ->where('post_type', '=', Movie_Quiz_Constants::QUESTION_INFO)
            ->add_fields([
                Field::make('text', Movie_Quiz_Constants::QUESTION_TITLE, __('Question Title'))
                    ->set_required(true),

                Field::make('image', Movie_Quiz_Constants::QUESTION_IMAGE, __('Question Image'))
                    ->set_value_type('url')
                    ->set_required(true),

                Field::make('complex', Movie_Quiz_Constants::QUESTION_ANSWERS, __('Answers'))
                    ->add_fields([
                        Field::make('text', Movie_Quiz_Constants::QUESTION_ANSWERS_OPTION, __('Answer'))
                            ->set_required(true),
                        Field::make('checkbox', Movie_Quiz_Constants::QUESTION_CORRECT_ANSWER, __('Is Correct Answer?'))->set_help_text('check this if it is the correct answer to the question')
                            ->set_option_value('1'),
                    ])
                    ->set_required(true),

                Field::make('select', Movie_Quiz_Constants::QUESTION_DIFFICULTY, __('Difficulty Level'))
                    ->add_options([
                        'easy' => 'Easy',
                        'medium' => 'Medium',
                        'hard' => 'Hard',
                    ])
                    ->set_required(true)
            ]);

    }

}