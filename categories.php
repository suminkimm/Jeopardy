<?php
require('simple_html_dom.php');
include("config_db.php");
session_start();

if ($_SESSION['valid'] == 1) {

    ?>
    <html>
    <head>
        <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css">
        <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.7.2/css/all.css"
              integrity="sha384-fnmOCqbTlWIlj8LyTjo7mOUStjsKC4pOpQbqyi7RrhN7udi9RwhKkMHpvLbHG9Sr"
              crossorigin="anonymous">

        <!--        for modal:-->
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js"></script>
        <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js"></script>

        <!--        for date picker:-->
        <link rel="stylesheet" href="https://code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.1.0/jquery.min.js"></script>
        <script src="https://code.jquery.com/jquery-3.4.1.js"
                integrity="sha256-WpOohJOqMqqyKL9FccASB9O0KwACQJpFTUBLTYOVvVU=" crossorigin="anonymous"></script>
        <script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>

        <script type="text/javascript"
                src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.4.1/js/bootstrap-datepicker.min.js"></script>
        <link rel="stylesheet"
              href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.4.1/css/bootstrap-datepicker3.css"/>

        <link rel="stylesheet" href="https://use.typekit.net/zhq8pzz.css">
        <link rel="stylesheet" type="text/css" href="css.css">

    </head>

    <body>
    <nav class="navbar sticky-top navbar-expand-lg">
        <div class="container-fluid">
            <div class="navbar-header">
                <a class="navbar-brand" href="categories.php" style="font-size: 30px;">Jeopardy!</a>
            </div>
            <ul class="nav navbar-nav ml-auto">
                <li class="nav-item">
                    <button class="menu-button" onclick="window.location= 'play_game.php'"> Play Game</button>
                </li>
                <li class="nav-item">
                    <button class="menu-button" onclick="window.location= 'view_favorites.php'"> View Favorites</button>
                </li>
                <li class="nav-item">
                    <button class="menu-button" onclick="window.location= 'logout.php'">Log Out</button>
                </li>
            </ul>
        </div>
    </nav>

    <div class="container">
        <h1>Search Jeopardy!</h1>
        <form method="post" action="#">
            <div class="row" style="text-align: center;">
                <!--                search bar-->
                <div class="col-lg-12 col-xs-12 search-bar">
                    <input type="text" name="search-answer" placeholder="Search by category keywords">
                    <button type="submit" name="submit"><i class="fa fa-search"></i></button>
                </div>
            </div>


    <?php

    if (isset($_POST['submit'])) { // the search function was used
        $user_id = $_SESSION['user_id'];

        // get all inputted search criteria
        $search_ans = $_POST['search-answer'];
        $sort_by = $_POST['sortBy'];

        if ($_POST['from-date'] != null) {
            $from_date = date("Y-m-d", strtotime($_POST['from-date']));
        }

        if ($_POST['to-date'] != null) {
            $to_date = date("Y-m-d", strtotime($_POST['to-date']));
        }

        $difficulty = $_POST['difficulty'];

        $url = "http://jservice.io/search?query=" . $search_ans;
        $html = file_get_html($url);
        $results_exist = 0;
        echo "<table class='center'>";
        foreach ($html->find('a') as $category) {
            echo "<tr>";
            echo "<td>";
            $category_id = str_replace('/popular/', '', $category->href);
            $cat = $category->text();
            if ($cat != "jService" && $cat != "Home") {
                $results_exist = 1;
                echo "<a href='main.php?cat_id=" . $category_id . "&cat=" . $cat . "'>" . $cat . "</a>";
            }
            echo "<td>";
            echo "</tr>";
        }

        if ($results_exist == 0) {
            echo "<tr><td>No Results Found</td></tr>";
        }
        echo "</table>";
    }
}
else {
    include("error.php");
}
?>