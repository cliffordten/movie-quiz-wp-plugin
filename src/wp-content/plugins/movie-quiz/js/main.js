jQuery(document).ready(function ($) {
  var USER_ID_STORAGE_KEY = "movie_quiz_user_id";

  function showError(message) {
    $("#form_error")
      .html(message)
      .css({
        padding: "5px",
      })
      .fadeIn();

    setTimeout(function () {
      $("#form_error")
        .html("")
        .css({
          padding: "0px",
        })
        .fadeIn();
    }, 2000);
  }

  function showSuccess(message) {
    $("#form_success")
      .html(message)
      .css({
        padding: "5px",
      })
      .fadeIn();

    setTimeout(function () {
      $("#form_success")
        .html("")
        .css({
          padding: "0px",
        })
        .fadeIn();
    }, 2000);
  }

  function openTab(evt, tabName) {
    $(".tabcontent").hide();
    $(".tablinks").removeClass("active");

    $("#" + tabName).show();
    if (evt) {
      $(evt.currentTarget).addClass("active");
    } else {
      $(".tablinks[data-tab='" + tabName + "']").addClass("active");
    }
  }

  // Set the default open tab
  openTab(null, $(".tablinks").first().attr("data-tab"));

  // Add click event listener for tab buttons
  $(".tablinks").on("click", function (evt) {
    var tabName = $(this).attr("data-tab");
    openTab(evt, tabName);
  });

  // Add click event listener for the start button
  $("#startQuizButton").on("click", function () {
    $("#quizContent").show();
    $(this).hide();

    // Disable the "Show History" tab
    $(".tablinks[data-tab='ShowHistory']").addClass("disabled");
    $(".tablinks[data-tab='ShowHistory']").off("click");
  });

  // Add click event listener for the finish button
  $("#quizResult").on("click", function () {
    window.location.reload();
  });

  // Handle next and previous question buttons
  $(".next, .previous").on("click", function (e) {
    e.preventDefault();
    var button = $(this);
    var url = button.attr("href");

    // Check if the current question has been answered
    if (button.hasClass("next")) {
      var currentQuestion = $("#question-" + currentQuestionIndex);
      var isAnswered =
        currentQuestion.find("input[type='radio']:checked").length > 0;

      if (!isAnswered) {
        showError("Please select an answer before proceeding.");
        return;
      }
    }

    $.ajax({
      url: url,
      success: function (response) {
        $(".quiz-question").html($(response).find(".quiz-question").html());
      },
    });
  });

  // Initialize question index
  var currentQuestionIndex = 1;
  var totalQuestions = $("#quiz-container .quiz-question").length;

  function showQuestion(index) {
    $(".quiz-question").hide();
    $("#question-" + index).show();

    $("#prevQuestion").toggle(index > 1);
    $("#nextQuestion").toggle(index < totalQuestions);
    $("#submitQuiz").toggle(index === totalQuestions);
  }

  $("#nextQuestion").on("click", function () {
    var currentQuestion = $("#question-" + currentQuestionIndex);
    var isAnswered =
      currentQuestion.find("input[type='radio']:checked").length > 0;

    if (isAnswered && currentQuestionIndex < totalQuestions) {
      currentQuestionIndex++;
      showQuestion(currentQuestionIndex);
    } else if (!isAnswered) {
      showError("Please select an answer before proceeding.");
    }
  });

  $("#prevQuestion").on("click", function () {
    if (currentQuestionIndex > 1) {
      currentQuestionIndex--;
      showQuestion(currentQuestionIndex);
    }
  });

  function finishQuiz(score) {
    $("#quizResultScore").html(score).fadeIn();
    $("#quizResult").show().css({
      display: "flex",
      "flex-direction": "column",
      "justify-content": "center",
      "align-items": "center",
      "padding-bottom": "40px",
    });
    $("#quizContent").hide();

    // show the "Show History" tab
    $(".tablinks[data-tab='ShowHistory']").removeClass("disabled");
    // Add click event listener for tab buttons
    $(".tablinks").on("click", function (evt) {
      var tabName = $(this).attr("data-tab");
      openTab(evt, tabName);
    });
  }

  $("#submitQuiz").on("click", function () {
    // Collect answers and submit them
    var answers = [];
    $(".quiz-question").each(function () {
      var questionIndex = $(this).attr("id").replace("question-", "");
      var questionId = $(this).attr("question-id");

      var selectedAnswer = $(
        "input[name='question-" + questionIndex + "']:checked"
      ).val();

      answers.push({
        question_id: questionId,
        answer: selectedAnswer,
      });
    });

    var user_id = localStorage.getItem(USER_ID_STORAGE_KEY);

    console.log("Quiz Answers:", answers);

    // Send answers to the server via AJAX
    $.ajax({
      url: "/wp-json/api/movie-quiz/submit",
      method: "POST",
      data: JSON.stringify({ answers, user_id }),
      contentType: "application/json",
      success: function (response) {
        console.log("Server Response:", response);

        if (!user_id) {
          localStorage.setItem(USER_ID_STORAGE_KEY, response.user_id);
        }

        finishQuiz(response.user_score);

        showSuccess("Quiz submitted successfully!");
      },
      error: function (xhr, status, error) {
        console.error("Submission Error:", error);
        showError(
          "An error occurred while submitting the quiz. Please try again."
        );
      },
    });
  });

  // Set the default open question
  showQuestion(currentQuestionIndex);

  // load user history
  // Function to load quiz history
  function loadQuizHistory(userId) {
    $("#historyLoading")
      .html("Loading...")
      .css({
        padding: "5px",
      })
      .fadeIn();

    $.ajax({
      url: `/wp-json/api/movie-quiz/history/${userId}`,
      method: "GET",
      success: function (response) {
        // Clear previous content
        $("#historyContent").empty();
        $("#historyLoading").hide(); // Hide loading indicator

        // Check if there is data
        if (response.results && response.results.length > 0) {
          // Create table content
          let content = "";

          // Populate content for each entry
          $.each(response.results, function (index, entry) {
            content += `
              <div>
                <h3>Score: ${entry.user_score}</h3>
                <table>
                  <thead>
                    <tr>
                      <th>No</th>
                      <th>Question Title</th>
                      <th>User Answer</th>
                      <th>Status</th>
                    </tr>
                  </thead>
                  <tbody>
            `;

            $.each(entry.user_response, function (i, item) {
              content += `
                <tr>
                  <td>${i + 1}</td>
                  <td>${item.question_title || "N/A"}</td>
                  <td>${item.answer || "N/A"}</td>
                  <td>${item.status || "N/A"}</td>
                </tr>
              `;
            });

            content += `
                  </tbody>
                </table>
              </div>
              <hr> <!-- Optional: separator between entries -->
            `;
          });

          // Append the content to the history content
          $("#historyContent").html(content);
        } else {
          $("#historyContent").html("<p>No history available.</p>");
        }
      },
      error: function (xhr, status, error) {
        console.error("Error fetching quiz history:", error);
        $("#historyLoading").hide(); // Hide loading indicator
        $("#historyContent").html(
          "<p>An error occurred while fetching the quiz history.</p>"
        );
      },
    });
  }

  var user_id = localStorage.getItem(USER_ID_STORAGE_KEY);

  if (user_id) {
    loadQuizHistory(user_id);
  }
});
