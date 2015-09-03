<!DOCTYPE html>
<html>
    <head>
        <title>Be right back.</title>

        <link href="//fonts.googleapis.com/css?family=Lato:100,400" rel="stylesheet" type="text/css">

        <style>
            html, body {
                height: 100%;
            }

            body {
                margin: 0;
                padding: 0;
                width: 100%;
                color: #000000;
                display: table;
                font-weight: 400;
                font-family: 'Lato';
            }

            .container {
                text-align: center;
                display: table-cell;
                vertical-align: middle;
            }

            .content {
                text-align: center;
                display: inline-block;
            }

            .title {
                font-size: 72px;
                margin-bottom: 40px;
                font-weight: 100;
            }
        </style>
        <script type="text/javascript">
            function redirect() {
                window.location="{{env('APP_URL')}}";
            }
            setTimeout('redirect()', 3000);
        </script>
    </head>
    <body>
        <div class="container">
            <div class="content">
                <div class="title">404 Not Found.</div>
                Redirecting to home page...
            </div>
        </div>
    </body>
</html>
