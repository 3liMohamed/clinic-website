@if ($doctors->isNotEmpty())
    <!-- Start Team -->
    <section id="team" class="team section single-page">
        <div class="container">
            <div class="row">
                @foreach ($doctors as $doctor)
                    <div class="col-lg-4 col-md-6 col-12">
                        <!-- Single Team -->
                        <div class="single-team">
                            <div class="t-head">
                                <img src="{{ $doctor->image_url }}" alt="{{ $doctor->name }}" />
                            </div>
                            <div class="t-bottom">
                                <p>{{ $doctor->department->name }}</p>
                                <h2><a
                                        @if ($doctor->slug !== null) href="@route('doctor.show', $doctor->slug)" @else aria-disabled="true" @endif>
                                        {{ $doctor->name }}
                                    </a></h2>
                            </div>
                        </div>
                        <!-- End Single Team -->
                    </div>
                @endforeach

            </div>
        </div>
    </section>
    <!--/ End Team -->
@endif
