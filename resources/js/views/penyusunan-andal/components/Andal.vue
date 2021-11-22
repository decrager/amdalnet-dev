<template>
  <el-collapse v-model="activeName" accordion>
    <el-collapse-item>
      <template slot="title" class="head-accordion">
        <span class="title">PELINGKUPAN</span>
      </template>
    </el-collapse-item>
    <el-collapse-item>
      <template slot="title" class="head-accordion">
        <span class="title">MATRIKS IDENTIFIKASI DAMPAK</span>
      </template>
    </el-collapse-item>
    <el-collapse-item>
      <template slot="title" class="head-accordion">
        <span class="title">PETA BATAS WILAYAH STUDI & PETA LAINNYA</span>
      </template>
    </el-collapse-item>
    <el-collapse-item>
      <template slot="title" class="head-accordion">
        <span class="title">DAMPAK POTENSIAL & DAMPAK PENTING HIPOTETIK</span>
      </template>
    </el-collapse-item>
    <el-collapse-item>
      <template slot="title" class="head-accordion">
        <span class="title">METODE STUDI</span>
      </template>
    </el-collapse-item>
    <el-collapse-item>
      <template slot="title" class="head-accordion">
        <span class="title">MATRIKS DAMPAK PENTING HIPOTETIK</span>
      </template>
    </el-collapse-item>
    <el-collapse-item>
      <template slot="title" class="head-accordion">
        <span class="title">BAGAN ALIR PELINGKUPAN</span>
      </template>
    </el-collapse-item>
    <el-collapse-item name="tableAndal">
      <template slot="title" class="head-accordion">
        <span class="title">ANALISA DAMPAK LINGKUNGAN</span>
      </template>
      <TableAndal />
    </el-collapse-item>
    <el-collapse-item>
      <template slot="title" class="head-accordion">
        <span class="title">BAGAN ALIR DAMPAK PENTING</span>
      </template>
    </el-collapse-item>
  </el-collapse>
</template>

<script>
import TableAndal from '@/views/penyusunan-andal/components/Table';

export default {
  name: 'Andal',
  components: {
    TableAndal,
  },
  data() {
    return {
      activeName: 'tableAndal',
    };
  },
};
</script>

<style>
.el-collapse-item__header {
  /* background-color: #296d36; */
  background-color: #1e5128;
  padding-left: 10px;
  font-size: large;
  font-weight: bold;
  color: rgb(196, 196, 196);
}
.el-collapse-item__content {
  padding-top: 10px;
}
</style>
