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
            <li class="nav-item"><a class="nav-link" href="#" data-bs-toggle="modal" data-bs-target="#adminLoginModal"><span>Admin Login</span></a></li>
        </ul>
    </div>

    <div class="parallax-content baner-content" id="home">
        <div class="container">
            <div class="first-content">
                <h1>Vanilla</h1>
                <span><em>Bootstrap</em> v4.2.1 Theme</span>
                <div class="primary-button">
                    <a href="#services">Discover More</a>
                </div>
            </div>
        </div>
    </div>';
}



function form_submission(){
    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        $name = htmlspecialchars($_POST['name'] ?? '');
        $password = htmlspecialchars($_POST['password'] ?? '');
        $message = htmlspecialchars($_POST['message'] ?? '');

        if  (!empty($name) && !empty($password) && !empty($message)){
            echo "
                <script>
                    window.onload = function() {
                        $('#successModal').modal('show');
                    }
                </script>
            ";
        } else {
            echo "<div class='alert alert-danger'>Please fill in all the fields.</div>";
        }
    }
}




/*
function form_submission(){
    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        $name = htmlspecialchars($_POST['name'] ?? '');
         //  converts special HTML characters to safe codes (like < becomes &lt;) 
         //to prevent XSS attacks (cross-site scripting)
        $password = htmlspecialchars($_POST['password'] ?? '');
        $message = htmlspecialchars($_POST['message'] ?? '');

        if  (!empty($name) && !empty($password) && !empty($message)){
            echo "<div class = 'alert alert-success'>Thanks you , $name ! " ;
        }else {
            echo "<div class = 'alert alert-danger'>Please fill in all the fields.</div>";
        }
    }
}
*/


?>