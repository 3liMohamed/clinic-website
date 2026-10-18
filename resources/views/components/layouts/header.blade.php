    <!-- Header Area -->
    <header class="header">
        <!-- Topbar -->
        <div class="topbar">
            <div class="container">
                <div class="row">
                    <div class="col-lg-6 col-md-5 col-12">
                        <!-- Contact -->
                        <ul class="top-link">
                            @foreach ($topNavPages as $page)
                                @php($routeName = config("pages.{$page->name}.route"))

                                @if ($routeName && Route::has($routeName))
                                    <li><a href="@route($routeName)">{{ $page->nav_title }}</a></li>
                                @endif
                            @endforeach

                        </ul>
                        <!-- End Contact -->
                    </div>
                    <div class="col-lg-6 col-md-7 col-12">
                        <!-- Top Contact -->
                        <ul class="top-contact">
                            @settingAvailable('contact:phone')
                                <li>
                                    <i class="fa fa-phone"></i>
                                    <a href="tel:@setting('contact:phone')">@setting('contact:phone')</a>
                                </li>
                            @endsettingAvailable
                            @settingAvailable('contact:email')
                                <li>
                                    <i class="fa fa-envelope"></i>
                                    <a href="mailto:@setting('contact:email')">@setting('contact:email')</a>
                                </li>
                            @endsettingAvailable

                        </ul>
                        <!-- End Top Contact -->
                    </div>
                </div>
            </div>
        </div>
        <!-- End Topbar -->
        <!-- Header Inner -->
        <div class="header-inner">
            <div class="container">
                <div class="inner">
                    <div class="row align-items-center">
                        <div class="col-lg-3 col-md-3 col-12">
                            <!-- Start Logo -->
                            <div class="logo">
                                <a href="@route('home')"><img src="@settingAsset('logo:light')"
                                        alt="@setting('site:name')" /></a>
                            </div>
                            <!-- End Logo -->
                            <!-- Mobile Nav -->
                            <div class="mobile-nav"></div>
                            <!-- End Mobile Nav -->
                        </div>
                        <div class="col-lg-7 col-md-9 col-12">
                            <!-- Main Menu -->
                            <div class="main-menu">

                                <nav class="navigation">
                                    <ul class="nav menu">
                                        @foreach ($mainNavPages as $page)
                                            @php($routeName = config("pages.{$page->name}.route"))


                                            @if ($routeName && Route::has($routeName))
                                                <li class="{{ request()->routeIs($routeName) ? 'active' : '' }}">
                                                    <a href="@route($routeName)">
                                                        {{ $page->nav_title }}
                                                    </a>
                                                </li>
                                            @endif
                                        @endforeach

                                    </ul>
                                </nav>
                            </div>
                            <!--/ End Main Menu -->
                        </div>

                        @if ($ctaPage)
                            <div class="col-lg-2 col-12">
                                <div class="get-quote">
                                    <a href="{{ route(config("pages.{$ctaPage->name}.route")) }}" class="btn">
                                        {{ $ctaPage->nav_title }}
                                    </a>
                                </div>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
        <!--/ End Header Inner -->
    </header>
    <!-- End Header Area -->
