<!-- Start Appointment -->
<section class="appointment single-page">
    <div class="container">
        <div class="row" data-form-container>
            <div class="col-lg-7 col-md-12 col-12" data-form-content>

                <div data-form-content>
                    <div class="title">
                        <h3>@t('forms', 'appointment.title')</h3>
                        <p>@t('forms', 'appointment.description')</p>
                    </div>
                    <form class="form" action="@route('appointment.store')" method="POST" data-form>
                        @csrf
                        <input type="text" name="website" tabindex="-1" autocomplete="off" class="d-none">
                        <div class="row">
                            <div class="col-lg-6 col-md-6 col-12">
                                <div class="form-group">
                                    <input name="name" type="text" placeholder="@t('forms', 'name')" required />
                                    <span class="form-error" data-error-for="name"></span>
                                </div>
                            </div>
                            <div class="col-lg-6 col-md-6 col-12">
                                <div class="form-group">
                                    <input name="email" type="email" placeholder="@t('forms', 'email')" required />
                                    <span class="form-error" data-error-for="email"></span>
                                </div>
                            </div>
                            <div class="col-lg-6 col-md-6 col-12">
                                <div class="form-group">
                                    <input name="phone" type="text" placeholder="@t('forms', 'phone')" required />
                                    <span class="form-error" data-error-for="phone"></span>
                                </div>
                            </div>
                            <div class="col-lg-6 col-md-6 col-12">
                                <div class="form-group">
                                    <select name="doctor_id" class="form-control wide">
                                        <option value="" selected>@t('forms', 'doctor')</option>

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
                                    <textarea name="message" placeholder="@t('forms', 'message')"></textarea>
                                    <span class="form-error" data-error-for="message"></span>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-lg-5 col-md-4 col-12">
                                <div class="form-group">
                                    <div class="button">
                                        <button type="submit" class="btn">
                                            @t('forms', 'appointment.send')
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

            </div>
            <div class="col-lg-7 col-md-12 col-12 mail-seccess" data-form-success hidden>
                <div class="success-inner">

                    <h1>
                        <i class="icofont-send-mail"></i>
                        <span>@t('forms', 'appointment.success.title')</span>
                    </h1>

                    <p>@t('forms', 'appointment.success.description')</p>

                </div>
            </div>
            <div class="col-lg-5 col-md-12">
                <div class="work-hour">
                    <h3>@t('sections', 'appointment.working_hours')</h3>

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
</section>
<!--/End Appointment -->
