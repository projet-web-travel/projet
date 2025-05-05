<?php
require_once '../../config.php';
require_once '../../controller/UserController.php';


$db = new Database();
$conn = $db->getConnection();
$userController = new UserController($conn);

$message = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['action']) && $_POST['action'] === 'register') {
        // Validate form data
        if ($_POST['mot_de_passe'] !== $_POST['confirm_password']) {
            $message = "Les mots de passe ne correspondent pas !";
        } else {
            $result = $userController->register($_POST);
            $message = $result;
        }
    } elseif (isset($_POST['action']) && $_POST['action'] === 'login') {
        $result = $userController->login($_POST);
        $message = $result;
    }
}

// Fetch all customers
$customers = $userController->getAllCustomers();
?>

<!doctype html>
<html class="no-js" lang="en">
<head>
    <!-- META DATA -->
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <!--font-family-->
    <link href="https://fonts.googleapis.com/css?family=Rufina:400,700" rel="stylesheet" />
    <link href="https://fonts.googleapis.com/css?family=Poppins:100,200,300,400,500,600,700,800,900" rel="stylesheet" />
    <!-- TITLE OF SITE -->
    <title>TRIPPED - Subscription</title>
    <!-- favicon img -->
    <link rel="shortcut icon" type="image/icon" href="../assets/logo/favicon.png" />
    <!--font-awesome.min.css-->
    <link rel="stylesheet" href="../assets/css/font-awesome.min.css" />
    <!--animate.css-->
    <link rel="stylesheet" href="../assets/css/animate.css" />
    <!--hover.css-->
    <link rel="stylesheet" href="../assets/css/hover-min.css">
    <!--datepicker.css-->
    <link rel="stylesheet" href="../assets/css/datepicker.css">
    <!--owl.carousel.css-->
    <link rel="stylesheet" href="../assets/css/owl.carousel.min.css">
    <link rel="stylesheet" href="../assets/css/owl.theme.default.min.css" />
    <!-- range css-->
    <link rel="stylesheet" href="../assets/css/jquery-ui.min.css" />
    <!--bootstrap.min.css-->
    <link rel="stylesheet" href="../assets/css/bootstrap.min.css" />
    <!-- bootsnav -->
    <link rel="stylesheet" href="../assets/css/bootsnav.css" />
    <!--style.css-->
    <link rel="stylesheet" href="../assets/css/style.css" />
    <!--responsive.css-->
    <link rel="stylesheet" href="../assets/css/responsive.css" />
    <link rel="stylesheet" href="../assets/css/sts.css" />

</head>
<body>
    <!-- main-menu Start -->
    <!-- Removed the header section -->
    <!-- main-menu End -->

    <!-- Subscription Area Start -->
    <section class="subscription-bg">
        <div class="container">
            <div class="subscription-container">
            <?php if (!empty($message)): ?>
    <div class="message <?php echo strpos($message, 'erreur') !== false ? 'error' : 'success'; ?>">
        <?php echo htmlspecialchars($message); ?>
    </div>
<?php endif; ?>

<div class="subscription-header">
    <h2>Join TRIPPED Today</h2>
    <p>Create an account or login to access exclusive travel deals and manage your bookings.</p>
</div>

<div class="subscription-tabs">
    <div class="tab-btn active" id="login-tab">Login</div>
    <div class="tab-btn" id="register-tab">Register</div>
</div>
<div id="login-form" class="active">
    <form method="POST" action="../../controller/login.php" id="loginForm">
        <input type="hidden" name="action" value="login">
        <div class="form-group">
            <label for="email">Email Address</label>
            <input type="email" class="form-control" id="email" placeholder="Email Address" name="email" >
        </div>
        <div class="form-group">
            <label for="mot_de_passe">Password</label>
            <input type="password" class="form-control" id="mot_de_passe" placeholder="Password" name="mot_de_passe" >
        </div>
        <div class="form-group">
            <button type="submit" class="submit-btn">Login</button>
        </div>
        <div class="forgot-password">
            <a href="resetpassword.php">Forgot Password?</a>
        </div>
    </form>
