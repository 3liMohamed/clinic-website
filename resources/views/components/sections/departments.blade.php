@if ($departments->isNotEmpty())
    <!-- Start Departments -->
    <section class="departments section">
        <div class="container">
            <div class="row">
                <div class="col-lg-12">
                    <div class="section-title">
                        <h2>@t('sections', 'departments.title')</h2>
                        <img src="@galleryImage('sections:sections_image')" alt="@galleryTitle('sections:sections_image')" />
                        <p>@t('sections', 'departments.description')</p>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-12">
                    <div class="department-tab tab-menu">
                        <!-- Tab Menu -->
                        <div class="list-group nav nav-tabs" id="list-tab" role="tablist">
                            @foreach ($departments as $department)
                                <a class="list-group-item nav-item {{ $loop->first ? 'active' : '' }}"
                                    data-bs-toggle="list" href="#department-{{ $department->id }}" role="tab">
                                    <i class="{{ $department->icon }}"></i>
                                    <span class="first">{{ $department->name }}</span>
                                </a>
                            @endforeach
                        </div>
                        <!-- Tab Details -->
                        <div class="tab-details">
                            <div class="tab-content" id="nav-tabContent">
                                @foreach ($departments as $department)
                                    <div class="tab-pane fade {{ $loop->first ? 'show active' : '' }}"
                                        id="department-{{ $department->id }}" role="tabpanel">
                                        <div class="row">

                                            <div class="col-lg-6">
                                                <div class="department-left">

                                                    <h3>{{ $department->name }}</h3>

                                                    <p>{{ $department->description }}</p>

                                                    @if ($department->items->isNotEmpty())
                                                        <ul class="list">
                                                            @foreach ($department->items as $item)
                                                                <li>
                                                                    <i class="fa fa-check"></i>
                                                                    {{ $item->text }}
                                                                </li>
                                                            @endforeach
                                                        </ul>
                                                    @endif

                                                </div>
                                            </div>

                                            <div class="col-lg-6">
                                                <div class="department-right">
                                                    <img src="{{ $department->image_url }}"
                                                        alt="{{ $department->name }}" />
                                                </div>
                                            </div>

                                        </div>
                                    </div>
                                @endforeach

                            </div>
                        </div>
                        <!-- End Tab Details -->
                    </div>
                </div>
            </div>
        </div>
    </section>
    <!-- End Departments -->
@endif
