<?php 

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

session_start();

require_once 'php/functions.php';
require 'php/header.php';
include 'php/database.php';

$errors = [];

if (isset($_POST['sign_up'])){
    $name = trim($_POST["name"]);
    $password = $_POST["password"];
    $email = trim($_POST["email"]);

    $password_hashed = password_hash($password , PASSWORD_DEFAULT);

    $errors  = array();

    if (empty($name) || empty($password) || empty($email)){
        array_push($errors , "All fields are required");
    }

    if (!filter_var($email , FILTER_VALIDATE_EMAIL)){
        array_push($errors , "Email is not valid");
    }

    if (strlen($password) < 8){
        array_push($errors , "Password must be at least 8 characters long");
    }

    $sql = "SELECT * FROM users WHERE email = ?";
    $stmt = mysqli_prepare($conn , $sql);

    if ($stmt){
        mysqli_stmt_bind_param($stmt , "s" , $email);
        mysqli_stmt_execute($stmt);
        mysqli_stmt_store_result($stmt);
        $rowCount = mysqli_stmt_num_rows($stmt);
        mysqli_stmt_close($stmt);

        if ($rowCount > 0){
            array_push($errors , "Email already exists");
        }
    } else {
        array_push($errors , "Database error");
    }

    if (count($errors) === 0){
      $sql = "INSERT INTO users (full_name, password, email) VALUES (?, ?, ?)";
      $stmt = mysqli_prepare($conn, $sql);

      if ($stmt){
          mysqli_stmt_bind_param($stmt ,"sss" , $name , $password_hashed , $email);
          mysqli_stmt_execute($stmt);
          echo "<div class='alert alert-success'>You are registered successfully.</div>";
          mysqli_stmt_close($stmt);
      } else {
          array_push($errors , "Something went wrong. Please try again.");
      }
  }
}
?>


