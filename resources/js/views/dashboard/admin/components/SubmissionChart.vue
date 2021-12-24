<template>
  <el-card class="box-card">
    <div style="margin: 0 0 2em 0; font-weight:bold;">Pengajuan</div>
    <line-chart :chart-data="lineChartData.data" />
  </el-card>
</template>
<script>

import LineChart from './LineChart';

export default {
  name: 'SubmissionChart',
  components: { LineChart },
  data() {
    return {
      lineChartData: {
        data: {
          amdalData: [130, 140, 141, 142, 145, 150, 160],
          ukluplData: [120, 82, 91, 154, 162, 140, 130],
          spplData: [80, 100, 121, 104, 105, 90, 100],
          aAMDALData: [120, 90, 100, 138, 142, 130, 130],
          aUKLUPLData: [180, 160, 151, 106, 145, 150, 130],
        },
      },
    };
  },
};
</script>
