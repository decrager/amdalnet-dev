<template>
  <div>
    <el-tabs type="card">
      <el-tab-pane v-for="s of projectStages" :key="s.id" :label="s.name">
        <pelingkupan-table
          :id-project="idProject"
          :id-project-stage="s.id"
          :current-id-sub-project="currentIdSubProject"
          @handleCurrentIdSubProject="handleCurrentIdSubProject"
        />
      </el-tab-pane>
    </el-tabs>
    <div class="save-changes">
      <el-button
        v-if="!isAndal && isFormulator"
        type="success"
        size="small"
        icon="el-icon-check"
        @click="handleSaveForm()"
      >Simpan Perubahan</el-button>
    </div>
    <Comment :withstage="true" commenttype="pelingkupan" :kolom="commentColumn" />
  </div>
</template>
<script>

import Resource from '@/api/resource';
import PelingkupanTable from './tables/PelingkupanTable.vue';
import Comment from './Comment.vue';
const projectStageResource = new Resource('project-stages');

export default {
  name: 'Pelingkupan',
  components: { PelingkupanTable, Comment },
  data() {
    return {
      idProject: 0,
      projectStages: [],
      currentIdSubProject: 0,
      commentColumn: [
        {
          label: 'Komponen Kegiatan',
          value: 'Komponen Kegiatan',
        },
        {
          label: 'Geofisika Kimia',
          value: 'Geofisika Kimia',
        },
        {
          label: 'Biologi',
          value: 'Biologi',
        },
        {
          label: 'Sosial Ekonomi Budaya',
          value: 'Sosial Ekonomi Budaya',
        },
        {
          label: 'Kesehatan Masyarakat',
          value: 'Kesehatan Masyarakat',
        },
        {
          label: 'Kegiatan Lain Sekitar',
          value: 'Kegiatan Lain Sekitar',
        },
        {
          label: 'Lainnya',
          value: 'Lainnya',
        },
      ],
    };
  },
  computed: {
    isAndal() {
      return this.$route.name === 'penyusunanAndal';
    },
    isFormulator() {
      return this.$store.getters.roles.includes('formulator');
    },
  },
  mounted() {
    this.getData();
  },
  methods: {
    handleSaveForm() {
      this.$emit('handleReloadVsaList', 'matriks-identifikasi-dampak');
    },
    handleCurrentIdSubProject(id) {
      this.currentIdSubProject = id;
    },
    async getData() {
      this.idProject = parseInt(this.$route.params && this.$route.params.id);
      const prjStages = await projectStageResource.list({
        ordered: true,
      });
      this.projectStages = prjStages.data;
    },
  },
};
</script>
<style scoped>
.save-changes {
  text-align:right;
}
</style>
