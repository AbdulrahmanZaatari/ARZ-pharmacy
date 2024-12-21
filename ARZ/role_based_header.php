<?php
// Determine the user's role
$role = isset($_SESSION['role']) ? $_SESSION['role'] : '';

// Include the appropriate header file based on the role
switch ($role) {
    case 'pharmacist':
        include('pharmacist_header.php');
        break;
    case 'customer':
        include('header.php');
        break;
    case 'owner':
        include('owner_header.php');
        break;
    default:
        // Default header for guests
        include('default_header.php');
        break;
}
?>
