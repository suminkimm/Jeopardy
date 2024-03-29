<?php include("config_db.php");
session_start();

if($_SESSION['valid'] == 1) { ?>
    <html>
        <head>
            <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css">
            <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.7.2/css/all.css" integrity="sha384-fnmOCqbTlWIlj8LyTjo7mOUStjsKC4pOpQbqyi7RrhN7udi9RwhKkMHpvLbHG9Sr" crossorigin="anonymous">

            <link rel="stylesheet" href="https://use.typekit.net/zhq8pzz.css">
            <link rel="stylesheet" type="text/css" href="css.css">

            <!--        for modal:-->
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
            <h1>Play Game!</h1>
            <div class="row">
                <div class="col-lg-12 col-xs-12">
                    <div style="text-align: center;">
                        <h5 style="display:inline;">Score: </h5>
                        <h5 id="trackScore" style="display:inline;">0</h5>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-lg-10 offset-lg-1 col-xs-12">
                    <!--                    creating the jeopardy board-->

                    <table class="table table-borderless center jeopardy-board">
                        <?php
                        // fetch categories from clues
                        $url = "http://jservice.io/api/clues";
                        $json = file_get_contents($url);
                        $old_categories = json_decode($json, true);

                        $temp_categories = array();
                        $key_tracker = array();
                        $i = 0;
                        // make sure category doesn't repeat
                        foreach($old_categories as $cat) {
                            if (!in_array($cat['category']['id'], $key_tracker)) {
                                $temp_categories[$i] = $cat;
                                $key_tracker[$i] = $cat['category']['id'];
                                $i++;
                            }
                        }
                        $old_categories = $temp_categories;

                        // shuffle categories array to get different categories every time
                        $keys = array_keys($old_categories);
                        shuffle($keys);
                        $categories = array();
                        foreach($keys as $key) {
                            $categories[$key] = $old_categories[$key];
                        }

                        echo "<tr>";
                        $i = 0;
                        foreach($categories as $cat) { // populate jeopardy table with category
                            if ($i < 5) {
                                echo "<td>".$cat['category']['title']."</td>";
                                echo "<span id='" .$cat['category']['id']. "' hidden>".$cat['category']['id']."</span>";
                            }
                            $i++;
                        }
                        echo "</tr>";

                        $value = 100;
                        $td_id = 0; // create an id for each table cell

                        for($i = 0; $i < 5; $i++) { // populate jeopardy table with values of difficulty 100-500 for category
                            $col = 0;
                            echo "<tr>";
                            foreach($categories as $cat) {
                                if ($col < 5) {
                                    echo "<td id='td:" .$td_id. "'>";
                                    echo "<button type='button' onclick='viewQuestion(" .$cat['id']. "," .$value. "," .$td_id. ")'>";
                                    echo $value;
                                    echo "</button>";
                                    echo "</td>";
                                    $col++;
                                    $td_id++;
                                }
                            }
                            $value += 100;
                            echo "</tr>";
                        }
                        ?>
                    </table>
                </div>
            </div>
            <div class="row">
                <div class="col-lg-12 col-xs-12" style="text-align: center;">
                    <button type="button" id="start-over" onclick="window.location.reload()">Start Over</button>
                </div>
            </div>
        </div>

<!--        modal containing a categorical question that pops up once jeopardy board box is clicked-->
        <div class="modal" id="myModal" tabindex="-1" role="dialog">
            <div class="modal-dialog modal-dialog-centered" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Modal title</h5>
                        <button type="button" class="close" onclick="closeModal()" aria-label="Close" style="display:none;">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                    </div>
                    <div class="modal-footer">
                        <button type="button" id="submit-answer" class="btn btn-primary" onclick="checkAnswer()">Submit</button>
                    </div>
                </div>
            </div>
        </div>
        </body>
    </html>

    <script type="text/javascript">
        // keep track of which questions you already randomly pulled
        let questions_arr = new Array();
        // track whether the submit answer button was clicked
        let clicked = false;

        // view question after clicking jeopardy board
        function viewQuestion(cat_id, value, td_id) {

            // randomly pull a question of the selected category and difficulty using API
            let request = new XMLHttpRequest();
            let url = 'http://jservice.io/api/clues?value=' + value + '&category=' + cat_id;
            request.open('GET', url, true);

            request.onload = function() {
                // begin accessing JSON data here
                let data = JSON.parse(this.response);

                for (let i = 0; i < data.length; i++) {
                    if (!questions_arr.includes(data[i].id)) { // this question hasn't already been pulled
                        let question = data[i].question;
                        if (question == null) {
                            question = "N/A";
                        }
                        question = question.replace(/"/g, "&quot;"); // replace quotation marks (") with (&quot)

                        let answer = data[i].answer;
                        let id = data[i].id;
                        questions_arr.push(id);


                        // fill modal with question content
                        document.getElementsByClassName('modal-title')[0].innerText = "A: " + answer;
                        document.getElementsByClassName('modal-body')[0].innerHTML =
                            '<form id="submit-ans">' +
                            '<div id="countdown"></div>' +
                            '<input type="text" id="user-answer" name="user-answer" placeholder="Q: What is the question?">' +
                            '<input type="text" id="question" value="' + question + '" hidden>' +
                            '</form>';

                        // begin 30 second countdown for answering question
                        var timeleft = 30;
                        var downloadTimer = setInterval(function(){
                            if (clicked == true) { // submit answer button clicked
                                clearInterval(downloadTimer);

                                let ans = document.getElementById("user-answer").value;
                                let question = document.getElementById("question").value;

                                // display user's answer and the correct answer
                                // let user choose whether their answer was close enough to the correct answer
                                document.getElementById("countdown").innerHTML =
                                    "<p><b>Your question was: </b>" + ans + "</p>" +
                                    "<p><b>The correct question is: </b>" + question + "</p>" +
                                    "<button type='button' id='correct-button' onclick='correctAns(" + value + "," + td_id + ")'>I'm correct!</button>" +
                                    "<button type='button' id='wrong-button' onclick='wrongAns(" + td_id + ")'>I'm wrong...</button>";

                                document.getElementsByClassName("modal-footer")[0].style.display="none";
                                document.getElementById("user-answer").style.display="none";

                                // reset
                                clicked = false;
                            }

                            else { // time ran out
                                document.getElementById("countdown").innerHTML = "<b>" + timeleft + " seconds remaining</b>";
                                timeleft -= 1;
                                if (timeleft <= 0) {
                                    clearInterval(downloadTimer);
                                    // modal shows the correct answer
                                    document.getElementById("countdown").innerHTML =
                                        "<p><b>Time's Up!</b></p>" +
                                        "<p>The correct question is: </p>" +
                                        "<p>" + question + ".</p>";

                                    // time's up, so allow user to exit out of the question
                                    // once user exits question, mark question as wrong
                                    document.getElementsByClassName("close")[0].style.display = "block";
                                    document.getElementsByClassName("close")[0].setAttribute("onclick", "wrongAns(" + td_id + ")");

                                    document.getElementsByClassName("modal-footer")[0].style.display = "none";
                                    document.getElementById("user-answer").style.display = "none";
                                }
                            }
                        }, 1000);

                        // allow user to see the modal
                        document.getElementById('myModal').style.display="inline";
                        break;
                    }
                }
            }

            request.send();

        }

        function closeModal() {
            document.getElementById('myModal').style.display="none";
            document.getElementsByClassName("close")[0].style.display="none";
            document.getElementsByClassName("modal-footer")[0].style.display="flex";
        }

        function checkAnswer() { // determine whether user submitted answer before they ran out of time
            clicked = true;
        }

        function correctAns(value, td_id) { // user answer was correct

            // add question value to total score
            let score = parseInt(document.getElementById('trackScore').innerText, 10);
            score += value;
            document.getElementById('trackScore').innerText = score;

            // mark question cell as correct
            let viewed_cell = document.getElementById("td:" + td_id);
            viewed_cell.style.backgroundColor = "aquamarine";


            // close modal
            document.getElementById('myModal').style.display="none";
            document.getElementsByClassName("close")[0].style.display="none";
            document.getElementsByClassName("modal-footer")[0].style.display="flex";
        }

        function wrongAns(td_id) { // user was incorrect

            // mark question cell as incorrect
            let viewed_cell = document.getElementById("td:" + td_id);
            viewed_cell.style.backgroundColor = "pink";

            // close modal
            document.getElementById('myModal').style.display="none";
            document.getElementsByClassName("close")[0].style.display="none";
            document.getElementsByClassName("modal-footer")[0].style.display="flex";
        }
    </script>

<?php
}
else {
    include("error.php");
}

?>

