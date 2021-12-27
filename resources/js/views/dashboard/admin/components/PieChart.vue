<template>
  <div :class="className" :style="{height:height,width:width}" />
</template>

<script>
import echarts from 'echarts';
require('echarts/theme/macarons'); // echarts theme
import { debounce } from '@/utils';

export default {
  props: {
    className: {
      type: String,
      default: 'chart',
    },
    width: {
      type: String,
      default: '100%',
    },
    height: {
      type: String,
      default: '350px',
    },
  },
  data() {
    return {
      chart: null,
    };
  },
  mounted() {
    this.initChart();
    this.__resizeHandler = debounce(() => {
      if (this.chart) {
        this.chart.resize();
      }
    }, 100);
    window.addEventListener('resize', this.__resizeHandler);
  },
  beforeDestroy() {
    if (!this.chart) {
      return;
    }
    window.removeEventListener('resize', this.__resizeHandler);
    this.chart.dispose();
    this.chart = null;
  },
  methods: {
    initChart() {
      this.chart = echarts.init(this.$el, 'macarons');

      this.chart.setOption({
        color: ['#5E727F', '#90a2ad', '#A27D95'],
        tooltip: {
          trigger: 'item',
          formatter: '{b} : {c} ({d}%)',
        },
        legend: {
          left: 'center',
          bottom: '10',
          data: ['Pusat', 'Provinsi', 'Kabupaten/Kota'],
        },
        calculable: true,
        series: [
          {
            name: 'Kewenangan Izin',
            type: 'pie',
            // roseType: 'radius',
            radius: [15, 95],
            center: ['50%', '38%'],
            data: [
              { value: 3056, name: 'Pusat' },
              { value: 2356, name: 'Provinsi' },
              { value: 6851, name: 'Kabupaten/Kota' },
            ],
            animationEasing: 'cubicInOut',
            animationDuration: 2600,
          },
        ],
      });
    },
  },
};
</script>
<style lang="scss" scoped>
div.chart {
  padding: 1em 0;
}
</style>
