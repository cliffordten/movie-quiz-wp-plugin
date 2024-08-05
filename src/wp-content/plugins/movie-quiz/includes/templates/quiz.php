<?php
if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly.
}

class Movie_Quiz_Question_Template
{
    public function render_quiz_questions_template()
    {
        // Query for quiz questions
        $args = [
            'post_type' => Movie_Quiz_Constants::QUESTION_INFO,
            'posts_per_page' => -1,
            'post_status' => 'publish',
        ];

        $quiz_questions = new WP_Query($args);

        if ($quiz_questions->have_posts()) {
            echo '<div id="quiz-container">';
            $index = 1;
            $total_questions = $quiz_questions->post_count;

            while ($quiz_questions->have_posts()) {
                $quiz_questions->the_post();

                $question_id = get_the_ID();
                $question_image = carbon_get_post_meta($question_id, Movie_Quiz_Constants::QUESTION_IMAGE);
                $question_title = carbon_get_post_meta($question_id, Movie_Quiz_Constants::QUESTION_TITLE);
                $answers = carbon_get_post_meta($question_id, Movie_Quiz_Constants::QUESTION_ANSWERS);

                ?>
                <div class="quiz-question" question-id="<?php echo $question_id; ?>" id="question-<?php echo $index; ?>"
                    style="display: <?php echo $index === 1 ? 'block' : 'none'; ?>;">

                    <p>Question <?php echo $index; ?>/<?php echo $total_questions; ?></p>

                    <?php if ($question_image): ?>
                        <img src="<?php echo esc_url($question_image); ?>" alt="<?php echo esc_attr($question_title); ?>" />
                    <?php else: ?>
                        <p>No image available</p>
                    <?php endif; ?>

                    <h4><?php echo esc_html($question_title); ?></h4>

                    <?php if ($answers): ?>
                        <ol class="answers">
                            <?php foreach ($answers as $answer): ?>
                                <li>
                                    <label>
                                        <input type="radio" name="question-<?php echo $index; ?>"
                                            value="<?php echo esc_attr($answer[Movie_Quiz_Constants::QUESTION_ANSWERS_OPTION]); ?>" />
                                        <?php echo esc_html($answer[Movie_Quiz_Constants::QUESTION_ANSWERS_OPTION]); ?>
                                    </label>
                                </li>
                            <?php endforeach; ?>
                        </ol>
                    <?php else: ?>
                        <p>No answers available</p>
                    <?php endif; ?>
                </div>
                <?php

                $index++;
            }

            echo '<div id="navigation-buttons">';
            if ($total_questions > 1) {
                echo '<button id="prevQuestion" style="display: none;">Previous</button>';
                echo '<button id="nextQuestion">Next</button>';
            }
            echo '<button id="submitQuiz" style="display: none;">Submit Quiz</button>';
            echo '</div>';

            echo '</div>';
        } else {
            echo '<p>No quiz questions available.</p>';
        }

        wp_reset_postdata();
    }
}


