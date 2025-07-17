<?php get_header(); ?>

<style>
  .not-found-wrapper {
    text-align: center;
    padding: 80px 20px;
    font-family: Arial, sans-serif;
  }
  .not-found-wrapper h1 {
    font-size: 120px;
    margin: 0;
    color: #f7941d;
  }
  .not-found-wrapper h2 {
    font-size: 32px;
    margin: 10px 0;
    color: #333;
  }
  .not-found-wrapper p {
    font-size: 18px;
    color: #666;
  }
  .not-found-wrapper a {
    display: inline-block;
    margin-top: 20px;
    padding: 12px 30px;
    background-color: #f7941d;
    color: #fff;
    text-decoration: none;
    border-radius: 5px;
    font-weight: bold;
  }
  .not-found-wrapper a:hover {
    background-color: #e08300;
  }
</style>

<div class="not-found-wrapper">
  <h1>404</h1>
  <h2>Oops! Page Not Found</h2>
  <p>The page you are looking for doesn’t exist or has been moved.</p>
  <a href="<?php echo home_url(); ?>">Go to Homepage</a>
</div>

<?php get_footer(); ?>