</div>
<div id="register-form">
    <form method="POST" action="../../controller/register.php" id="registerForm">
        <input type="hidden" name="action" value="register">
        <div class="form-group">
            <label for="nom">Nom</label>
            <input type="text" class="form-control" id="nom" placeholder="Nom" name="nom">
        </div>
        <div class="form-group">
            <label for="prenom">Prénom</label>
            <input type="text" class="form-control" id="prenom" placeholder="Prénom" name="prenom">
        </div>
        <div class="form-group">
            <label for="email">Email</label>
            <input type="email" class="form-control" id="email" placeholder="Email" name="email">
        </div>
        <div class="form-group">
            <label for="telephone">Téléphone</label>
            <input type="tel" class="form-control" id="telephone" placeholder="Téléphone" name="telephone">
        </div>
        <div class="form-group">
            <label for="date_naissance">Date de naissance</label>
            <input type="date" class="form-control" id="date_naissance" placeholder="Date de naissance" name="date_naissance">
        </div>
        <div class="form-group">
            <label for="mot_de_passe">Mot de passe</label>
            <input type="password" class="form-control" id="mot_de_passe" placeholder="Mot de passe" name="mot_de_passe">
        </div>
        <div class="form-group">
            <label for="confirm_password">Confirmer mot de passe</label>
            <input type="password" class="form-control" id="confirm_password" placeholder="Confirmer mot de passe" name="confirm_password">
        </div>
        <div class="form-group">
            <button type="submit" class="submit-btn">Créer un compte</button>
        </div>
    </form>
