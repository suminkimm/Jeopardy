<?php
session_start();

require('config_db.php');

$resid = $_GET['q'];
$user_id = $_SESSION['user_id'];

// Check to see if the question has already been favorited by the user
$exists = "Add to Favorites";
$check_fav = pg_query($conn, "SELECT * FROM public.rel_favorite_qs WHERE user_id = '$user_id' AND question_id='$resid'");
if (pg_num_rows($check_fav)!=0) {
    $exists = "Remove from Favorites";
}
echo $exists;