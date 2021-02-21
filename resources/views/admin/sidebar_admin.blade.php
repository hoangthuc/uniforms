<?php
$slug = Request::path();
$url_active = Request::url();
?>
<!-- Navbar -->
<nav class="main-header navbar navbar-expand navbar-white navbar-light">
    <!-- Left navbar links -->
    <ul class="navbar-nav">
        <li class="nav-item">
            <a class="nav-link" data-widget="pushmenu" href="#" role="button"><i class="fas fa-bars"></i></a>
        </li>
        <li class="nav-item d-none d-sm-inline-block">
            <a href="{{ url('/') }}" class="nav-link">View site</a>
        </li>
    </ul>
    <!-- Right navbar links -->
    <ul class="navbar-nav ml-auto">
        <li class="nav-item">
            <a class="nav-link" data-widget="control-sidebar" data-slide="true" href="#" role="button">
                <i class="fas fa-th-large"></i>
            </a>
        </li>
    </ul>
</nav>
<!-- /.navbar -->

<!-- Main Sidebar Container -->
<aside class="main-sidebar sidebar-dark-primary elevation-4">
    <!-- Brand Logo -->
    <a href="{{ route('admin') }}" class="brand-link">
        <img src="{{ asset('images/favicon_dashboard.png') }}" alt="Admin" class="brand-image img-circle elevation-3"
             style="opacity: .8">
        <span class="brand-text font-weight-light">UniPro</span>
    </a>

    <!-- Sidebar -->
    <div class="sidebar">


        <!-- Sidebar Menu -->
        <nav class="mt-2">
            <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu" data-accordion="false">
                {{--User dashboard--}}
                <li class="nav-item has-treeview">
                    <a href="#" class="nav-link">
                        <i class="nav-icon fas fa-user-circle"></i>
                        <p>
                            {{ Auth::user()->name }}
                            <i class="right fas fa-angle-left"></i>
                        </p>
                    </a>
                    <ul class="nav nav-treeview">
                        <li class="nav-item">
                            <a class="nav-link comming_soon" href="#">
                                <i class="far fa-circle nav-icon"></i>
                                <p>{{ __('Profile') }}</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="{{ route('logout') }}"
                               onclick="event.preventDefault();
                                                     document.getElementById('logout-form').submit();">
                                <i class="far fa-circle nav-icon"></i>
                                <p>{{ __('Logout') }}</p>
                                <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                                    @csrf
                                </form>
                            </a>
                        </li>
                    </ul>
                </li>

                {{--User manage--}}
                <li class="nav-item">
                    <a href="{{ route('admin.users') }}" class="nav-link">
                        <i class="nav-icon fas fa-users"></i>
                        <p>Users</p>
                    </a>
                </li>
                <li class="nav-item has-treeview">
                    <a href="#" class="nav-link">
                        <i class="nav-icon fas fa-copy"></i>
                        <p>
                            Posts
                            <i class="fas fa-angle-left right"></i>
                            <span class="badge badge-info right">{{ App\Posts::get_posts()->total() }}</span>
                        </p>
                    </a>
                    <ul class="nav nav-treeview">
                        <li class="nav-item">
                            <a href="{{route('admin.posts')}}" class="nav-link">
                                <i class="far fa-circle nav-icon"></i>
                                <p>All Posts</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="{{route('admin.add-post')}}" class="nav-link">
                                <i class="far fa-circle nav-icon"></i>
                                <p>Add new</p>
                            </a>
                        </li>
                    </ul>
                </li>
                <!---Products-->
                <li class="nav-item has-treeview">
                    <a href="#" class="nav-link">
                        <i class="nav-icon fas fa-shopping-cart"></i>
                        <p>
                            Products
                            <i class="right fas fa-angle-left"></i>
                        </p>
                    </a>
                    <ul class="nav nav-treeview">
                        <li class="nav-item">
                            <a href="{{ route('admin.products') }}" class="nav-link">
                                <i class="far fa-circle nav-icon"></i>
                                <p> All Products</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="{{ route('admin.add-product') }}" class="nav-link">
                                <i class="far fa-circle nav-icon"></i>
                                <p> Add new</p>
                            </a>
                        </li>
                        <li class="nav-item ">
                            <a href="{{ route('admin.product_categories') }}" class="nav-link">
                                <i class="far fa-circle nav-icon"></i>
                                <p> Product categories</p>
                            </a>
                        </li>
                        <li class="nav-item ">
                            <a href="{{ route('admin.product_attributes') }}" class="nav-link">
                                <i class="far fa-circle nav-icon"></i>
                                <p> Product attributes</p>
                            </a>
                        </li>
                        <li class="nav-item ">
                            <a href="{{ route('admin.import_products') }}" class="nav-link">
                                <i class="far fa-circle nav-icon"></i>
                                <p> Import Products</p>
                            </a>
                        </li>

                        <li class="nav-item ">
                            <a href="{{ route('admin.reviews') }}" class="nav-link">
                                <i class="far fa-circle nav-icon"></i>
                                <p> Reviews</p>
                            </a>
                        </li>

                        <li class="nav-item ">
                            <a href="{{ route('admin.questions') }}" class="nav-link">
                                <i class="far fa-circle nav-icon"></i>
                                <p> Questions</p>
                            </a>
                        </li>

                    </ul>
                </li>
                <!---Order-->
                <li class="nav-item">
                    <a href="{{ route('admin.orders') }}" class="nav-link">
                        <i class="nav-icon fas fa-file-invoice"></i>
                        <p>
                            Orders
                        </p>
                    </a>
                </li>
                <!--Media-->
                <li class="nav-item">
                    <a href="{{ route('admin.media') }}" class="nav-link">
                        <i class="nav-icon far fa-image"></i>
                        <p>
                            Media
                        </p>
                    </a>
                </li>
                <li class="nav-header">SETTING</li>
                <!--Widgets-->
                <li class="nav-item  has-treeview">
                    <a href="#" class="nav-link">
                        <i class="nav-icon fas fa-th"></i>
                        <p>
                            Widgets
                            <i class="right fas fa-angle-left"></i>
                        </p>
                    </a>

                    <ul class="nav nav-treeview">
                        <li class="nav-item">
                            <a href="{{ route('admin.faq') }}" class="nav-link">
                                <i class="far fa-circle nav-icon"></i>
                                <p> FAQ</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="{{ route('admin.contact-form') }}" class="nav-link">
                                <i class="far fa-circle nav-icon"></i>
                                <p> Contact Form</p>
                            </a>
                        </li>
                    </ul>
                </li>
                <li class="nav-item">
                    <a href="{{ route('admin.data-form') }}" class="nav-link">
                        <i class="nav-icon fas fa-bullhorn"></i>
                        <p>
                            Database Form
                        </p>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="{{ route('admin.settings') }}" class="nav-link">
                        <i class="nav-icon fas fa-cogs"></i>
                        <p>
                            Settings
                        </p>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="{{ route('admin.menu') }}" class="nav-link">
                        <i class="nav-icon fas fa-bars"></i>
                        <p>
                            Menu
                        </p>
                    </a>
                </li>
                <!-- Add icons to the links using the .nav-icon class
                    with font-awesome or any other icon font library -->

                <li class="nav-item has-treeview">
                    <a href="#" class="nav-link">
                        <i class="nav-icon fas fa-chart-pie"></i>
                        <p>
                            Analytics
                            <i class="right fas fa-angle-left"></i>
                        </p>
                    </a>
                    <ul class="nav nav-treeview">
                        <li class="nav-item">
                            <a href="{{ route('admin.analytics') }}" class="nav-link">
                                <i class="far fa-circle nav-icon"></i>
                                <p>Sales</p>
                            </a>
                        </li>
                        <li class="nav-item comming_soon d-none">
                            <a href="#" class="nav-link">
                                <i class="far fa-circle nav-icon"></i>
                                <p>Flot</p>
                            </a>
                        </li>
                    </ul>
                </li>
                <li class="nav-item has-treeview d-none">
                    <a href="#" class="nav-link">
                        <i class="nav-icon far fa-envelope"></i>
                        <p>
                            Mailbox
                            <i class="fas fa-angle-left right"></i>
                        </p>
                    </a>
                    <ul class="nav nav-treeview">
                        <li class="nav-item">
                            <a href="#" class="nav-link comming_soon">
                                <i class="far fa-circle nav-icon"></i>
                                <p>Inbox</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="#" class="nav-link comming_soon">
                                <i class="far fa-circle nav-icon"></i>
                                <p>Compose</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="#" class="nav-link comming_soon">
                                <i class="far fa-circle nav-icon"></i>
                                <p>Read</p>
                            </a>
                        </li>
                    </ul>
                </li>
                <li class="nav-item has-treeview d-none">
                    <a href="#" class="nav-link">
                        <i class="nav-icon fas fa-book"></i>
                        <p>
                            Pages
                            <i class="fas fa-angle-left right"></i>
                        </p>
                    </a>
                    <ul class="nav nav-treeview">
                        <li class="nav-item comming_soon">
                            <a href="#" class="nav-link">
                                <i class="far fa-circle nav-icon"></i>
                                <p>Invoice</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="#" class="nav-link comming_soon">
                                <i class="far fa-circle nav-icon"></i>
                                <p>E-commerce</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="#" class="nav-link comming_soon">
                                <i class="far fa-circle nav-icon"></i>
                                <p>Contacts</p>
                            </a>
                        </li>
                    </ul>
                </li>
                <li class="nav-header">MISCELLANEOUS</li>
                <li class="nav-item">
                    <a href="#" class="nav-link comming_soon">
                        <i class="nav-icon fas fa-file"></i>
                        <p>Documentation</p>
                    </a>
                </li>
            </ul>
        </nav>
        <!-- /.sidebar-menu -->
    </div>
    <!-- /.sidebar -->
</aside>
<script>
    document.querySelectorAll('.nav-link').forEach(el=>{
        let url = el.getAttribute('href');
        if(url == '{{ $url_active }}')el.className = 'nav-link active';
    })
   let active =  document.querySelector('ul li .nav-link.active')
       if(active)active.parentElement.parentElement.parentElement.className = 'nav-item has-treeview menu-open';
</script>
