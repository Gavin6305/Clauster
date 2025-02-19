<?php
// Set default error message, 400 for bad request
$response = ["message" => "There was a problem saving your score"];
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
// If score exists in data
if (isset($data["score"])) {
    // Start session and get user id
    session_start();
    $reject = false;
    require_once(__DIR__ . "/../../../lib/functions.php");
    $user_id = get_user_id();
    // If not logged in, don't save score
    if ($user_id <= 0) {
        $reject = true;
        error_log("User not logged in");
        http_response_code(403);
        $response["message"] = "You must be logged in to save your score";
        //flash($response["message"], "warning");
    }
    // If logged in, save score and add points
    if (!$reject) {
        $score = (int)se($data, "score", 0, false);
        http_response_code(200);
        save_score($score, $user_id, true);
        add_points($user_id, floor($score / 10.0), "Won from game");
        //error_log("Score of $score saved successfully for $user_id");
    }
    else {
        $response["message"] = "Score rejected.";
    }
}
echo json_encode($response);
