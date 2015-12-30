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
            <li class="treeview {{ areActiveRoutes([
            'sql.index',
            'sql.store',
            'sql.history',
            'sql.show',
            'sql.favorites'
            ]) }}">
              <a href="#"><i class="fa fa-database"></i> <span>SQL Query Tool</span> <i class="fa fa-angle-left pull-right"></i></a>
              <ul class="treeview-menu">
                @if(\Auth::user()->hasRole(['Administrator', 'SQL Admin']))
                  <li><a href="{{ url('/sql') }}">New Query</a></li>
                @endif
                <li><a href="{{ url('/sql/favorites') }}">Favorite Queries</a></li>
                <li><a href="{{ url('/sql/history') }}">Query History</a></li>
              </ul>
            </li>
            <li class="treeview {{ areActiveRoutes([
              'device.counts',
              'registration.index',
              'registration.store',
              'service.index',
              'service.store',
              'reports.index',
              'firmware.index',
              'firmware.store'
            ]) }}">
              <a href="#"><i class="fa fa-file-pdf-o"></i> <span>UC Reporting</span> <i class="fa fa-angle-left pull-right"></i></a>
              <ul class="treeview-menu">
                <li><a href="{{ url('/service') }}">Service Status</a></li>
                <li><a href="{{ url('/registration') }}">Device Registration</a></li>
                <li><a href="{{ url('/firmware') }}">Device Firmware Report</a></li>
                <li><a href="{{ url('/reports/device/counts') }}">Phone Counts</a></li>
              </ul>
            </li>
            <li class="treeview {{ areActiveRoutes([
            'autodialer.index',
            'autodialer.bulk.index'
            ]) }}">
              <a href="#"><i class="fa fa-phone"></i> <span>Auto Dialer</span> <i class="fa fa-angle-left pull-right"></i></a>
              <ul class="treeview-menu">
                <li><a href="{{ url('/autodialer') }}">Single Call</a></li>
                <li><a href="{{ url('/autodialer/bulk') }}">Bulk Calls</a></li>
              </ul>
            </li>
            <li class="treeview {{ areActiveRoutes([
            'itl.index',
            'ctl.index',
            'phone.show',
            'eraser.bulk.index',
            'eraser.bulk.show',
            'eraser.bulk.create',
            ]) }}">
              <a href="#"><i class="fa fa-eraser"></i> <span>Cert Eraser</span> <i class="fa fa-angle-left pull-right"></i></a>
              <ul class="treeview-menu">
                <li><a href="{{ route('ctl.index') }}">CTL</a></li>
                <li><a href="{{ route('itl.index') }}">ITL</a></li>
                <li><a href="{{ route('eraser.bulk.index') }}">Bulk</a></li>
              </ul>
            </li>
            <li class=" {{isActiveRoute('cdrs.index')}}">
              <a href="{!! route('cdrs.index') !!}"><i class="fa fa-random"></i> <span>CDR's</span>
                <i class=""></i></a>
            </li>
            <li class="treeview {{ areActiveRoutes([
            'cluster.index',
            'cluster.create',
            'cluster.show',
            'user.index',
            'user.create',
            'user.edit',
            'role.index',
            'role.create',
            'role.edit'
            ]) }}">
              <a href="#"><i class="fa fa-user-secret"></i> <span>Admin Settings</span> <i class="fa fa-angle-left pull-right"></i></a>
              <ul class="treeview-menu">
                <li><a href="{{ url('/user') }}">Users</a></li>
                <li><a href="{{ url('role') }}">Roles</a></li>
                <li><a href="{{ url('/cluster') }}">Clusters</a></li>
              </ul>
            </li>
          </ul><!-- /.sidebar-menu -->
        </section>
        <!-- /.sidebar -->
      </aside>
