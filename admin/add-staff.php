<?php 
// Enable error reporting for debugging
error_reporting(E_ALL);
ini_set('display_errors', 1);

include_once 'includes/header.php';
include_once 'includes/sidebar.php';

// Database configuration - Updated based on your phpMyAdmin screenshot
$host = 'localhost';
$username = 'root';  // Default XAMPP username
$password = '';      // Default XAMPP password (empty)
$database = 'esaber'; // Your database name from the image

// Create connection
$conn = new mysqli($host, $username, $password, $database);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

echo "<!-- Database connection successful -->";

// Initialize variables
$message = '';
$messageType = '';
$firstname = $lastname = $email = $phone = $position = '';

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    echo "<!-- Form submitted -->";
    
    // Get form data and sanitize
    $firstname = trim($_POST['firstname']);
    $lastname = trim($_POST['lastname']);
    $email = trim($_POST['email']);
    $phone = trim($_POST['phone']);
    $position = trim($_POST['position']);
    
    echo "<!-- Form data received: firstname=$firstname, lastname=$lastname, email=$email -->";
    
    // Basic validation
    $errors = [];
    
    if (empty($firstname)) {
        $errors[] = "First name is required";
    }
    
    if (empty($lastname)) {
        $errors[] = "Last name is required";
    }
    
    if (empty($email)) {
        $errors[] = "Email is required";
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $errors[] = "Invalid email format";
    }
    
    // Check if email already exists
    if (empty($errors)) {
        echo "<!-- Checking for duplicate email -->";
        $check_email = $conn->prepare("SELECT id FROM staff WHERE email = ?");
        
        if ($check_email === false) {
            $errors[] = "Database error: " . $conn->error;
        } else {
            $check_email->bind_param("s", $email);
            $check_email->execute();
            $result = $check_email->get_result();
            
            if ($result->num_rows > 0) {
                $errors[] = "Email already exists";
            }
            $check_email->close();
        }
    }
    
    // If no errors, insert data
    if (empty($errors)) {
        echo "<!-- Attempting to insert data -->";
        
        // Insert data - removed added_by since it can be NULL
        $stmt = $conn->prepare("INSERT INTO staff (firstname, lastname, email, phone, position) VALUES (?, ?, ?, ?, ?)");
        
        if ($stmt === false) {
            $message = "Prepare failed: " . $conn->error;
            $messageType = "danger";
        } else {
            $stmt->bind_param("sssss", $firstname, $lastname, $email, $phone, $position);
            
            if ($stmt->execute()) {
                $message = "Staff member added successfully! ID: " . $conn->insert_id;
                $messageType = "success";
                
                // Clear form data after successful submission
                $firstname = $lastname = $email = $phone = $position = '';
            } else {
                $message = "Execute failed: " . $stmt->error;
                $messageType = "danger";
            }
            
            $stmt->close();
        }
    } else {
        $message = implode("<br>", $errors);
        $messageType = "danger";
    }
}

$conn->close();
?>

