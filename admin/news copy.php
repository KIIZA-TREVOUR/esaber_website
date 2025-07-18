<?php
// Enable error reporting for debugging
error_reporting(E_ALL);
ini_set('display_errors', 1);

include_once 'includes/header.php';
include_once 'includes/sidebar.php';
$news= getNews($conn);

if(isset($_GET['id'])){
  $res = deleteNews($conn, $_GET['id']);
  if($res){
       echo "<script>alert('News deleted')</script>";
       echo "<script>window.location.href='news.php'</script>";
  } else{
 echo "<script>alert('fail')</script>";
}
  }
  
?>

<!-- Main Content -->
<div class="main-content">
    <section class="section">
        <div class="section-body">
            <div class="row">
              <div class="col-12 col-md-6 col-lg-12">
                <div class="card">
                  <div class="card-header">
                    <h4>All News</h4>
                  </div>
                  <div class="card-body">
                    <table class="table">
                      <thead>
                        <tr>
                          <th scope="col">#</th>
                          <th scope="col">Image</th>
                          <th scope="col">Title</th>
                          <th scope="col">Description</th>
                          
                          <th scope="col">Actions</th>
                        </tr>
                      </thead>
                      <tbody>
                        <?php foreach ($news as $new):?>
                        <tr>
                          <th scope="row">1</th>
                          <td><img width="35"src="<?=$new['image']?>" alt="img" class="img-thumbnail" /></td>
                          <td><?=$new['title']?></td>
                          <td><?=$new['description']?></td>
                          
                          <td><a href="news.php?id=<?=$new['id']?>" class="btn-sm bg-warning" ><i class="fas fa-trash
                          "></i></a></td>
                        </tr>
                       <?php endforeach?>
                      </tbody>
                      <tfoot>
                        <tr>
                          <th scope="col">#</th>
                           <th scope="col">Image</th>
                          <th scope="col">Title</th>
                          <th scope="col">Description</th>
                         
                          <th scope="col">Actions</th>
                        </tr>
                      </tfoot>
                    </table>
                  </div>
                </div>
            </div>
        </div>
    </section>
</div>

<?php
include_once 'includes/footer.php';
?>