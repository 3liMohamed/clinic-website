    <!-- Footer Area -->
    <footer id="footer" class="footer">
        <!-- Footer Top -->
        <div class="footer-top">
            <div class="container">
                <div class="row">
                    <div class="col-lg-4 col-md-6 col-12">
                        <div class="single-footer">
                            <h2>@t('footer', 'about_title')</h2>
                            <p>@t('footer', 'about_description')</p>
                            <!-- Social -->
                            <ul class="social">
                                @foreach (['facebook', 'instagram', 'x', 'youtube'] as $social)
                                    @settingAvailable("social:{$social}")
                                        <li>
                                            <a href="@setting("social:{$social}")" target="_blank" rel="noopener noreferrer">
                                                <i
                                                    class="icofont-{{ match ($social) {
                                                        'x' => 'x',
                                                        'youtube' => 'youtube-play',
                                                        default => $social,
                                                    } }}"></i>

                                            </a>
                                        </li>
                                    @endsettingAvailable
                                @endforeach
                            </ul>
                            <!-- End Social -->
                        </div>
                    </div>
                    <div class="col-lg-4 col-md-6 col-12">
                        <div class="single-footer f-link">
                            <h2>@t('footer', 'quick_links')</h2>
                            <div class="row">
                                @foreach ($footerPages->chunk(7) as $pages)
                                    <div class="col-lg-6 col-md-6 col-12">
                                        <ul>
                                            @foreach ($pages as $page)
                                                <li>
                                                    <a href="@route(config("pages.{$page->name}.route"))"><i class="fa fa-caret-right"
                                                            aria-hidden="true"></i>{{ $page->nav_title }}</a>
                                                </li>
                                            @endforeach
                                        </ul>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-4 col-md-6 col-12">
                        <div class="single-footer">
                            <h2>@t('footer', 'open_hours_title')</h2>
                            <p>@t('footer', 'open_hours_description')</p>

                            <ul class="time-sidual">
                                @foreach ($workingDays as $workingDay)
                                    @if ($workingDay['is_visible'] ?? false)
                                        <li class="day">
                                            {{ \App\Enums\WorkingDay::from($workingDay['day'])->getLabel() }}

                                            <span>
                                                {{ \Carbon\Carbon::createFromFormat('H:i', $workingDay['start_time'])->format('g:i A') }}
                                                -
                                                {{ \Carbon\Carbon::createFromFormat('H:i', $workingDay['end_time'])->format('g:i A') }}
                                            </span>
                                        </li>
                                    @endif
                                @endforeach
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!--/ End Footer Top -->
        <!-- Copyright -->
        <div class="copyright">
            <div class="container">
                <div class="row">
                    <div class="col-lg-12 col-md-12 col-12">
                        <div class="copyright-content">
                            <p>
                                © Copyright 2026 All rights reserved. Developed by Ali Mohamed
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!--/ End Copyright -->
    </footer>
    <!--/ End Footer Area -->
