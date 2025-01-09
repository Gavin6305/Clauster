<?php
// Set default error message, 400 for bad request
$response = ["message" => "There was a problem setting the theme"];
http_response_code(400);
// Get content type
$contentType = $_SERVER["CONTENT_TYPE"];
error_log("Content Type $contentType");
// If json, read data with php://input
if ($contentType === "application/json") {
    $json = file_get_contents('php://input');
    $data = json_decode($json, true)["data"];
} 
// If url encoded, read from $_POST
else if ($contentType === "application/x-www-form-urlencoded") {
    $data = $_POST;
}
// Log recieved data
error_log(var_export($data, true));
// If themeNum exists in data
if (isset($data["themeNum"])) {
    // Start session and get user id
    session_start();
    $reject = false;
    require_once(__DIR__ . "/../../../lib/functions.php");
    $user_id = get_user_id();
    // If not logged in, don't set theme
    if ($user_id <= 0) {
        $reject = true;
        error_log("User not logged in");
        http_response_code(403);
        $response["message"] = "You must be logged in to save theme settings";
    }
    // If logged in, set theme
    if (!$reject) {
        $theme = (int)se($data, "themeNum", 0, false);
        http_response_code(200);
        set_theme($user_id, $theme);
    }
    else {
        $response["message"] = "Failed to set theme.";
    }
}
echo json_encode($response);