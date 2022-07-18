<template>
  <el-card class="box-card">
    <div slot="header" class="clearfix">
      <span>Jumlah Penyusun</span>
    </div>
    <div class="user-summary-cards">
      <el-row :gutter="10">
        <el-col :span="6">
          <el-card
            id="total"
            class="box-card"
            style="background: #eb8a00; color: white"
          >
            <span class="title">Total Penyusun</span>
            <span class="value">20</span>
          </el-card>
        </el-col>
        <el-col :span="6">
          <el-card
            id="total"
            class="box-card"
            style="background: #fac400; color: white"
          >
            <span class="title">Aktif</span>
            <span class="value">10</span>
          </el-card>
        </el-col>
        <el-col :span="6">
          <el-card
            id="total"
            class="box-card"
            style="background: #efcc4d; color: white"
          >
            <span class="title">NonAktif</span>
            <span class="value">10</span>
          </el-card>
        </el-col>
        <el-col :span="6">
          <el-card
            id="total"
            class="box-card"
            style="background: #f7d96c; color: white"
          >
            <span class="title">Sertifikasi</span>
            <span class="value">15</span>
          </el-card>
        </el-col>
      </el-row>
    </div>
  </el-card>
</template>
<script>
export default {
  name: 'FormulatorSummary',
};
</script>
<style lang="scss" scoped>
.user-summary-cards {
  .el-card {
    height: 150px;
    margin: 0;
    padding: 0;
    overflow: hidden;
    overflow: -moz-hidden-unscrollable;

    span {
      display: block;
      text-align: center;
    }

    .title {
      font-weight: bold;
    }
    .value {
      margin-top: 0.8em;
      font-size: 250%;
    }
    .smaller {
      font-size: 86%;
    }
  }
}
</style>
