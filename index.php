<?php 
session_start(); 
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

require_once 'php/functions.php';
require_once 'php/header.php';
require_once 'php/crud-operation.php';

class UserRegistration {
  private $conn;
  private $errors = [];

  public function __construct($dbConnection) {
    $this->conn = $dbConnection;
  }

  public function registerUser($name, $password, $email) {
    $this->validateInput($name, $password, $email);

    if (empty($this->errors)) {
      if ($this->isEmailExists($email)) {
        $this->errors[] = "Email already exists";
      } else {
        $this->saveUser($name, $password, $email);
      }
    }

    return $this->errors;
  }

  private function validateInput($name, $password, $email) {
    if (empty($name) || empty($password) || empty($email)) {
      $this->errors[] = "All fields are required";
    }

    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
      $this->errors[] = "Email is not valid";
    }

    if (strlen($password) < 8) {
      $this->errors[] = "Password must be at least 8 characters long";
    }
  }

  private function isEmailExists($email) {
    $sql = "SELECT * FROM users WHERE email = ?";
    $stmt = mysqli_prepare($this->conn, $sql);

    if ($stmt) {
      mysqli_stmt_bind_param($stmt, "s", $email);
      mysqli_stmt_execute($stmt);
      mysqli_stmt_store_result($stmt);
      $rowCount = mysqli_stmt_num_rows($stmt);
      mysqli_stmt_close($stmt);

      return $rowCount > 0;
    } else {
      $this->errors[] = "Database error";
      return false;
    }
  }

  private function saveUser($name, $password, $email) {
    $password_hashed = password_hash($password, PASSWORD_DEFAULT);
    $sql = "INSERT INTO users (full_name, password, email) VALUES (?, ?, ?)";
    $stmt = mysqli_prepare($this->conn, $sql);

    if ($stmt) {
      mysqli_stmt_bind_param($stmt, "sss", $name, $password_hashed, $email);
      mysqli_stmt_execute($stmt);
      $_SESSION['registration_success'] = true;
      mysqli_stmt_close($stmt);
    } else {
      $this->errors[] = "Something went wrong. Please try again.";
    }
  }
}

if (!isset($conn) || !$conn) {
    die("Database connection error. Please check your configuration.");
}

$userRegistration = new UserRegistration($conn);

$errors = [];

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['sign_up'])) {
    $name = trim($_POST["name"]);
    $password = trim($_POST["password"]);
    $email = trim($_POST["email"]);
    $errors = $userRegistration->registerUser($name, $password, $email);

    if (empty($errors)) {
        $_SESSION['registration_success'] = true;
        header("Location: " . $_SERVER['PHP_SELF']);
        exit();
    }
}

if (isset($_SESSION['errors'])) {
    $errors = array_merge($errors, $_SESSION['errors']);
    unset($_SESSION['errors']);
}

$showRegistrationSuccess = false;
if (isset($_SESSION['registration_success']) && $_SESSION['registration_success']) {
    $showRegistrationSuccess = true;
    unset($_SESSION['registration_success']);
}

if (isset($_SESSION['login_success']) && $_SESSION['login_success']) {
    echo "<script>
        document.addEventListener('DOMContentLoaded', function () {
            var loginSuccessModal = new bootstrap.Modal(document.getElementById('loginSuccessModal'));
            loginSuccessModal.show();
        });
    </script>";
    unset($_SESSION['login_success']);
}

add_navbar();
?>

<body>
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
    <div class="modal-content">
      <form method="post" action="php/admin_login.php">
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
          <div class="form-check mb-3">
            <input class="form-check-input" type="checkbox" id="rememberMeUser" name="remember_me">
            <label class="form-check-label" for="rememberMeUser">Remember Me</label>
          </div>
        </div>
        <div class="modal-footer">
          <button type="submit" class="btn btn-primary" name="log_in">Log in</button>
          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
        </div>
      </form>
    </div>
  </div>
</div>

<?php ?>

<?php if (isset($errors) && count($errors) > 0): ?>
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
              <div class="alert alert-danger mb-1 p-2"><?= htmlspecialchars($error) ?></div>
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

 <div class="modal fade" id="registrationSuccessModal" tabindex="-1" aria-labelledby="registrationSuccessModalLabel" aria-hidden="true">
      <div class="modal-dialog">
        <div class="modal-content">
          <div class="modal-header bg-success text-white">
            <h5 class="modal-title" id="registrationSuccessModalLabel">Registration Successful</h5>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
          </div>
          <div class="modal-body">
            You are registered successfully.
          </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-success" data-bs-dismiss="modal">OK</button>
          </div>
        </div>
      </div>
    </div>

    <?php if ($showRegistrationSuccess): ?>
    <script>
      document.addEventListener('DOMContentLoaded', function () {
        var registrationSuccessModal = new bootstrap.Modal(document.getElementById('registrationSuccessModal'));
        registrationSuccessModal.show();
      });
    </script>
    <?php endif; ?>

<!-- Login Success Modal -->
<div class="modal fade" id="loginSuccessModal" tabindex="-1" aria-labelledby="loginSuccessModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header bg-success text-white">
        <h5 class="modal-title" id="loginSuccessModalLabel">Login Successful</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <?= isset($_SESSION['login_message']) ? htmlspecialchars($_SESSION['login_message']) : 'Welcome back! You\'ve logged in successfully.' ?>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-success" data-bs-dismiss="modal">OK</button>
      </div>
    </div>
  </div>
</div>

<script>
// Add this script to hide footer when modal shows
document.addEventListener('DOMContentLoaded', function() {
    var loginModal = document.getElementById('loginSuccessModal');
    if (loginModal) {
        var modal = new bootstrap.Modal(loginModal);
        
        // Hide footer when modal shows
        loginModal.addEventListener('show.bs.modal', function() {
            document.querySelector('footer').style.display = 'none';
        });
        
        // Show footer when modal hides
        loginModal.addEventListener('hidden.bs.modal', function() {
            document.querySelector('footer').style.display = 'block';
        });
        
        <?php if (isset($_SESSION['login_success']) && $_SESSION['login_success']): ?>
        modal.show();
        <?php unset($_SESSION['login_success']); ?>
        <?php endif; ?>
    }
});
</script>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.10.2/dist/umd/popper.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.min.js"></script>


<?php 
  include ('php/footer.php');
?>
