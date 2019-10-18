<?php
session_start();

require('config_db.php');

$user_id = $_SESSION['user_id'];
//$question = trim($_GET['q'], '"');
$question = $_GET['q'];
$question = pg_escape_literal($question);
$ans = pg_escape_literal($_GET['a']);
$airdate = pg_escape_literal($_GET['air']);
$category = pg_escape_literal($_GET['cat']);
$difficulty = $_GET['d'];
$q_id = $_GET['qid'];

// check if delete
$sql = "SELECT * FROM public.rel_favorite_qs WHERE user_id='$user_id' AND question_id='$q_id'";
$result = pg_query($conn, $sql);

if (pg_num_rows($result) != 0) { // already exists, delete
    $delete =pg_query($conn, "DELETE FROM public.rel_favorite_qs WHERE user_id='$user_id' AND question_id='$q_id'");
}

else {
    // add into questions table if it doesn't already exist

    $sql = " INSERT INTO public.questions (question, answer, airdate, category, difficulty, q_id) 
        SELECT $question, $ans, $airdate, $category, $difficulty, $q_id
            WHERE NOT EXISTS (
            SELECT 1 FROM public.questions 
            WHERE q_id = $q_id)";
    $insert_question = pg_query($conn, $sql);


    $sql = "INSERT INTO public.rel_favorite_qs (user_id, question_id) VALUES ('$user_id', '$q_id')";
    $insert_rel_table = pg_query($conn, $sql);

}

