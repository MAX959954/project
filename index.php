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

class Application {
  private $conn;
  private $userRegistration;
  private $pageManager;
  private $errors = [];
  private $showRegistrationSuccess = false;

  public function __construct($dbConnection) {
    $this->conn = $dbConnection;
    $this->initializeComponents();
  }

  private function initializeComponents() {
    if (!isset($this->conn) || !$this->conn) {
      die("Database connection error. Please check your configuration.");
    }

    $this->userRegistration = new UserRegistration($this->conn);
    $this->pageManager = new PageManager();
  }

  public function handleRequest() {
    if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['sign_up'])) {
      $this->processRegistration();
    }

    $this->loadSessionErrors();
    $this->checkRegistrationSuccess();
    $this->checkLoginSuccess();
    $this->renderPageComponents();
  }

  private function processRegistration() {
    $name = trim($_POST["name"]);
    $password = trim($_POST["password"]);
    $email = trim($_POST["email"]);
    $this->errors = $this->userRegistration->registerUser($name, $password, $email);

    if (empty($this->errors)) {
      $_SESSION['registration_success'] = true;
      header("Location: " . $_SERVER['PHP_SELF']);
      exit();
    }
  }

  private function loadSessionErrors() {
    if (isset($_SESSION['errors'])) {
      $this->errors = array_merge($this->errors, $_SESSION['errors']);
      unset($_SESSION['errors']);
    }
  }

  private function checkRegistrationSuccess() {
    if (isset($_SESSION['registration_success']) && $_SESSION['registration_success']) {
      $this->showRegistrationSuccess = true;
      unset($_SESSION['registration_success']);
    }
  }

  private function checkLoginSuccess() {
    if (isset($_SESSION['login_success']) && $_SESSION['login_success']) {
      echo "<script>
        document.addEventListener('DOMContentLoaded', function () {
          var loginSuccessModal = new bootstrap.Modal(document.getElementById('loginSuccessModal'));
          loginSuccessModal.show();
        });
      </script>";
      unset($_SESSION['login_success']);
    }
  }

  private function renderPageComponents() {
    $this->pageManager->add_navbar();
    $this->pageManager->add_book_window();
    $this->pageManager->add_booking_successful_modal();
  }

  public function getErrors() {
    return $this->errors;
  }

  public function isRegistrationSuccessful() {
    return $this->showRegistrationSuccess;
  }
}

$app = new Application($conn);
$app->handleRequest();

$errors = $app->getErrors();
$showRegistrationSuccess = $app->isRegistrationSuccessful();

?>

