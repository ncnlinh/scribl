<!DOCTYPE HTML>
<html>
    <head>
        <meta charset="utf-8">
        <title>Scribbl!</title>
        <link href="//fonts.googleapis.com/css?family=Lato:100" rel="stylesheet" type="text/css">

        <style>
            html, body {
                height: 100%;
            }

            body {
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
            }
        </style>
    </head>
    <body>
        <div class="container">
            <div class="content">
                <div class="title">Scribl</div>
                <div>{!! link_to('login', 'Login with Facebook')!!}</div>
            </div>
        </div>
    </body>
</html>
