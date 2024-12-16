<?php
session_start();

if (isset($_SESSION["loggedin"]) && $_SESSION["loggedin"] === true) {
    header("location: dashboard");
    exit;
}

require_once "connectDB.php";

$email = $password = $email_err = $password_err = $login_err = $user_roles = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    if (empty(trim($_POST["email"]))) {
        $email_err = "Please enter email.";
    } else {
        $email = trim($_POST["email"]);
    }

    if (empty(trim($_POST["password"]))) {
        $password_err = "Please enter password.";
    } else {
        $password = trim($_POST["password"]);
    }

    if (empty($email_err) && empty($password_err)) {
        $sql = "SELECT admin_id, email, password FROM admins WHERE email = ?";

        if ($stmt = mysqli_prepare($link, $sql)) {
            mysqli_stmt_bind_param($stmt, "s", $param_email);

            $param_email = $email;

            if (mysqli_stmt_execute($stmt)) {
                mysqli_stmt_store_result($stmt);

                if (mysqli_stmt_num_rows($stmt) == 1) {
                    mysqli_stmt_bind_result($stmt, $id, $email, $hashed_password);
                    if (mysqli_stmt_fetch($stmt)) {
                        if (password_verify($password, $hashed_password)) {
                            session_start();

                            $_SESSION["loggedin"] = true;
                            $_SESSION["id"] = $id;
                            $_SESSION["email"] = $email;

                            header("location: dashboard");
                        } else {
                            $login_err = "Invalid email or password.";
                        }
                    }
                } else {
                    $login_err = "Invalid email or password.";
                }
            }
        } else {
            $login_err = "Invalid email or password.";
        }
    }

    mysqli_close($link);
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
    <link href="https://fonts.googleapis.com/css2?family=Nunito:wght@300;400;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="assets/css/bootstrap.css">
    <link rel="stylesheet" href="assets/vendors/bootstrap-icons/bootstrap-icons.css">
    <link rel="stylesheet" href="assets/css/app.css">
    <link rel="stylesheet" href="assets/css/pages/auth.css">
    <script src="https://unpkg.com/sweetalert/dist/sweetalert.min.js"></script>
    <link rel="icon" href="assets/images/logo/logo-site.png" type="image/icon type">
</head>

<body>
    <div id="auth">

        <div class="row h-100">
            <div class="col-lg-12 col-12">
                <div id="auth-left">
                    <div class="auth-logo">
                        <a href="login"><img src="assets/images/logo/TECHNO LOGO.png" alt="Logo"
                                style="width: 400px; height: auto;"></a>
                    </div>
                    <div class="row">
                        <div class="col-md-9">
                            <h1 class="auth-title">Login</h1>
                        </div>
                    </div>

                    <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">

                        <?php
                        if (!empty($login_err)) {
                            echo '<div class="alert alert-danger">' . $login_err . '</div>';
                        }
                        ?>

                        <div class="form-group position-relative has-icon-left mb-4">
                            <input type="email"
                                class="form-control <?php echo (!empty($email_err)) ? 'is-invalid' : ''; ?> form-control-xl"
                                name="email" placeholder="Email" value="<?php echo $email; ?>">
                            <div class="form-control-icon">
                                <i class="bi bi-envelope"></i>
                            </div>
                            <span class="invalid-feedback"><?php echo $email_err; ?></span>
                        </div>
                        <div class="form-group position-relative has-icon-left mb-4">
                            <input type="password" id="password"
                                class="form-control <?php echo (!empty($password_err)) ? 'is-invalid' : ''; ?> form-control-xl"
                                name="password" placeholder="Password" value="<?php echo $password; ?>">
                            <div class="form-control-icon">
                                <i class="bi bi-shield-lock"></i>
                            </div>
                            <span class="invalid-feedback"><?php echo $password_err; ?></span>
                        </div>
                        <div class="my-2 d-flex justify-content-between align-items-center">
                            <div class="form-check">
                                <label class="form-check-label text-muted">
                                    <input type="checkbox" class="form-check-input" id="check">
                                    Show Password
                                </label>
                            </div>
                        </div>
                        <script>
                            const passwordInput = document.getElementById("password");
                            const showPasswordCheckbox = document.getElementById("check");

                            showPasswordCheckbox.addEventListener("change", function () {
                                if (showPasswordCheckbox.checked) {
                                    passwordInput.type = "text";
                                } else {
                                    passwordInput.type = "password";
                                }
                            });
                        </script>
                        <button class="btn btn-primary btn-block btn-lg shadow-lg mt-5" type="submit">Log in</button>
                    </form>
                </div>
            </div>
        </div>

    </div>
</body>

</html>