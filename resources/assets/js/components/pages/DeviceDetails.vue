<template>
  <div>
    <div class="container">
      <div class="row">
        <div class="col-6 content-box">
          <button type="button" class="btn btn-outline-secondary btn-sm" @click="goBack">&laquo; Vissza</button>
        </div>
        <div v-if="!loading" class="col-6 content-box text-right">
          <button type="button" class="btn btn-outline-danger btn-sm" @click="deleteDeviceDetails">Töröl</button>
          <button type="button" class="btn btn-outline-success btn-sm" @click="showDeviceDetailsModal">Szerkeszt</button>
        </div>
      </div>
      <div v-if="loading" class="row">
        <div class="col-12 content-box">
          <loading-spinner></loading-spinner>
        </div>
      </div>
      <div v-if="!loading" class="row">
        <div class="col-6 content-box">
          <h3>{{ device.name }}</h3>
          <h5>EUI: {{ device.eui }}</h5>
        </div>
        <div class="col-6 content-box text-right">
          <p>
            Utoljára aktív 
            <span v-if="device.last_seen_hours===null" class="font-weight-normal">-</span>
            <span v-else-if="device.last_seen_hours>0" class="badge badge-primary">
              {{ device.last_seen_hours }} órája
            </span>
            <span v-else class="badge badge-primary">
              < 1 órája
            </span>
            <br />
            Akku töltöttség 
            <span v-if="device.battery_level" class="badge badge-secondary" :class="{'badge-success': (device.battery_level > 0.2), 'badge-warning': (0.1 < device.battery_level && device.battery_level <= 0.2), 'badge-danger': (device.battery_level <= 0.1)}">
              {{ Math.round(device.battery_level * 100) }}%
            </span>
            <span v-else class="font-weight-normal">-</span>
          </p>
        </div>
      </div>
      <div v-if="!loading" class="row">
        <div class="col-sm-12 col-md-8">
          <div class="row">
            <div class="col-12 content-box">
              <h5>Utolsó 3 hét statisztikája</h5>
              <chart id="three-weeks-chart" :data="lastThreeWeeksChart"></chart>
            </div>
          </div>
          <div class="row">
            <div class="col-12 content-box">
              <h5>Egyedi statisztika</h5>
              <chart-datepicker id="custom-traffic-stat" @callback="updateCustomTrafficStat"></chart-datepicker>
              <chart id="custom-traffic-chart" :data="customTrafficChart"></chart>
            </div>
          </div>
        </div>
        <div class="col-sm-12 col-md-4">
          <div class="row">
            <div class="col-12 content-box">
              <h5>Akkuhasználat</h5>
              <chart id="battery-chart" :data="batteryChart"></chart>
            </div>
          </div>
          <div v-if="isMapVisible" class="row">
            <div class="col-12 content-box">
              <h5>Térkép</h5>
              <google-map id="details-devices-map" :options="bikeMapOptions"></google-map>
            </div>
          </div>
        </div>
      </div>
    </div>

    <device-details-modal id="deviceDetailsModal" title="Eszköz módosítása" :options="deviceDetailsModalOptions" @success-callback="deviceDetailsModalSuccessCallback" @dismiss-callback="deviceDetailsModalDismissCallback"></device-details-modal>
  </div>
</template>


