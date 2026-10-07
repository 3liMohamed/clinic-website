<!-- Start Appointment -->
<section class="appointment">
    <div class="container">
        <div class="row">
            <div class="col-lg-12">
                <div class="section-title">

                    <h2>@t('sections', 'appointment.title')</h2>
                    <img src="@galleryImage('sections:sections_image')" alt="@galleryTitle('sections:sections_image')" />
                    <p>@t('sections', 'appointment.description')</p>
                </div>
            </div>
        </div>
        <div class="row" data-form-container>
            <div class="col-lg-6 col-md-12 col-12" data-form-content>
                <form class="form" action="@route('appointments.store')" method="POST" data-form>
                    <div class="row">
                        <div class="col-lg-6 col-md-6 col-12">
                            <div class="form-group">
                                <input name="name" type="text" placeholder="Name" />
                                <span class="form-error" data-error-for="name"></span>
                            </div>
                        </div>
                        <div class="col-lg-6 col-md-6 col-12">
                            <div class="form-group">
                                <input name="email" type="email" placeholder="Email" />
                                <span class="form-error" data-error-for="email"></span>
                            </div>
                        </div>
                        <div class="col-lg-6 col-md-6 col-12">
                            <div class="form-group">
                                <input name="phone" type="text" placeholder="Phone" />
                                <span class="form-error" data-error-for="phone"></span>
                            </div>
                        </div>
                        <div class="col-lg-6 col-md-6 col-12">
                            <div class="form-group">
                                <select name="doctor_id" class="form-control wide">
                                    <option value="">Doctor</option>

                                    @foreach ($doctors as $doctor)
                                        <option value="{{ $doctor->id }}">
                                            {{ $doctor->name }}
                                        </option>
                                    @endforeach
                                </select>

                                <span class="form-error" data-error-for="doctor_id"></span>
                            </div>
                        </div>

                        <div class="col-lg-12 col-md-12 col-12">
                            <div class="form-group">
                                <textarea name="message" placeholder="Write Your Message Here....."></textarea>
                                <span class="form-error" data-error-for="message"></span>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-lg-5 col-md-4 col-12">
                            <div class="form-group">
                                <div class="button">
                                    <button type="submit" class="btn">
                                        Book An Appointment
                                    </button>
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-7 col-md-8 col-12">
                            <h2 class="form-error" data-form-error hidden></h2>
                        </div>
                    </div>

                </form>
            </div>
            <div class="col-lg-6 col-md-12 col-12 mail-seccess" data-form-success hidden>
                <div class="success-inner">

                    <h1>
                        <i class="icofont-send-mail"></i>
                        <span>
                            Your Mail Sent Successfully!
                        </span>
                    </h1>

                    <p>
                        Your appointment request has been sent
                        successfully. We will contact you shortly.
                    </p>

                </div>
            </div>
            <div class="col-lg-6 col-md-12">
                <div class="appointment-image">
                    <img src="@galleryImage('sections:appointment_image')" alt="@galleryTitle('sections:appointment_image')" />
                </div>
            </div>
        </div>
    </div>
</section>
<!-- End Appointment -->
