<?php
// Enable error reporting for debugging
error_reporting(E_ALL);
ini_set('display_errors', 1);

include_once 'includes/header.php';
include_once 'includes/sidebar.php';

// --- Start of Database Connection Handling ---
$staff_result = ['success' => false, 'data' => [], 'error' => ''];

// Multiple possible paths for connection.php
$possiblePaths = [
    'connection.php',           // Same directory
    '../connection.php',        // Parent directory
    'config/connection.php',    // Config subdirectory
    'includes/connection.php',  // Includes subdirectory
    'db/connection.php',        // Database subdirectory
];

$databaseConfigPath = null;
foreach ($possiblePaths as $path) {
    if (file_exists($path)) {
        $databaseConfigPath = $path;
        break;
    }
}

if ($databaseConfigPath === null) {
    $staff_result['error'] = "Critical Error: Database configuration file 'connection.php' not found. Checked paths: " . implode(', ', $possiblePaths);
    echo "<!-- DEBUG: " . htmlspecialchars($staff_result['error']) . " -->";
    echo "<!-- DEBUG: Current working directory: " . getcwd() . " -->";
    echo "<!-- DEBUG: Files in current directory: " . implode(', ', scandir('.')) . " -->";
} else {
    echo "<!-- DEBUG: Found connection.php at: " . $databaseConfigPath . " -->";
    // Include the database connection file
    include_once $databaseConfigPath;

    // Check if the MySQLi connection was established successfully
    if (!isset($conn) || $conn === null) {
        // If $conn is not set or null, it means connection failed
        $error_message = isset($db_connection_error_mysqli) ? $db_connection_error_mysqli : 'Database connection failed or not established. Check connection.php for details.';
        $staff_result['error'] = $error_message;
        echo "<!-- DEBUG: MySQLi connection variable (\$conn) is NOT available. Error: " . htmlspecialchars($staff_result['error']) . " -->";
    } else {
        echo "<!-- DEBUG: MySQLi connection variable (\$conn) is available. -->";

        // Check if get_all_staff function exists
        if (!function_exists('get_all_staff')) {
            echo '<!-- DEBUG: Error: get_all_staff() function is not defined. Check connection.php. -->';
            $staff_result['error'] = 'get_all_staff() function not defined in connection.php';
        } else {
            echo "<!-- DEBUG: get_all_staff() function is defined. Attempting to fetch staff. -->";
            // Get all staff members using the function from connection.php
            $staff_result = get_all_staff();

            // Debugging: Output the raw result of get_all_staff()
            echo "<!-- DEBUG: Raw \$staff_result from get_all_staff(): " . htmlspecialchars(print_r($staff_result, true)) . " -->";
        }
    }
}
// --- End of Database Connection Handling ---

echo "<!-- DEBUG: Script finished initial processing. -->";

// Check for success messages from edit/delete operations
$show_success = false;
$success_message = '';
if (isset($_GET['deleted']) && $_GET['deleted'] == '1') {
    $show_success = true;
    $success_message = 'Staff member deleted successfully!';
}
if (isset($_GET['updated']) && $_GET['updated'] == '1') {
    $show_success = true;
    $success_message = 'Staff member updated successfully!';
}
?>

<!-- Main Content -->
<div class="main-content">
    <section class="section">
        <div class="section-body">
            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <div class="card-header">
                            <h4>All Staff Members</h4>
                            <!-- Removed "Add New Staff" button as requested -->
                            <!-- <a href="add_staff.php" class="btn btn-sm btn-primary">Add New Staff</a> -->
                        </div>
                        <div class="card-body">
                            <?php if ($show_success): ?>
                                <div class="alert alert-success alert-dismissible fade show" role="alert">
                                    <strong>Success!</strong> <?php echo htmlspecialchars($success_message); ?>
                                    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                        <span aria-hidden="true">&times;</span>
                                    </button>
                                </div>
                            <?php endif; ?>
                            
                            <?php if ($staff_result['success']): ?>
                                <?php if (count($staff_result['data']) > 0): ?>
                                    <div class="table-responsive">
                                        <table class="table table-striped">
                                            <thead>
                                                <tr>
                                                    <th>ID</th>
                                                    <th>First Name</th>
                                                    <th>Last Name</th>
                                                    <th>Email</th>
                                                    <th>Phone</th>
                                                    <th>Position</th>
                                                    <th>Date Added</th>
                                                    <th>Actions</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <?php foreach ($staff_result['data'] as $staff): ?>
                                                    <tr>
                                                        <td><?php echo htmlspecialchars($staff['id']); ?></td>
                                                        <td><?php echo htmlspecialchars($staff['firstname']); ?></td>
                                                        <td><?php echo htmlspecialchars($staff['lastname']); ?></td>
                                                        <td><?php echo htmlspecialchars($staff['email']); ?></td>
                                                        <td><?php echo htmlspecialchars($staff['phone'] ?? 'N/A'); ?></td>
                                                        <td><?php echo htmlspecialchars($staff['position'] ?? 'N/A'); ?></td>
                                                        <td><?php echo date('M j, Y', strtotime($staff['date_added'])); ?></td>
                                                        <td>
                                                            <a href="edit_staff.php?id=<?php echo $staff['id']; ?>" class="btn btn-sm btn-warning">Edit</a>
                                                            <a href="delete_staff.php?id=<?php echo $staff['id']; ?>" class="btn btn-sm btn-danger" onclick="return confirm('Are you sure you want to delete this staff member?')">Delete</a>
                                                        </td>
                                                    </tr>
                                                <?php endforeach; ?>
                                            </tbody>
                                        </table>
                                    </div>
                                <?php else: ?>
                                    <div class="alert alert-info">
                                        <h5>No Staff Members Found</h5>
                                        <p>There are no staff members in the database yet.</p>
                                        <!-- Removed "Add First Staff Member" button as requested, since this page is for viewing only -->
                                        <!-- <a href="add_staff.php" class="btn btn-primary">Add First Staff Member</a> -->
                                    </div>
                                <?php endif; ?>
                            <?php else: ?>
                                <div class="alert alert-danger">
                                    <h5>Database Error</h5>
                                    <p><?php echo htmlspecialchars($staff_result['error']); ?></p>
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
// Ensure the connection is closed at the end of the script, if close_connection is defined
if (function_exists('close_connection')) {
    close_connection();
}
include_once 'includes/footer.php';
?>