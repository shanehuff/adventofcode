<?php

// Check if the test.txt file exists
if (file_exists('test.txt')) {
    // Count how many lines are in the file and show it out
    //echo count(file('test.txt'));

    // Count how many characters in the file
    echo strlen(file_get_contents('test.txt'));

    // Create an array with 1000 items, store it into the test.txt file
    // $array = array();
} else {
    // Create a new file named 'test.txt'
    $fp = fopen('test.txt', 'w');
// Write empty string to the file
    fwrite($fp, '');
// Close the file
    fclose($fp);
}