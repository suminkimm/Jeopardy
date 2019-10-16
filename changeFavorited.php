<?php
session_start();

require('config_db.php');

$user_id = $_SESSION['user_id'];
$question = pg_escape_literal($_GET['q']);
$ans = pg_escape_literal($_GET['a']);
$airdate = pg_escape_literal($_GET['air']);
$category = pg_escape_literal($_GET['cat']);
$difficulty = $_GET['d'];
$q_id = $_GET['qid'];

// check if delete
$sql = "SELECT * FROM spublic.rel_favorite_qs WHERE user_id='$user_id' AND question_id='$q_id'";
$result = pg_query($conn, $sql);

if (pg_num_rows($result) != 0) { // already exists, delete
    $delete =pg_query($conn, "DELETE FROM public.rel_favorite_qs WHERE user_id='$user_id' AND question_id='$q_id'");
}

else {
    // add into questions table if it doesn't already exist

    $sql = " INSERT INTO public.questions (question, answer, airdate, category, difficulty, q_id) 
        VALUES ('$question', '$ans', '$airdate', '$category', '$difficulty', '$q_id')
        WHERE NOT EXISTS (
        SELECT 1 FROM public.questions 
        WHERE q_id = '$q_id')";
    $insert_question = pg_query($conn, $sql);

    echo $question;
    echo $ans;
    echo $airdate;
    echo $category;
    echo $difficulty;
    echo $q_id;
    echo $sql;
    echo "error: " .pg_last_error($conn);

    if ($insert_question) {
        $sql = "INSERT INTO public.rel_favorite_qs (user_id, question_id) VALUES ('$user_id', '$q_id')";
        $insert_rel_table = pg_query($conn, $sql);
    }
}

