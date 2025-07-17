<?php
include_once 'includes/header.php';
include_once 'includes/connection.php'; // Make sure this file sets up $conn
?>


<!--===== HERO AREA STARTS =======-->
<div class="inner-header-section-area" style="background-image: url(assets/img/all-images/bg/bg9.png); background-position: center; background-repeat: no-repeat; background-size: cover;">
  <img src="assets/img/elements/elements28.png" alt="" class="elements28">
  <div class="container">
    <div class="row align-items-center">
      <div class="col-lg-12">
        <div class="hero-header">
          <h1 class="text-anime-style-1">Our Services</h1>
          <div class="space28"></div>
          <a href="index.html" class="bradecrumb">Home <i class="fa-solid fa-angle-right"></i> Our Services</a>
        </div>
      </div>
    </div>
  </div>
</div>
<!--===== HERO AREA ENDS =======-->

<!--===== SERVICE AREA STARTS =======-->
<div class="service-inner-area sp8">
  <div class="container">
    <div class="row">
      <?php
      $sql = "SELECT * FROM services";
      $result = mysqli_query($conn, $sql);
      if ($result && mysqli_num_rows($result) > 0) {
        while ($row = mysqli_fetch_assoc($result)) {
      ?>
      <div class="col-lg-3 col-md-6">
        <div class="service4-boxarea">
          <div class="content-area">
            <a href="service-single.php?id=<?php echo $row['id']; ?>" class="title"><?php echo htmlspecialchars($row['title']); ?></a>
            <div class="space16"></div>
            <p><?php echo htmlspecialchars($row['description']); ?></p>
            <div class="space24"></div>
            <a href="service-single.php?id=<?php echo $row['id']; ?>" class="readmore">
              Learn More 
              <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 16 16" fill="none">
              <g clip-path="url(#clip0_5927_10805)">
                <path fill-rule="evenodd" clip-rule="evenodd" d="M7.99992 0.833008C4.04188 0.833008 0.833252 4.04163 0.833252 7.99967C0.833252 11.9577 4.04188 15.1663 7.99992 15.1663C11.958 15.1663 15.1666 11.9577 15.1666 7.99967C15.1666 4.04163 11.958 0.833008 7.99992 0.833008ZM7.33325 5.33301C7.06359 5.33301 6.82052 5.49543 6.71732 5.74455C6.61415 5.99367 6.67119 6.28042 6.86185 6.47108L7.72379 7.33301L5.52851 9.52827C5.26817 9.78861 5.26817 10.2107 5.52851 10.4711C5.78887 10.7314 6.21097 10.7314 6.47133 10.4711L8.66659 8.27581L9.52852 9.13774C9.71919 9.32841 10.0059 9.38547 10.2551 9.28227C10.5042 9.17907 10.6666 8.93601 10.6666 8.66634V5.99967C10.6666 5.63149 10.3681 5.33301 9.99992 5.33301H7.33325Z" fill="#4416FF"/>
              </g>
              <defs>
                <clipPath id="clip0_5927_10805">
                  <rect width="16" height="16" fill="white"/>
                </clipPath>
              </defs>
            </svg>
            </a>
          </div>
  
          <div class="img1 image-anime">
            <img src="admin/assets/img/services/<?php echo htmlspecialchars($row['image'] ?? 'default.png'); ?>" alt="">
          </div>
        </div>
      </div>
      <?php
        }
      } else {
        echo '<p>No services found.</p>';
      }
      ?>
    </div>
  </div>
</div>
<!--===== SERVICE AREA ENDS =======-->

<?php
include_once 'includes/footer.php';
?>