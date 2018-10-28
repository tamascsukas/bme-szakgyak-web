<template>
  <canvas :id="id"></canvas>
</template>

<script>
  var Chart = require('chart.js');

  export default {
    name: 'chart',
    props: {
      id: {
        type: String,
        required: true
      },
      data: {
        type: Object,
        default: function () {
          return {};
        }
      }
    },
    data() {
      return {
        chart: null
      }
    },
    mounted: function () {
      this.chart = new Chart(this.$el, this.data);
    },
    watch: {
      data: function(val) {
        this.chart.update();
      }
    }
  }
</script>