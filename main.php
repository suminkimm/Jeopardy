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
                <a class="navbar-brand" href="categories.php" style="font-size: 30px;">Jeopardy!</a>
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
        <?php
        $cat_name=$_GET['cat'];
        echo "<h5 style='color: #ff8fa5;'>Search within category: ".$cat_name."</h5>";
        ?>
        <form method="post" action="#">
            <div class="row" style="text-align: center;">
                <!--                search bar-->
                <div class="col-lg-12 col-xs-12 search-bar">
                    <input type="text" name="search-answer" placeholder="Search by question keywords">
                    <button type="submit" name="submit"><i class="fa fa-search"></i></button>
                    <button type="button" id="show-hide" onclick="showDiv()"><i class="fas fa-plus-circle"></i></button>
                </div>
            </div>
            <!--                advanced search bar that is revealed onclick-->
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
    <!--                modal that pops up to display more info about a specific question -->
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
        function showDiv() { // shows or hides the advanced search bar on click
            let x = document.getElementsByClassName('advanced-search')[0];
            let button = document.getElementById('show-hide');
            if (x.style.display == "none") {
                x.style.display = "block";
                button.innerHTML = "<i class=\"fas fa-minus-circle\"></i>";

            } else {
                console.log("none");
                x.style.display = "none";
                button.innerHTML = "<i class=\"fas fa-plus-circle\"></i>";

            }
        }

        function getMoreInfo(res_id) {

            // get detailed info about question
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

            // populate modal content with question information
            document.getElementsByClassName('modal-title')[0].innerText = "Q: " + question;
            document.getElementsByClassName('modal-body')[0].innerHTML =
                '<p> Difficulty: ' + difficulty + '<p>' +
                '<p> Category: ' + category + '<p>' +
                '<p> Answer: ' + answer + '<p>' +
                '<p> Air date: ' + airdate + '<p>';

            // check if the modal button should say add to favorites or remove from favorites
            xmlhttp = new XMLHttpRequest();
            xmlhttp.onreadystatechange = function() {
                if(this.readyState == 4 && this.status == 200) {
                    console.log("response" + this.response);
                    document.getElementById('add-to-fav').innerText=this.response; // change the innertext of modal button to either add/remove from favorites
                }
            }
            xmlhttp.open("GET","checkIfFavorited.php?q="+res_id,true);
            xmlhttp.send();

            document.getElementById('add-to-fav').setAttribute("onclick", "changeFavorites(" + res_id + ")"); // on button click, user can add/remove question from favorites
            document.getElementById('myModal').style.display="inline"; // display the modal

        }


        function closeModal() {
            document.getElementById('myModal').style.display="none";
        }


        function changeFavorites(res_id) {
            let star = document.getElementById("star:" + res_id);
            let addToFavButton = document.getElementById("add-to-fav");

            if(star.style.color == "gold"|| addToFavButton.innerText=="Remove from Favorites") { // remove this question from favorites
                star.style.color = "black";
                addToFavButton.innerText="Add to Favorites";

            }
            else { // add this question to favorites
                star.style.color = "gold";
                addToFavButton.innerText="Remove from Favorites";

            }

            let difficulty = document.getElementById("difficulty:" + res_id).innerText;
            let category = document.getElementById("category:" + res_id).innerText;
            let question = document.getElementById("question:" + res_id).innerText;
            let answer = document.getElementById("answer:" + res_id).innerText;
            let airdate = document.getElementById("airdate:" + res_id).innerText;

            // add or remove question from the favorites table in database
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

        $category_id=$_GET['cat_id'];
        $url = "http://jservice.io/api/clues?category=".$category_id;
        $json = file_get_contents($url);
        $results = json_decode($json, true);

        if($search_ans != null) { // keywords
            $temp_results = array();

            foreach($results as $res) { // search through the questions and add to result array if inputted keyword matches

                if (strpos($res['question'], $search_ans) !== false) {
                    array_push($temp_results, $res);
                }
            }

            $results = $temp_results;
        }

        if ($from_date != null || $to_date != null) { // airing dates

            $temp_results = array();

            if ($from_date != null && $to_date !== null) { // both ranges given

                foreach ($results as $res) {
                    if ($res['airdate'] >= $from_date && $res['airdate'] <= $to_date) { // add to result array if question is in range
                        array_push($temp_results, $res);
                    }
                }
            }
            elseif ($from_date == null) { // only to date given

                foreach ($results as $res) {
                    if ($res['airdate'] <= $to_date) { // add to result array if question aired before this date
                        array_push($temp_results, $res);
                    }
                }
            }
            else { // only from date given
                foreach ($results as $res) {
                    if ($res['airdate'] >= $from_date) { // add to result array if question aired after this date
                        array_push($temp_results, $res);
                    }
                }
            }
            $results = $temp_results;
        }


        if($difficulty != null) { // difficulty, or value of question

            $temp_results = array();
            foreach($results as $res) {
                if ($res['value'] == $difficulty) { // add to result array if question matches value
                    array_push($temp_results, $res);
                }
            }
            $results = $temp_results;
        }

        echo "<table class='center questions-table'>";

        if ($sort_by == null || $sort_by == 1) { // sort A-Z order
            usort($results, function ($a, $b) {
                return $a['question'] <=> $b['question'];
            });
        }
        elseif ($sort_by == 2) { // sort Z-A order
            usort($results, function ($a, $b) {
                return $b['question'] <=> $a['question'];
            });
        }
        else { // sort by question value
            usort($results, function ($a, $b) {
                return $a['value'] <=> $b['value'];
            });
        }

        if($results != null) {
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

                    if ($exists == 1) { // star is gold if favorited
                        echo "<td><button type='button' id='star:" .$res['id']. "' onclick='changeFavorites(" .$res['id']. ")' style='color:gold'><i class='fas fa-star add-to-fav'></i></button></td>";
                    }
                    else { // star is black if not favorited
                        echo "<td><button type='button' id='star:" .$res['id']. "' onclick='changeFavorites(" .$res['id']. ")' style='color:black'><i class='fas fa-star add-to-fav'></i></button></td>";
                    }

                    echo "</tr>";
                    echo "<span id='difficulty:" .$res['id']. "' hidden>" .$res['value']. "</span>";
                    echo "<span id='category:" .$res['id']. "' hidden>" .$res['category']['title']. "</span>";
                    echo "<span id='question:" .$res['id']. "' hidden>" .$res['question']. "</span>";
                    echo "<span id='answer:" .$res['id']. "' hidden>" .$res['answer']. "</span>";
                    echo "<span id='airdate:" .$res['id']. "' hidden>" .$res['airdate']. "</span>";

                    if ($exists != 0) { // if question is already favorited, mark question as to-be-deleted
                        echo "<span id='changeFav:" .$res['id']. "' hidden>delete</span>";
                    }
                    else { // mark question as to-be-added to favorites
                        echo "<span id='changeFav:" .$res['id']. "' hidden>add</span>";
                    }

                }
            }
        }
        else {
            echo "<tr>";
            echo "<td> No search results found </td>";
            echo "</tr>";
        }
        echo "</table>";
    }
    else { // default view displays everything
        $category_id=$_GET['cat_id'];
        $url = "http://jservice.io/api/clues?category=".$category_id;
        $json = file_get_contents($url);
        $results = json_decode($json, true);

        // remove duplicate results
        $temp_arr = array(); // keep track of unique results
        $i = 0;
        $key_arr = array(); // keep track of looped values

        foreach($results as $res) {
            if (!in_array($res['question'], $key_arr)) {
                $key_arr[$i] = $res['question'];
                $temp_arr[$i] = $res;
            }
            $i++;
        }
        $results = $temp_arr;

        usort($results, function ($a, $b) { // sort results in abc order
            return $a['question'] <=> $b['question'];
        });

        echo "<table class='center questions-table'>";
        
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

                if ($exists == 1) { // star is gold if favorited
                    echo "<td><button type='button' id='star:" .$res['id']. "' onclick='changeFavorites(" .$res['id']. ")' style='color:gold'><i class='fas fa-star add-to-fav'></i></button></td>";
                }
                else { // star is black if not favorited
                    echo "<td><button type='button' id='star:" .$res['id']. "' onclick='changeFavorites(" .$res['id']. ")' style='color:black'><i class='fas fa-star add-to-fav'></i></button></td>";
                }

                echo "</tr>";
                echo "<span id='difficulty:" .$res['id']. "' hidden>" .$res['value']. "</span>";
                echo "<span id='category:" .$res['id']. "' hidden>" .$res['category']['title']. "</span>";
                echo "<span id='question:" .$res['id']. "' hidden>" .$res['question']. "</span>";
                echo "<span id='answer:" .$res['id']. "' hidden>" .$res['answer']. "</span>";
                echo "<span id='airdate:" .$res['id']. "' hidden>" .$res['airdate']. "</span>";

                if ($exists != 0) { // if question is already favorited, mark question as to-be-deleted
                    echo "<span id='changeFav:" .$res['id']. "' hidden>delete</span>";
                }
                else { // mark question as to-be-added to favorites
                    echo "<span id='changeFav:" .$res['id']. "' hidden>add</span>";
                }
            }
        }
        echo "</table>";
    }
}
else {
    include("error.php");
}


