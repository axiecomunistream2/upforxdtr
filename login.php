<?php 
session_start();
if (isset($_SESSION['username'])) {
    header('Location: index.php');
}

require_once(__DIR__ . '/include/Core.php'); 
require_once(__DIR__ . '/include/Config.php');
require_once(__DIR__ . '/include/PDOQuery.php');
?>
<!DOCTYPE html>
<html><head>
<?php echo MinifyTemplate(__DIR__ . '/template/header.php'); ?>
</head><body>
<?php echo MinifyTemplate(__DIR__ . '/template/navbar.php'); ?>
    <main class="page contact-page">
        <section data-aos="fade-up" class="portfolio-block contact bg-light">
            <div class="container">
                <div class="heading">
                    <h2 data-bs-hover-animate="pulse">PLEASE LOGIN!</h2>
                </div>
                <form data-aos="flip-left" data-aos-duration="800" method="post" action="" name="login">
                    <div class="form-group"><label for="username">USERNAME</label><input class="form-control item" type="text" id="username" name="username" required></div>
                    <div class="form-group"><label for="password">PASSWORD</label><input class="form-control item" type="password" id="password" name="password" required></div>
                    <div class="form-group"><button class="btn btn-primary btn-block btn-lg justify-content-center" type="submit">LOGIN</button></div>
                </form>
            </div>
        </section>
    </main>
<?php echo MinifyTemplate(__DIR__ . '/template/footer.php'); ?>
</body>
</html>