<template>
  <el-row :gutter="20" style="line-height:130%; word-break:break-word;">
    <el-col :span="14">
      <div><strong>Deskripsi</strong></div>
      <div v-html="description" />
    </el-col>
    <el-col :span="10">
      <div><strong>Besaran</strong></div>
      <div style="margin-top: 1em;" v-html="measurement" />
    </el-col>
  </el-row>
</template>
<script>
export default {
  name: 'Deskripsi',
  props: {
    description: {
      type: String,
      default: '',
    },
    measurement: {
      type: String,
      default: '',
    },
  },
};
</script>
