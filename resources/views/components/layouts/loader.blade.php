    <!-- Preloader -->
    <div class="preloader">
        <div class="loader">
            <div class="loader-outter"></div>
            <div class="loader-inner"></div>

            <div class="indicator">
                <svg width="16px" height="12px">
                    <polyline id="back" points="1 6 4 6 6 11 10 1 12 6 15 6"></polyline>
                    <polyline id="front" points="1 6 4 6 6 11 10 1 12 6 15 6"></polyline>
                </svg>
            </div>
        </div>
    </div>
    <!-- End Preloader -->

    <!-- Mediplus Color Plate -->
    <div class="color-plate">
        <a href="{{ route('locale', app()->getLocale() === 'ar' ? 'en' : 'ar') }}" class="color-plate-icon"
            aria-label="{{ app()->getLocale() === 'ar' ? 'Switch to English' : 'التبديل إلى العربية' }}"><i
                class="fa fa-language"></i></a>
    </div>
    <!-- /End Color Plate -->