<body>
    <div class="service-content" id="services">
        <div class="container">
            <div class="row">
                <div class="col-md-4">
                    <div class="left-text">
                        <h4>More About RoamEasy</h4>
                        <div class="line-dec"></div>
                        <p>RoamEasy is your trusted partner in unforgettable travel experiences. Whether you're dreaming of serene beaches, bustling cities, or hidden natural wonders, we make it easy for you to explore the world. Our team of travel experts works around the clock to design personalized trips that fit your budget, interests, and schedule. <a rel="nofollow" href="https://templatemo.com">website</a> to your friends or collegues. Thank you.</p>
                        <ul>
                            <li>-  Tailored travel packages for every traveler</li>
                            <li>-  Local insights to enhance your adventures</li>
                            <li>-  Transparent pricing with no hidden costs</li>
                            <li>-  24/7 assistance before and during your journey</li>
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
                                <p>We blend timeless travel traditions with modern booking tools, giving you the perfect balance of ease, style, and comfort every step of your journey.</p>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="service-item">
                                <h4>Unique &amp; Creative Ideas</h4>
                                <div class="line-dec"></div>
                                <p>Every journey we plan is one-of-a-kind. From off-the-beaten-path gems to themed tours, our creative approach ensures your trip is truly unforgettable</p>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="service-item">
                                <h4>Effective Team Work</h4>
                                <div class="line-dec"></div>
                                <p>Our passionate travel planners, local partners, and customer care experts collaborate seamlessly to bring your dream trip to life — hassle-free and joyful.</p>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="service-item">
                                <h4>Fast Support 24/7</h4>
                                <div class="line-dec"></div>
                                <p>No matter where you are in the world, our friendly support team is always a message or call away. We’ve got your back, every hour of every day.</p>
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
                                <a href="img/niklas-weiss--2WlTWZLnRc-unsplash.jpg" data-lightbox="image-1"><img src="img/niklas-weiss--2WlTWZLnRc-unsplash.jpg" alt=""></a>
                                <div class="text-content">
                                    <h4>Bali is unmatched</h4>
                                    <span>$820.00</span><br>
                                     <div class="d-flex justify-content-center">
                                      <button class="btn btn-primary mt-2 book-btn" data-bs-toggle="modal" data-bs-target="#bookingModal" data-title="Bali is unmatched">Book this trip</button>
                                    </div>
                                </div>
                               
                              </div>
                        </div>
                        <div class="item">
                            <div class="testimonials-item">
                                <a href="img/ali-maah-zyOeEm4NsPM-unsplash.jpg" data-lightbox="image-1"><img src="img/ali-maah-zyOeEm4NsPM-unsplash.jpg" alt=""></a>
                                <div class="text-content">
                                    <h4>Maldives</h4>
                                    <span>$1000.00</span>
                                       <div class="d-flex justify-content-center">
                                      <button class="btn btn-primary mt-2 book-btn" data-bs-toggle="modal" data-bs-target="#bookingModal" data-title="Bali is unmatched">Book this trip</button>

                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="item">
                            <div class="testimonials-item">
                                <a href="img/kristina-tolmacheva-TfTxZtVnMc0-unsplash.jpg" data-lightbox="image-1"><img src="img/kristina-tolmacheva-TfTxZtVnMc0-unsplash.jpg" alt=""></a>
                                <div class="text-content">
                                    <h4>New Zealand</h4>
                                    <span>$1200.00</span>
                                      <div class="d-flex justify-content-center">
                                      <button class="btn btn-primary mt-2 book-btn" data-bs-toggle="modal" data-bs-target="#bookingModal" data-title="Bali is unmatched">Book this trip</button>

                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="item">
                            <div class="testimonials-item">
                                <a href="img/carlos-torres-lH5RmE-TEHc-unsplash.jpg" data-lightbox="image-1"><img src="img/carlos-torres-lH5RmE-TEHc-unsplash.jpg" alt=""></a>
                                <div class="text-content">
                                    <h4>Italy</h4>
                                    <span>$650.00</span>
                                      <div class="d-flex justify-content-center">
                                      <button class="btn btn-primary mt-2 book-btn" data-bs-toggle="modal" data-bs-target="#bookingModal" data-title="Bali is unmatched">Book this trip</button>

                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="item">
                            <div class="testimonials-item">
                                <a href="img/mike-swigunski-6SgLJg7kM7E-unsplash.jpg" data-lightbox="image-1"><img src="img/mike-swigunski-6SgLJg7kM7E-unsplash.jpg" alt=""></a>
                                <div class="text-content">
                                    <h4>Alpes</h4>
                                    <span>$720.00</span>
                                       <div class="d-flex justify-content-center">
                                      <button class="btn btn-primary mt-2 book-btn" data-bs-toggle="modal" data-bs-target="#bookingModal" data-title="Bali is unmatched">Book this trip</button>

                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="item">
                            <div class="testimonials-item">
                                <a href="img/anastasia-petrova-xu2WYJek5AI-unsplash.jpg" data-lightbox="image-1"><img src="img/anastasia-petrova-xu2WYJek5AI-unsplash.jpg" alt=""></a>
                                <div class="text-content">
                                    <h4>Carpathians</h4>
                                    <span>$850</span>
                                       <div class="d-flex justify-content-center">
                                      <button class="btn btn-primary mt-2 book-btn" data-bs-toggle="modal" data-bs-target="#bookingModal" data-title="Bali is unmatched">Book this trip</button>

                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="item">
                            <div class="testimonials-item">
                                <a href="img/guilherme-stecanella-SaVlzqe9068-unsplash.jpg" data-lightbox="image-1"><img src="img/guilherme-stecanella-SaVlzqe9068-unsplash.jpg" alt=""></a>
                                <div class="text-content">
                                    <h4>New York</h4>
                                    <span>$1300.50</span>
                                       <div class="d-flex justify-content-center">
                                      <button class="btn btn-primary mt-2 book-btn" data-bs-toggle="modal" data-bs-target="#bookingModal" data-title="Bali is unmatched">Book this trip</button>

                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="item">
                            <div class="testimonials-item">
                                <a href="img/aayush-gupta-ljhCEaHYWJ8-unsplash.jpg" data-lightbox="image-1"><img src="img/aayush-gupta-ljhCEaHYWJ8-unsplash.jpg" alt=""></a>
                                <div class="text-content">
                                    <h4>Poland</h4>
                                    <span>$750.75</span>
                                      <div class="d-flex justify-content-center">
                                      <button class="btn btn-primary mt-2 book-btn" data-bs-toggle="modal" data-bs-target="#bookingModal" data-title="Bali is unmatched">Book this trip</button>

                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <?php
    class BookingManager {
      private $pageManager;

      public function __construct($pageManager) {
        $this->pageManager = $pageManager;
      }

      public function handleBookingSession() {
        $this->showBookingSuccessModal();
        $this->showBookingErrorAlert();
        $this->renderBookingComponents();
      }

      private function showBookingSuccessModal() {
        if (isset($_SESSION['booking_success']) && $_SESSION['booking_success']) {
          echo "<script>
            document.addEventListener('DOMContentLoaded', function () {
              var bookingSuccessModal = new bootstrap.Modal(document.getElementById('bookingSuccessModal'));
              bookingSuccessModal.show();
            });
          </script>";
          unset($_SESSION['booking_success']);
        }
      }

      private function showBookingErrorAlert() {
        if (isset($_SESSION['booking_error'])) {
          echo "<script>
            document.addEventListener('DOMContentLoaded', function () {
              alert('" . addslashes($_SESSION['booking_error']) . "');
            });
          </script>";
          unset($_SESSION['booking_error']);
        }
      }

      private function renderBookingComponents() {
        $this->pageManager->add_book_window();
        $this->pageManager->add_booking_successful_modal();
      }
    }

    $bookingManager = new BookingManager($pageManager);
    $bookingManager->handleBookingSession();
    ?>

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
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>


<?php 
  include ('php/footer.php');
?>