<body>

   <?php 

    if (isset($_POST["log_in"])){
      $email = $_POST["email"];
      $password = $_POST["password"];
      require_once "database.php";
      $sql = "SELECT * FROM users WHERE email = '$email'";
      $result = mysqli_query($conn , $sql);
      $user = mysqli_fetch_array($result , MYSQLI_ASSOC);

      if ($user ){
        if (password_verify($password , $user["password"] )){
          header("Location: index.php");
          die ();
        }else {
          echo "<div class = 'alert alert danger'>Passsword does not match </div>";
        }
      }else {
        echo "<div class = 'alert alert danger'> Email does not match </div>";
      }


        
    }

    add_navbar();
   ?>

    <div class="service-content" id="services">
        <div class="container">
            <div class="row">
                <div class="col-md-4">
                    <div class="left-text">
                        <h4>More About Vanilla</h4>
                        <div class="line-dec"></div>
                        <p>Vanilla is free HTML CSS template with Bootstrap v4.2.1 and you can apply this theme for your sites. 
                        Please share our <a rel="nofollow" href="https://templatemo.com">website</a> to your friends or collegues. Thank you.</p>
                        <ul>
                            <li>-  Praesent porta urna id eros</li>
                            <li>-  Curabitur consectetur malesuada</li>
                            <li>-  Nam pretium imperdiet enim</li>
                            <li>-  Sed viverra arcu non nisi efficitur</li>
                        </ul>
                        <div class="primary-button">
                            <a href="#portfolio">Learn More About Us</a>
                        </div>
                    </div>
                </div>
                <div class="col-md-8">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="service-item">
                                <h4>Classic Modern Design</h4>
                                <div class="line-dec"></div>
                                <p>Sed lacinia ligula est, at venenatis ex iaculis quis. Morbi sollicitudin nulla eget odio pellentesque.</p>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="service-item">
                                <h4>Unique &amp; Creative Ideas</h4>
                                <div class="line-dec"></div>
                                <p>Sed lacinia ligula est, at venenatis ex iaculis quis. Morbi sollicitudin nulla eget odio pellentesque.</p>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="service-item">
                                <h4>Effective Team Work</h4>
                                <div class="line-dec"></div>
                                <p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Sed lacinia ligula est, at venenatis ex iaculis quis.</p>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="service-item">
                                <h4>Fast Support 24/7</h4>
                                <div class="line-dec"></div>
                                <p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Sed lacinia ligula est, at venenatis ex iaculis quis.</p>
                            </div>
                        </div>
                    </div>
                </div>                
            </div>
        </div>
    </div>

    
    <div class="parallax-content projects-content" id="portfolio">
        <div class="container">
            <div class="row">
                <div class="col-md-12">
                    <div id="owl-testimonials" class="owl-carousel owl-theme">
                        <div class="item">
                            <div class="testimonials-item">
                                <a href="img/1st-big-item.jpg" data-lightbox="image-1"><img src="img/1st-item.jpg" alt=""></a>
                                <div class="text-content">
                                    <h4>Awesome Note Book</h4>
                                    <span>$18.00</span>
                                </div>
                            </div>
                        </div>
                        <div class="item">
                            <div class="testimonials-item">
                                <a href="img/2nd-big-item.jpg" data-lightbox="image-1"><img src="img/2nd-item.jpg" alt=""></a>
                                <div class="text-content">
                                    <h4>Antique Decoration Photo</h4>
                                    <span>$27.00</span>
                                </div>
                            </div>
                        </div>
                        <div class="item">
                            <div class="testimonials-item">
                                <a href="img/3rd-big-item.jpg" data-lightbox="image-1"><img src="img/3rd-item.jpg" alt=""></a>
                                <div class="text-content">
                                    <h4>Work Hand Bag</h4>
                                    <span>$36.00</span>
                                </div>
                            </div>
                        </div>
                        <div class="item">
                            <div class="testimonials-item">
                                <a href="img/4th-big-item.jpg" data-lightbox="image-1"><img src="img/4th-item.jpg" alt=""></a>
                                <div class="text-content">
                                    <h4>Smart Watch</h4>
                                    <span>$45.00</span>
                                </div>
                            </div>
                        </div>
                        <div class="item">
                            <div class="testimonials-item">
                                <a href="img/5th-big-item.jpg" data-lightbox="image-1"><img src="img/5th-item.jpg" alt=""></a>
                                <div class="text-content">
                                    <h4>PC Tablet Draw</h4>
                                    <span>$63.00</span>
                                </div>
                            </div>
                        </div>
                        <div class="item">
                            <div class="testimonials-item">
                                <a href="img/6th-big-item.jpg" data-lightbox="image-1"><img src="img/6th-item.jpg" alt=""></a>
                                <div class="text-content">
                                    <h4>Healthy Milkshake</h4>
                                    <span>$77.00</span>
                                </div>
                            </div>
                        </div>
                        <div class="item">
                            <div class="testimonials-item">
                                <a href="img/2nd-big-item.jpg" data-lightbox="image-1"><img src="img/2nd-item.jpg" alt=""></a>
                                <div class="text-content">
                                    <h4>Antique Decoration Photo</h4>
                                    <span>$84.50</span>
                                </div>
                            </div>
                        </div>
                        <div class="item">
                            <div class="testimonials-item">
                                <a href="img/1st-big-item.jpg" data-lightbox="image-1"><img src="img/1st-item.jpg" alt=""></a>
                                <div class="text-content">
                                    <h4>Awesome Notes Book</h4>
                                    <span>$96.75</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>


    <div class="tabs-content" id="our-story">
        <div class="container">
            <div class="row">
                <div class="col-md-8 mx-auto">
                    <div class="wrapper">
                    <section id="first-tab-group" class="tabgroup">
                      <div id="tab1">
                        <img src="img/1st-tab.jpg" alt="">
                        <p>Please do not re-distribute our template ZIP file on your template collection sites. You can make a screenshot and a link back to our website. This template can be used for personal or commercial purposes by end-users.</p>
                      </div>
                      <div id="tab2">
                        <img src="img/2nd-tab.jpg" alt="">
                        <p>Aliquam eu ultrices risus, sed condimentum diam. Duis risus nulla, elementum vitae nisi a, ornare maximus nisl. Morbi et vehicula est. Cras at vulputate justo. Cras eu nulla metus. Ut et pretium velit. Pellentesque at neque tristique dui tempor venenatis.</p>
                      </div>
                      <div id="tab3">
                        <img src="img/3rd-tab.jpg" alt="">
                        <p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Sed lacinia ligula est, at venenatis ex iaculis quis. Morbi sollicitudin nulla eget odio pellentesque, sed cursus diam iaculis.</p>
                      </div>
                      <div id="tab4">
                        <img src="img/4th-tab.jpg" alt="">
                        <p>Duis risus nulla, elementum vitae nisi a, ornare maximus nisl. Morbi et vehicula est. Cras at vulputate justo. Cras eu nulla metus. Ut et pretium velit. Pellentesque at neque tristique.</p>
                      </div>
                    </section>
                    <ul class="tabs clearfix" data-tabgroup="first-tab-group">
                      <li><a href="#tab1" class="active">2008 - 2014</a></li>
                      <li><a href="#tab2">2014 - 2016</a></li>
                      <li><a href="#tab3">2016 - 2019</a></li>
                      <li><a href="#tab4">2019 - Now</a></li>
                    </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>

  

