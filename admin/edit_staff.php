<?php
// Enable error reporting for debugging
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Initialize variables
$staff_data = null;
$error_message = '';
$success_message = '';
$staff_id = null;

// Define multiple possible paths for the database connection file
$possiblePaths = [
    'connection.php',          // Case 1: In the same directory as this script
    '../connection.php',       // Case 2: In the parent directory
    'config/connection.php',   // Case 3: In a 'config' subdirectory
    'includes/connection.php', // Case 4: In an 'includes' subdirectory
    'db/connection.php',       // Case 5: In a 'db' subdirectory
];

$databaseConfigPath = null;
foreach ($possiblePaths as $path) {
    if (file_exists($path)) {
        $databaseConfigPath = $path;
        break; // Found the file, stop checking
    }
}

if ($databaseConfigPath === null) {
    $error_message = "Critical Error: Database configuration file 'connection.php' not found. Checked paths: " . implode(', ', $possiblePaths);
    // Debugging output, ensure this doesn't break headers if redirect is attempted later
    error_log("DEBUG: " . $error_message);
    error_log("DEBUG: Current working directory: " . getcwd());
} else {
    // Debugging output, ensure this doesn't break headers if redirect is attempted later
    error_log("DEBUG: Found connection.php at: " . $databaseConfigPath);
    include_once $databaseConfigPath;

    // Check if connection is available
    global $conn;
    global $db_connection_error_mysqli; // This variable is set in connection.php on error

    if (!isset($conn) || $conn === null) {
        $error_message = $db_connection_error_mysqli ?: "Database connection not available. Check connection.php for details.";
        error_log("DEBUG: mysqli connection variable (\$conn) is NOT available. Error: " . $error_message);
    } else {
        error_log("DEBUG: mysqli connection variable (\$conn) is available.");

        // Check if ID is provided in URL
        if (isset($_GET['id']) && !empty($_GET['id'])) {
            $staff_id = intval($_GET['id']);
        } else {
            $error_message = "No staff ID provided for editing.";
        }

        // Handle form submission for update
        if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['update_staff'])) {
            error_log("DEBUG: Form submitted for update.");
            $firstname = clean_input($_POST['firstname']);
            $lastname = clean_input($_POST['lastname']);
            $email = clean_input($_POST['email']);
            $phone = clean_input($_POST['phone']);
            $position = clean_input($_POST['position']);

            // Validate inputs
            if (empty($firstname) || empty($lastname) || empty($email)) {
                $error_message = "First name, last name, and email are required.";
            } elseif (!validate_email($email)) {
                $error_message = "Invalid email format.";
            } else {
                // Update staff member using the function from connection.php
                if (function_exists('update_staff')) {
                    $result = update_staff($staff_id, $firstname, $lastname, $email, $phone, $position);

                    if ($result['success']) {
                        $success_message = "Staff member updated successfully!";
                        // Redirect after successful update to news.php with a success flag
                        header("Location: news.php?updated=1"); // FIXED: Redirect to 'news.php'
                        exit(); // Crucial to exit after header redirect
                    } else {
                        $error_message = "Error updating staff: " . $result['error'];
                    }
                } else {
                    $error_message = "Error: update_staff() function not found. Check connection.php.";
                }
            }
        }

        // Fetch staff data if ID is provided and no error occurred
        if ($staff_id && empty($error_message)) {
            error_log("DEBUG: Attempting to fetch staff data for ID: " . $staff_id);
            if (function_exists('get_staff_by_id')) {
                $result = get_staff_by_id($staff_id);

                if ($result['success']) {
                    $staff_data = $result['data'];
                    error_log("DEBUG: Staff data fetched: " . json_encode($staff_data));
                } else {
                    $error_message = "Staff member not found or error fetching data: " . $result['error'];
                    error_log("DEBUG: Error fetching staff data: " . $result['error']);
                }
            } else {
                $error_message = "Error: get_staff_by_id() function not found. Check connection.php.";
            }
        }
    }
}

// Now include header and sidebar AFTER all potential header redirects
include_once 'includes/header.php';
include_once 'includes/sidebar.php';
?>

<!-- Main Content -->
<div class="main-content">
    <section class="section">
        <div class="section-body">
            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <div class="card-header">
                            <h4>Edit Staff Member</h4>
                            <a href="news.php" class="btn btn-sm btn-secondary">Back to Staff List</a>
                        </div>
                        <div class="card-body">
                            <?php if (!empty($error_message)): ?>
                                <div class="alert alert-danger">
                                    <h5>Error</h5>
                                    <p><?php echo htmlspecialchars($error_message); ?></p>
                                </div>
                            <?php endif; ?>

                            <?php if (!empty($success_message)): ?>
                                <div class="alert alert-success">
                                    <h5>Success</h5>
                                    <p><?php echo htmlspecialchars($success_message); ?></p>
                                </div>
                            <?php endif; ?>

                            <?php if ($staff_data): ?>
                                <form method="POST" action="">
                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label for="firstname">First Name <span class="text-danger">*</span></label>
                                                <input type="text"
                                                       class="form-control"
                                                       id="firstname"
                                                       name="firstname"
                                                       value="<?php echo htmlspecialchars($staff_data['firstname']); ?>"
                                                       required>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label for="lastname">Last Name <span class="text-danger">*</span></label>
                                                <input type="text"
                                                       class="form-control"
                                                       id="lastname"
                                                       name="lastname"
                                                       value="<?php echo htmlspecialchars($staff_data['lastname']); ?>"
                                                       required>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label for="email">Email <span class="text-danger">*</span></label>
                                                <input type="email"
                                                       class="form-control"
                                                       id="email"
                                                       name="email"
                                                       value="<?php echo htmlspecialchars($staff_data['email']); ?>"
                                                       required>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label for="phone">Phone</label>
                                                <input type="text"
                                                       class="form-control"
                                                       id="phone"
                                                       name="phone"
                                                       value="<?php echo htmlspecialchars($staff_data['phone'] ?? ''); ?>">
                                            </div>
                                        </div>
                                    </div>

                                    <div class="form-group">
                                        <label for="position">Position</label>
                                        <input type="text"
                                               class="form-control"
                                               id="position"
                                               name="position"
                                               value="<?php echo htmlspecialchars($staff_data['position'] ?? ''); ?>">
                                    </div>

                                    <div class="form-group">
                                        <button type="submit" name="update_staff" class="btn btn-primary">Update Staff</button>
                                        <a href="news.php" class="btn btn-secondary">Cancel</a>
                                    </div>
                                </form>
                            <?php else: ?>
                                <div class="alert alert-info">
                                    <p>No staff member found for editing or an error occurred.</p>
                                </div>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
</div>

<?php
// Close database connection
if (function_exists('close_connection')) {
    close_connection();
}
include_once 'includes/footer.php';
?>
