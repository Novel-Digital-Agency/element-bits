;(function($) {
  var widget = function($scope, $) {
    var wrapper = $scope.find('.eb-widget-wrapper');
    var data = wrapper.data('elebits');

    // Check if required data exists
    if (!data || !data.map_id) {
      console.error('Required map data is missing');
      return;
    }

    // Defer Google Maps initialization to ensure API is loaded
    setTimeout(function() {
      // Check if Google Maps API is loaded
      if (typeof google === 'undefined' || typeof google.maps === 'undefined') {
        console.error('Google Maps API is not loaded');
        return;
      }

      initMap();
    }, 100);

    ///--------------------------------------
    function initMap() {
      try {
        // Get map container
        const mapElement = document.getElementById(data.map_id);
        if (!mapElement) {
          console.error('Map container not found');
          return;
        }

        const loc = {
          lat: parseFloat(data.lat) || 0,
          lng: parseFloat(data.lng) || 0
        };

        // Initialize map
        const mapOptions = {
          zoom: parseInt(data.zoom) || 12,
          center: loc,
          scrollwheel: Boolean(data.scroll),
          mapTypeControl: true,
          fullscreenControl: true
        };
        
        // Get map styles safely
        const mapStyle = wrapper.data('eb-map-style');
        if (mapStyle) {
          mapOptions.styles = mapStyle;
        }

        const map = new google.maps.Map(mapElement, mapOptions);

        // Add marker
        const markerOptions = {
          position: loc,
          map: map
        };

        // Only add custom icon if provided
        if (data.icon) {
          markerOptions.icon = data.icon;
        }

        const marker = new google.maps.Marker(markerOptions);

        // Add info window if content provided
        if (data.info) {
          const infowindow = new google.maps.InfoWindow({
            content: data.info
          });

          marker.addListener('click', function() {
            infowindow.open(map, marker);
          });

          // Optional: Close infowindow when clicking on map
          google.maps.event.addListener(map, 'click', function() {
            infowindow.close();
          });
        }

        // Handle window resize
        google.maps.event.addDomListener(window, 'resize', function() {
          const center = map.getCenter();
          google.maps.event.trigger(map, 'resize');
          map.setCenter(center);
        });
      } catch (error) {
        console.error('Error initializing map:', error);
      }
    }
  };

  ///--------------------------------------
  // Initialize widget when it's ready in the DOM
  $(window).on('elementor/frontend/init', function() {
    elementorFrontend.hooks.addAction('frontend/element_ready/eb-google-map.default', widget);
  });
})(jQuery);
