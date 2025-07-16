<?php
// Enable error reporting for debugging (remove in production)
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Database configuration - Based on your phpMyAdmin screenshot
$host = 'localhost';
$username = 'root';         // Default XAMPP username
$password = '';             // Default XAMPP password (empty)
$database = 'esaber';       // Your database name from the image

// Global variable for mysqli connection and error
$conn = null;
$db_connection_error_mysqli = null; // New global variable for connection error

// Create connection
$conn = new mysqli($host, $username, $password, $database);

// Check connection
if ($conn->connect_error) {
    // If connection fails, set the global error variable instead of dying
    $db_connection_error_mysqli = "Connection failed: " . $conn->connect_error;
    $conn = null; // Ensure $conn is null if connection failed
} else {
    // Set charset to match your database, only if connection is successful
    $conn->set_charset("utf8mb4");
    // Success message for debugging (remove in production)
    // You can uncomment this line to verify if the connection itself is successful
    // echo "<!-- Database connection successful to: $database -->";
}

// Function to clean input data
function clean_input($data) {
    global $conn;
    $data = trim($data);
    $data = stripslashes($data);
    $data = htmlspecialchars($data);
    // Ensure $conn is not null before using mysqli_real_escape_string
    return $conn ? mysqli_real_escape_string($conn, $data) : $data;
}

// Function to validate email
function validate_email($email) {
    return filter_var($email, FILTER_VALIDATE_EMAIL);
}

// Function to check if email exists in staff table
function email_exists($email) {
    global $conn;
    // Check if connection is valid before preparing statement
    if (!$conn) {
        error_log("email_exists: Database connection not available.");
        return false;
    }

    $stmt = $conn->prepare("SELECT id FROM staff WHERE email = ?");

    if ($stmt === false) {
        error_log("email_exists: Prepare failed: " . $conn->error);
        return false; // Return false if preparation fails
    }

    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();
    $exists = $result->num_rows > 0;
    $stmt->close();
    return $exists;
}

// Function to insert staff member
function insert_staff($firstname, $lastname, $email, $phone = null, $position = null) {
    global $conn;
    // Check if connection is valid before preparing statement
    if (!$conn) {
        return array('success' => false, 'error' => 'Database connection not available for insert_staff.');
    }

    $stmt = $conn->prepare("INSERT INTO staff (firstname, lastname, email, phone, position) VALUES (?, ?, ?, ?, ?)");

    if ($stmt === false) {
        return array('success' => false, 'error' => $conn->error);
    }

    $stmt->bind_param("sssss", $firstname, $lastname, $email, $phone, $position);

    if ($stmt->execute()) {
        $insert_id = $conn->insert_id;
        $stmt->close();
        return array('success' => true, 'id' => $insert_id);
    } else {
        $error = $stmt->error;
        $stmt->close();
        return array('success' => false, 'error' => $error);
    }
}

// Function to get all staff members
function get_all_staff() {
    global $conn;
    // Check if connection is valid
    if (!$conn) {
        error_log("get_all_staff: Database connection not available.");
        return array('success' => false, 'error' => 'Database connection not available.');
    }

    $query = "SELECT id, firstname, lastname, email, phone, position, date_added FROM staff ORDER BY date_added DESC";
    $result = $conn->query($query);

    // --- DEBUGGING START ---
    echo "<!-- DEBUG: Entering get_all_staff() -->";
    echo "<!-- DEBUG: Query executed: " . htmlspecialchars($query) . " -->";

    if ($result === false) {
        $error_message = $conn->error;
        echo "<!-- DEBUG: Query failed: " . htmlspecialchars($error_message) . " -->";
        return array('success' => false, 'error' => $error_message);
    }

    $num_rows = $result->num_rows;
    echo "<!-- DEBUG: Number of rows returned: " . $num_rows . " -->";

    $staff = array();
    if ($num_rows > 0) {
        // Fetch the first row to inspect its structure
        $first_row = $result->fetch_assoc();
        echo "<!-- DEBUG: First row data (if any): " . htmlspecialchars(json_encode($first_row)) . " -->";
        // Rewind result set to fetch all rows in the loop
        $result->data_seek(0);

        while ($row = $result->fetch_assoc()) {
            $staff[] = $row;
        }
    }
    echo "<!-- DEBUG: Exiting get_all_staff() -->";
    // --- DEBUGGING END ---

    return array('success' => true, 'data' => $staff);
}

// Function to get staff member by ID
function get_staff_by_id($id) {
    global $conn;
    // Check if connection is valid before preparing statement
    if (!$conn) {
        return array('success' => false, 'error' => 'Database connection not available for get_staff_by_id.');
    }

    $stmt = $conn->prepare("SELECT * FROM staff WHERE id = ?");

    if ($stmt === false) {
        return array('success' => false, 'error' => $conn->error);
    }

    $stmt->bind_param("i", $id);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $staff = $result->fetch_assoc();
        $stmt->close();
        return array('success' => true, 'data' => $staff);
    } else {
        $stmt->close();
        return array('success' => false, 'error' => 'Staff member not found');
    }
}

// Function to update staff member
function update_staff($id, $firstname, $lastname, $email, $phone = null, $position = null) {
    global $conn;
    // Check if connection is valid before preparing statement
    if (!$conn) {
        return array('success' => false, 'error' => 'Database connection not available for update_staff.');
    }

    $stmt = $conn->prepare("UPDATE staff SET firstname = ?, lastname = ?, email = ?, phone = ?, position = ?, date_modified = CURRENT_TIMESTAMP WHERE id = ?");

    if ($stmt === false) {
        return array('success' => false, 'error' => $conn->error);
    }

    $stmt->bind_param("sssssi", $firstname, $lastname, $email, $phone, $position, $id);

    if ($stmt->execute()) {
        $stmt->close();
        return array('success' => true);
    } else {
        $error = $stmt->error;
        $stmt->close();
        return array('success' => false, 'error' => $error);
    }
}

// Function to delete staff member
function delete_staff($id) {
    global $conn;
    // Check if connection is valid before preparing statement
    if (!$conn) {
        return array('success' => false, 'error' => 'Database connection not available for delete_staff.');
    }

    $stmt = $conn->prepare("DELETE FROM staff WHERE id = ?");

    if ($stmt === false) {
        return array('success' => false, 'error' => $conn->error);
    }

    $stmt->bind_param("i", $id);

    if ($stmt->execute()) {
        $affected_rows = $stmt->affected_rows;
        $stmt->close();

        if ($affected_rows > 0) {
            return array('success' => true);
        } else {
            return array('success' => false, 'error' => 'Staff member not found');
        }
    } else {
        $error = $stmt->error;
        $stmt->close();
        return array('success' => false, 'error' => $error);
    }
}

// Function to close database connection
function close_connection() {
    global $conn;
    if ($conn) {
        $conn->close();
    }
}
?>
