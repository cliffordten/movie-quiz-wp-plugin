<?php
if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly.
}

class QuizConfig
{
    private $easy_score = 10;
    private $medium_score = 25;
    private $difficult_store = 50;

    // Constructor
    public function __construct()
    {
        $easy_score = intval(carbon_get_theme_option(Movie_Quiz_Constants::EASY_SCORE));
        $medium_score = intval(carbon_get_theme_option(Movie_Quiz_Constants::MEDIUM_SCORE));
        $difficult_store = intval(carbon_get_theme_option(Movie_Quiz_Constants::DIFFICULT_SCORE));

        if ($easy_score) {
            $this->easy_score = $easy_score;
        }

        if ($medium_score) {
            $this->medium_score = $medium_score;
        }

        if ($difficult_store) {
            $this->difficult_store = $difficult_store;
        }
    }

    public function get_difficulty_score($difficulty)
    {
        return match ($difficulty) {
            'easy' => $this->easy_score,
            'medium' => $this->medium_score,
            'hard' => $this->difficult_store,
            default => $this->easy_score, // Default to easy score if difficulty is not recognized
        };
    }

}