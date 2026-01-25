<div class="app-admin-wrap layout-sidebar-large">
        <div class="main-header">
            <div class="logo">
            <a href="<?=base_url()?>"><img src="<?=base_url('public')?>/dist-assets/images/logo.png" alt="logo" style="
    width: 100%;
"></a>
            </div>
            <div style="margin: auto"></div>
            <div class="header-part-right">
                <i class="i-Full-Screen header-icon d-none d-sm-inline-block" data-fullscreen></i>                
                <div class="dropdown">
                    <div class="user col align-self-end">
                        <img src="<?=base_url('public')?>/uploads/members/avatar.png" id="userDropdown" alt="" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                        <div class="dropdown-menu dropdown-menu-right" aria-labelledby="userDropdown">
                            <div class="dropdown-header">
                                <i class="i-Lock-User mr-1"></i> <?=$this->session->userdata('user')?>
                            </div>
                            
                            <a class="dropdown-item" href="<?=base_url('welcome/logout')?>">Sign out</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        