<div class="parallax-content contact-content" id="contact-us">
  <div class="container">
    <div class="row align-items-start"> <!-- Added align-items-start to align both sides -->
      <div class="col-md-6">
        <div class="contact-form">
          <form id="contact" action="index.php" method="post">
            <div class="row">
              <div class="col-md-12">
                <fieldset>
                  <input name="name" type="text" class="form-control" id="name" placeholder="Your name..." required>
                </fieldset>
              </div>
              <div class="col-md-12">
                <fieldset>
                  <input name="password" type="password" class="form-control" id="password" placeholder="Your password..." required>
                </fieldset>
              </div>
              <div class="col-md-12">
                <fieldset>
                  <input name="email" type="email" class="form-control" id="email" placeholder="Your email..." required>
                </fieldset>
              </div>
              <div class="col-md-12">
                <fieldset>
                  <button type="submit" id="form-submit" class="btn" name = "sign_up">Sign up</button>
                </fieldset>
              </div>
            </div>
          </form>
        </div>
      </div>
      <div class="col-md-6">
        <div class="map">
          <iframe src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d1197183.8373802372!2d-1.9415093691103689!3d6.781986417238027!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0xfdb96f349e85efd%3A0xb8d1e0b88af1f0f5!2sKumasi+Central+Market!5e0!3m2!1sen!2sth!4v1532967884907"
            width="100%" height="390" frameborder="0" style="border:0" allowfullscreen></iframe>
        </div>
      </div>
    </div>
  </div>
</div>


<div class="modal fade" id="successModal" tabindex="-1" role="dialog" aria-labelledby="modalTitle" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered" role="document">
    <div class="modal-content">
      <div class="modal-header bg-success text-white">
        <h5 class="modal-title" id="modalTitle">Message Sent</h5>
        <button type="button" class="close text-white" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        Thank you for your message!
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-outline-success" data-dismiss="modal">Close</button>
      </div>
    </div>
  </div>
</div>

<!-- Admin Login Modal -->
<div class="modal fade" id="adminLoginModal" tabindex="-1" aria-labelledby="adminLoginLabel" aria-hidden="true">
  <div class="modal-dialog">
    <form class="modal-content" method="post" action="php/admin_login.php">
      <div class="modal-header">
        <h5 class="modal-title" id="adminLoginLabel">Log in</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      
      <div class="modal-body">
        <div class="mb-3">
          <label for="adminUsername" class="form-label">Email</label>
          <input type="text" class="form-control" id="adminUsername" name="email" required>
        </div>
        
        <div class="mb-3">
          <label for="adminPassword" class="form-label">Password</label>
          <input type="password" class="form-control" id="adminPassword" name="password" required>
        </div>

        <!-- Moved checkbox inside modal-body -->
        <div class="form-check mb-3">
        <input class="form-check-input" type="checkbox" id="rememberMeUser" name="remember_me">
        <label class="form-check-label" for="rememberMeUser">Remember Me</label>
        </div>

      </div>

      <div class="modal-footer">
        <button type="submit" class="btn btn-primary">Log in</button>
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
      </div>
    </form>
  </div>
</div>

<?php if (count($errors) > 0): ?>
    <div id="errorModal" class="modal fade" tabindex="-1" role="dialog">
      <div class="modal-dialog" role="document">
        <div class="modal-content">
          <div class="modal-header bg-danger text-white">
            <h5 class="modal-title">Error</h5>
            <button type="button" class="close text-white" data-dismiss="modal" aria-label="Close">
              <span aria-hidden="true">&times;</span>
            </button>
          </div>
          <div class="modal-body">
            <?php foreach ($errors as $error): ?>
              <div class="alert alert-danger mb-1 p-2"><?= $error ?></div>
            <?php endforeach; ?>
          </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
          </div>
        </div>
      </div>
    </div>

    <script>
      window.addEventListener('DOMContentLoaded', function () {
        $('#errorModal').modal('show');
      });
    </script>
<?php endif; ?>


  <?php 
    include ('php/footer.php');
  ?>