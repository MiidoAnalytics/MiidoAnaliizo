<?php setlocale(LC_ALL,"es_ES"); ?>
<!-- Navigation -->
<nav class="navbar navbar-default navbar-static-top" role="navigation" style="margin-bottom: 0">
    <div class="navbar-header">
        <button type="button" class="navbar-toggle" data-toggle="collapse" data-target=".navbar-collapse">
            <span class="sr-only">Toggle navigation</span>
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
        </button>
        <!--<img style="display: block; float: left; margin: 15px;" src="images/analiizo_logo.png" width="120px">-->
        <a class="navbar-brand" href="../../../index.php"> Analiizo v2.0</a>
    </div>

    <ul class="nav navbar-top-links navbar-right">

        <li class="dropdown">
            <a class="dropdown-toggle" data-toggle="dropdown" href="#">
                <i class="fa fa-user fa-fw"></i>  <i class="fa fa-caret-down"></i>
            </a>
            <ul class="dropdown-menu dropdown-user">
                <li><a href="#"><i class="fa fa-user fa-fw"></i> User Profile</a>
                </li>
                <li><a href="#"><i class="fa fa-gear fa-fw"></i> Settings</a>
                </li>
                <li class="divider"></li>
                <li><a href="../../../Administrador/login/controlador/logout.php"><i class="fa fa-sign-out fa-fw"></i> Logout</a>
                </li>
            </ul>            
        </li>        
    </ul>

    <div class="navbar-default sidebar" role="navigation">
        <div class="sidebar-nav navbar-collapse">
            <ul class="nav" id="side-menu">
                <li class="sidebar-search">
                    <div class="input-group custom-search-form">
                        <input type="text" class="form-control" placeholder="Search...">
                        <span class="input-group-btn">
                            <button class="btn btn-default" type="button">
                                <i class="fa fa-search"></i>
                            </button>
                        </span>
                    </div>
                    <?php
                    if (count($menus) > 0) {
                        foreach ($menus as $item) {
                            $parent = $item['menu_id'];
                            $role_id = $item['role_id'];
                            
                                echo '<li>';
                            ?>
                                <a href="#"><i class="fa fa-bar-chart-o fa-fw"></i><?php echo $item['menu_name']; ?> <span class="fa arrow"></span></a>
                            <?php
                            
                            $item['menu_name'] = str_replace(" ", "",$item['menu_name']);
                            require_once "../../../Administrador/menu/modelo/classroles.php";
                            $menuhijos = Roles::buscarMenusHijos($parent, $role_id);
                            //echo count($menuhijos);
                            foreach ($menuhijos as $item2) {
                                if ($item2['parent'] == $parent) {
                                    $item2['page_link'] = trim($item2['page_link']);
                                    $carpeta = str_replace(".php", "",$item2['page_link']);
                                    //echo '../../../'.$item["menu_name"].'/'.$carpeta.'/controlador/'.$item2["page_link"].'><i class="fa fa-table fa-fw"></i>'.$item2["menu_name"];
                                    //die();
                                    echo '<ul class="nav nav-second-level">';
                                    ?>
                                <li>
                                    <?php
                                    echo '<a href=../../../'.$item["menu_name"].'/'.$carpeta.'/controlador/'.$item2["page_link"].'><i class="fa fa-table fa-fw"></i>'.$item2["menu_name"].'</a>';
                                    ?>
                                </li>
                                <?php
                                echo '</ul>';
                            }

                        }
                        echo '</li>';
                    }
                }
                ?>
                </li>
            </ul>   
        </div>
    </div>    
</nav>
