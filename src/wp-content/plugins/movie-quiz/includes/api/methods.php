<?php

if (!defined('ABSPATH')) {
    exit;
}


class Movie_Quiz_API_Methods
{


    static function array_find(array $array, callable $callback)
    {
        foreach ($array as $key => $value) {
            if ($callback($value, $key)) {
                return $value;
            }
        }
        return null;
    }

    public function submit_answers(WP_REST_Request $request)
    {

        $params = $request->get_json_params();
        $request_user_id = $params['user_id'];
        $answers = $params['answers'];
        $user_id = User_Info::generate_user_id($request_user_id);
        $quiz_config = new QuizConfig();
        $score = 0;
        $user_response = [];


        foreach ($answers as $answer) {
            $question_id = $answer["question_id"];
            $user_answer = $answer["answer"];
            $answer["status"] = "Wrong";

            $question_title = carbon_get_post_meta($question_id, Movie_Quiz_Constants::QUESTION_TITLE);
            $answer["question_title"] = $question_title;

            $question_difficulty = carbon_get_post_meta($question_id, Movie_Quiz_Constants::QUESTION_DIFFICULTY);
            $question_answers = carbon_get_post_meta($question_id, Movie_Quiz_Constants::QUESTION_ANSWERS);
            $correct_answer = Movie_Quiz_API_Methods::array_find($question_answers, function ($question_answer) {
                return !empty($question_answer[Movie_Quiz_Constants::QUESTION_CORRECT_ANSWER]);
            });

            if (!empty($correct_answer)) {
                $question_score = $quiz_config->get_difficulty_score($question_difficulty);
                $correct_option = $correct_answer[Movie_Quiz_Constants::QUESTION_ANSWERS_OPTION];

                if ($correct_option === $user_answer) {
                    $answer["status"] = "Correct";
                    $score += $question_score;
                }
            }

            array_push($user_response, $answer); // Push element


        }


        $post_data = [
            'post_title' => $request_user_id,
            'post_status' => 'publish',
            'post_type' => Movie_Quiz_Constants::RESPONSE,
        ];

        // Insert the post into the database
        $post_id = wp_insert_post($post_data);

        add_post_meta($post_id, "user_id", $request_user_id);
        add_post_meta($post_id, "answers", json_encode($user_response));
        add_post_meta($post_id, "score", $score);

        return new WP_REST_Response(
            [
                'message' => 'Quiz submitted successfully',
                "user_id" => $user_id,
                "user_score" => $score
            ],
            200
        );
    }

    public function get_history(WP_REST_Request $request)
    {
        $user_id = $request->get_param('user_id');

        $args = [
            'post_type' => Movie_Quiz_Constants::RESPONSE,
            'meta_query' => [
                [
                    'key' => 'user_id',
                    'value' => $user_id,
                    'compare' => '='
                ]
            ],
            'posts_per_page' => -1
        ];

        // Perform the query
        $query = new WP_Query($args);
        $results = [];

        // Check if there are posts
        if ($query->have_posts()) {

            // Loop through the posts
            while ($query->have_posts()) {
                $query->the_post();
                $post_id = get_the_ID();
                $results[] = [
                    'post_id' => $post_id,
                    'title' => get_the_title(),
                    'content' => get_the_content(),
                    'user_id' => get_post_meta($post_id, 'user_id', true),
                    'user_score' => get_post_meta($post_id, 'score', true),
                    'date' => get_post_meta($post_id, 'date', true),
                    'user_response' => json_decode(get_post_meta($post_id, 'answers', true), true)
                ];
            }

            // Restore original Post Data
            wp_reset_postdata();

        }

        return new WP_REST_Response(
            [
                'message' => 'Quiz submitted successfully',
                "user_id" => $user_id,
                "results" => $results
            ],
            200
        );

    }
}