<?php

session_start();
include_once '../back_end/userController.php';
include_once '../views/reports.php';
$controller = new userController();
$controller->loginGeneral();

class general {

    public function head($titulo) {
        $layout = "<head>
            <title>$titulo</title>
            <meta charset='utf-8'>
            <meta name='viewport' content='width=device-width, initial-scale=1'>
            <link rel='stylesheet' href='../assets/css/bootstrap.css'>
            <link rel='stylesheet' href='../assets/css/navBar.css'>
            <link rel='stylesheet' href='https://cdnjs.cloudflare.com/ajax/libs/animate.css/3.7.0/animate.min.css'>
            <link rel='stylesheet' href='https://cdnjs.cloudflare.com/ajax/libs/tether/1.4.4/css/tether.min.css'>
            <link rel='stylesheet' href='https://maxcdn.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css'>
            <link rel='stylesheet' href='../assets/css/style.css'>

            <script src='https://code.jquery.com/jquery-3.3.1.slim.min.js'></script>
            <script src='https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.3/umd/popper.min.js'></script>
            <script src='https://cdnjs.cloudflare.com/ajax/libs/tether/1.4.4/js/tether.min.js'></script>
            <script src='../assets/js/jquery.twbsPagination.min.js'></script>
            <script src='../assets/js/bootstrap.js'></script>
            <script src='../assets/js/navBar.js'></script>
            <script src='https://kit.fontawesome.com/3662a08565.js' crossorigin='anonymous'></script>
        </head>  ";
        return $layout;
    }

    public function navbar($active) {
        $reports = excelReport();
        $active_info = "";
        $active_text_info = "";
        $active_orders = "";
        $active_text_orders = "";
        $active_collected = "";
        $active_text_collected = "";
        $active_reports = "";
        $active_text_reports = "";
        $active_users = "";
        $active_text_users = "";
        $active_group = "";
        $active_text_group = "";
        $active_invoice = "";
        $active_text_invoice = "";
        $title = "Información General";
        if (isset($_SESSION['name'])) {
            $user_name = $_SESSION['name'];
            $picture = $_SESSION['picture'];
            $id = $_SESSION['id'];
        } elseif (isset($_COOKIE['login_ok'])) {
            $user_name = $_COOKIE['name'];
            $picture = $_COOKIE['picture'];
            $id = $_COOKIE['id'];
        }

        switch ($active) {
            case "info":
                $active_info = "active";
                $active_text_info = "active_text";
                $title = "Información General";
                break;
            case "orders":
                $active_orders = "active";
                $active_text_orders = "active_text";
                $title = "Ordenes de servicio";
                break;
            case "collected":
                $active_collected = "active";
                $active_text_collected = "active_text";
                $title = "Pendientes";
                break;
            case "reports":
                $active_reports = "active";
                $active_text_reports = "active_text";
                $title = "Informes";
                break;
            case "users":
                $active_users = "active";
                $active_text_users = "active_text";
                $title = "Usuarios";
                break;
            case "group":
                $active_group = "active";
                $active_text_group = "active_text";
                $title = "Recogidas";
                break;
            case "invoice":
                $active_invoice = "active";
                $active_text_invoice = "active_text";
                $title = "Facturas";
                break;
        }

        $layout = "
            <!-- Sidebar -->
            <nav class='navbar navbar-inverse fixed-top active' id='sidebar-wrapper' role='navigation'>
                <ul class='nav sidebar-nav'>
                    <div class='sidebar-header'>
                        <div class='sidebar-brand'>
                            <img class='logo' src='../assets/img/logo1.png' alt='logo'>
                            <h2 class='text_logo'>DEB Transportes</h1>
                            <hr class='badge-light'>
                        </div>
                    </div>
                    <li class='$active_info'><a id='info' class='$active_text_info' href='info.php'>&nbsp; Información General</a></li>
                    <li class='$active_orders'><a id='order' class='$active_text_orders' href='orders.php'>&nbsp; Ordenes de Servicio</a></li>
                    <li class='$active_collected'><a id='collected' class='$active_text_collected' href='collectedOrders.php'>&nbsp; Pendientes</a></li>
                    <li class='$active_group'><a id='group' class='$active_text_group' href='groupOrders.php'>&nbsp; Recogidas</a></li>
                    <li class='$active_reports'><a id='report' class='' data-toggle='modal' data-target='#reportsModal' href='#'>&nbsp; Informes</a></li>
                    <li class='$active_invoice'><a id='invoice' class='$active_text_invoice' href='invoice.php'>&nbsp; Facturas</a></li>   
                    <li class='$active_users'><a id='user' class='$active_text_users' href='users.php'>&nbsp; Usuarios</a></li>
                    <li class='show-user hide-user'>
                        <hr class='badge-light'> </li>
                    <li class='hide-user'><a href='modifyUser.php?id=$id'><img src='../assets/img_user/$picture' width='40' height='40' class='rounded-circle'>&nbsp; $user_name</a></li>
                    <li class='margint10'>
                        <hr class='badge-light'> </li>
                    <li>
                        <a id='logout' href='../back_end/userController.php?type=logout'>&nbsp; Cerrar Sesión</a>
                    </li>                    
                </ul>
                <div class='footerNav'></div>
            </nav>$reports   
        <!--/#sidebar-wrapper -->";

        return $layout;
    }

    public function navTitle($user_name, $user_id, $title) {
        if (isset($_SESSION['name'])) {
            $user_name = $_SESSION['name'];
            $picture = $_SESSION['picture'];
            $id = $_SESSION['id'];
        } elseif (isset($_COOKIE['login_ok'])) {
            $user_name = $_COOKIE['name'];
            $picture = $_COOKIE['picture'];
            $id = $_COOKIE['id'];
        }
        return "<h1 class='body_title'>$title</h1>
                    <div class='user-nav'>
                        <div class='user_name user-nav'>$user_name</div>                    
                        <div class='dropdown user-nav'>
                            <div class='navbar-nav'>
                                <div class='nav-item dropdown'>
                                    <a class='nav-link dropdown-toggle user-dropdown' href='#' id='navbarDropdownMenuLink' role='button' data-toggle='dropdown' aria-haspopup='true' aria-expanded='false'>
                                        <img src='../assets/img_user/$picture' width='50' height='50' class='rounded-circle user_picture'>
                                    </a>
                                    <div class='dropdown-menu' aria-labelledby='navbarDropdownMenuLink'>
                                        <a class='dropdown-item' href='modifyUser.php?id=$id'>Editar Perfil</a>
                                        <a class='dropdown-item' href='../back_end/userController.php?type=logout'>Cerrar Sesión</a>
                                    </div>
                                </div>   
                                </ul> 
                            </div>
                        </div>
                    </div>";
    }

}
