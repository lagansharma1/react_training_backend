<?php 
function getGroups($db) {
    $excluded_ids = [1, 2, 30, 31, 26, 27, 23];

    // Use the correct format for NOT IN
    $db->where('id', $excluded_ids, 'NOT IN');

    // Fetch results
    $result = $db->get('`groups`');

    // Check for errors
    if ($db->getLastError()) {
        throw new Exception('Database error: ' . $db->getLastError());
    }

    return $result;
}


?>