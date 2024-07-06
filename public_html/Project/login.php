<?php
require(__DIR__ . "/../../partials/nav.php");
?>
<?php
if (isset($_POST["email"]) && isset($_POST["password"])) {
    $email = se($_POST, "email", "", false);
    $password = se($_POST, "password", "", false);

    // Error checking
    $hasError = false;
    if (empty($email)) {
        flash("Email must not be empty", "danger");
        $hasError = true;
    }
    if (str_contains($email, "@")) {
        //sanitize
        $email = sanitize_email($email);
        //validate
        if (!is_valid_email($email)) {
            flash("Invalid email address", "warning");
            $hasError = true;
        }
    } else {
        if (!preg_match('/^[a-z0-9_-]{3,30}$/i', $email)) {
            flash("Username must only be alphanumeric and can only contain - or _", "warning");
            $hasError = true;
        }
    }
    if (empty($password)) {
        flash("password must not be empty", "danger");
        $hasError = true;
    }
    if (strlen($password) < 8) {
        flash("Password too short", "danger");
        $hasError = true;
    }
    if (!$hasError) {
        // Connect to DB and prepare query
        $db = getDB();
        $stmt = $db->prepare("SELECT id, email, username, password from Users where email = :email OR username = :email");

        try {
            // Execute query
            $r = $stmt->execute([":email" => $email]);
            if ($r) {
                // Get selected attributes from executed query
                $user = $stmt->fetch(PDO::FETCH_ASSOC);

                if ($user) {
                    // Store password in another variable and unset it to remove it from stored user data
                    $hash = $user["password"];
                    unset($user["password"]);

                    // Verify password
                    if (password_verify($password, $hash)) {
                        // Flash welcome message and set session data
                        flash("Welcome $email");
                        $_SESSION["user"] = $user;

                        //lookup potential roles
                        $stmt = $db->prepare("SELECT Roles.name FROM Roles 
                        JOIN UserRoles on Roles.id = UserRoles.role_id 
                        where UserRoles.user_id = :user_id and Roles.is_active = 1 and UserRoles.is_active = 1");
                        $stmt->execute([":user_id" => $user["id"]]);
                        $roles = $stmt->fetchAll(PDO::FETCH_ASSOC); //fetch all since we'll want multiple

                        //save roles or empty array
                        if ($roles) {
                            $_SESSION["user"]["roles"] = $roles; 
                        } else {
                            $_SESSION["user"]["roles"] = []; 
                        }

                        // Redirect to home.php
                        die(header("Location: home.php"));
                    } 
                    else {
                        flash("Invalid password", "danger");
                    }
                } 
                else {
                    flash("Email not found", "danger");
                }
            }
        } 
        catch (Exception $e) {
            flash("<pre>" . var_export($e, true) . "</pre>");
        }
    }
}
?>
<!-- Login page -->
<section class="bg-custom py-3 py-md-5">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-12 col-sm-10 col-md-9 col-lg-7 col-xl-6 col-xxl-5">
                <div class="card border border-dark rounded-3 shadow-sm">
                    <div class="card-bg-custom card-body p-3 p-md-4 p-xl-5">
                        <!-- Clauster logo -->
                        <div class="text-center mb-3">
                            <h1 class="clauster-logo">Clauster</h1>
                        </div>
                        <!-- Sign in label -->
                        <h2 class="fs-6 fw-normal text-center text-secondary mb-4">Sign in to your account</h2>
                        <!-- Login form -->
                        <form onsubmit="return validate(this)" method="POST">
                            <div class="row gy-2 overflow-hidden">
                                <!-- Username/Email input-->
                                <div class="col-12">
                                    <div class="form-floating mb-3">
                                        <input class="form-control" type="text" id="email" name="email" required />
                                        <label class="form-label" for="email">Username/Email</label>
                                    </div>
                                </div>
                                <!-- Password input -->
                                <div class="col-12">
                                    <div class="form-floating mb-3">
                                        <input class="form-control" type="password" id="pw" name="password" required minlength="8" />
                                        <label class="form-label" for="pw">Password</label>
                                    </div>
                                </div>
                                <!-- Submit button -->
                                <div class="col-12">
                                    <div class="d-grid my-3">
                                        <button class="btn btn-custom btn-lg" type="submit">Log in</button>
                                    </div>
                                </div>
                                <!-- Register link -->
                                <div class="col-12">
                                    <p class="m-0 text-secondary text-center">Don't have an account? <a href="register.php" class="link-primary text-decoration-none">Sign up</a></p>
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
require(__DIR__ . "/../../partials/flash.php");
?>