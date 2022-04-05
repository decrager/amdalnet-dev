<template>
  <div class="app-container">
    <el-card>
      <WorkFlow />
      <el-tabs v-model="activeName" type="card">
        <el-tab-pane label="Catatan Form KA" name="catatan">
          <NotesRecap v-if="activeName === 'catatan'" :flow="'ka'" />
        </el-tab-pane>
        <el-tab-pane label="Berita Acara" name="beritaacaraka">
          <BeritaAcaraKa v-if="activeName === 'beritaacaraka'" />
        </el-tab-pane>
      </el-tabs>
    </el-card>
  </div>
</template>

<script>
import WorkFlow from '@/components/Workflow';
import NotesRecap from '@/views/notes-recap/index';
import BeritaAcaraKa from '@/views/pengujian/components/beritaAcaraKa/index';

export default {
  name: 'BeritaAcaraKaIndex',
  components: {
    WorkFlow,
    NotesRecap,
    BeritaAcaraKa,
  },
  data() {
    return {
      activeName: 'catatan',
    };
  },
  created() {
    this.$store.dispatch('getStep', 3);
  },
};
</script>
