<template>
  <div class="app-container">
    <date-filter @filterChange="onFilterChange" />
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

import InitiatorList from './components/InitiatorList';
import SubmissionChart from './components/SubmissionChart';
import PermitByRegion from './components/Permit';
import SubmissionStats from './components/SubmissionStats';
import DateFilter from './components/DateFilter';

export default {
  name: 'AdminDashboard',
  components: {
    InitiatorList,
    SubmissionChart,
    PermitByRegion,
    SubmissionStats,
    DateFilter,
  },
  data() {
    return {
      filter: '',
    };
  },
  methods: {
    onFilterChange(val){
      this.filter = val;
      console.log('onFilterChange', this.filter);
    },
  },
};
</script>

<style rel="stylesheet/scss" lang="scss" scoped>

</style>
