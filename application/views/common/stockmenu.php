<style>
.badge {
  padding-left: 15px;
  padding-right: 15px;
  -webkit-border-radius: 15px;
  -moz-border-radius: 15px;
  border-radius: 15px;
}

.label-warning[href],
.badge-warning[href] {
  background-color: #c67605;
}
#cartvs {
    font-size: 12px;
    background: #ff0000;
    color: #fff;
    padding: 7px 7px 7px 7px;
    vertical-align: top;
    margin-left: -12px;
}
	</style>
<div class="app-admin-wrap layout-sidebar-large">
        <div class="main-header">
            <div class="logo">
            <a href="<?=base_url()?>"><img src="<?=base_url('public')?>/dist-assets/images/logo.png" alt="logo" style="
    width: 250px;
"></a>
            </div>
            <div class="menu-toggle">
                <div></div>
                <div></div>
                <div></div>
            </div>

            <div style="margin: auto"></div>
            <div class="header-part-right">
            <div class="wthreecartaits wthreecartaits2 cart cart text-right" style="cursor:pointer;" onclick="getChekout()">
				<i class="fa fa-cart-arrow-down" aria-hidden="true" style="font-size:26px;padding:3px;color:#000"></i>
				<span class='badge badge-warning' id='cartvs'> 0 </span>
			</div>
                <!-- Full screen toggle -->
                <i class="i-Full-Screen header-icon d-none d-sm-inline-block" data-fullscreen></i>

                <!-- User avatar dropdown -->
                <div class="dropdown">
                    <div class="user col align-self-end">
                        <img src="<?=base_url('public')?>/dist-assets/images/faces/1.jpg" id="userDropdown" alt="" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                        <div class="dropdown-menu dropdown-menu-right" aria-labelledby="userDropdown">
                            <div class="dropdown-header">
                                <i class="i-Lock-User mr-1"></i> <?=$this->session->userdata('user')?>
                            </div>
                            <!-- <a class="dropdown-item" href="<?=base_url('home/profile')?>"><i class="i-Lock-User mr-1"></i> Profile</a> -->
                            <a class="dropdown-item" href="<?=base_url('home/changepassword')?>"><i class="i-Lock-User mr-1"></i> Change Password</a>
                            <a class="dropdown-item" href="<?=base_url('welcome/logout')?>"><i class="i-Lock-User mr-1"></i> Sign out</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="side-content-wrap">
            <div class="sidebar-left rtl-ps-none" data-perfect-scrollbar="" data-suppress-scroll-x="true">
                <ul class="navigation-left">
                
                    <li class="nav-item"><a class="nav-item-hold" href="<?=base_url('stockpointeritems')?>"><i class="fa fa-user"></i><span class="nav-text">PRODUCTS<S></S></span></a>
                        <div class="triangle"></div>
                    </li>
                    <li class="nav-item"><a class="nav-item-hold" href="<?=base_url('userdetails/orders/'.$this->session->userdata('userid'))?>"><i class="fa fa-user"></i><span class="nav-text">ORDERS<S></S></span></a>
                        <div class="triangle"></div>
                    </li>
                    <li class="nav-item"><a class="nav-item-hold" href="<?=base_url('profile/add/0')?>"><i class="fa fa-user"></i><span class="nav-text">USERS</span></a>
                        <div class="triangle"></div>
                    </li>
                
                
                </ul>
            </div>
            
            <div class="sidebar-overlay"></div>
        </div>