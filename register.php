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
        <section data-aos="fade-up" class="portfolio-block contact">
            <div class="container">
                <div class="heading">
                    <h2 data-bs-hover-animate="pulse">REGISTER A NEW ACCOUNT</h2>
                </div>
                <form data-aos="flip-left" data-aos-duration="800" method="post" action="" name="register">
                    <div class="form-group"><label for="name">USERNAME</label><input class="form-control item" type="text" name="username" required></div>
                    <div class="form-group"><label for="email">EMAIL</label><input class="form-control item" type="email" name="email" required></div>
                    <div class="form-group"><label for="subject">PASSWORD</label><input class="form-control item" type="password" name="password" required></div>
                    <div class="form-group"><label for="subject">CONFIRM PASSWORD</label><input class="form-control item" type="password" name="password_confirm" required></div>
                    <div class="form-group"><button class="btn btn-primary btn-block btn-lg" type="submit">REGISTER</button></div>
                </form>
            </div>
        </section>
    </main>
<?php echo MinifyTemplate(__DIR__ . '/template/footer.php'); ?>
</body>
</html>