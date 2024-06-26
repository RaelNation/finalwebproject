    <footer>
      <div class="footer-content">
        <div class="logo">
          <img src="./image/auth-picture/logo.png" alt="" />
        </div>
        <div class="categories">
          <h4>Categories</h4>
          <div class="columns">
            <ul>
              <?php foreach ($categories as $category) : ?>
                <li><a href="categories-detail.php?id=<?= $category['id'] ?>"><?= $category['name'] ?></a></li>
              <?php endforeach; ?>
            </ul>
          </div>
        </div>
      </div>
      <div class="footer-bottom">
        <div class="bottom-area">
          <div class="copyright">
            <p>2024 Copyright All Rights Reserved</p>
          </div>
          <div class="about">
            <a href="./about-us.php">
              <p>About Us</p>
            </a>
            <i class="fa-solid fa-chevron-up"></i>
          </div>
        </div>
      </div>
    </footer>

  </body>
  <!-- <script src="script.js"></script> -->
  <script src="./script/script.js?v=<?php echo time(); ?>"></script>
    <script>

    </script>



</html>
