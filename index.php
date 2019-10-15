<?php include("config_db.php"); ?>
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
        <div class="container">
            <div class="row h-100">
                <div class="col-lg-12 col-xs-12" style="margin-top: auto; margin-bottom: auto;">
                    <h1 style="padding-bottom: 20px;">Welcome to Jeopardy!</h1>
                    <table class="center">
                        <tr>
                            <td>
                                <button type="button" class="sign-up-button" data-toggle="modal" data-target="#sign-up">Sign Up</button>
                            </td>
                            <td>
                                <button type="button" class="log-in-button" data-toggle="modal" data-target="#log-in">Log In</button>
                            </td>
                        </tr>
                    </table>
                </div>
            </div>
        </div>
        <div class="modal" id="sign-up" tabindex="-1" role="dialog">
            <div class="modal-dialog modal-dialog-centered" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">CREATE AN ACCOUNT</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <h5>Your Information</h5>
                        <form method="post" action="index.php">
                            <table class="center">
                                <tr>
                                    <td>
                                        <i class="fas fa-user"></i>
                                    </td>
                                    <td>
                                        <input type="text" class="user-answer" name="firstname" id="user-answer" placeholder="First Name" required>
                                    </td>
                                    <td>
                                        <input type="text" class="user-answer" name="lastname" placeholder="Last Name" required>
                                    </td>
                                </tr>
                                <tr>
                                    <td>
                                        <i class="fas fa-lock"></i>
                                    </td>
                                    <td>
                                        <input type="text" class="user-answer" name="username" placeholder="Username" required>
                                    </td>
                                    <td>
                                        <input type="password" class="user-answer" name="password" placeholder="Password" required>
                                    </td>
                                </tr>
                            </table>
                    </div>
                    <div class="modal-footer">
                        <button type="submit" id="submitSignUp" class="btn btn-primary" name="submitSignUp">Submit</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>

        <div class="modal" id="log-in" tabindex="-1" role="dialog">
            <div class="modal-dialog modal-dialog-centered" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">LOG IN TO YOUR ACCOUNT</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <h5>Your Information</h5>
                        <form method="post" action="index.php">
                            <table class="center">
                                <tr>
                                    <td>
                                        <i class="fas fa-user"></i>
                                    </td>
                                    <td style="width:100%">
                                        <input type="text" class="user-answer" id="user-answer" name="username" placeholder="Username" required>
                                    </td>
                                </tr>
                                <tr>
                                    <td>
                                        <i class="fas fa-lock"></i>
                                    </td>
                                    <td>
                                        <input type="password" class="user-answer" placeholder="Password" name=password style="width:100%" required>
                                    </td>
                                </tr>
                            </table>
                    </div>
                    <div class="modal-footer">
                        <button type="submit" id="submitLogIn" name="submitLogIn" class="btn btn-primary">Submit</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </body>
</html>

<?php
if (isset($_POST['submitLogIn'])) {
    $username = $_POST['username'];
    $password = $_POST['password'];

    // check if user exists
    $check_sql = pg_query($conn, "SELECT * FROM public.users WHERE username = '$username'");
    if (pg_num_rows($check_sql) != 0) { // existing
        while($row = $check_sql->fetch_assoc()) {
            $hashed_pwd = $row['password'];
            $user_id = $row['user_id'];
            $first_name = $row['first_name'];
            $last_name = $row['last_name'];
        }

        if (password_verify($password, $hashed_pwd)) { // correct password

            // set session variables
            $_SESSION['username'] = $username;
            $_SESSION['user_id'] = $user_id;
            $_SESSION['first_name'] = $first_name;
            $_SESSION['last_name'] = $last_name;
            $_SESSION['valid'] = 1;

            echo "<script>window.location='main.php'</script>";

        }
        else {
            echo "<script>alert(\"Incorrect username or password. Please try again.\")</script>";
        }
    }
    else {
        echo "<script>alert(\"You have not created an account. Please sign up.\")</script>";
    }
}

elseif (isset($_POST['submitSignUp'])) {

    echo "submitsignup";

    $username = $_POST['username'];
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT);
    $firstname = $_POST['firstname'];
    $lastname = $_POST['lastname'];
    echo "user:". $username;
    echo "pass:". $password;
    echo "first:".$firstname;
    echo "last:".$lastname;

    echo "<script>alert(\"submitlogin\")</script>";

    $check_sql = pg_query($conn, "SELECT * FROM public.users WHERE username = '$username'");
    echo pg_last_error($check_sql);
//     check to see if existing user

    if (pg_num_rows($check_sql) != 0) { // existing
        echo "<script>alert(\"This username is already taken. Please try again.\")</script>";
    }
    else {
        $sql = "INSERT INTO public.users (username, password, first_name, last_name) VALUES ('$username', '$password', '$firstname', '$lastname')";
        $result = pg_query($conn, $sql);

        if ($result) {
            echo "<script>alert('You have successfully created your account! Please now log in through the portal.'); window.location='index.php'</script>";
        }
        else {
            echo "<script>alert(\"Sorry, there was an error creating your account.\")</script>";
        }
    }
}
