<?php
// Enable error reporting for debugging
error_reporting(E_ALL);
ini_set('display_errors', 1);

include_once 'includes/header.php';
include_once 'includes/sidebar.php';
include_once 'includes/connection.php';

// Fetch staff/doctors from the database
$default_staff_img = 'team-img10.png';
$staff = [];
$sql = "SELECT name, specialty, image FROM staff";
$result = $conn->query($sql);
if (!$result) {
    echo '<div style="color:red;">Query failed: ' . $conn->error . '</div>';
} else {
    echo '<!-- Number of staff found: ' . $result->num_rows . ' -->';
    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            $staff[] = $row;
        }
    }
}
?>

<!--===== HERO AREA STARTS =======-->
<!-- ... your existing hero area code ... -->
<!--===== HERO AREA ENDS =======-->

<!--===== TEAM AREA STARTS =======-->
<div class="team-inner-section-area sp1">
    <div class="container">
        <div class="row">
            <?php if (!empty($staff)): ?>
                <?php foreach ($staff as $member): ?>
                    <?php echo '<!-- DEBUG: ' . htmlspecialchars($member['name']) . ' -->'; ?>
                    <div class="col-lg-4 col-md-6">
                        <div class="team4-boxarea">
                            <div class="img1">
                                <img src="assets/img/all-images/team/<?php echo htmlspecialchars($member['image'] ? $member['image'] : $default_staff_img); ?>" alt="" class="team-img10">
                                <img src="assets/img/elements/elements13.png" alt="" class="elements13 keyframe5">
                            </div>
                            <div class="name-area">
                                <a href="doctor.html"><?php echo htmlspecialchars($member['name']); ?></a>
                                <div class="space12"></div>
                                <p><?php echo htmlspecialchars($member['specialty']); ?></p>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            <?php else: ?>
                <div class="col-12">
                    <p>No staff found.</p>
                </div>
            <?php endif; ?>
        </div>
        <!-- ...pagination and other content... -->
    </div>
</div>
<!--===== TEAM AREA ENDS =======-->

<!--===== CTA AREA STARTS =======-->
<!-- ... your existing CTA area code ... -->
<!--===== CTA AREA ENDS =======-->

<?php
include_once 'includes/footer.php';
?>