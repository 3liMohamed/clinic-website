    <!-- Start Newsletter Area -->
    <section class="newsletter section">
        <div class="container">
            <div class="row align-items-center" data-form-container>
                <div class="col-lg-6 col-12" data-form-content>
                    <!-- Start Newsletter Form -->
                    <div class="subscribe-text">
                        <h6>Subscribe for newsletter</h6>
                        <p class="">
                            Cu qui soleat partiendo urbanitas. Eum aperiri indoctum eu,<br />
                            homero alterum.
                        </p>
                    </div>
                    <!-- End Newsletter Form -->
                </div>
                <div class="col-lg-6 col-12" data-form-content>
                    <!-- Start Newsletter Form -->
                    <div class="subscribe-form">
                        <form action="@route('subscribe')" method="post" class="newsletter-inner" data-form>
                            @csrf
                            <input type="text" name="website" tabindex="-1" autocomplete="off" class="d-none">
                            <input name="email" placeholder="Your email address" class="common-input"
                                onfocus="this.placeholder = ''" onblur="this.placeholder = 'Your email address'"
                                required type="email" {{-- required --}} />
                            <span class="form-error" data-error-for="email"></span>
                            <span class="form-error" data-form-error hidden></span>
                            <button type="submit" class="btn">Subscribe</button>
                        </form>
                    </div>
                    <!-- End Newsletter Form -->
                </div>
                <div class="col-12 success-inner" data-form-success hidden>
                    <h1>
                        <i class="icofont-send-mail"></i>
                        <span>Subscription Successful!</span>
                    </h1>

                    <p>
                        Thank you for subscribing to our newsletter.
                    </p>
                </div>
            </div>
        </div>
    </section>
    <!-- /End Newsletter Area -->
