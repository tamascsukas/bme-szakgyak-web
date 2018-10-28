
/**
 * First we will load all of this project's JavaScript dependencies which
 * includes Vue and other libraries. It is a great starting point when
 * building robust, powerful web applications using Vue and Laravel.
 */

global.$ = global.jQuery = require('jquery');
window.Vue = require('vue');
require('bootstrap');
// bootstrap-notify
require('./bootstrap-notify.js');
$.notifyDefaults({
  placement: {
    from: "bottom"
  },
  animate:{
    enter: 'animated fadeInRight',
    exit: 'animated fadeOutRight'
  },
  newest_on_top: true,
  delay: 3500
});

// Global network error handler function
window.handleNetworkError = function (response) {
  // reload page if not authorized
  if (response.status == 401) {
    alert('Az oldal időközben kiléptette a fiókjából. Kérjük jelentkezzen be újra!');
    location.reload();
  }

  if (!response.ok) {
    // Something bad happened
    // create notification
    $.notify({
      message: 'A kért adatok betöltése közben hiba lépett fel. Kérjük ellenőrizd, hogy nem szakadt-e meg az internetkaőcsolat!'
    }, {
      type: 'danger',
      delay: 10000
    });
  }

  return response;
};

// Pages
var listDevicesPageComponent = require('./components/pages/ListDevices.vue');
var deviceDetailsPageComponent = require('./components/pages/DeviceDetails.vue');

app = new Vue({
  el: '#app',
  components: {
    'list-devices': listDevicesPageComponent,
    'device-details': deviceDetailsPageComponent
  },
  data () {
    return {
      currentPage: 'ListDevices',
      selectedDevice: 0
    }
  },
  methods: {
    showPage: function(page) {
      this.currentPage = page;
    },
    showDevice: function(id) {
      this.selectedDevice = id;
      this.showPage('DeviceDetails')
    }
  }
});