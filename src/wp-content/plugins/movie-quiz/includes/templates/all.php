<?php

if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly.
}


class Movie_Quiz_Templates
{

    public function __construct()
    {
        require_once MOVIE_QUIZ_PATH . 'includes/templates/quiz.php';
    }

    public function render_quiz_template()
    {
        $quiz_question = new Movie_Quiz_Question_Template();

        ob_start();

        ?>

        <div id="movie-quiz-container">
            <div id="form_success"></div>
            <div id="form_error"></div>

            <div class="tab">
                <button class="tablinks" data-tab="TakeQuiz">Take a Quiz</button>
                <button class="tablinks" data-tab="ShowHistory">Show Quiz History</button>
            </div>

            <div id="TakeQuiz" class="tabcontent">

                <div id="startQuizButton">
                    <h3>Ready?</h3>
                    <p>Click the button below to start the quiz.</p>
                    <button>Start Quiz</button>
                </div>

                <div id="quizContent" style="display:none;">
                    <?php
                    $quiz_question->render_quiz_questions_template()
                        ?>
                </div>

                <div id="quizResult" style="display:none;">
                    <h3>Your Quiz Result is <span id="quizResultScore"></span></h3>
                    <p>Click the button below to start the quiz.</p>
                    <button>Finished Quiz</button>
                </div>

            </div>

            <div id="ShowHistory" class="tabcontent">
                <div id="historyContent">
                    <div id="historyLoading"></div>

                    <!-- Quiz history will be loaded here -->
                </div>
            </div>
        </div>


        <?php

        return ob_get_clean();

    }

}
