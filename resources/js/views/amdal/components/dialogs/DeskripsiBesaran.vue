<template>
  <el-row
    v-if="data !== null"
    :gutter="20"
    class="description-box"
    style="line-height: 150% !important;"
  >
    <el-col :span="12">
      <p><strong>Deskripsi</strong></p>
      <div v-html="data.description || data.description_specific" />
    </el-col>
    <el-col :span="12">
      <p><strong>Besaran</strong></p>
      {{ data.measurement || data.unit }}
    </el-col>
  </el-row>
</template>
<script>
export default {
  name: 'DeskripsiBesaran',
  props: {
    data: {
      type: Object,
      default: () => null,
    },
  },
};
</script>

