            <!-- sidebar menu -->
            <div id="sidebar-menu" class="main_menu_side hidden-print main_menu">
              <div class="menu_section">
                <h3>General</h3>
                @can("ADMIN_USERS")
                <ul class="nav side-menu">
                  <li><a href="#/users"><i class="fa fa-users"></i> Usuarios registrados </a></li>
                </ul>
                @endcan
              </div>
              @can("DEBUG")
              <div class="menu_section">
                <h3>Desarrolladores</h3>
                <ul class="nav side-menu">
                  <li><a href="javascript:void(0)" onclick="panel.refreshVersion();"><i class="fa fa-refresh"></i> Borrar LocalStorage </a></li>
                </ul>
              </div>
              @endcan

            </div>
            <!-- /sidebar menu -->
