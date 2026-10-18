<!-- Start Contact Us -->
<section class="contact-us section">
    <div class="container">
        <div class="inner">
            <div class="row" data-form-container>
                <div class="col-lg-6">
                    <div class="contact-us-left">
                        <!--Start Google-map -->
                        <div id="myMap">
                            <iframe id="gmap_canvas"
                                src="https://maps.google.com/maps?q={{ $location['lat'] }},{{ $location['lng'] }}&amp;t=&amp;z=13&amp;ie=UTF8&amp;iwloc=&amp;output=embed"></iframe>
                        </div>
                        <!--/End Google-map -->
                    </div>
                </div>
                <div class="col-lg-6" data-form-content>
                    <div class="contact-us-form">
                        <h2>@t('forms', 'contact.title')</h2>
                        <p>@t('forms', 'contact.description')</p>
                        <!-- Form -->
                        <form class="form" method="post" action="@route('contact.store')" data-form>
                            @csrf
                            <input type="text" name="website" tabindex="-1" autocomplete="off" class="d-none">

                            <div class="row">
                                <div class="col-lg-6">
                                    <div class="form-group">
                                        <input type="text" name="name" placeholder="@t('forms', 'name')" required />
                                        <span class="form-error" data-error-for="name"></span>
                                    </div>
                                </div>
                                <div class="col-lg-6">
                                    <div class="form-group">
                                        <input type="email" name="email" placeholder="@t('forms', 'email')" required />
                                        <span class="form-error" data-error-for="email"></span>
                                    </div>
                                </div>
                                <div class="col-lg-6">
                                    <div class="form-group">
                                        <input type="text" name="phone" placeholder="@t('forms', 'phone')" required />
                                        <span class="form-error" data-error-for="phone"></span>
                                    </div>
                                </div>
                                <div class="col-lg-6">
                                    <div class="form-group">
                                        <input type="text" name="subject" placeholder="@t('forms', 'subject')" required />
                                        <span class="form-error" data-error-for="subject"></span>

                                    </div>
                                </div>
                                <div class="col-lg-12">
                                    <div class="form-group">
                                        <textarea name="message" placeholder="@t('forms', 'message')" required></textarea>
                                        <span class="form-error" data-error-for="message"></span>
                                    </div>
                                </div>
                                <div class="col-12">
                                    <div class="form-group login-btn">
                                        <button class="btn" type="submit">@t('forms', 'contact.send')</button>
                                    </div>
                                    <span class="form-error" data-form-error hidden></span>
                                </div>
                            </div>
                        </form>
                        <!--/ End Form -->
                    </div>
                </div>
                <div class="col-lg-6 mail-seccess" data-form-success hidden>
                    <div class="success-inner">

                        <h1>
                            <i class="icofont-send-mail"></i>
                            <span>@t('forms', 'contact.success.title')</span>

                        </h1>

                        <p>@t('forms', 'contact.success.description')</p>

                    </div>
                </div>
            </div>
        </div>
        <div class="contact-info">
            <div class="row justify-content-center">
                @settingAvailable('contact:phone')
                    <!-- single-info -->
                    <div class="col-lg-4 col-12">
                        <div class="single-info">
                            <i class="icofont icofont-ui-call"></i>
                            <div class="content">
                                <h3><a href="tel:@setting('contact:phone')">@setting('contact:phone')</a></h3>
                            </div>
                        </div>
                    </div>
                    <!--/End single-info -->
                @endsettingAvailable

                @settingAvailable('contact:email')
                    <!-- single-info -->
                    <div class="col-lg-4 col-12">
                        <div class="single-info">
                            <i class="fa fa-envelope"></i>
                            <div class="content">
                                @php($email = app(\App\Services\SettingService::class)->get('contact:email'))
                                <h3><a href="mailto:@setting('contact:email')">{{ str($email)->limit(20) }}</a></h3>
                            </div>
                        </div>
                    </div>
                    <!--/End single-info  -->
                @endsettingAvailable
                @settingAvailable('contact:address')
                <!-- single-info -->
                <div class="col-lg-4 col-12">
                    <div class="single-info">
                        <i class="icofont-google-map"></i>
                        <div class="content">
                            @php($address = app(\App\Services\SettingService::class)->get('contact:address'))
                            <h3>{{ str($address)->limit(20) }}</h3>
                        </div>
                    </div>
                </div>
                <!--/End single-info -->
                @endsettingAvailable
            </div>
        </div>
    </div>
</section>
<!--/ End Contact Us -->
