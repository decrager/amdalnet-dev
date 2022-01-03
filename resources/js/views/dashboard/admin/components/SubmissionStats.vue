<template>
  <el-row :gutter="20">
    <el-col :span="6">
      <el-card class="box-card" style="background: #0A2F08; color: white;">
        <div class="header">Total Permohonan</div>
        <div class="value">350</div>
      </el-card>
    </el-col>
    <el-col :span="6">
      <!--  #216221 -->
      <el-card class="box-card" style="background: #2B743C; color: white;">
        <div class="header">Disetujui</div>
        <div class="value">80</div>
      </el-card>
    </el-col>
    <el-col :span="6">
      <!-- #D99F24 -->
      <el-card class="box-card" style="background: #4c9b4f; color: white;">
        <div class="header">Diproses</div>
        <div class="value">222</div>
      </el-card>
    </el-col>
    <el-col :span="6">
      <!--  #7B9A76 #6F0000 -->
      <el-card class="box-card" style="background: #97B093; color: white;">
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
