<!-- Left side column. contains the logo and sidebar -->
      <aside class="main-sidebar">

        <!-- sidebar: style can be found in sidebar.less -->
        <section class="sidebar">

          <!-- Sidebar user panel (optional) -->
          <div class="user-panel">
            <div class="pull-left image">
              <img src="{{ Gravatar::src(Auth::user()->email) }}" class="img-circle" alt="User Image">
            </div>
            <div class="pull-left info">
              <p>{{ Auth::user()->name }}</p>
              <!-- Status -->
              <a href="#"><i class="fa fa-circle text-success"></i> Online</a>
            </div>
          </div>

          <!-- search form (Optional) -->
          <form action="#" method="get" class="sidebar-form">
            <div class="input-group">
              <input type="text" name="q" class="form-control" placeholder="Search...">
              <span class="input-group-btn">
                <button type="submit" name="search" id="search-btn" class="btn btn-flat"><i class="fa fa-search"></i></button>
              </span>
            </div>
          </form>
          <!-- /.search form -->

          <!-- Sidebar Menu -->
          <ul class="sidebar-menu">
            <li class="header">Menu</li>
            <li class="treeview {{ areActiveRoutes(['sql.index','sql.store','sql.history', 'sql.show']) }}">
              <a href="#"><i class="fa fa-link"></i> <span>SQL Query Tool</span> <i class="fa fa-angle-left pull-right"></i></a>
              <ul class="treeview-menu">
                <li><a href="{{ url('/sql') }}">New Query</a></li>
                <li><a href="{{ url('/sql/history') }}">Query History</a></li>
              </ul>
            </li>
            <li class="treeview {{ areActiveRoutes([
              'device.counts',
              'registration.index',
              'registration.store',
              'service.index',
              'service.store',
              'reports.index'
            ]) }}">
              <a href="#"><i class="fa fa-link"></i> <span>UC Reporting</span> <i class="fa fa-angle-left pull-right"></i></a>
              <ul class="treeview-menu">
                <li><a href="{{ url('/service') }}">Service Status</a></li>
                <li><a href="{{ url('/registration') }}">Device Registration</a></li>
                <li><a href="{{ url('/reports/device/counts') }}">Phone Counts</a></li>
              </ul>
            </li>
            <li class="treeview {{ areActiveRoutes(['cluster.index','cluster.create','cluster.show','user.index','user.create','user.edit']) }}">
              <a href="#"><i class="fa fa-link"></i> <span>Admin Settings</span> <i class="fa fa-angle-left pull-right"></i></a>
              <ul class="treeview-menu">
                <li><a href="{{ url('/user') }}">Users</a></li>
                <li><a href="{{ url('/cluster') }}">Clusters</a></li>
              </ul>
            </li>
          </ul><!-- /.sidebar-menu -->
        </section>
        <!-- /.sidebar -->
      </aside>
