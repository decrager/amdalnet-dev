<template>
  <div>
    <el-card class="box-card">
      <div slot="header" class="clearfix">
        <span>Persetujuan</span>
      </div>
      <!-- flexbox -->

      <el-skeleton :rows="6" animated />
    </el-card>
  </div>
</template>
<script>
export default {
  name: 'UserSummary',
  data() {
    return {
      data: [
        { label: 'Total', value: 18 },
        { label: 'AMDAL', value: 3 },
        { label: 'UKL-UPL', value: 5 },
        { label: 'SPPL', value: 7 },
        { label: 'Adendum AMDAL', value: 3 },
        { label: 'Adendum UKL-UPL', value: 3 },
      ],
    };
  },
};
</script>
