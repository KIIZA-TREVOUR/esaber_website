<?php
// Enable error reporting for debugging
error_reporting(E_ALL);
ini_set('display_errors', 1);

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
                            <h4>All Staff Members</h4>
                            <!-- Removed "Add New Staff" button as requested -->
                            <!-- <a href="add_staff.php" class="btn btn-sm btn-primary">Add New Staff</a> -->
                        </div>
                        <div class="card-body">
                            <?php if (count($staff) > 0): ?>
                                <div class="table-responsive">
                                    <table class="table table-striped">
                                        <thead>
                                            <tr>
                                                <th>Name</th>
                                                <th>Specialty</th>
                                                <th>Image</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php foreach ($staff as $member): ?>
                                                <tr>
                                                    <td><?php echo htmlspecialchars($member['name']); ?></td>
                                                    <td><?php echo htmlspecialchars($member['specialty']); ?></td>
                                                    <td>
                                                        <?php if (!empty($member['image'])): ?>
                                                            <img src="uploads/<?php echo htmlspecialchars($member['image']); ?>" alt="Staff Image" style="max-width: 100px; height: auto;">
                                                        <?php else: ?>
                                                            No Image
                                                        <?php endif; ?>
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
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
</div>

<?php
include_once 'includes/footer.php';
?>