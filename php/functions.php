<?php 

function add_scrips(){
    $page_name  = basename($_SERVER['SCRIPT_NAME'] , '.php');

    if ($page_name == 'index'){
        echo '<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.2/jquery.min.js"></script>';
        echo '<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.2/jquery.min.js"></script>';
        echo '<script>window.jQuery || document.write(\'<script src="js/vendor/jquery-1.11.2.min.js"><\/script>\')</script>';
        echo '<script src="js/vendor/bootstrap.min.js"></script>';
        echo '<script src="js/plugins.js"></script>';
        echo '<script src="js/main.js"></script>';
        echo '<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>';
        echo '<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.2.1/js/bootstrap.min.js"></script>';
        echo '<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>';
        
        echo '
             <script>
            function openCity(cityName) {
                var i;
                var x = document.getElementsByClassName("city");
                for (i = 0; i < x.length; i++) {
                   x[i].style.display = "none";  
                }
                document.getElementById(cityName).style.display = "block";  
            }
        </script>';

        echo '<script>
            $(document).ready(function() {
                // Add smooth scrolling to all links
                $(".fixed-side-navbar a, .primary-button a").on("click", function(event) {
                    if (this.hash !== "") {
                        event.preventDefault();
                        var hash = this.hash;
                        $("html, body").animate({
                            scrollTop: $(hash).offset().top
                        }, 800, function() {
                            window.location.hash = hash;
                        });
                    }
                });
            });
        </script>';

        echo "
            <script>
            document.addEventListener('DOMContentLoaded', function () {
                var bookingModal = document.getElementById('bookingModal');
                var titleInput = bookingModal.querySelector('#title');

                bookingModal.addEventListener('show.bs.modal', function (event) {
                var button = event.relatedTarget;
                var title = button.getAttribute('data-title');
                titleInput.value = title;
                });
            });
            </script>";

    }
}

function add_styles (){

    $page_name  = basename($_SERVER['SCRIPT_NAME'] , '.php');

    if ($page_name == 'index'){
        echo '<link rel="stylesheet" href="css/bootstrap.min.css">';
        echo '<link rel="stylesheet" href="css/bootstrap.min.css">';
        echo ' <link rel="stylesheet" href="css/fontAwesome.css">';
        echo ' <link rel="stylesheet" href="css/hero-slider.css">';
        echo '<link rel="stylesheet" href="css/templatemo-main.css">';
        echo ' <link rel="stylesheet" href="css/owl-carousel.css">';
        echo '<link href="https://fonts.googleapis.com/css?family=Open+Sans:300,400,600,700,800" rel="stylesheet">';
    }
}

function add_navbar(){
    echo ' 
    <div class="fixed-side-navbar">
        <ul class="nav flex-column">
            <li class="nav-item"><a class="nav-link" href="#home"><span>Intro</span></a></li>
            <li class="nav-item"><a class="nav-link" href="#services"><span>Services</span></a></li>
            <li class="nav-item"><a class="nav-link" href="#portfolio"><span>Portfolio</span></a></li>
            <li class="nav-item"><a class="nav-link" href="#our-story"><span>Our Story</span></a></li>
            <li class="nav-item"><a class="nav-link" href="#contact-us"><span>Contact Us</span></a></li>
            <li class="nav-item"><a class="nav-link" href="#" data-bs-toggle="modal" data-bs-target="#adminLoginModal" name = "log_in"><span>Log in</span></a></li>
            <li class="nav-item">
                <a class="nav-link" href="/project/php/crud.php" name="crud_menu">
                    <span>Crud_menu</span>
                </a>
            </li>

        </ul>
    </div>

    <div class="parallax-content baner-content" id="home">
        <div class="container">
            <div class="first-content">
                <h1>RoamEasy</h1>
                <span><em>Affordable group tours for young travelers</em></span>
                <div class="primary-button">
                    <a href="#services">Discover More</a>
                </div>
            </div>
        </div>
    </div>';
}

?>


<?php
 
function add_book_window(){
    echo '
    <div class="modal fade" id="bookingModal" tabindex="-1" aria-labelledby="bookingModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <form action="/project/php/booking.php" method="post">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="bookingModalLabel">Book This Trip</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <div class="mb-3">
                            <label for="title" class="form-label">Vacation Title</label>
                            <input type="text" class="form-control" id="title" name="title" required>
                        </div>
                        <div class="mb-3">
                            <label for="startDate" class="form-label">Start Date</label>
                            <input type="date" class="form-control" id="startDate" name="start_date" required>
                        </div>
                        <div class="mb-3">
                            <label for="endDate" class="form-label">Return Date</label>
                            <input type="date" class="form-control" id="endDate" name="end_date" required>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="submit" class="btn btn-success">Submit Booking</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
    ';
}

function add_booking_successful_modal(){
    echo '
        <div class="modal fade" id="bookingSuccessModal" tabindex="-1" aria-labelledby="bookingSuccessModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="bookingSuccessModalLabel">Booking Successful</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                Thank you for booking your trip!
            </div>
            </div>
        </div>
        </div>
    ';
}

?>