<!-- Main Content -->
<div class="main-content">
    <section class="section">
        <div class="section-body">
            <div class="row">
                <div class="col-12 col-md-6 col-lg-12">
                    <div class="card">
                        <div class="card-header row">
                            <h4>Add Staff </h4>
                            <a href="news.php" class="btn btn-sm btn-success">All Staff</a>
                        </div>
                        <div class="card-body">
                            <!-- Display success/error messages -->
                            <?php if (!empty($message)): ?>
                                <div class="alert alert-<?php echo $messageType; ?> alert-dismissible fade show" role="alert">
                                    <?php echo $message; ?>
                                    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                        <span aria-hidden="true">&times;</span>
                                    </button>
                                </div>
                            <?php endif; ?>

                            <form method="POST" action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']); ?>">
                                <div class="row">
                                    <div class="form-group col-lg-4">
                                        <label>First Name <span class="text-danger">*</span></label>
                                        <input type="text" name="firstname" class="form-control" value="<?php echo htmlspecialchars($firstname); ?>" required>
                                    </div>
                                    <div class="form-group col-lg-4">
                                        <label>Last Name <span class="text-danger">*</span></label>
                                        <input type="text" name="lastname" class="form-control" value="<?php echo htmlspecialchars($lastname); ?>" required>
                                    </div>
                                    <div class="form-group col-lg-4">
                                        <label>Email <span class="text-danger">*</span></label>
                                        <input type="email" name="email" class="form-control" value="<?php echo htmlspecialchars($email); ?>" required>
                                    </div>
                                    <div class="form-group col-lg-4">
                                        <label>Phone</label>
                                        <input type="text" name="phone" class="form-control" value="<?php echo htmlspecialchars($phone); ?>">
                                    </div>
                                    <div class="form-group col-lg-4">
                                        <label>Position</label>
                                        <input type="text" name="position" class="form-control" value="<?php echo htmlspecialchars($position); ?>">
                                    </div>
                                    <div class="form-group col-lg-4">
                                        <button type="submit" class="btn btn-primary">Submit</button>
                                        <button type="reset" class="btn btn-secondary ml-2">Reset</button>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
    
    <!-- Settings Sidebar (kept unchanged) -->
    <div class="settingSidebar">
        <a href="javascript:void(0)" class="settingPanelToggle"> <i class="fa fa-spin fa-cog"></i>
        </a>
        <div class="settingSidebar-body ps-container ps-theme-default">
            <div class=" fade show active">
                <div class="setting-panel-header">Setting Panel
                </div>
                <div class="p-15 border-bottom">
                    <h6 class="font-medium m-b-10">Select Layout</h6>
                    <div class="selectgroup layout-color w-50">
                        <label class="selectgroup-item">
                            <input type="radio" name="value" value="1" class="selectgroup-input-radio select-layout" checked>
                            <span class="selectgroup-button">Light</span>
                        </label>
                        <label class="selectgroup-item">
                            <input type="radio" name="value" value="2" class="selectgroup-input-radio select-layout">
                            <span class="selectgroup-button">Dark</span>
                        </label>
                    </div>
                </div>
                <div class="p-15 border-bottom">
                    <h6 class="font-medium m-b-10">Sidebar Color</h6>
                    <div class="selectgroup selectgroup-pills sidebar-color">
                        <label class="selectgroup-item">
                            <input type="radio" name="icon-input" value="1" class="selectgroup-input select-sidebar">
                            <span class="selectgroup-button selectgroup-button-icon" data-toggle="tooltip"
                                data-original-title="Light Sidebar"><i class="fas fa-sun"></i></span>
                        </label>
                        <label class="selectgroup-item">
                            <input type="radio" name="icon-input" value="2" class="selectgroup-input select-sidebar" checked>
                            <span class="selectgroup-button selectgroup-button-icon" data-toggle="tooltip"
                                data-original-title="Dark Sidebar"><i class="fas fa-moon"></i></span>
                        </label>
                    </div>
                </div>
                <div class="p-15 border-bottom">
                    <h6 class="font-medium m-b-10">Color Theme</h6>
                    <div class="theme-setting-options">
                        <ul class="choose-theme list-unstyled mb-0">
                            <li title="white" class="active">
                                <div class="white"></div>
                            </li>
                            <li title="cyan">
                                <div class="cyan"></div>
                            </li>
                            <li title="black">
                                <div class="black"></div>
                            </li>
                            <li title="purple">
                                <div class="purple"></div>
                            </li>
                            <li title="orange">
                                <div class="orange"></div>
                            </li>
                            <li title="green">
                                <div class="green"></div>
                            </li>
                            <li title="red">
                                <div class="red"></div>
                            </li>
                        </ul>
                    </div>
                </div>
                <div class="p-15 border-bottom">
                    <div class="theme-setting-options">
                        <label class="m-b-0">
                            <input type="checkbox" name="custom-switch-checkbox" class="custom-switch-input"
                                id="mini_sidebar_setting">
                            <span class="custom-switch-indicator"></span>
                            <span class="control-label p-l-10">Mini Sidebar</span>
                        </label>
                    </div>
                </div>
                <div class="p-15 border-bottom">
                    <div class="theme-setting-options">
                        <label class="m-b-0">
                            <input type="checkbox" name="custom-switch-checkbox" class="custom-switch-input"
                                id="sticky_header_setting">
                            <span class="custom-switch-indicator"></span>
                            <span class="control-label p-l-10">Sticky Header</span>
                        </label>
                    </div>
                </div>
                <div class="mt-4 mb-4 p-3 align-center rt-sidebar-last-ele">
                    <a href="#" class="btn btn-icon icon-left btn-primary btn-restore-theme">
                        <i class="fas fa-undo"></i> Restore Default
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>

<?php include_once 'includes/footer.php'; ?>