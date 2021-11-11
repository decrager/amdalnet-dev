<template>
  <div>
    <el-collapse v-model="activeName" accordion>
      <el-collapse-item name="1">
        <template slot="title"><span class="title">BAB 1</span></template>
        <div class="list-container">
          <a href="#"> 1.1 Identitas Pemrakarsa </a>
          <el-divider />
          <a href="#"> 1.2 Latar Belakang </a>
          <el-divider />
          <a href="#"> 1.3 Tujuan dan Manfaat Rencana Usaha/Kegiatan </a>
        </div>
      </el-collapse-item>
      <el-collapse-item name="2">
        <template slot="title"><span class="title">BAB 2</span></template>
      </el-collapse-item>
      <el-collapse-item name="3">
        <template slot="title"><span class="title">BAB 3</span></template>
      </el-collapse-item>
    </el-collapse>
    <div class="no-collapse">
      <a href="#"> SURAT PERNYATAAN </a>
      <el-divider />
      <a href="#"> DAFTAR PUSTAKA </a>
      <el-divider />
      <a href="#"> LAMPIRAN </a>
      <el-divider />
    </div>
  </div>
</template>

<script>
export default {
  name: 'MapList',
  data() {
    return {
      activeName: '1',
    };
  },
};
</script>

<style scoped>
.title {
  font-weight: bold;
  font-size: 1rem;
}
.list-container {
  padding-left: 18px;
  padding-right: 18px;
}
.list-container .el-divider {
  margin: 11px 0;
}
.no-collapse {
  color: black;
  font-weight: bold;
  font-size: 1rem;
  padding-top: 15px;
}
.no-collapse .el-divider {
  margin: 12px 0;
  background-color: #e6ebf5;
}
</style>
