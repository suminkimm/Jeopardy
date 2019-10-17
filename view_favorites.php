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
            <div class="row">
                <div class="col-lg-12 col-xs-12">
                    <h1>My Favorite Q's!</h1>
                </div>
            </div>
            <div class="row">
                <table class="center">
                    <?php
                    $user_id = $_SESSION['user_id'];
                    $sql = "SELECT * 
                    FROM public.rel_favorite_qs rfq
                    INNER JOIN public.questions q
                    ON rfq.question_id=q.q_id
                    WHERE user_id='$user_id'";
                    $result = pg_query($conn, $sql);
                    if (pg_num_rows($result) != 0) {
                        while ($row = pg_fetch_assoc($result)) { // now parse json
                            echo "<tr>";
                            echo "<td>";
                            echo "Q:";
                            echo "</td>";
                            echo "<td>";
                            echo $row['question'];
                            echo "</td>";
                            echo "<td>";
                            echo $row['category'];
                            echo "</td>";
                            echo "<td>";
                            echo "<button type='button' onclick='getMoreInfo(" .$row['q_id']. ")' name='moreInfo'><i class=\"fas fa-info-circle\"></i></button>";
                            echo "<td><button type='button' id='star:" .$row['q_id']. "' onclick='changeFavorites(".$row['q_id'].")' style='color:gold'><i class='fas fa-star add-to-fav'></i></button></td>";
                            echo "</tr>";
                            echo "<span id='difficulty:" .$row['q_id']. "' hidden>" .$row['value']. "</span>";
                            echo "<span id='category:" .$row['q_id']. "' hidden>" .$row['category']. "</span>";
                            echo "<span id='question:" .$row['q_id']. "' hidden> Q: " .$row['question']. "</span>";
                            echo "<span id='answer:" .$row['q_id']. "' hidden>" .$row['answer']. "</span>";
                            echo "<span id='airdate:" .$row['q_id']. "' hidden>" .$row['airdate']. "</span>";
                        }
                    }
                    else {
                        echo "<tr>";
                        echo "<td> You currently have no favorites </td>";
                        echo "</tr>";
                    }
                    ?>

                </table>
            </div>
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
    <?php
}
else {

}
?>
<script type="text/javascript">
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
        xmlhttp = new XMLHttpRequest();
        xmlhttp.onreadystatechange = function() {
            if(this.readyState == 4 && this.status == 200) {
                console.log("response" + this.response);
                document.getElementById('add-to-fav').innerText=this.response;
            }
        }
        xmlhttp.open("GET","checkIfFavorited.php?q="+res_id,true);
        xmlhttp.send();

        document.getElementById('add-to-fav').setAttribute("onclick", "changeFavorites(" + res_id + ")");
        document.getElementById('myModal').style.display="inline";

    }


    function closeModal() {
        document.getElementById('myModal').style.display="none";
    }

    function changeFavorites(res_id) {
        console.log("addToFav");
        let star = document.getElementById("star:" + res_id);
        let addToFavButton = document.getElementById("add-to-fav");

        if(star.style.color == "gold" || addToFavButton.innerText=="Remove from Favorites") { // remove this question from favorites
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
