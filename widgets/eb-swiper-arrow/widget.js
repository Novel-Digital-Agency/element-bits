(function($) {
    'use strict';

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
    var EBSwiperArrowHandler = function($scope, $) {
        var btn = $scope.find('[data-elebits-swiper-arrow]');
        var btnData = getBtnData( btn );

        if ( ! btnData || btnData.swiper_id === '' || !btnData.swiper_id ) {
            return;
        }

        console.log('btnData', btnData);

        btn.on('click', function(e) {
            e.preventDefault();
            if( ! swiperInstance ) {
                swiperInstance = document.querySelector('#' + btnData.swiper_id + '>.elementor-widget-container>.swiper').swiper;
            }

            if( ! swiperInstance ) {
                return;
            }

            btnData.direction === 'prev'
                ? swiperInstance.slidePrev()
                : swiperInstance.slideNext();
        });
    };

    // Register the handler
    $(window).on('elementor/frontend/init', function() {
        elementorFrontend.hooks.addAction('frontend/element_ready/eb-swiper-arrow.default', function($scope) {
            return new EBSwiperArrowHandler($scope);
        });
    });

})(jQuery);
