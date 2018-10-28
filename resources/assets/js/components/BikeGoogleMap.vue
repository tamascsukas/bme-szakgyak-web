<template>
  <div class="google-map" :id="id"></div>
</template>

<script>
  var MarkerClusterer = require('@google/markerclusterer');

  export default {
    name: 'google-map',
    props: {
      id: {
        type: String,
        required: true
      },
      options: {
        type: Object,
        default: function () {
          return {
            markers: [],
            center: {
              lat: 47.497800,
              lon: 19.040318
            },
            zoom: 12
          };
        }
      }
    },
    data() {
      return {
        map: {
          instance: null,
          bounds: null,
          markers: [],
          markerCluster: null
        }
      };
    },
    mounted: function () {
      //this.map.bounds = new google.maps.LatLngBounds();

      // Setting up the map
      const element = document.getElementById(this.id);
      const options = {
        center: new google.maps.LatLng(this.options.center.lat, this.options.center.lon),
        zoom: this.options.zoom,
        styles: [{
         featureType: "poi",
         stylers: [
            { visibility: "off" }
          ]   
        }],
        mapTypeControl: false,
        mapTypeId: google.maps.MapTypeId.ROADMAP,
        streetViewControl: false
      };
      this.map.instance = new google.maps.Map(element, options);

      // Setting up a marker clusterer
      this.map.markerCluster = new MarkerClusterer(this.map.instance, [],
            {imagePath: 'https://developers.google.com/maps/documentation/javascript/examples/markerclusterer/m'});

      // Add listener for the map
      let vm = this;
      this.map.instance.addListener('idle', function() {
        var bounds = vm.map.instance.getBounds();

        if (bounds == null) {
          return;
        }

        var data = {
          sw: {
            lat: bounds.getSouthWest().lat(),
            lon: bounds.getSouthWest().lng()
          },
          ne: {
            lat: bounds.getNorthEast().lat(),
            lon: bounds.getNorthEast().lng()
          }
        };

        vm.$emit('bounds-changed', data);
      });

      this.addMarkers();
    },
    methods: {
      addMarkers: function () {
        this.options.markers.forEach((mrkr) => {
          const icon = {
              url: BASE_SITE_URL + "img/arrows.svg?id=" + mrkr.id,
              fillOpacity: 1.0,
              size: new google.maps.Size(32, 32),
              anchor: new google.maps.Point(24,48),
              scaledSize: new google.maps.Size(48,48),
              rotation: 45

          }
          const position = new google.maps.LatLng(mrkr.lat, mrkr.lon);
          const marker = new google.maps.Marker({ 
            position,
            icon: icon,
            map: this.map.instance,
            draggable: false,
            title: mrkr.name
          });

          // Add listener for the marker
          let vm = this;
          marker.addListener('click', function() {
            vm.$emit('click', mrkr.id);
          });

          this.map.markers.push(marker);
          // Make the map big enough to contain all the markers
          //this.map.instance.fitBounds(this.map.bounds.extend(position));

          //Rotate marker background
          google.maps.event.addListenerOnce(this.map, 'idle', function() {
            $('img[src="' + BASE_SITE_URL + "img/arrows.svg?id=" + mrkr.id + '"]').css({
                'transform': 'rotate(' + 90 + 'deg)'
              });
            alert(1);
          });
        });
      }
    },
    watch: {
      'options.markers': function () {
        //this.map.bounds = new google.maps.LatLngBounds();
        this.map.markerCluster.clearMarkers();
        this.map.markers.forEach((mrkr) => {
          mrkr.setMap(null);
        });
        this.map.markers = [];

        this.addMarkers();

        // Add markers to the clusterer
        this.map.markerCluster.addMarkers(this.map.markers);
      },
      'options.center': function () {
        this.map.instance.setCenter(new google.maps.LatLng(this.options.center.lat, this.options.center.lon));
      },
      'options.zoom': function () {
        this.map.instance.setZoom(this.options.zoom);
      }
    }
  }
</script>

<style type="text/css">
  .google-map {
    width: 100%;
    height: 100%;
    margin: 0 auto;
    background: gray;
  }
</style>