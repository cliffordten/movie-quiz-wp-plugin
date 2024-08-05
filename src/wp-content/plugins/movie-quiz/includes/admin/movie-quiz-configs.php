<?php

if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly.
}

use Carbon_Fields\Container;
use Carbon_Fields\Field;

class Movie_Quiz_Admin_Configs
{
    public function __construct()
    {

        add_action('carbon_fields_register_fields', [$this, 'create_config_page']);
    }

    function create_config_page()
    {
        Container::make('theme_options', __('Quiz Config'))
            ->set_page_menu_position(30)
            ->set_icon('dashicons-superhero-alt')
            ->add_fields(
                [
                    Field::make('html', Movie_Quiz_Constants::CONFIG_INFO)
                        ->set_html('<h3>' . __('This permits you to override the default configuration for the Movie Quiz Scores') . '</h3>'),
                    Field::make('text', Movie_Quiz_Constants::EASY_SCORE, __('Easy Score'))
                        ->set_attribute('type', 'number')
                        ->set_attribute('placeholder', 'enter your desired score for the easy questions')
                        ->set_help_text('default value is 10'),
                    Field::make('text', Movie_Quiz_Constants::MEDIUM_SCORE, __('Medium Score'))
                        ->set_attribute('type', 'number')
                        ->set_attribute('placeholder', 'enter your desired score for the medium questions')
                        ->set_help_text('default value is 25'),
                    Field::make('text', Movie_Quiz_Constants::DIFFICULT_SCORE, __('Difficult Score'))
                        ->set_attribute('type', 'number')
                        ->set_attribute('placeholder', 'enter your desired score for the difficult questions')
                        ->set_help_text('default value is 50'),
                ]
            );
    }
}