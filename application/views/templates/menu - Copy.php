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

        <ul class="sidebar-menu" data-widget="tree">
            <li class="<?php
                if($pageid == "masterexam") {
                    echo "active";
                } elseif($pageid == "mastercosting") {
                    echo "active";
                }
            ?> treeview">
                <a href="#">
                    <i class="fa fa-files-o"></i>
                    <span>Master Data</span>
                    <span class="pull-right-container">
                        <i class="fa fa-angle-left pull-right"></i>
                    </span>
                </a>
                <ul class="treeview-menu">
                    <li><a href="<?php echo site_url('exammaster'); ?>"><i class="fa fa-circle-o"></i> Examination</a></li>
                    <li><a href="<?php echo site_url('costingmaster'); ?>"><i class="fa fa-circle-o"></i> Costing</a></li>
                </ul>
            </li>

            <li class="<?php
            if($pageid == "generatepack") {
                echo "active";
            }
            ?> treeview">
                <a href="#">
                    <i class="fa fa-files-o"></i>
                    <span>MCU Package</span>
                    <span class="pull-right-container">
                        <i class="fa fa-angle-left pull-right"></i>
                    </span>
                </a>
                <ul class="treeview-menu">
                    <li><a href="<?php echo site_url('packcompany'); ?>"><i class="fa fa-circle-o"></i> Generate Package</a></li>
                </ul>
            </li>
        </ul>
    </section>
</aside>
