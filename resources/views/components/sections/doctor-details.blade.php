<!-- Doctor Details -->
<div class="doctor-details-area section">
    <div class="container">
        <div class="row">
            <div class="col-lg-5">
                <div class="doctor-details-item doctor-details-left">
                    <img src="{{ $entity->image_url }}" alt="{{ $entity->name }}" />
                    <div class="doctor-details-contact">
                        <h3>@t('sections', 'doctor.contact_info')</h3>
                        <ul class="basic-info">
                            @if ($entity->phone !== '#')
                                <li>
                                    <i class="icofont-ui-call"></i>
                                    {{ $entity->phone }}
                                </li>
                            @endif

                            @if ($entity->email !== '#')
                                <li>
                                    <i class="icofont-ui-message"></i>
                                    {{ $entity->email }}
                                </li>
                            @endif


                            @if ($entity->address !== '#')
                                <li>
                                    <i class="icofont-location-pin"></i>
                                    {{ $entity->address }}
                                </li>
                            @endif


                        </ul>
                        <!-- Social -->
                        <ul class="social">
                            @foreach ($entity->socials as $social)
                                @if ($social['is_visible'] ?? false)
                                    <li>
                                        <a href="{{ $social['url'] }}" target="_blank" rel="noopener">
                                            <i class="{{ $social['icon'] }}"></i>
                                        </a>
                                    </li>
                                @endif
                            @endforeach
                        </ul>
                        <!-- End Social -->
                        <div class="doctor-details-work">
                            <h3>@t('sections', 'doctor.working_hours')</h3>
                            <ul class="time-sidual">
                                @foreach ($entity->workingHours as $workingHour)
                                    <li class="day">
                                        {{ $workingHour->day->getLabel() }}

                                        <span>
                                            <span>
                                                {{ $workingHour->start_time->format('g:i A') }}
                                                -
                                                {{ $workingHour->end_time->format('g:i A') }}
                                            </span>
                                        </span>
                                    </li>
                                @endforeach
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-lg-7">
                <div class="doctor-details-item">
                    <div class="doctor-details-right">
                        <div class="doctor-name">
                            <h3 class="name">{{ $entity->name }}</h3>
                            <p class="deg">{{ $entity->department->name }}</p>
                        </div>
                        <div class="doctor-details-biography">
                            {!! $entity->biography !!}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- End Doctor Details -->
</div>