<script>
  var deviceDetailsModalComponent = require('./../DeviceDetailsModal.vue');
  var chartDatePickerComponent = require('./../ChartDatePicker.vue');
  var loadingSpinnerComponent = require('./../LoadingSpinner.vue');
  var chartComponent = require('./../Chart.vue');
  var googleMapComponent = require('./../BikeGoogleMap.vue');

  export default {
    name: 'list-devices',
    components: {
      'device-details-modal': deviceDetailsModalComponent,
      'loading-spinner': loadingSpinnerComponent,
      'chart': chartComponent,
      'chart-datepicker': chartDatePickerComponent,
      'google-map': googleMapComponent
    },
    props: {
      deviceId: {
        type: Number,
        required: true
      }
    },
    data() {
      return {
        loading: true,
        device: {},

        deviceDetailsModalOptions: {
          isHidden: true,
          device: {},
          errors: []
        },

        bikeMapOptions: {
          markers: [],
          center: {
            lat: 47.497800,
            lon: 19.040318
          },
          zoom: 14
        },

        // Get more from https://www.chartjs.org/docs/latest/
        batteryChart: {
          type: 'line',
          data: {
            labels: [],
            datasets: [{
              label: 'Töltöttség (%)',
              data: [],
              fill: false,
              backgroundColor: 'rgba(255, 206, 86, 0.2)',
              borderColor: 'rgba(255, 206, 86, 1)',
              borderWidth: 1
            }]
          },
          options: {
            tooltips: {
              mode: 'index',
              intersect: false,
              displayColors: false
            },
            scales: {
              yAxes: [{
                ticks: {
                  beginAtZero:true,
                  min: 0,
                  max: 100
                }
              }]
            },
            legend: {
              position: 'bottom'
            }
          }
        },

        lastThreeWeeksChart: {
          type: 'bar',
          data: {
            labels: [],
            datasets: [{
              label: 'Kerékpár (db)',
              data: [],
              fill: true,
              backgroundColor: 'rgba(54, 162, 235, 0.2)',
              borderColor: 'rgba(54, 162, 235, 1)',
              borderWidth: 1
            }]
          },
          options: {
            tooltips: {
              mode: 'index',
              intersect: false,
              displayColors: false
            },
            scales: {
              yAxes: [{
                ticks: {
                  beginAtZero:true,
                  min: 0
                }
              }]
            },
            legend: {
              position: 'bottom'
            }
          }
        },

        customTrafficChart: {
          type: 'bar',
          data: {
            labels: [],
            datasets: [{
              label: 'Kerékpár (db)',
              data: [],
              fill: true,
              backgroundColor: 'rgba(54, 162, 235, 0.2)',
              borderColor: 'rgba(54, 162, 235, 1)',
              borderWidth: 1
            }]
          },
          options: {
            tooltips: {
              mode: 'index',
              intersect: false,
              displayColors: false
            },
            scales: {
              yAxes: [{
                ticks: {
                  beginAtZero:true,
                  min: 0
                }
              }]
            },
            legend: {
              position: 'bottom'
            }
          }
        }
      }
    },
    activated() {
      this.fetchData();
      
      // clear custom traffic stats
      this.customTrafficChart.data.labels = [];
      $.each(this.customTrafficChart.data.datasets, function (key, val) {
        val.data = [];
      });
    },
    methods: {
      fetchData: function () {
        this.loading = true;

        let vm = this;
        fetch('api/device/' + this.deviceId)
          .then(window.handleNetworkError)
          .then(res => res.json())
          .then(res => {
            if (res.result != 'success') {
              // Something bad happened
              // create notification
              $.notify({
                message: 'Nem sikerült betölteni az eszköz adatait.'
              }, {
                type: 'danger',
              });

              return;
            }

            this.batteryChart.data.labels = res.data.battery.labels;
            this.batteryChart.data.datasets[0].data = res.data.battery.data;
            delete res.data.battery;

            this.lastThreeWeeksChart.data.labels = res.data.threeweeksstat.labels;
            this.lastThreeWeeksChart.data.datasets[0].data = res.data.threeweeksstat.data;
            delete res.data.threeweeksstat;

            this.device = res.data
            
            this.loading = false;
          })
          .catch(err => console.log('Error while fetching device details: ' + err));
      },

      goBack: function () {
        // go back
        this.$emit('callback');
      },

      showDeviceDetailsModal: function () {
        this.deviceDetailsModalOptions.isHidden = false;
      },
      deviceDetailsModalSuccessCallback: function (data) {
        let constData = data;
        fetch('api/devices', {
            method: 'put',
            body: JSON.stringify(data),
            headers: {
              'content-type': 'application/json'
            }
          })
          .then(window.handleNetworkError)
          .then(res => res.json())
          .then(data => {
            if (data.result != 'success') {
              this.deviceDetailsModalOptions.errors = data.errors;
              return;
            }

            // hide modal
            this.deviceDetailsModalOptions.isHidden = true;

            // create notification
            $.notify({
              message: 'Az eszköz adatait sikeresen elmentettük.'
            }, {
              type: 'success',
            });

            // refresh device details
            this.device = constData;
          })
          .catch(err => console.log(err));
      },
      deviceDetailsModalDismissCallback: function () {
        this.deviceDetailsModalOptions.device = Object.assign({}, this.device);
        this.deviceDetailsModalOptions.isHidden = true;
      },

      deleteDeviceDetails: function() {
        if (confirm('Biztos törölni szeretnéd az eszköz adatait a rendszerből?')) {
          fetch('api/device/' + this.deviceId, {
              method: 'delete',
            })
            .then(window.handleNetworkError)
            .then(res => res.json())
            .then(data => {
              if (data.result != 'success') {
                // Something bad happened
                // create notification
                $.notify({
                  message: 'Nem sikerült törölni az eszköz adatait.'
                }, {
                  type: 'danger',
                });

                return;
              }

              // create notification
              $.notify({
                message: 'Az eszköz adatait sikeresen töröltük.'
              }, {
                type: 'success',
              });

              // go back
              this.$emit('callback');
            })
            .catch(err => console.log(err));
        }
      },

      updateCustomTrafficStat: function (data) {
        fetch('api/device/' + this.deviceId + '/traffic-stats?' + $.param(data))
          .then(window.handleNetworkError)
          .then(res => res.json())
          .then(res => {
            if (res.result != 'success') {
              // Something bad happened
              // create notification
              let error_message = (res.result == 'date_not_specified') ? 'A statisztika betöltéséhez kötelező dátumot megadni!' : 'Nem sikerült betölteni a statisztikai adatokat.';
              $.notify({
                message: error_message
              }, {
                type: 'danger',
              });

              return;
            }

            // add data to chart
            this.customTrafficChart.data.labels = res.data.labels;
            this.customTrafficChart.data.datasets[0].data = res.data.data;
            // lets clone to get a new reference to it
            // chart-datepicker vue component listens changes on that object...
            this.customTrafficChart = Object.assign({}, this.customTrafficChart);
          })
          .catch(err => console.log(err));
      },

      setMap: function () {
        this.bikeMapOptions.markers = [{
          id: this.deviceId,
          title: this.device.name,
          lat: this.device.lat,
          lon: this.device.lon
        }];
        this.bikeMapOptions.center = {
          lat: this.device.lat,
          lon: this.device.lon
        };
      }
    },
    watch: {
      device: function(val) {
        this.deviceDetailsModalOptions.device = Object.assign({}, val);
        this.setMap();
      }
    },
    computed: {
      isMapVisible: function () {
        return this.device.lat !== null && this.device.lat != '' && this.device.lon !== null && this.device.lon != '';
      }
    }
  }
</script>

<style>
  #details-devices-map {
    height: 350px;
  }
</style>