<?php
// Enable error reporting for debugging
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Initialize variables
$error_message = '';
$success_message = '';
$staff_id = null;
$staff_data = null;

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
    echo "<!-- DEBUG: " . htmlspecialchars($error_message) . " -->";
    echo "<!-- DEBUG: Current working directory: " . getcwd() . " -->";
    // For further debugging, you can list files in the current directory:
    // echo "<!-- DEBUG: Files in current directory: " . implode(', ', scandir('.')) . " -->";
} else {
    echo "<!-- DEBUG: Found connection.php at: " . htmlspecialchars($databaseConfigPath) . " -->";
    include_once $databaseConfigPath;

    // Check if connection is available
    global $conn;
    global $db_connection_error_mysqli; // This variable is set in connection.php on error

    if (!isset($conn) || $conn === null) {
        $error_message = $db_connection_error_mysqli ?: "Database connection not available. Check connection.php for details.";
        echo "<!-- DEBUG: mysqli connection variable (\$conn) is NOT available. Error: " . htmlspecialchars($error_message) . " -->";
    } else {
        echo "<!-- DEBUG: mysqli connection variable (\$conn) is available. -->";

        // Check if ID is provided in URL
        if (isset($_GET['id']) && !empty($_GET['id'])) {
            $staff_id = intval($_GET['id']);
        } else {
            // Redirect back to staff list if no ID provided
            // FIXED: Redirect to 'news.php' as it's the actual staff list page
            header("Location: news.php");
            exit();
        }

        // Handle form submission for deletion confirmation
        if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['confirm_delete'])) {
            echo "<!-- DEBUG: Form submitted for deletion confirmation. -->";
            if (function_exists('delete_staff')) {
                $result = delete_staff($staff_id);

                if ($result['success']) {
                    // Redirect to staff list with success message
                    // FIXED: Redirect to 'news.php' as it's the actual staff list page
                    header("Location: news.php?deleted=1");
                    exit();
                } else {
                    $error_message = "Error deleting staff: " . $result['error'];
                }
            } else {
                $error_message = "Error: delete_staff() function not found. Check connection.php.";
            }
        }

        // Fetch staff data to show confirmation details (if not already deleted)
        if ($staff_id && empty($error_message)) {
            echo "<!-- DEBUG: Attempting to fetch staff data for ID: " . htmlspecialchars($staff_id) . " for deletion confirmation. -->";
            if (function_exists('get_staff_by_id')) {
                $result = get_staff_by_id($staff_id);

                if ($result['success']) {
                    $staff_data = $result['data'];
                    echo "<!-- DEBUG: Staff data fetched: " . htmlspecialchars(json_encode($staff_data)) . " -->";
                } else {
                    $error_message = "Staff member not found or error fetching data: " . $result['error'];
                    echo "<!-- DEBUG: Error fetching staff data: " . htmlspecialchars($result['error']) . " -->";
                }
            } else {
                $error_message = "Error: get_staff_by_id() function not found. Check connection.php.";
            }
        }
    }
}

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
                            <h4>Delete Staff Member</h4>
                            <a href="news.php" class="btn btn-sm btn-secondary">Back to Staff List</a>
                        </div>
                        <div class="card-body">
                            <?php if (!empty($error_message)): ?>
                                <div class="alert alert-danger">
                                    <h5>Error</h5>
                                    <p><?php echo htmlspecialchars($error_message); ?></p>
                                </div>
                            <?php endif; ?>

                            <?php if ($staff_data): ?>
                                <div class="alert alert-warning">
                                    <h5>⚠️ Confirmation Required</h5>
                                    <p>Are you sure you want to delete this staff member? This action cannot be undone.</p>
                                </div>

                                <div class="card">
                                    <div class="card-header">
                                        <h5>Staff Member Details</h5>
                                    </div>
                                    <div class="card-body">
                                        <div class="row">
                                            <div class="col-md-6">
                                                <p><strong>ID:</strong> <?php echo htmlspecialchars($staff_data['id']); ?></p>
                                                <p><strong>First Name:</strong> <?php echo htmlspecialchars($staff_data['firstname']); ?></p>
                                                <p><strong>Last Name:</strong> <?php echo htmlspecialchars($staff_data['lastname']); ?></p>
                                            </div>
                                            <div class="col-md-6">
                                                <p><strong>Email:</strong> <?php echo htmlspecialchars($staff_data['email']); ?></p>
                                                <p><strong>Phone:</strong> <?php echo htmlspecialchars($staff_data['phone'] ?? 'N/A'); ?></p>
                                                <p><strong>Position:</strong> <?php echo htmlspecialchars($staff_data['position'] ?? 'N/A'); ?></p>
                                            </div>
                                        </div>
                                        <p><strong>Date Added:</strong> <?php echo date('M j, Y g:i A', strtotime($staff_data['date_added'])); ?></p>
                                    </div>
                                </div>

                                <form method="POST" action="">
                                    <div class="form-group mt-3">
                                        <button type="submit" name="confirm_delete" class="btn btn-danger">
                                            <i class="fa fa-trash"></i> Yes, Delete This Staff Member
                                        </button>
                                        <a href="news.php" class="btn btn-secondary">
                                            <i class="fa fa-times"></i> Cancel
                                        </a>
                                    </div>
                                </form>
                            <?php else: ?>
                                <div class="alert alert-info">
                                    <p>No staff member found for deletion or an error occurred.</p>
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
