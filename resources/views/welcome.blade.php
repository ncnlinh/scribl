<!DOCTYPE HTML>
<html>
    <head>
        <meta charset="utf-8">
        <title>Scribbl!</title>
        <link href="//fonts.googleapis.com/css?family=Lato:100" rel="stylesheet" type="text/css">
        <link rel="stylesheet" href="/css/all.css">

    <!-- Custom Fonts -->
    <link href="http://fonts.googleapis.com/css?family=Lato:300,400,700,300italic,400italic,700italic" rel="stylesheet" type="text/css">

        <style>
            html, body {
                height: 100%;
            }

            .fb-login {
                background-color:#3b579d;
                border-radius:5px;
                cursor:pointer;
                color:#ffffff;
            }
            .fb-login:hover {
                background-color:#526fb5;
                outline: none;
            }
            .fb-login:active {
                position:relative;
                top:1px;
                outline: none;
            }

/*            body {
                margin: 0;
                padding: 0;
                width: 100%;
                display: table;
            }

            .container {
                text-align: center;
                display: table-cell;
                vertical-align: middle;
            }

            .content {
                text-align: center;
                display: inline-block;
                font-family: 'Lato';
            }

            .title {
                font-size: 96px;
                font-weight: 100;
            }
            .body {
                font-size: 16px;
                font-weight: 100;
            }*/
        </style>
    </head>
    <body>
        <!-- Navigation -->
        <nav class="navbar navbar-default navbar-fixed-top topnav" role="navigation">
            <div class="container topnav">
                <!-- Brand and toggle get grouped for better mobile display -->
                <div class="navbar-header">
                    <button type="button" class="navbar-toggle" data-toggle="collapse" data-target="#bs-example-navbar-collapse-1">
                        <span class="sr-only">Toggle navigation</span>
                        <span class="icon-bar"></span>
                        <span class="icon-bar"></span>
                        <span class="icon-bar"></span>
                    </button>
                    <a class="navbar-brand topnav" href="#">Scribl</a>
                </div>
                <!-- Collect the nav links, forms, and other content for toggling -->
                <div class="collapse navbar-collapse" id="bs-example-navbar-collapse-1">
                    <ul class="nav navbar-nav navbar-right">
                        <li>
                            <a href="#about">About</a>
                        </li>
                        <li>
                            <a href="#services">Services</a>
                        </li>
                        <li>
                            <a href="#contact">Contact</a>
                        </li>
                    </ul>
                </div>
                <!-- /.navbar-collapse -->
            </div>
            <!-- /.container -->
        </nav>


        <!-- Header -->
        <a name="about"></a>
        <div class="intro-header">
            <div class="container">
                <div class="row">
                    <div class="col-lg-12">
                        <div class="intro-message">
                            <h1>Scribl</h1>
                            <h3>Letting you draw on people's walls</h3>
                            <hr class="intro-divider">
                            <a href={{url('login')}}>
                                <button type="button" class="fb-login">
                                    <img src="../images/FB-f-Logo__blue_29.png" /> Login with Facebook
                                </button>
                            </a>
                        </div>
                    </div>
                </div>

            </div>
            <!-- /.container -->

        </div>
        <!-- /.intro-header -->

        <!-- Page Content -->

        <a name="services"></a>
        <div class="content-section-a">

            <div class="container">
                <div class="row">
                    <div class="col-lg-5 col-sm-6">
                        <hr class="section-heading-spacer">
                        <div class="clearfix"></div>
                        <h2 class="section-heading">Walls are <em>meant</em> to be <br>drawn on!</h2>
                        <p class="lead">
                            Remember the days when you put up scribbles of your family on the refrigerator,
                            or the times your friends drew faces on your photos?
                            Scribl allows you to create quick drawings and post them to your friends' walls, so you can relive those days!
                        </p>
                    </div>
                    <div class="col-lg-5 col-lg-offset-2 col-sm-6">
                        <img class="img-responsive" src="images/ipad.png" alt="">
                    </div>
                </div>

            </div>
            <!-- /.container -->

        </div>
        <!-- /.content-section-a -->

        <div class="content-section-b">

            <div class="container">

                <div class="row">
                    <div class="col-lg-5 col-lg-offset-1 col-sm-push-6  col-sm-6">
                        <hr class="section-heading-spacer">
                        <div class="clearfix"></div>
                        <h2 class="section-heading">Decided not to post it? <br>Keep it!</h2>
                        <p class="lead">
                            We all have good and bad days. 
                            If you don't feel like sharing a particularly bad scribl, 
                            you can always save it on our server and come back to improve it another day. 
                            Afraid of the Cloud? You can also choose to download it, and hide it away somewhere we can't find.
                            We promise not to keep anything you choose to delete!
                        </p>
                    </div>
                    <div class="col-lg-5 col-sm-pull-6  col-sm-6">
                        <img class="img-responsive" src="images/dog.png" alt="">
                    </div>
                </div>

            </div>
            <!-- /.container -->

        </div>
        <!-- /.content-section-b -->

        <div class="content-section-a">

            <div class="container">

                <div class="row">
                    <div class="col-lg-5 col-sm-6">
                        <hr class="section-heading-spacer">
                        <div class="clearfix"></div>
                        <h2 class="section-heading">Bored of just drawing? <br>There's more!</h2>
                        <p class="lead">
                            Our system records every action you make on the drawing board,
                            and at the end compiles these into an animated GIF.
                            You can also choose to share a particular drawing,
                            and allow your friends to continue it.
                            We're excited to see what you will do!
                        </p>
                    </div>
                    <div class="col-lg-5 col-lg-offset-2 col-sm-6">
                        <img class="img-responsive" src="images/phones.png" alt="">
                    </div>
                </div>

            </div>
            <!-- /.container -->

        </div>
        <!-- /.content-section-a -->

        <a name="contact"></a>
        <div class="banner">

            <div class="container">

                <div class="row">
                    <div class="col-lg-12" style="text-align: center">
                        <h2>Start Scribling now!</h2>
                        <br>
                        <a href={{url('login')}}>
                            <button  type="button" class="fb-login">
                                <img src="../images/FB-f-Logo__blue_29.png" /> Login with Facebook
                            </button>
                        </a>
                    </div>
                </div>

            </div>
            <!-- /.container -->

        </div>
        <!-- /.banner -->

        <!-- Footer -->
        <footer>
            <div class="container">
                <div class="row">
                    <div class="col-lg-12" style="text-align: center">
                        <ul class="list-inline">
                            <li>
                                <a href="#">Home</a>
                            </li>
                            <li class="footer-menu-divider">⋅</li>
                            <li>
                                <a href="#about">About</a>
                            </li>
                            <li class="footer-menu-divider">⋅</li>
                            <li>
                                <a href="#services">Services</a>
                            </li>
                            <li class="footer-menu-divider">⋅</li>
                            <li>
                                <a href="#contact">Contact</a>
                            </li>
                        </ul>
                        <p class="copyright text-muted small">A CS3216 Project (15/16 Sem 1)</p>
                    </div>
                </div>
            </div>
        </footer>
    </body>
</html>


<!--
<div class="title">Scribl</div>
{!! link_to('login', 'Login with Facebook')!!}
                            <div class="fb-login-button" data-max-rows="1" data-size="xlarge" data-show-faces="false" data-auto-logout-link="false"></div>
-->

<!-- FB SDK
<div id="fb-root"></div>
<script>(function(d, s, id) {
  var js, fjs = d.getElementsByTagName(s)[0];
  if (d.getElementById(id)) return;
  js = d.createElement(s); js.id = id;
  js.src = "//connect.facebook.net/en_US/sdk.js#xfbml=1&version=v2.4&appId=1480631925592204";
  fjs.parentNode.insertBefore(js, fjs);
}(document, 'script', 'facebook-jssdk'));
</script>
-->