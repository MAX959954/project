<?php 
  require_once 'php/functions.php';
?>

<footer>
        <div class="container">
            <div class="row">
                <div class="col-md-12">
                    <div class="primary-button">
                        <a href="#home">Back To Top</a>
                    </div>
                    <ul>
                        <li><a href="#"><i class="fa fa-facebook"></i></a></li>
                        <li><a href="#"><i class="fa fa-twitter"></i></a></li>
                        <li><a href="#"><i class="fa fa-linkedin"></i></a></li>
                        <li><a href="#"><i class="fa fa-google"></i></a></li>
                        <li><a href="#"><i class="fa fa-dribbble"></i></a></li>
                    </ul>
                    <p>Copyright &copy; 2019 Company Name 
            
            		- Design: <a rel="nofollow noopener" href="https://templatemo.com"><em>TemplateMo</em></a></p>
                </div>
            </div>
        </div>
    </footer>

   <?php
      $pageManager = new PageManager();
      $pageManager->add_scripts();
   ?>

</body>
</html>