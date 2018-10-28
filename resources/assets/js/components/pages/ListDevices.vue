<template>
  <div>
    <div class="container">
      <div class="row">
        <div class="col-sm-12 col-md-6 content-box">
          <google-map id="list-devices-map" :options="bikeMapOptions" @click="showDevice" @bounds-changed="bikeMapCallback"></google-map>
        </div>
        <div class="col-sm-12 col-md-6 content-box">
          <nav class="navbar navbar-expand navbar-light bg-light">
            <div class="collapse navbar-collapse" id="navbarSupportedContent">
              <ul class="navbar-nav mr-auto">
                <li class="nav-item form-check">
                  <label class="form-check-label" for="enable-map-filter">
                    <input type="checkbox" class="form-check-input" id="enable-map-filter" v-model="enableMapFilter"> Csak a térképen láthatók
                  </label>
                </li>
              </ul>
              <ul class="navbar-nav">
                <li class="nav-item">
                  <button type="button" class="btn btn-outline-success btn-sm" @click="showDeviceDetailsModal">Hozzáad</button>
                </li>
              </ul>
            </div>
          </nav>
          <table id="devices-table" class="table table-hover">
            <thead>
              <tr>
                <th scope="col">Név</th>
                <th scope="col">Utoljára aktív</th>
                <th scope="col" colspan="2">Töltöttség</th>
              </tr>
            </thead>
            <tbody>
              <tr v-for="device in devices" :key="device.id">
                <td class="font-weight-normal">{{ device.name }}</td>
                <td>
                  <span v-if="device.last_seen_hours===null" class="font-weight-normal">-</span>
                  <span v-else-if="device.last_seen_hours>0" class="font-weight-light">
                    {{ device.last_seen_hours }} órája
                  </span>
                  <span v-else class="font-weight-light">
                    < 1 órája
                  </span>
                </td>
                <td>
                  <span v-if="device.battery_level" :class="{'font-weight-light': (device.battery_level > 0.2), 'badge badge-secondary': (device.battery_level <= 0.2), 'badge-warning': (0.1 < device.battery_level && device.battery_level <= 0.2), 'badge-danger': (device.battery_level <= 0.1)}">
                    {{ Math.round(device.battery_level * 100) }}%
                  </span>
                  <span v-else class="font-weight-normal">-</span>
                </td>
                <td>
                  <a href="javascript:void(0);" @click="showDevice(device.id)">
                    <ion-icon name="play"></ion-icon>
                  </a>
                </td>
              </tr>
              <tr v-if="devices.length==0">
                <td colspan="4">
                  <span class="font-weight-normal">Nincs eszköz a rendszerben.</span>
                </td>
              </tr>
            </tbody>
            <caption>
              <pagination :data="pagination" @callback="paginationCallback"></pagination>
            </caption>
          </table>
        </div>
      </div>
    </div>

    <device-details-modal id="deviceDetailsModal" title="Eszköz hozzáadása" :options="deviceDetailsModalOptions" @success-callback="deviceDetailsModalSuccessCallback" @dismiss-callback="deviceDetailsModalDismissCallback"></device-details-modal>
  </div>
</template>


<script>
  var deviceDetailsModalComponent = require('./../DeviceDetailsModal.vue');
  var paginationComponent = require('./../Pagination.vue');
  var googleMapComponent = require('./../BikeGoogleMap.vue');

  export default {
    name: 'list-devices',
    components: {
      'device-details-modal': deviceDetailsModalComponent,
      'pagination': paginationComponent,
      'google-map': googleMapComponent
    },
    data() {
      return {
        devices: [],
        currentPageUrl: null,
        pagination: {},

        enableMapFilter: false,
        currentMapBounds: null,

        bikeMapOptions: {
          markers: [],
          center: {
            lat: 47.497800,
            lon: 19.040318
          },
          zoom: 12
        },

        deviceDetailsModalOptions: {
          isHidden: true,
          device: {},
          errors: []
        }
      }
    },
    activated() {
      this.fetchDevicesList(this.currentPageUrl);
      this.fetchDevicesMap();
    },
    methods: {
      fetchDevicesList: function (page_url) {
        let vm = this;

        this.currentPageUrl = page_url || 'api/devices';
        if (this.enableMapFilter) {
          if (this.currentPageUrl.indexOf("?") == -1) {
            this.currentPageUrl = this.currentPageUrl + '?';
          }
          this.currentPageUrl = this.currentPageUrl + '&' + $.param({bounds: this.currentMapBounds});
        }

        fetch(this.currentPageUrl)
          .then(window.handleNetworkError)
          .then(res => res.json())
          .then(res => {
            this.devices = res.data
            delete res.data;

            vm.makePagination(res);
          })
          .catch(err => console.log('Error while fetching devices for the list: ' + err));
      },
      makePagination: function (data) {
        let pagination = {
          current_page: data.current_page,
          last_page: data.last_page,
          prev_page_url: data.prev_page_url,
          next_page_url: data.next_page_url
        };

        this.pagination = pagination;
      },
      paginationCallback: function (data) {
        this.fetchDevicesList(data);
        // scroll top
        // $("html, body").animate({
        //   scrollTop: $("#devices-table").position().top + 135
        // }, 1000);
      },

      fetchDevicesMap: function () {
        let vm = this;
        fetch('api/devices?map=1')
          .then(window.handleNetworkError)
          .then(res => res.json())
          .then(res => {
            vm.bikeMapOptions.markers = res;
          })
          .catch(err => console.log('Error while fetching devices for the map: ' + err));
      },
      bikeMapCallback: function (bounds) {
        this.currentMapBounds = bounds;
        if (this.enableMapFilter) {
          this.fetchDevicesList();
        }
      },

      showDevice: function (id) {
        this.$emit('callback', id);
      },

      showDeviceDetailsModal: function () {
        this.deviceDetailsModalOptions.isHidden = false;
      },
      deviceDetailsModalSuccessCallback: function (data) {
        fetch('api/devices', {
            method: 'post',
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
            this.deviceDetailsModalOptions.device = {};
            this.deviceDetailsModalOptions.isHidden = true;

            // create notification
            $.notify({
              message: 'Az eszköz adatait sikeresen elmentettük.'
            }, {
              type: 'success',
            });

            // refetch devices
            this.fetchDevicesList();
            this.fetchDevicesMap();
          })
          .catch(err => console.log(err));
      },
      deviceDetailsModalDismissCallback: function () {
        this.deviceDetailsModalOptions.device = {};
        this.deviceDetailsModalOptions.isHidden = true;
      }
    },
    watch: {
      enableMapFilter: function() {
        this.fetchDevicesList();
      }
    }
  }
</script>

<style>
  #list-devices-map {
    height: 650px;
  }
</style>