<template>
  <div>
    <div class="filter-container">
      <el-button class="filter-item" type="primary" style="font-size: 0.8rem">
        {{ 'Download file DOCX' }}
      </el-button>
    </div>
    <el-row :gutter="32">
      <el-col :sm="12" :md="12">
        <FormBerita />
      </el-col>
      <el-col :sm="12" :md="12">
        <DaftarHadir />
      </el-col>
    </el-row>
  </div>
</template>

<script>
import FormBerita from '@/views/pengujian/components/beritaAcara/FormBerita';
import DaftarHadir from '@/views/pengujian/components/beritaAcara/DaftarHadir';

export default {
  name: 'BeritaAcara',
  components: {
    FormBerita,
    DaftarHadir,
  },
};
</script>
