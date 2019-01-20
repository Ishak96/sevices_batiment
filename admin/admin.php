<!DOCTYPE html>
<html>
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <title>Admin</title>
        <link rel="stylesheet" href="../bootstrap-4.0.0-dist/css/bootstrap.min.css">
        <link rel="stylesheet" href="../style/myStyle.css">
    </head>
    <body>
        <header>
            <h1>Services-Batiment.com</h1>
        </header>

        <div class="wrapper" style="display: flex;">
            <nav id="sidebar">
                <a href="#">Portfolio</a>
                <a href="#">Contact</a>
            </nav>
            <div id="content">
                <button type="button"  class="btn" id="menu_btn">
                    <i class="glyphicon glyphicon-align-left"></i>
                    <span>MENU</span>
                </button> 
                <div id="main">
                
                </div>  
            </div>            
        </div>

        <footer>            
            <p>Site créé par HACHOUD Rassem & METIDJI Fares</p>
        </footer>

         <script src="../js/jquery-1.12.1.js"></script>
         <script type="text/javascript">
             $(document).ready(function () {
                 $('#menu_btn').on('click', function () {
                     $('#sidebar').toggleClass('active');
                 });
             });
         </script>
    </body>
</html>
