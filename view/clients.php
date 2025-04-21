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
    <link rel="shortcut icon" type="image/icon" href="assets/logo/favicon.png" />

    <!--font-awesome.min.css-->
    <link rel="stylesheet" href="assets/css/font-awesome.min.css" />

    <!--animate.css-->
    <link rel="stylesheet" href="assets/css/animate.css" />

    <!--hover.css-->
    <link rel="stylesheet" href="assets/css/hover-min.css">

    <!--datepicker.css-->
    <link rel="stylesheet" href="assets/css/datepicker.css">

    <!--owl.carousel.css-->
    <link rel="stylesheet" href="assets/css/owl.carousel.min.css">
    <link rel="stylesheet" href="assets/css/owl.theme.default.min.css" />

    <!-- range css-->
    <link rel="stylesheet" href="assets/css/jquery-ui.min.css" />

    <!--bootstrap.min.css-->
    <link rel="stylesheet" href="assets/css/bootstrap.min.css" />

    <!-- bootsnav -->
    <link rel="stylesheet" href="assets/css/bootsnav.css" />

    <!--style.css-->
    <link rel="stylesheet" href="assets/css/style.css" />

    <!--responsive.css-->
    <link rel="stylesheet" href="assets/css/responsive.css" />

    <style>
        .subscription-container {
            background: rgba(255, 255, 255, 0.9);
            padding: 30px;
            border-radius: 5px;
            box-shadow: 0 0 20px rgba(0, 0, 0, 0.1);
            max-width: 500px;
            margin: 40px auto;
        }

        .subscription-tabs {
            display: flex;
            margin-bottom: 30px;
        }

        .tab-btn {
            flex: 1;
            text-align: center;
            padding: 15px;
            background: #f8f8f8;
            cursor: pointer;
            font-weight: 600;
            border-radius: 5px 5px 0 0;
        }

        .tab-btn.active {
            background: #00d8ff;
            color: white;
        }

        .form-group {
            margin-bottom: 20px;
        }

        .form-control {
            height: 45px;
            border-radius: 3px;
            font-size: 16px;
        }

        .submit-btn {
            background: #00d8ff;
            color: white;
            border: none;
            padding: 12px 25px;
            border-radius: 3px;
            font-weight: 600;
            cursor: pointer;
            width: 100%;
            transition: all 0.3s ease;
        }

        .submit-btn:hover {
            background: #00b8e0;
        }

        .forgot-password {
            text-align: right;
            margin-top: 10px;
        }

        .forgot-password a {
            color: #00d8ff;
            text-decoration: none;
        }

        .subscription-header {
            text-align: center;
            margin-bottom: 30px;
        }

        .subscription-header h2 {
            color: #333;
            font-size: 28px;
            margin-bottom: 10px;
        }

        .subscription-header p {
            color: #777;
            font-size: 16px;
        }

        .subscription-bg {
            background: linear-gradient(rgba(0, 0, 0, 0.5), rgba(0, 0, 0, 0.5)), url('assets/images/home/banner-bg.jpg');
            background-size: cover;
            background-position: center;
            min-height: 100vh;
            padding: 50px 0;
        }

        #login-form, #register-form {
            display: none;
        }

        #login-form.active, #register-form.active {
            display: block;
        }
    </style>
</head>

