(function($) {
    'use strict';

    ///--------------------------------------
    var getBtnData = function(btn) {
        if (btn.length && btn[0].dataset && btn[0].dataset.elebitsSwiperArrow) {
            return JSON.parse(btn[0].dataset.elebitsSwiperArrow);
        }
        return null;
    };

    var swiperInstance = null;

    /**
     * EB Swiper Arrow Widget
     * 
     * Handles the interaction for the Swiper Arrow widget.
     */
    ///--------------------------------------
    var EBSwiperArrowHandler = function($scope, $) {
        var btn = $scope.find('[data-elebits-swiper-arrow]');
        var btnData = getBtnData( btn );

        if ( ! btnData || btnData.swiper_id === '' || ! btnData.swiper_id ) {
            return;
        }

        // Initialize swiper instance
        ///--------------------------------------
        var initSwiperInstance = function() {
            if (!swiperInstance) {
                swiperInstance = document.querySelector('#' + btnData.swiper_id + '>.elementor-widget-container>.swiper')?.swiper;
            }
            return swiperInstance;
        };

        // Handle go to specific slide functionality
        ///--------------------------------------
        var handleGoToSlide = function() {
            var swiper = initSwiperInstance();
            if (!swiper) return;
            
            if (btnData.go_to_slide && btnData.go_to_slide > 0) {
                // Subtract 1 from the input value since Swiper is 0-indexed but users think in 1-indexed
                var slideIndex = parseInt(btnData.go_to_slide) - 1;
                swiper.slideTo(slideIndex);
            }
        };

        btn.on('click', function(e) {
            e.preventDefault();
            var swiper = initSwiperInstance();
            
            if (!swiper) return;
            
            // If go_to_slide is set and greater than 0, go to that slide
            if (btnData.go_to_slide && btnData.go_to_slide >= 0) {
                handleGoToSlide();
            } else {
                // Otherwise use the regular prev/next functionality
                btnData.direction === 'prev'
                    ? swiper.slidePrev()
                    : swiper.slideNext();
            }
        });
        
        // Initial go to slide if specified
        if (btnData.go_to_slide && btnData.go_to_slide > 0) {
            // Wait for swiper to be fully initialized
            setTimeout(handleGoToSlide, 500);
        }
    };

    // Register the handler
    $(window).on('elementor/frontend/init', function() {
        elementorFrontend.hooks.addAction('frontend/element_ready/eb-swiper-arrow.default', function($scope) {
            return new EBSwiperArrowHandler($scope);
        });
    });

})(jQuery);
