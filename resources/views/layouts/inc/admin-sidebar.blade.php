<div id="layoutSidenav_nav">
    <nav class="sb-sidenav accordion sb-sidenav-dark" id="sidenavAccordion">
        <div class="sb-sidenav-menu">
            <div class="nav">
                <div class="sb-sidenav-menu-heading">Core</div>
                <a class="nav-link {{ Request::is('admin/dashboard') ? 'active':''}}" href="{{ url('admin/dashboard') }}">
                    <div class="sb-nav-link-icon"><i class="fas fa-tachometer-alt"></i></div>
                    Dashboard
                </a>

                <div class="sb-sidenav-menu-heading">Interface</div>

                @if(auth()->user()->user_type == 1)
                    <!-- Full Menu for userType = 1 -->
                    <a class="nav-link {{Request::is('admin/client') || Request::is('admin/add-category') || Request::is('admin/edit-category/*')? 'collapse active' : 'collapsed'}}" href="#" data-bs-toggle="collapse" data-bs-target="#collapseLayouts" aria-expanded="false" aria-controls="collapseLayouts">
                        <div class="sb-nav-link-icon"><i class="fas fa-users"></i></div>
                        Client Management
                        <div class="sb-sidenav-collapse-arrow"><i class="fas fa-angle-down"></i></div>
                    </a>
                    <div class="collapse {{Request::is('admin/client') || Request::is('admin/add-client') || Request::is('admin/edit-client/*')  ? 'show' : ''}}" id="collapseLayouts" aria-labelledby="headingOne" data-bs-parent="#sidenavAccordion">
                        <nav class="sb-sidenav-menu-nested nav">
                            <a class="nav-link {{ Request::is('admin/add-client') ? 'active':''}}" href="{{ url('admin/add-client') }}">Add Client</a>
                            <a class="nav-link {{ Request::is('admin/client') || Request::is('admin/edit-client/*') ? 'active':''}}" href="{{ url('admin/client') }}">View Client</a>
                        </nav>
                    </div>

                    <a class="nav-link {{Request::is('admin/employees') || Request::is('admin/add-employee') || Request::is('admin/employee/*')? 'collapse active' : 'collapsed'}}" href="#" data-bs-toggle="collapse" data-bs-target="#collapsePost" aria-expanded="false" aria-controls="collapseLayouts">
                        <div class="sb-nav-link-icon"><i class="fas fa-users"></i></div>
                        Employee Management
                        <div class="sb-sidenav-collapse-arrow"><i class="fas fa-angle-down"></i></div>
                    </a>
                    <div class="collapse {{Request::is('admin/employees') ||  Request::is('admin/add-employee') || Request::is('admin/employee/*')  ? 'show' : ''}}" id="collapsePost" aria-labelledby="headingOne" data-bs-parent="#sidenavAccordion">
                        <nav class="sb-sidenav-menu-nested nav">
                            <a class="nav-link {{ Request::is('admin/add-employee') ? 'active':''}}" href="{{ url('admin/add-employee') }}">Add Employee</a>
                            <a class="nav-link {{ Request::is('admin/employees') ? 'active':''}}" href="{{ url('admin/employees') }}">View Employee</a>
                        </nav>
                    </div>

                    <a class="nav-link {{ Request::is('admin/servicecategory') ? 'active':''}}" href="{{ url('admin/servicecategory') }}">
                        <div class="sb-nav-link-icon"><i class="fas fa-hammer"></i></div>
                        Service Category
                    </a>

                    <a class="nav-link {{ Request::is('admin/joblisting') ? 'active':''}}" href="{{ url('admin/joblisting') }}">
                        <div class="sb-nav-link-icon"><i class="fas fa-briefcase"></i></div>
                        Job Listing
                    </a>

                    <a class="nav-link {{ Request::is('admin/workerfeedback') ? 'active':''}}" href="{{ url('admin/workerfeedback') }}">
                        <div class="sb-nav-link-icon"><i class="fas fa-star"></i></div>
                        Feedback and Rating Management
                    </a>

                    <a class="nav-link {{Request::is('admin/employees') || Request::is('admin/add-employee') || Request::is('admin/employee/*')? 'collapse active' : 'collapsed'}}" href="#" data-bs-toggle="collapse" data-bs-target="#collapsePost2" aria-expanded="false" aria-controls="collapseLayouts">
                        <div class="sb-nav-link-icon"><i class="fas fa-money-bill-wave"></i></div>
                        Rate Management
                        <div class="sb-sidenav-collapse-arrow"><i class="fas fa-angle-down"></i></div>
                    </a>
                    <div class="collapse {{Request::is('admin/worker_rate') ||  Request::is('admin/client_rate') || Request::is('admin/worker_rate/*')  ? 'show' : ''}}" id="collapsePost2" aria-labelledby="headingOne" data-bs-parent="#sidenavAccordion">
                        <nav class="sb-sidenav-menu-nested nav">
                            <a class="nav-link {{ Request::is('admin/worker_rate') ? 'active':''}}" href="{{ url('admin/worker_rate') }}">Rates for Worker</a>
                            <a class="nav-link {{ Request::is('admin/client_rate') ? 'active':''}}" href="{{ url('admin/client_rate') }}">Rates for Client</a>
                        </nav>
                    </div>

                    <a class="nav-link {{Request::is('admin/employees') || Request::is('admin/add-employee') || Request::is('admin/employee/*')? 'collapse active' : 'collapsed'}}" href="#" data-bs-toggle="collapse" data-bs-target="#collapsePost3" aria-expanded="false" aria-controls="collapseLayouts">
                        <div class="sb-nav-link-icon"><i class="fas fa-dollar-sign"></i></div>
                        Payment Management
                        <div class="sb-sidenav-collapse-arrow"><i class="fas fa-angle-down"></i></div>
                    </a>
                    <div class="collapse {{Request::is('admin/payment_worker') ||  Request::is('admin/payment_client') || Request::is('admin/payment_worker/*')  ? 'show' : ''}}" id="collapsePost3" aria-labelledby="headingOne" data-bs-parent="#sidenavAccordion">
                        <nav class="sb-sidenav-menu-nested nav">
                            <a class="nav-link {{ Request::is('admin/payment_worker') ? 'active':''}}" href="{{ url('admin/payment_worker') }}">Payment for Worker</a>
                            <a class="nav-link {{ Request::is('admin/payment_refferal') ? 'active':''}}" href="{{ url('admin/payment_refferal') }}">Payment for Client</a>
                        </nav>
                    </div>

                    <a class="nav-link {{ Request::is('admin/Invoice') ? 'active':''}}" href="{{ url('admin/Invoice') }}">
                        <div class="sb-nav-link-icon"><i class="fas fa-file-invoice"></i></div>
                        Invoice
                    </a>

                    <a class="nav-link {{ Request::is('admin/banktransfer') ? 'active':''}}" href="{{ url('admin/banktransfer') }}">
                        <div class="sb-nav-link-icon"><i class="fas fa-exchange-alt"></i></div>
                        Bank Transfer Management
                    </a>

                    <a class="nav-link {{Request::is('admin/employees') || Request::is('admin/add-employee') || Request::is('admin/employee/*')? 'collapse active' : 'collapsed'}}" href="#" data-bs-toggle="collapse" data-bs-target="#collapsePost4" aria-expanded="false" aria-controls="collapseLayouts">
                        <div class="sb-nav-link-icon"><i class="fas fa-clock"></i></div>
                        Extended Rate Management
                        <div class="sb-sidenav-collapse-arrow"><i class="fas fa-angle-down"></i></div>
                    </a>
                    <div class="collapse {{Request::is('admin/extended-hour-worker') ||  Request::is('admin/extended-hour-client') || Request::is('admin/extended-hour-worker/*')  ? 'show' : ''}}" id="collapsePost4" aria-labelledby="headingOne" data-bs-parent="#sidenavAccordion">
                        <nav class="sb-sidenav-menu-nested nav">
                            <a class="nav-link {{ Request::is('admin/extended-hour-worker') ? 'active':''}}" href="{{ url('admin/extended-hour-worker') }}">Extended rate for worker</a>
                            <a class="nav-link {{ Request::is('admin/extended-hour-client') ? 'active':''}}" href="{{ url('admin/extended-hour-client') }}">Extended rate for client</a>
                        </nav>
                    </div>

                    <a class="nav-link {{ Request::is('admin/allowance-management') ? 'active':''}}" href="{{ url('admin/allowance-management') }}">
                        <div class="sb-nav-link-icon"><i class="fas fa-road"></i></div>
                        Allowance Management
                    </a>

                @elseif (auth()->user()->user_type == 4)
                    <a class="nav-link {{Request::is('admin/employees') || Request::is('admin/add-employee') || Request::is('admin/employee/*')? 'collapse active' : 'collapsed'}}" href="#" data-bs-toggle="collapse" data-bs-target="#collapsePost" aria-expanded="false" aria-controls="collapseLayouts">
                        <div class="sb-nav-link-icon"><i class="fas fa-users"></i></div>
                        Employee Management
                        <div class="sb-sidenav-collapse-arrow"><i class="fas fa-angle-down"></i></div>
                    </a>
                    <div class="collapse {{Request::is('admin/employees') ||  Request::is('admin/add-employee') || Request::is('admin/employee/*')  ? 'show' : ''}}" id="collapsePost" aria-labelledby="headingOne" data-bs-parent="#sidenavAccordion">
                        <nav class="sb-sidenav-menu-nested nav">
                            <a class="nav-link {{ Request::is('admin/employees') ? 'active':''}}" href="{{ url('admin/employees') }}">View Employee</a>
                        </nav>
                    </div>
                    <a class="nav-link {{ Request::is('admin/joblisting') ? 'active':''}}" href="{{ url('admin/joblisting') }}">
                        <div class="sb-nav-link-icon"><i class="fas fa-briefcase"></i></div>
                        Job Listing
                    </a>

                    <a class="nav-link {{ Request::is('admin/complaints') ? 'active':''}}" href="{{ url('admin/complaints') }}">
                        <div class="sb-nav-link-icon"><i class="fas fa-comments"></i></div>
                        Complaints
                    </a>
                    
                    <a class="nav-link {{Request::is('admin/workerfeedback') ? 'active':''}}" href="{{ url('admin/workerfeedback') }}">
                        <div class="sb-nav-link-icon"><i class="fas fa-star"></i></div>
                        Feedback and Rating Management
                    </a>
                @endif

            </div>
        </div>
        <div class="sb-sidenav-footer">
            <div class="small">Logged in as:</div>
            Admin
        </div>
    </nav>
</div>
