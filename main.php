<?php include("config_db.php");
session_start();

if($_SESSION['valid'] == 1) { ?>
    <html>
    <head>
        <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css">
        <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.7.2/css/all.css" integrity="sha384-fnmOCqbTlWIlj8LyTjo7mOUStjsKC4pOpQbqyi7RrhN7udi9RwhKkMHpvLbHG9Sr" crossorigin="anonymous">

        <!--        for modal:-->
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js"></script>
        <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js"></script>

        <!--        for date picker:-->
        <link rel="stylesheet" href="https://code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.1.0/jquery.min.js"></script>
        <script src="https://code.jquery.com/jquery-3.4.1.js" integrity="sha256-WpOohJOqMqqyKL9FccASB9O0KwACQJpFTUBLTYOVvVU=" crossorigin="anonymous"></script>
        <script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>

        <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.4.1/js/bootstrap-datepicker.min.js"></script>
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.4.1/css/bootstrap-datepicker3.css"/>

        <link rel="stylesheet" href="https://use.typekit.net/zhq8pzz.css">
        <link rel="stylesheet" type="text/css" href="css.css">

    </head>

    <body>
    <nav class="navbar sticky-top navbar-expand-lg">
        <div class="container-fluid">
            <div class="navbar-header">
                <a class="navbar-brand" href="main.php" style="font-size: 30px;">Jeopardy!</a>
            </div>
            <ul class="nav navbar-nav ml-auto">
                <li class="nav-item"><button class="menu-button" onclick="window.location= 'play_game.php'"> Play Game</button></li>
                <li class="nav-item"><button class="menu-button" onclick="window.location= 'view_favorites.php'"> View Favorites</button></li>
                <li class="nav-item"><button class="menu-button" onclick="window.location= 'logout.php'">Log Out</button></li>
            </ul>
        </div>
    </nav>

    <div class="container">
        <h1>Search Jeopardy!</h1>
        <form method="post" action="#">
            <div class="row" style="text-align: center;">
                <div class="col-lg-12 col-xs-12 search-bar">
                    <input type="text" name="search-answer" placeholder="What's your answer?">
                    <button type="submit" name="submit"><i class="fa fa-search"></i></button>
                    <button type="button" id="show-hide" onclick="showDiv()"><i class="fas fa-plus-circle"></i></button>
                </div>
            </div>
            <div class="advanced-search" style="display: none;">
                <div class="row" style="text-align: center;">
                    <div class="col-lg-12 col-xs-12">
                        <table class="center">
                            <tr>
                                <td>
                                    Timeframe aired:
                                </td>
                                <td>
                                    <input type="text" name="from-date" class="datepicker" placeholder="From">
                                </td>
                                <td>
                                    <input type="text" name="to-date" class="datepicker" placeholder="To">
                                </td>
                            </tr>
                        </table>
                    </div>
                    <script type="text/javascript">
                        $('.datepicker').datepicker();
                    </script>
                </div>
                <div class="row" style="text-align: center;">
                    <div class="col-lg-12 col-xs-12" style="text-align: center;">
                        <table class="center">
                            <tr>
                                <td>
                                    Category:
                                    <select name="categories">
                                        <option value="">Select Category</option>
                                        <?php
                                        $url = "http://jservice.io/api/clues";
                                        $json = file_get_contents($url);
                                        $categories = json_decode($json, true);

                                        // sort alphabetically
                                        usort($categories, function ($a, $b) {
                                            return $a['category']['title'] <=> $b['category']['title'];
                                        });

                                        $unique_categories_id = array();

                                        // show only unique categories
                                        foreach ($categories as $category) {
                                            if (!in_array($category['category']['id'], $unique_categories_id)) {
                                                array_push($unique_categories_id, $category['category']['id']);
                                                echo "<option value=". $category['category']['id'] .">". $category['category']['title'] ."</option>";
                                            }
                                        }
                                        ?>
                                    </select>
                                </td>
                                <td>
                                    Difficulty:
                                    <select name="difficulty">
                                        <option value="">Select Difficulty</option>
                                        <?php
                                        $i = 100;
                                        while ($i <= 1000) {
                                            echo "<option value=".$i.">".$i."</option>";
                                            $i += 100;
                                        }
                                        ?>
                                    </select>
                                </td>
                            </tr>
                        </table>
                    </div>
                </div>
                <div class="row" style="text-align: center">
                    <div class="col-lg-12 col-xs-12" style="text-align: center">
                        Sort Results By:
                        <select name="sortBy">
                            <option value="1">A-Z</option>
                            <option value="2">Z-A</option>
                            <option value="3">Difficulty</option>
                        </select>
                    </div>
                </div>
            </div>
        </form>
    </div>
    <div class="modal" id="myModal" tabindex="-1" role="dialog">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Modal title</h5>
                    <button type="button" class="close" onclick="closeModal()" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <p>Modal body text goes here.</p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-primary" id="add-to-fav"></button>
                </div>
            </div>
        </div>
    </div>
    </body>
    </html>

    <script type="text/javascript">
        function showDiv() {
            let x = document.getElementsByClassName('advanced-search')[0];
            let button = document.getElementById('show-hide');
            if (x.style.display == "none") {
                x.style.display = "block";
                button.innerHTML = "<i class=\"fas fa-minus-circle\"></i>";
                console.log("block");

            } else {
                console.log("none");
                x.style.display = "none";
                button.innerHTML = "<i class=\"fas fa-plus-circle\"></i>";

            }
        }

        function getMoreInfo(res_id) {
            let difficulty = document.getElementById('difficulty:' + res_id).innerText;
            if (difficulty == '') {
                difficulty = "N/A";
            }
            let category = document.getElementById('category:' + res_id).innerText;
            if (category == '') {
                category = "N/A";
            }
            let question = document.getElementById('question:' + res_id).innerText;
            if (question == '') {
                question = "N/A";
            }
            let answer = document.getElementById('answer:' + res_id).innerText;
            if (answer == '') {
                answer = "N/A";
            }
            let airdate = document.getElementById('airdate:' + res_id).innerText;
            if (airdate == '') {
                airdate = "N/A";
            }
            else {
                airdate = new Date(airdate);
                airdate = (airdate.getMonth() + 1) + "/" + (airdate.getDate()) + "/" + airdate.getFullYear();
            }

            document.getElementsByClassName('modal-title')[0].innerText = "Q: " + question;
            document.getElementsByClassName('modal-body')[0].innerHTML =
                '<p> Difficulty: ' + difficulty + '<p>' +
                '<p> Category: ' + category + '<p>' +
                '<p> Answer: ' + answer + '<p>' +
                '<p> Air date: ' + airdate + '<p>';

            // check if add or remove favorites
            let addDel = document.getElementById('changeFav:' + res_id).innerText;
            if (addDel == "add") {
                document.getElementById('add-to-fav').innerText="Add to Favorites";
                document.getElementById('add-to-fav').setAttribute("onclick", "changeFavorites(" + res_id + ")");
            }
            else {
                document.getElementById('add-to-fav').innerText="Remove from Favorites";
                document.getElementById('add-to-fav').setAttribute("onclick", "changeFavorites(" + res_id + ")");
            }

            document.getElementById('myModal').style.display="inline";

        }

        function closeModal() {
            document.getElementById('myModal').style.display="none";
        }

        function changeFavorites(res_id) {
            console.log("addToFav");
            let star = document.getElementById("star:" + res_id);

            if(star.style.color == "gold") { // remove this question from favorites
                star.style.color = "black";

            }
            else { // add this question to favorites
                star.style.color = "gold";

            }

            let difficulty = document.getElementById("difficulty:" + res_id).innerText;
            let category = document.getElementById("category:" + res_id).innerText;
            let question = document.getElementById("question:" + res_id).innerText;
            let answer = document.getElementById("answer:" + res_id).innerText;
            let airdate = document.getElementById("airdate:" + res_id).innerText;

            xmlhttp = new XMLHttpRequest();
            xmlhttp.open("GET","changeFavorited.php?q="+question+"&a="+answer+"&air="+airdate+"&cat="+category+"&d="+difficulty+"&qid="+res_id,true);


            xmlhttp.onreadystatechange = function() {
                if(this.readyState == 4 && this.status == 200) {
                    console.log(xmlhttp.responseText);
                }
            }
            xmlhttp.send();

        }
    </script>

    <?php

    if (isset($_POST['submit'])) {
        $user_id = $_SESSION['user_id'];
        $search_ans = $_POST['search-answer'];
        $sort_by = $_POST['sortBy'];

        if ($_POST['from-date'] != null) {
            $from_date = date("Y-m-d", strtotime($_POST['from-date']));
        }

        if ($_POST['to-date'] != null) {
            $to_date = date("Y-m-d", strtotime($_POST['to-date']));
        }

        $category = $_POST['categories'];
        $difficulty = $_POST['difficulty'];


        $url = "http://jservice.io/api/clues";
        $json = file_get_contents($url);
        $results = json_decode($json, true);

        if($search_ans != null) {
            $temp_results = array();

            foreach($results as $res) {

                if (strpos($res['answer'], $search_ans) !== false) {
                    array_push($temp_results, $res);
                }
            }

            $results = $temp_results;
        }

        if ($from_date != null || $to_date != null) { // both dates

            $temp_results = array();

            if ($from_date != null && $to_date !== null) {

                foreach ($results as $res) {
                    if ($res['airdate'] >= $from_date && $res['airdate'] <= $to_date) {
                        array_push($temp_results, $res);
                    }
                }
            }
            elseif ($from_date == null) {

                foreach ($results as $res) {
                    if ($res['airdate'] <= $to_date) {
                        array_push($temp_results, $res);
                    }
                }
            }
            else {
                foreach ($results as $res) {

                    if ($res['airdate'] >= $from_date) {
                        array_push($temp_results, $res);
                    }
                }
            }
            $results = $temp_results;
        }

        if($category != null) {
            $temp_results = array();
            foreach($results as $res) {
                if ($res['category']['id'] == $category) {
                    array_push($temp_results, $res);
                }
            }
            $results = $temp_results;

        }

        if($difficulty != null) { // field not filled

            $temp_results = array();
            foreach($results as $res) {
                if ($res['value'] == $difficulty) {
                    array_push($temp_results, $res);
                }
            }
            $results = $temp_results;
        }

        echo "<table class='center questions-table'>";

        if ($sort_by == null || $sort_by == 1) { // A-Z order
            usort($results, function ($a, $b) {
                return $a['question'] <=> $b['question'];
            });
        }
        elseif ($sort_by == 2) {
            usort($results, function ($a, $b) {
                return $b['question'] <=> $a['question'];
            });
        }
        else {
            usort($results, function ($a, $b) {
                return $a['value'] <=> $b['value'];
            });
        }

        foreach($results as $res) {
            if ($res['question'] != "") {

                // check if the question already exists in favorites
                $exists = 0;
                $resid = $res['id'];
                $check_fav = pg_query($conn, "SELECT * FROM public.rel_favorite_qs WHERE user_id = '$user_id' AND question_id='$resid'");
                if (pg_num_rows($check_fav)!=0) {
                    $exists = 1;
                }

                // build search results table of jeopardy questions
                echo "<tr>";
                echo "<td>";
                echo "Q:";
                echo "</td>";
                echo "<td>";
                echo $res['question'];
                echo "</td>";
                echo "<td>";
                echo "<button type='button' onclick='getMoreInfo(".$res['id'].")' name='moreInfo'><i class=\"fas fa-info-circle\"></i></button>";
                ?>
                <td><button type='button' id='star:<?php echo $res['id']; ?>'onclick='changeFavorites(<?php echo $res['id']; ?>)'>
                <?php
                if ($exists == 1) { // star is gold if favorited
                    echo "<i class='fas fa-star add-to-fav' style='color:gold'></i></button></td>";
                }
                else {
                    echo "<i class='fas fa-star add-to-fav' style='color:black'></i></button></td>";
                }

                echo "</tr>";
                echo "<span id='difficulty:" .$res['id']. "' hidden>" .$res['value']. "</span>";
                echo "<span id='category:" .$res['id']. "' hidden>" .$res['category']['title']. "</span>";
                echo "<span id='question:" .$res['id']. "' hidden>" .$res['question']. "</span>";
                echo "<span id='answer:" .$res['id']. "' hidden>" .$res['answer']. "</span>";
                echo "<span id='airdate:" .$res['id']. "' hidden>" .$res['airdate']. "</span>";

                if ($exists != 0) {
                    echo "<span id='changeFav:" .$res['id']. "' hidden>delete</span>";
                }
                else {
                    echo "<span id='changeFav:" .$res['id']. "' hidden>add</span>";
                }

            }
        }
    }
}
else {

}


