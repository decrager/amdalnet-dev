<template>
  <el-row :gutter="20">
    <el-col :span="6">
      <el-card class="box-card" style="background: #ffffff">
        <div class="header">Total Permohonan</div>
        <div class="value">350</div>
      </el-card>
    </el-col>
    <el-col :span="6">
      <el-card class="box-card" style="background: #216221; color: white;">
        <div class="header">Disetujui</div>
        <div class="value">80</div>
      </el-card>
    </el-col>
    <el-col :span="6">
      <el-card class="box-card" style="background: #D99F24; color: white;">
        <div class="header">Diproses</div>
        <div class="value">222</div>
      </el-card>
    </el-col>
    <el-col :span="6">
      <!-- BB0A0A -->
      <el-card class="box-card" style="background: #6F0000; color: white;">
        <div class="header">Ditolak</div>
        <div class="value">48</div>
      </el-card>
    </el-col>
  </el-row>
</template>
<script>
export default {
  name: 'SubmissionStats',
};
</script>
<style scoped>
.header {
  font-weight: bold;
  letter-spacing: 0.05em;
}
.value {
  font-size:350%;
  line-height: 130%;
  margin-top: 10px;
}

</style>
