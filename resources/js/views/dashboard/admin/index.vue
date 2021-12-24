<template>
  <div class="app-container">
    <date-filter />
    <submission-stats />

    <el-row style="margin: 2em auto;">
      <el-col :span="16">
        <submission-chart />
      </el-col>
      <el-col :span="8">
        <permit-by-region />
      </el-col>
    </el-row>

    <el-row :gutter="8" style="margin: 2em auto;">
      <initiator-list />
    </el-row>
  </div>
</template>

<script>
// import PanelGroup from './components/PanelGroup';
// import RaddarChart from './components/RaddarChart';
// import PieChart from './components/PieChart';
import InitiatorList from './components/InitiatorList';
import SubmissionChart from './components/SubmissionChart';
import PermitByRegion from './components/Permit';
import SubmissionStats from './components/SubmissionStats';
import DateFilter from './components/DateFilter';

export default {
  name: 'AdminDashboard',
  components: {
    // PanelGroup,
    // RaddarChart,
    // PieChart,
    InitiatorList,
    SubmissionChart,
    PermitByRegion,
    SubmissionStats,
    DateFilter,
  },
  data() {
    return {
      // lineChartData: lineChartData.newVisitis,
    };
  },
  methods: {
    handleSetLineChartData(type) {
      // this.lineChartData = lineChartData[type];
    },
  },
};
</script>

<style rel="stylesheet/scss" lang="scss" scoped>

</style>
