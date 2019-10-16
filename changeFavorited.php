<?php
session_start();

require('config_db.php');

$user_id = $_SESSION['user_id'];
$question = $_GET['q'];
$ans = $_GET['a'];
$airdate = $_GET['air'];
$category = $_GET['cat'];
$difficulty = $_GET['d'];
$q_id = $_GET['qid'];

// check if delete
$sql = "SELECT * FROM spublic.rel_favorite_qs WHERE user_id='$user_id' AND question_id='$q_id'";
$result = pg_query($conn, $sql);

if (pg_num_rows($result) != 0) { // already exists, delete
    $delete =pg_query($conn, "DELETE FROM public.rel_favorite_qs WHERE user_id='$user_id' AND question_id='$q_id'");
}

else {
    // add into table if it doesn't already exist
    $sql = "IF NOT EXISTS (
        SELECT * FROM public.questions 
        WHERE q_id = '$q_id')
        INSERT INTO public.questions (question, answer, airdate, category, difficulty) 
        VALUES ('$question', '$ans', '$airdate', '$category', '$difficulty')";
    $insert_question = pg_query($conn, $sql);


    $sql = "INSERT INTO public.rel_favorite_qs (user_id, question_id) VALUES ('$user_id', '$q_id')";
    $insert_rel_table = pg_query($conn, $sql);
}

