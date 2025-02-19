<?php
//Note: this is to resolve cookie issues with port numbers
$domain = $_SERVER["HTTP_HOST"];
if (strpos($domain, ":")) {
    $domain = explode(":", $domain)[0];
}
$localWorks = true; //some people have issues with localhost for the cookie params
//if you're one of those people make this false

//this is an extra condition added to "resolve" the localhost issue for the session cookie
if (($localWorks && $domain == "localhost") || $domain != "localhost") {
    session_set_cookie_params([
        "lifetime" => 60 * 60,
        "path" => "/Project",
        //"domain" => $_SERVER["HTTP_HOST"] || "localhost",
        "domain" => $domain,
        "secure" => true,
        "httponly" => true,
        "samesite" => "lax"
    ]);
}
session_start();
require_once(__DIR__ . "/../lib/functions.php");

?>
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<!-- include css and js files -->
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-1BmE4kWBq78iYhFldvKuhfTAU6auU8tT94WrHftjDbrCEXSU1oBoqyl2QvZ6jIW3" crossorigin="anonymous">
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-ka7Sk0Gln4gmtz2MlQnikT1wXgYsOg+OMhuP+IlRH9sENBO0LRn5q+8nbTov4+1p" crossorigin="anonymous"></script>
<link rel="stylesheet" href="styles.css">
<script src="helpers.js"></script>
<!-- Navbar -->
<nav class="navbar navbar-expand-lg nav-bg-custom">
    <div class="container-fluid">
        <!-- Home -->
        <a class="navbar-brand" href="<?php echo get_url('home.php'); ?>">Home</a>
        <!-- Expand button -->
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
            <h6 class="nav-toggler-text">. . .</h6>
        </button>
        <div class="collapse navbar-collapse" id="navbarNav">
            <!-- Page links list -->
            <ul class="navbar-nav">
                <!-- Logged in list -->
                <?php if (is_logged_in()) : ?>
                    <li class="nav-item"><a class="nav-link" href="<?php echo get_url('game.php'); ?>">Clauster</a></li>
                    <li class="nav-item"><a class="nav-link" href="<?php echo get_url('create_competition.php'); ?>">Create a Competition</a></li>
                    <li class="nav-item"><a class="nav-link" href="<?php echo get_url('active_competitions.php'); ?>">Active Competitions</a></li>
                <?php endif; ?>
                <!-- Not logged in list -->
                <?php if (!is_logged_in()) : ?>
                    <li class="nav-item"><a class="nav-link" href="<?php echo get_url('login.php'); ?>">Login</a></li>
                    <li class="nav-item"><a class="nav-link" href="<?php echo get_url('register.php'); ?>">Register</a></li>
                    <li class="nav-item"><a class="nav-link" href="<?php echo get_url('game.php'); ?>">Clauster</a></li>
                <?php endif; ?>
                <!-- Admin list -->
                <?php if (has_role("Admin")) : ?>
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle" href="#" id="rolesDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                            Admin Roles
                        </a>
                        <ul class="dropdown-menu bg-warning" aria-labelledby="rolesDropdown">
                            <li><a class="dropdown-item" href="<?php echo get_url('admin/create_role.php'); ?>">Create</a></li>
                            <li><a class="dropdown-item" href="<?php echo get_url('admin/list_roles.php'); ?>">List</a></li>
                            <li><a class="dropdown-item" href="<?php echo get_url('admin/assign_roles.php'); ?>">Assign</a></li>
                        </ul>
                    </li>
                <?php endif; ?>
            </ul>
            <!-- User profile link far right -->
            <ul class="navbar-nav nav-profile-link">
                <?php if (is_logged_in()) : ?>
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle" href="#" id="navbarScrollingDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                            <?php echo get_username(); ?>
                        </a>
                        <ul class="dropdown-menu" aria-labelledby="navbarScrollingDropdown">
                            <li><a class="dropdown-item dropdown-link" href="<?php echo get_url('profile.php'); ?>">Profile</a></li>
                            <li><a class="dropdown-item dropdown-link" href="<?php echo get_url('logout.php'); ?>">Logout</a></li>
                        </ul>
                    </li>
                <?php endif; ?>
            </ul>
        </div>
    </div>
</nav>
