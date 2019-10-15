<?php //include("config_db.php"); ?>

<html>
    <head>
        <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css">
        <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.7.2/css/all.css" integrity="sha384-fnmOCqbTlWIlj8LyTjo7mOUStjsKC4pOpQbqyi7RrhN7udi9RwhKkMHpvLbHG9Sr" crossorigin="anonymous">
        <link rel="stylesheet" href="https://use.typekit.net/zhq8pzz.css">
        <link rel="stylesheet" type="text/css" href="css.css">

        <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js"></script>
        <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js"></script>

        <script src="https://code.jquery.com/jquery-3.4.1.js" integrity="sha256-WpOohJOqMqqyKL9FccASB9O0KwACQJpFTUBLTYOVvVU=" crossorigin="anonymous"></script>
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.1.0/jquery.min.js"></script>
    </head>
    <body>
        <nav class="navbar sticky-top navbar-expand-lg">
            <div class="container-fluid">
                <div class="navbar-header">
                    <a class="navbar-brand" href="main.php" style="font-size: 30px;">Jeopardy!</a>
                </div>
                <ul class="nav navbar-nav ml-auto">
                    <li class="nav-item"><button class="PlayGame" onclick="window.location= 'play_game.php'"> Play Game</button></li>
                    <li class="nav-item"><button class="ViewFavorites" onclick="window.location= 'view_favorites.php'"> View Favorites</button></li>
                </ul>
            </div>
        </nav>
        <div class="container">
            <div class="row">
                <div class="col-lg-12 col-xs-12">
                    <h1>View Favorite Q's!</h1>
                </div>
            </div>
            <div class="row">
                <table>

                </table>
            </div>
        </div>
    </body>
</html>