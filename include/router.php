<?php
session_start();
// Include the necessary classes
require 'database.php';
require 'ajax.php';
require 'functions.php';


// Create instances of the required classes
$ajax = new Ajax();
// Define the routes and corresponding actions
define('BASE_URL', '');
$routes = array(
    '/' => 'index',
    '/roles' => 'getRoles',
    '/register' => 'registerUser',
    '/login' => 'logIn',
    '/logout' => 'logout',
    '/addProject' => 'addProject',
    '/addAssignment' => 'addAssignment',
    '/deleteProject' => 'deleteProject',
    '/deleteAssignment' => 'deleteAssignment',
    '/updateStatus'=>'updateStatus'

    // Add more routes as needed
);
// Get the requested URL path
$path = $_SERVER['REQUEST_URI'];


// Remove query string parameters if present
if (($pos = strpos($path, '?')) !== false) {
    $path = substr($path, 0, $pos);
}

// Check if the requested route exists
if (isset($routes[$path])) {
    // Get the corresponding action
    $action = $routes[$path];
    // Check if the action method exists in the Ajax class
    if (method_exists($ajax, $action)) {
        // Call the action method
        $response = $ajax->$action();

        // Check if the path matches the base URL
        if ($path === BASE_URL.'/') {
            // Set the appropriate response headers for HTML
            header('Content-Type: text/html; charset=UTF-8');
            echo $response;
        } else {
            // Set the appropriate response headers for JSON
            header('Content-Type: application/json');
            echo $response;
        }
        exit;
    }
}

// If no valid route is found, return a 404 response
http_response_code(404);
echo "$path Not Found make rout for it";
echo "<pre>";
print_r($routes);
echo "</pre>";