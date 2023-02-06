<template>
  <el-card v-loading="loading" class="box-card">
    <div style="margin: 0 0 2em 0; font-weight: bold">Pengajuan</div>
    <bar-chart :chart-data="barChartData.data" :x-axis="xAxis" />
  </el-card>
</template>
<script>
import { mapGetters } from 'vuex';
import axios from 'axios';
import BarChart from './BarChart';

export default {
  name: 'SubmissionChart',
  components: { BarChart },
  data() {
    return {
      barChartData: {
        data: {
          amdalData: [],
          ukluplData: [],
          spplData: [],
          aARKLRPLData: [],
        },
      },
      xAxis: [],
      loading: false,
      userInfo: {},
      period: 2,
      date_start: new Date().setMonth().toString() - 3,
      date_end: new Date().getMonth().toString(),
    };
  },
  computed: {
    ...mapGetters(['roles', 'userId']),
    isExaminerSecretary() {
      return this.$store.getters.roles.includes('examiner-secretary');
    },
  },
  created() {
    this.getChart();
  },
  methods: {
    getChart() {
      this.loading = true;
      let search = '';
      if (this.period) {
        if (search !== '') {
          search += '&';
        }

        search += `period=${this.period}`;
      }
      if (this.date_start) {
        if (search !== '') {
          search += '&';
        }

        search += `start=${this.date_start}`;
      }
      if (this.date_end) {
        if (search !== '') {
          search += '&';
        }

        search += `end=${this.date_end}`;
      }
      axios
        .get(`/api/dashboard/chart?${search}`)
        .then((data) => {
          this.barChartData.data.amdalData = data.data.amdal;
          this.barChartData.data.ukluplData = data.data.ukl_upl;
          this.barChartData.data.spplData = data.data.sppl;
          this.barChartData.data.aARKLRPLData = data.data.adendum;
          this.xAxis = data.data.dates;
          this.loading = false;
        })
        .catch((error) => {
          console.log(error);
          this.loading = false;
        });
    },
    filterChart(filter) {
      this.period = filter.periode;
      if (filter.date_range) {
        this.date_start = filter.date_range[0];
        this.date_end = filter.date_range[1];
      }
      this.getChart();
    },
  },
};
</script>
