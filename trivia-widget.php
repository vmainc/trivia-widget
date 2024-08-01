<?php
/*
Plugin Name: Trivia Widget
Description: Adds a trivia question widget to the WordPress admin dashboard.
Version: 1.0
Author: Your Name
*/

// Hook to add the dashboard widget
add_action('wp_dashboard_setup', 'add_trivia_dashboard_widget');

function add_trivia_dashboard_widget() {
    wp_add_dashboard_widget('trivia_dashboard_widget', 'Trivia Question', 'trivia_dashboard_widget_display');
}

// Function to display the widget
function trivia_dashboard_widget_display() {
    // Include the trivia questions
    include('trivia-questions.php');

    // Get a random question
    $random_question = $trivia_questions[array_rand($trivia_questions)];
    
    // Display the question and options
    echo '<p>' . $random_question['question'] . '</p>';
    echo '<form method="post">';
    foreach ($random_question['options'] as $index => $option) {
        echo '<input type="radio" name="trivia_answer" value="' . $index . '"> ' . $option . '<br>';
    }
    echo '<input type="submit" name="submit_trivia" value="Submit">';
    echo '</form>';
    
    // Check if an answer is submitted
    if (isset($_POST['submit_trivia'])) {
        $selected_answer = intval($_POST['trivia_answer']);
        $correct_answer = $random_question['answer'];
        
        if ($selected_answer === $correct_answer) {
            echo '<p>Correct!</p>';
            $score = get_user_meta(get_current_user_id(), 'trivia_score', true);
            $score++;
            update_user_meta(get_current_user_id(), 'trivia_score', $score);
        } else {
            echo '<p>Incorrect. The correct answer is ' . $random_question['options'][$correct_answer] . '.</p>';
        }
    }

    // Display the user's score
    $score = get_user_meta(get_current_user_id(), 'trivia_score', true);
    if ($score === '') {
        $score = 0;
    }
    echo '<p>Your Score: ' . $score . '</p>';
}
?>
