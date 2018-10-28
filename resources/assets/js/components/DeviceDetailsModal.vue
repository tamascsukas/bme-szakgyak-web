<template>
  <div class="modal fade" v-bind:id="id" tabindex="-1" role="dialog" data-backdrop="static" data-keyboard="false" aria-labelledby="deviceDetailsModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="deviceDetailsModalLabel">{{ title }}</h5>
          <button type="button" class="close" v-on:click="onDismiss" aria-label="Close">
          <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <div class="modal-body">
          <form>
            <div class="form-row">
              <div class="form-group col-12">
                <label for="device-name" class="col-form-label">Név*:</label>
                <input type="text" class="form-control" :class="{'is-invalid': error_name_is_blank}" id="device-name" v-model="options.device.name" maxlength="30">
                <small v-if="error_name_is_blank" class="text-danger">
                Név megadása kötelező.
                </small>
              </div>
            </div>
            <div class="form-row">
              <div class="form-group col-12">
                <label for="device-eui" class="col-form-label">EUI*:</label>
                <input type="text" class="form-control" :class="{'is-invalid': error_eui_is_blank || error_eui_already_added}" id="device-eui" v-model="options.device.eui" maxlength="16" pattern="[a-fA-F\d]{16}">
                <small v-if="error_eui_is_blank" class="text-danger">
                EUI megadása kötelező.
                </small>
                <small v-if="error_eui_already_added" class="text-danger">
                A megadott EUI-al már szerepel eszköz a rendszerben.
                </small>
              </div>
            </div>
            <div class="form-row">
              <div class="form-group col-6">
                <label class="col-form-label">Koordináta:</label>
                <div class="input-group mb-3">
                  <div class="input-group-prepend">
                    <span class="input-group-text" id="device-lat" v-model="options.device.lat">lat</span>
                  </div>
                  <input type="number" class="form-control" :class="{'is-invalid': error_invalid_lat_value}" placeholder="Szélesség" aria-label="Szélesség" aria-describedby="device-lat" v-model="options.device.lat" min="-90" max="90" step="0.000001">
                  <small v-if="error_invalid_lat_value" class="text-danger">
                  Érvénytelen érték! Megengedett tartomány: -90 &ndash; 90.
                  </small>
                </div>
              </div>
              <div class="form-group col-6">
                <label class="col-form-label">&nbsp;</label>
                <div class="input-group mb-3">
                  <div class="input-group-prepend">
                    <span class="input-group-text" id="device-lat" v-model="options.device.lon">lon</span>
                  </div>
                  <input type="number" class="form-control" :class="{'is-invalid': error_invalid_lon_value}" placeholder="Hosszúság" aria-label="Hosszúság" aria-describedby="device-lon" v-model="options.device.lon" min="-180" max="180" step="0.000001">
                  <small v-if="error_invalid_lon_value" class="text-danger">
                  Érvénytelen érték! Megengedett tartomány: -180 &ndash; 180.
                  </small>
                </div>
              </div>
            </div>
          </form>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-outline-secondary" v-on:click="onDismiss">Mégsem</button>
          <button type="button" class="btn btn-primary" v-on:click="onSubmit">OK</button>
        </div>
      </div>
    </div>
  </div>
</template>


<script>
  export default {
    name: 'device-details-modal',
    props: {
      id: {
        type: String,
        required: true
      },
      title: {
        type: String,
        required: true
      },
      options: {
        type: Object,
        default: function () {
          return {
            isHidden: true,
            device: {},
            errors: []
          }
        }
      }
    },
    data() {
      return {}
    },
    computed: {
      error_name_is_blank: function () {
        return this.options.errors.indexOf('name_is_blank') > -1;
      },
      error_eui_is_blank: function () {
        return this.options.errors.indexOf('eui_is_blank') > -1;
      },
      error_eui_already_added: function () {
        return this.options.errors.indexOf('eui_already_added') > -1;
      },
      error_invalid_lat_value: function () {
        return this.options.errors.indexOf('invalid_lat_value') > -1;
      },
      error_invalid_lon_value: function () {
        return this.options.errors.indexOf('invalid_lon_value') > -1;
      }
    },
    methods: {
      onSubmit: function () {
        this.options.errors = [];
        this.$emit('success-callback', this.options.device);
      },
      onDismiss: function () {
        this.options.errors = [];
        this.$emit('dismiss-callback', this.options.device);
      }
    },
    watch: {
      'options.isHidden': function (val) {
        $('#' + this.id).modal((val) ? 'hide' : 'show');
      }
    }
  }
</script>
