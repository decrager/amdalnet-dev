<template>
  <el-row>
    <el-col span="12"><form-berita-acara /> </el-col>
  </el-row>

</template>
<script>
import FormBeritaAcara from './forms/FormBeritaAcara.vue';
export default {
  name: 'BeritaAcara',
  components: { FormBeritaAcara },
};
</script>
