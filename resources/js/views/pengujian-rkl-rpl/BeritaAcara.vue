<template>
  <div class="app-container">
    <el-card>
      <WorkFlow />
      <el-tabs v-model="activeName" type="card">
        <el-tab-pane label="Catatan Andal" name="catatanandal">
          <NotesRecap v-if="activeName === 'catatanandal'" :flow="'andal'" />
        </el-tab-pane>
        <el-tab-pane label="Catatan RKL-RPL" name="catatanrklrpl">
          <NotesRecap v-if="activeName === 'catatanrklrpl'" :flow="'rkl-rpl'" />
        </el-tab-pane>
        <el-tab-pane label="Berita Acara" name="beritaacaraandalrklrpl">
          <BeritaAcaraAndalRklRpl
            v-if="activeName === 'beritaacaraandalrklrpl'"
          />
        </el-tab-pane>
      </el-tabs>
    </el-card>
  </div>
</template>

<script>
import WorkFlow from '@/components/Workflow';
import NotesRecap from '@/views/notes-recap/index';
import BeritaAcaraAndalRklRpl from '@/views/pengujian-rkl-rpl/components/beritaAcaraAndalRklRpl/index';

export default {
  name: 'BeritaAcaraAndalRklRplIndex',
  components: {
    WorkFlow,
    NotesRecap,
    BeritaAcaraAndalRklRpl,
  },
  data() {
    return {
      activeName: 'catatanandal',
    };
  },
  created() {
    this.$store.dispatch('getStep', 6);
  },
};
</script>
