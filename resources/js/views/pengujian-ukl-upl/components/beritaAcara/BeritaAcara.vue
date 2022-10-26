<template>
  <div class="app-container">
    <el-card>
      <WorkflowUkl />
      <el-tabs v-model="activeName" type="card">
        <el-tab-pane label="Catatan Form UKL UPL" name="notesRekapUKLUPL">
          <NotesRecap v-if="activeName === 'notesRekapUKLUPL'" :flow="'ukl-upl'" />
        </el-tab-pane>
        <el-tab-pane label="Berita Acara" name="beritaAcaraUKLUPL">
          <BeritaAcaraUKLUPL v-if="activeName === 'beritaAcaraUKLUPL'" />
        </el-tab-pane>
      </el-tabs>
    </el-card>
  </div>
</template>

<script>
import WorkflowUkl from '@/components/WorkflowUkl';
import NotesRecap from '@/views/notes-recap/index';
import BeritaAcaraUKLUPL from '@/views/pengujian-ukl-upl/components/beritaAcara/index';

export default {
  name: 'BeritaAcaraUKLUPLIndex',
  components: {
    WorkflowUkl,
    NotesRecap,
    BeritaAcaraUKLUPL,
  },
  data() {
    return {
      activeName: 'notesRekapUKLUPL',
    };
  },
  created() {
    this.$store.dispatch('getStep', 5);
  },
};
</script>