</div>
            </div>
        </div>
    </section>
    <!-- Subscription Area End -->

    <!-- footer-copyright start -->
    <footer class="footer-copyright">
        <div class="container">
            <div class="footer-content">
                <div class="row">
                    <div class="col-sm-3">
                        <div class="single-footer-item">
                            <div class="footer-logo">
                                <a href="index.html">
                                    TRIP<span>PED</span>
                                </a>
                                <p>
                                    best travel agency
                                </p>
                            </div>
                        </div><!--/.single-footer-item-->
                    </div><!--/.col-->
                    <div class="col-sm-3">
                        <div class="single-footer-item">
                            <h2>link</h2>
                            <div class="single-footer-txt">
                                <p><a href="index.html">home</a></p>
                                <p><a href="index.html#gallery">Transport</a></p>
                                <p><a href="index.html#gallery">Tours</a></p>
                                <p><a href="index.html#spo">Accommodation</a></p>
                                <p><a href="index.html#blog">Blog</a></p>
                                <p><a href="subscription.html">subscription</a></p>
                            </div><!--/.single-footer-txt-->
                        </div><!--/.single-footer-item-->
                    </div><!--/.col-->
                    <div class="col-sm-3">
                        <div class="single-footer-item">
                            <h2>popular destination</h2>
                            <div class="single-footer-txt">
                                <p><a href="#">china</a></p>
                                <p><a href="#">venezuela</a></p>
                                <p><a href="#">brazil</a></p>
                                <p><a href="#">australia</a></p>
                                <p><a href="#">london</a></p>
                            </div><!--/.single-footer-txt-->
                        </div><!--/.single-footer-item-->
                    </div><!--/.col-->
                    <div class="col-sm-3">
                        <div class="single-footer-item text-center">
                            <h2 class="text-left">contacts</h2>
                            <div class="single-footer-txt text-left">
                                <p>+216 71 234 567 </p>
                                <p class="foot-email"><a href="#"> contact@tripped.com </a></p>
                                <p>North Warnner Park 336/A</p>
                                <p>Newyork, USA</p>
                            </div><!--/.single-footer-txt-->
                        </div><!--/.single-footer-item-->
                    </div><!--/.col-->
                </div><!--/.row-->
            </div><!--/.footer-content-->
            <hr>
            <div class="foot-icons ">
                <ul class="footer-social-links list-inline list-unstyled">
                    <li><a href="#" target="_blank" class="foot-icon-bg-1"><i class="fa fa-facebook"></i></a></li>
                    <li><a href="#" target="_blank" class="foot-icon-bg-2"><i class="fa fa-twitter"></i></a></li>
                    <li><a href="#" target="_blank" class="foot-icon-bg-3"><i class="fa fa-instagram"></i></a></li>
                </ul>
                <p>&copy; 2017 <a href="https://www.themesine.com">ThemeSINE</a>. All Right Reserved</p>
            </div><!--/.foot-icons-->
            <div id="scroll-Top">
                <i class="fa fa-angle-double-up return-to-top" id="scroll-top" data-toggle="tooltip" data-placement="top"
                    title="" data-original-title="Back to Top" aria-hidden="true"></i>
            </div><!--/.scroll-Top-->
        </div><!-- /.container-->
    </footer><!-- /.footer-copyright-->
    <!-- footer-copyright end -->
    <script src="../assets/js/jquery.js"></script>
    <!-- Include all compiled plugins (below), or include individual files as needed -->
    <!--modernizr.min.js-->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/modernizr/2.8.3/modernizr.min.js"></script>
    <!--bootstrap.min.js-->
    <script src="../assets/js/bootstrap.min.js"></script>
    <!-- bootsnav js -->
    <script src="../assets/js/bootsnav.js"></script>
    <script>
        // Tab functionality
        $(document).ready(function() {
            // Initial setup (default state)
            $('#login-tab').addClass('active');
            $('#register-tab').removeClass('active');
            $('#login-form').addClass('active');
            $('#register-form').removeClass('active');
            // Login tab click
            $('#login-tab').click(function() {
                $('#login-tab').addClass('active');
                $('#register-tab').removeClass('active');
                $('#login-form').addClass('active');
                $('#register-form').removeClass('active');
            });
            // Register tab click
            $('#register-tab').click(function() {
                $('#register-tab').addClass('active');
                $('#login-tab').removeClass('active');
                $('#register-form').addClass('active');
                $('#login-form').removeClass('active');
            });
        });
        $(document).ready(function() {
    $('#loginForm').submit(function(e) {
        e.preventDefault(); // Prevent default form submission

        // Clear previous error messages
        $('.message').remove();
        $('.is-invalid').removeClass('is-invalid');
        $('.invalid-feedback').remove();

        // Send an AJAX request
        $.ajax({
    url: $(this).attr('action'),
    method: 'POST',
    data: $(this).serialize(),
    dataType: 'json',
    success: function(response) {
        if (response.success) {
            console.log("Data received:", response);
            window.location.href = "sectionclient.php";

            // Process the data here
        } else {
            console.error("Error:", response.errors);
        }
    },
    error: function(jqXHR, textStatus, errorThrown) {
        console.error("AJAX Error:", textStatus, errorThrown);
        try {
            const response = JSON.parse(jqXHR.responseText);
            console.error("Server Response:", response);
        } catch (e) {
            console.error("Invalid JSON Response:", jqXHR.responseText);
        }
    }
});
    });
});
$(document).ready(function() {
    $('#registerForm').submit(function(e) {
        e.preventDefault(); // Prevent default form submission

        // Clear previous error messages and styles
        $('.message').remove();
        $('.is-invalid').removeClass('is-invalid');
        $('.invalid-feedback').remove();

        // Send an AJAX request to the backend
        $.ajax({
            url: $(this).attr('action'), // URL from the form's action attribute
            method: 'POST',
            data: $(this).serialize(), // Serialize form data
            dataType: 'json', // Expect JSON response
            success: function(response) {
                if (response.success) {
                    // Redirect to the login page on successful registration
                    window.location.href = "clients.php/login";
                } else {
                    // Display general error message
                    if (response.errors.general) {
                        $('#registerForm').before('<div class="message error">' + response.errors.general + '</div>');
                    }

                    // Display field-specific error messages
                    Object.keys(response.errors).forEach(function(field) {
                        if (field !== 'general') {
                            $('#' + field).addClass('is-invalid'); // Highlight invalid fields
                            $('#' + field).after('<div class="invalid-feedback">' + response.errors[field] + '</div>');
                        }
                    });
                }
            },
            error: function() {
                // Handle unexpected errors
                $('#registerForm').before('<div class="message error">Une erreur inattendue est survenue.</div>');
            }
        });
    });
});
    </script>
    <style>
        .message {
    padding: 10px;
    border-radius: 4px;
    margin-bottom: 20px;
    text-align: center;
}

.error {
    background-color: #f8d7da;
    color: #721c24;
    border: 1px solid #f5c6cb;
}

.invalid-feedback {
    display: block;
    color: #dc3545;
    margin-top: 5px;
}

.is-invalid {
    border-color: #dc3545;
}
    </style>
</body>
</html>