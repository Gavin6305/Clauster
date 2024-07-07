<?php
require(__DIR__ . "/../../partials/nav.php");
reset_session();
?>
<!-- Register page -->
<section class="bg-custom py-3 py-md-5">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-12 col-sm-10 col-md-9 col-lg-7 col-xl-6 col-xxl-5">
                <div class="card border border-dark rounded-3 shadow-sm">
                    <div class="card-bg-custom card-body p-3 p-md-4 p-xl-5">
                        <!-- Register for clauster -->
                        <div class="text-center mb-3">
                            <h1>Create an account</h1>
                        </div>
                        <!-- Account description -->
                        <p class="fs-6 fw-normal text-center text-secondary mb-4">
                            Create/join competitions<br>
                            Collect points to spend on exclusive items<br>
                            Compete with other players on the leaderboards
                        </p>
                        <!-- Register form -->
                        <form onsubmit="return validate(this)" method="POST">
                            <div class="row gy-2 overflow-hidden">
                                <!-- Email input -->
                                <div class="col-12">
                                    <div class="form-floating mb-3">
                                        <input class="form-control" type="email" id="email" name="email" required />
                                        <label class="form-label" for="email">Email</label>
                                    </div>
                                </div>
                                <!-- Username input -->
                                <div class="col-12">
                                    <div class="form-floating mb-3">
                                        <input class="form-control" type="text" name="username" required maxlength="30" />
                                        <label class="form-label" for="username">Username</label>
                                    </div>
                                </div>
                                <!-- Password input -->
                                <div class="col-12">
                                    <div class="form-floating mb-3">
                                        <input class="form-control" type="password" id="pw" name="password" required minlength="8" />
                                        <label class="form-label" for="pw">Password</label>
                                    </div>
                                </div>
                                <!-- Confirm Password input -->
                                <div class="col-12">
                                    <div class="form-floating mb-3">
                                        <input class="form-control" type="password" name="confirm" required minlength="8" />
                                        <label class="form-label" for="confirm">Confirm password</label>
                                    </div>
                                </div>
                                <!-- Submit button -->
                                <div class="col-12">
                                    <div class="d-grid my-3">
                                        <button class="btn btn-custom btn-lg" type="submit">Register</button>
                                    </div>
                                </div>
                                <!-- Login link -->
                                <div class="col-12">
                                    <p class="m-0 text-secondary text-center">Already have an account? <a href="login.php" class="link-primary text-decoration-none">Log in</a></p>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
<script>
    function validate(form) {
        //TODO 1: implement JavaScript validation
        //ensure it returns false for an error and true for success

        return true;
    }
</script>
<?php
if (isset($_POST["email"]) && isset($_POST["password"]) && isset($_POST["confirm"])) {
    // Set user attributes from $_POST
    $email = se($_POST, "email", "", false);
    $password = se($_POST, "password", "", false);
    $confirm = se(
        $_POST,
        "confirm",
        "",
        false
    );
    $username = se($_POST, "username", "", false);

    // Error checking
    $hasError = false;
    if (empty($email)) {
        flash("Email must not be empty", "danger");
        $hasError = true;
    }
    //sanitize
    $email = sanitize_email($email);
    //validate
    if (!is_valid_email($email)) {
        flash("Invalid email address", "danger");
        $hasError = true;
    }
    if (!preg_match('/^[a-z0-9_-]{3,16}$/i', $username)) {
        flash("Username must only be alphanumeric and can only contain - or _", "danger");
        $hasError = true;
    }
    if (empty($password)) {
        flash("password must not be empty", "danger");
        $hasError = true;
    }
    if (empty($confirm)) {
        flash("Confirm password must not be empty", "danger");
        $hasError = true;
    }
    if (strlen($password) < 8) {
        flash("Password too short", "danger");
        $hasError = true;
    }
    if (
        strlen($password) > 0 && $password !== $confirm
    ) {
        flash("Passwords must match", "danger");
        $hasError = true;
    }
    if (!$hasError) {
        // Hash the password before storing it in DB
        $hash = password_hash($password, PASSWORD_BCRYPT);

        // Connect to DB and prepare query with user attributes
        $db = getDB();
        $stmt = $db->prepare("INSERT INTO Users (email, password, username) VALUES(:email, :password, :username)");
        try {
            // Execute query
            $stmt->execute([":email" => $email, ":password" => $hash, ":username" => $username]);
            flash("Successfully registered!");
        } 
        catch (Exception $e) {
            users_check_duplicate($e->errorInfo);
        }
    }
}
?>
<?php
require(__DIR__ . "/../../partials/flash.php");
?>