<body>
    <!-- main-menu Start -->
    <header class="top-area">
        <div class="header-area">
            <div class="container">
                <div class="row">
                    <div class="col-sm-2">
                        <div class="logo">
                            <a href="index.html">
                                TRIP<span>PED</span>
                            </a>
                        </div><!-- /.logo-->
                    </div><!-- /.col-->
                    <div class="col-sm-10">
                        <div class="main-menu">
                            <!-- Brand and toggle get grouped for better mobile display -->
                            <div class="navbar-header">
                                <button type="button" class="navbar-toggle" data-toggle="collapse"
                                    data-target=".navbar-collapse">
                                    <i class="fa fa-bars"></i>
                                </button><!-- / button-->
                            </div><!-- /.navbar-header-->
                            <div class="collapse navbar-collapse">
                                <ul class="nav navbar-nav navbar-right">
                                    <li class="smooth-menu"><a href="index.html">home</a></li>
                                    <li class="smooth-menu"><a href="index.html#gallery">Transport</a></li>
                                    <li class="smooth-menu"><a href="index.html#gallery">Tours</a></li>
                                    <li class="smooth-menu"><a href="index.html#spo">Accommodation</a></li>
                                    <li class="smooth-menu"><a href="index.html#blog">blog</a></li>
                                    <li class="smooth-menu"><a href="clients.html">subscription</a></li>
                                    <li>
                                        <button class="book-btn">book now
                                        </button>
                                    </li><!--/.project-btn-->
                                </ul>
                            </div><!-- /.navbar-collapse -->
                        </div><!-- /.main-menu-->
                    </div><!-- /.col-->
                </div><!-- /.row -->
                <div class="home-border"></div><!-- /.home-border-->
            </div><!-- /.container-->
        </div><!-- /.header-area -->
    </header><!-- /.top-area-->
    <!-- main-menu End -->

    <!-- Subscription Area Start -->
    <section class="subscription-bg">
        <div class="container">
            <div class="subscription-container">
                <div class="subscription-header">
                    <h2>Join TRIPPED Today</h2>
                    <p>Create an account or login to access exclusive travel deals and manage your bookings.</p>
                </div>

                <div class="subscription-tabs">
                    <div class="tab-btn active" id="login-tab">Login</div>
                    <div class="tab-btn" id="register-tab">Register</div>
                </div>

                <div id="login-form" class="active">
                    <form>
                        <div class="form-group">
                            <input type="email" class="form-control" placeholder="Email Address" name="email" required>
                        </div>
                        <div class="form-group">
                            <input type="password" class="form-control" placeholder="Password" name="password" required>
                        </div>
                        <div class="form-group">
                            <button type="submit" class="submit-btn">Login</button>
                        </div>
                        <div class="forgot-password">
                            <a href="#">Forgot Password?</a>
                        </div>
                    </form>
                </div>

                <div id="register-form">
                    <form>
                        
                    <div>
    <form id="register-form">
        <div class="form-group">
            <input type="text" class="form-control" placeholder="Nom" name="nom" required>
        </div>
        <div class="form-group">
            <input type="text" class="form-control" placeholder="Prénom" name="prenom" required>
        </div>
        <div class="form-group">
            <input type="email" class="form-control" placeholder="Email" name="email" required>
        </div>
        <div class="form-group">
            <input type="tel" class="form-control" placeholder="Téléphone" name="telephone" required>
        </div>
        <div class="form-group">
            <input type="date" class="form-control" placeholder="Date de naissance" name="date_naissance" required>
        </div>
        <div class="form-group">
            <input type="password" class="form-control" placeholder="Mot de passe" name="mot_de_passe" required>
        </div>
        <div class="form-group">
        
            <input type="password" class="form-control" placeholder="Confirmer mot de passe" name="confirm_password" required>
        </div>
        <div class="form-group">
            <button type="submit" class="submit-btn">Créer un compte</button>
        </div>
    </form>
    <div id="resultat"></div>


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

    <script src="assets/js/jquery.js"></script>
    <!-- Include all compiled plugins (below), or include individual files as needed -->

    <!--modernizr.min.js-->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/modernizr/2.8.3/modernizr.min.js"></script>

    <!--bootstrap.min.js-->
    <script src="assets/js/bootstrap.min.js"></script>

    <!-- bootsnav js -->
    <script src="assets/js/bootsnav.js"></script>

    <script>
        // Tab functionality
        $(document).ready(function() {
            // Initial setup (default state)
            $('#login-form').addClass('active');
            $('#register-form').removeClass('active');
            $('#login-tab').addClass('active');
            $('#register-tab').removeClass('active');
            
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
    </script>
    <script>
    $(document).ready(function () {
        // Gérer l'inscription
        $('#register-form form').on('submit', function (e) {
            e.preventDefault();

            const data = {
            nom: $('input[name="nom"]').val(),
            prenom: $('input[name="prenom"]').val(),
            email: $('input[name="email"]').val(),
            telephone: $('input[name="telephone"]').val(),
            date_naissance: $('input[name="date_naissance"]').val(),
            pass: $('input[name="mot_de_passe"]').val(),
            confirm: $('input[name="confirm_password"]').val()
        };

            if (data.pass !== data.confirm) {
                alert("Les mots de passe ne correspondent pas !");
                return;
            }

            // Envoi vers un fichier PHP
            $.post("backoffice/register.php", data, function (res) {
                alert(res);
            }).fail(function () {
                alert("Erreur lors de l'inscription.");
            });
        });

        // Gérer la connexion
        $('#login-form form').on('submit', function (e) {
            e.preventDefault();

            const email = $('#login-form input[name="email"]').val();
            const pass = $('#login-form input[name="password"]').val();

            $.post("backoffice/login.php", { email, pass }, function (res) {
                alert(res);
            }).fail(function () {
                alert("Erreur lors de la connexion.");
            });
        });
    });
</script>
</body>

</html>