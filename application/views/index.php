<!DOCTYPE html>
<html>
    <head>
        <title>VSF</title>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">

        <!-- Latest compiled and minified CSS -->
        <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.5/css/bootstrap.min.css">

        <!-- Optional theme -->
        <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.5/css/bootstrap-theme.min.css">

        <style type="text/css">
            body {
                padding-top: 50px;
            }
            .starter-template {
                padding: 40px 15px;
            }
        </style>
    </head>
    <body>
        <div></div>

        <nav class="navbar navbar-inverse navbar-fixed-top">
            <div class="container">
                <div class="navbar-header">
                    <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#navbar" aria-expanded="false" aria-controls="navbar">
                        <span class="sr-only">Toggle navigation</span>
                        <span class="icon-bar"></span>
                        <span class="icon-bar"></span>
                        <span class="icon-bar"></span>
                    </button>
                    <a class="navbar-brand" href="#"><?php echo application\config\Application::NAME; ?></a>
                </div>
            </div>
        </nav>


        <div class="container">

            <div class="starter-template">

                <h1>Input parameters</h1>
                <p>Input parameters in Site Context are parsed from URL<br/>
                    <?php
                    $host = filter_input(INPUT_SERVER, 'HTTP_HOST');
                    $protocol = 'http' . (isset($_SERVER['HTTPS']) ? 's' : '') . '://';
                    ?>
                    <a href="<?php echo $protocol . $host; ?>"><?php echo $protocol . $host; ?>/<span>controller</span>/<span>action</span>/param1/param2/...</a>
                </p>
                <pre><?php var_dump($input); ?></pre>

                <h1>Model Data</h1>
                <pre><?php var_dump($model); ?></pre>

            </div>

        </div><!-- /.container -->

        <!-- Latest compiled and minified JavaScript -->
        <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.5/js/bootstrap.min.js"></script>

    </body>
</html>
