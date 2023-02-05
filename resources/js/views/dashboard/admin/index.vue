<template>
  <div class="app-container">
    <Header />
    <date-filter @filterChange="onFilterChange" />
    <submission-stats ref="stats" />

    <el-row style="margin: 2em auto">
      <el-col :span="16">
        <submission-chart ref="chart" />
      </el-col>
      <el-col :span="8">
        <permit-by-region ref="permit" />
      </el-col>
    </el-row>

    <el-row :gutter="8" style="margin: 2em auto">
      <initiator-list ref="initiators" />
    </el-row>
  </div>
</template>

<script>
import InitiatorList from './components/InitiatorList';
import SubmissionChart from './components/SubmissionChart';
import PermitByRegion from './components/Permit';
import SubmissionStats from './components/SubmissionStats';
import DateFilter from './components/DateFilter';
import Header from './components/Header';

export default {
  name: 'AdminDashboard',
  components: {
    InitiatorList,
    SubmissionChart,
    PermitByRegion,
    SubmissionStats,
    DateFilter,
    Header,
  },
  data() {
    return {
      filter: '',
    };
  },
  methods: {
    onFilterChange(val) {
      this.filter = val;
      this.$refs.stats.filterStats(this.filter);
      this.$refs.permit.filterPermit(this.filter);
      this.$refs.initiators.filterInitiator(this.filter);
      this.$refs.chart.filterChart(this.filter);
    },
  },
};
</script>

<style rel="stylesheet/scss" lang="scss" scoped></style>
