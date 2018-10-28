<template>
  <nav :id="id" class="navbar navbar-expand navbar-light bg-light">
    <div class="collapse navbar-collapse" id="navbarSupportedContent">
      <ul class="navbar-nav mr-auto">
        <li class="nav-item">
          <flatpickr name="chart_date" id="chart_date" class="form-control form-control-sm" placeholder="Időszak kiválasztása" 
                  v-model="options.date"
                  :config="dateConfig">
          </flatpickr>
        </li>
        <li class="nav-item form-check" tool>
          <select name="chart_step" v-model="options.step" class="form-control form-control-sm" data-toggle="tooltip" data-placement="top" title="Lépték">
            <option value="hours">Óránként</option>
            <option value="days">Naponta</option>
            <option value="months">Havonta</option>
          </select>
        </li>
      </ul>
      <ul class="navbar-nav">
        <li class="nav-item">
          <a href="javascript:void(0);" @click="onRefresh">
            <ion-icon name="refresh" style="width: 22px;height: 22px;"></ion-icon>
          </a>
        </li>
      </ul>
    </div>
  </nav>
</template>

<script>
  var flatpickr = require('vue-flatpickr-component');
  require('flatpickr/dist/flatpickr.css');

  export default {
    name: 'chart-datepicker',
    components: {
      'flatpickr': flatpickr
    },
    props: {
      id: {
        type: String,
        required: true
      },
      options: {
        type: Object,
        default: function () {
          return {
            date: '',
            step: 'days'
          }
        }
      }
    },
    data() {
      return {
        // Get more form https://chmln.github.io/flatpickr/options/
        dateConfig: { 
          mode: "range",
          dateFormat: "Y-m-d",
          maxDate: "today"
        }
      }
    },
    methods: {
      onRefresh: function () {
        this.$emit('callback', this.options);
      }
    },
  }
</script>