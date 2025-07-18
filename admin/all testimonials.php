<?php
// Database connection setup
$servername = "localhost";
$username = "root"; // Replace with your database username
$password = "";     // Replace with your database password
$dbname = "isaber"; // Changed from "esaber" to "isaber"

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Fetch testimonials data
$sql = "SELECT id, name, description, position FROM testimonials";
$result = $conn->query($sql);

include_once 'includes/header.php';
include_once 'includes/sidebar.php';
?>


      <!-- Main Content -->
      <div class="main-content">
        <section class="section">
          <div class="section-body">
            <div class="row">
              <div class="col-12 col-md-6 col-lg-12">
                <div class="card">
                  <div class="card-header">
                    <h4>All testimonials</h4>
                  </div>
                  <div class="card-body">
                    <table class="table">
                      <thead>
                        <tr>
                          <th scope="col">id</th>
                          <th scope="col">name</th>
                          <th scope="col">Description</th>
                          <th scope="col">position</th>
                        </tr>
                      </thead>
                      <tbody>
                        <?php if ($result->num_rows > 0) : ?>
                          <?php while($row = $result->fetch_assoc()) : ?>
                            <tr>
                              <td><?= $row['id'] ?></td>
                              <td><?= htmlspecialchars($row['name']) ?></td>
                              <td><?= htmlspecialchars($row['description']) ?></td>
                              <td><?= htmlspecialchars($row['position']) ?></td>
                            </tr>
                          <?php endwhile; ?>
                        <?php else : ?>
                          <tr>
                            <td colspan="4">No testimonials found</td>
                          </tr>
                        <?php endif; ?>
                      </tbody>
                    </table>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </section>
        <div class="settingSidebar">
          <!-- ... (existing setting sidebar content remains unchanged) ... -->
        </div>
      </div>

<?php 
// Close database connection
$conn->close();
include_once 'includes/footer.php'; 
?>