<?php
defined('BASEPATH') OR exit('No direct script access allowed');
?>
<aside class="main-sidebar">
    <section class="sidebar">
        <form action="#" method="get" class="sidebar-form">
            <div class="input-group">
                <input type="text" name="q" class="form-control" placeholder="Search...">
                <span class="input-group-btn">
                <button type="submit" name="search" id="search-btn" class="btn btn-flat"><i class="fa fa-search"></i>
                </button>
              </span>
            </div>
        </form>

        <ul class="sidebar-menu" data-widget="tree" style="color: white;">
            <?php
                $html = "";
                $lastType = "";
                foreach ($usermenu['usermenu'] as $rowmenu) {
                    if ($rowmenu['menu_type'] == "0") {
                        if($lastType == "1") {
                            $html .= "</ul>";
                        } elseif($lastType == "3" || $lastType == "5") {
                            $html .= "</ul></li></ul>";
                        }

                        $html .= "<li class=\"treeview\">" .
                                    "<a href=\"#\">" .
                                        "<i class=\"fa fa-clipboard\"></i>" .
                                        "<span>" . $rowmenu['menu_text'] . "</span>" .
                                        "<span class=\"pull-right-container\">" .
                                            "<i class=\"fa fa-angle-left pull-right\"></i>" .
                                        "</span>" .
                                    "</a>" .
                                    "<ul class=\"treeview-menu\">";
                    } elseif ($rowmenu['menu_type'] == "1") {
                        if($lastType == "3") {
                            $html .= "</ul>";
                        }

                        $url = site_url('') . $rowmenu['menu_url'];
                        $html .= "<li><a href=\"" . $url . "\"><i class=\"fa fa-angle-right\"></i> " . $rowmenu['menu_text'] . "</a></li>";
                    } elseif ($rowmenu['menu_type'] == "2") {
                        if($lastType == "3") {
                            $html .= "</ul>";
                        }

                        $html .= "<li class=\"treeview\">" .
                                    "<a href=\"#\"><i class=\"fa fa-angle-double-right\"></i>" . $rowmenu['menu_text'] . "" .
                                        "<span class=\"pull-right-container\">" .
                                            "<i class=\"fa fa-angle-left pull-right\"></i>" .
                                        "</span>" .
                                    "</a>" .
                                    "<ul class=\"treeview-menu\">";
                    } elseif ($rowmenu['menu_type'] == "3") {
                        $url = site_url('') . $rowmenu['menu_url'];
                        $html .= "<li><a href=\"" . $url . "\"><i class=\"fa fa-angle-right\"></i> " . $rowmenu['menu_text'] . "</a></li>";
                    }

                    $lastType = $rowmenu['menu_type'];
                }
                $html .= "</li>";

                echo $html;
            ?>
        </ul>
    </section>
</aside>
