<template>
  <div v-loading="loadingMasterComponents || loadingMasterHues || loadingProjectStages || loadingComponentTypes">
    <master-komponen
      :components="components"
      :hues="hues"
      :component-types="component_types"
      :project-stages="project_stages"
      @refreshData="refreshData"
    />
    <tabel-pelingkupan
      :master-components="components"
      :master-hues="hues"
      :component-types="component_types"
      :project-stages="project_stages"
    />
    <Comment :withstage="true" commenttype="pelingkupan" :kolom="commentColumn" />
  </div>
</template>
<script>
import TabelPelingkupan from './TabelPelingkupan.vue';
import MasterKomponen from './MasterKomponen.vue';
import Comment from '../components/Comment.vue';

import Resource from '@/api/resource';
// const ronaAwalResource = new Resource('rona-awals');
const projectComponentResource = new Resource('project-components');
const projectHueResource = new Resource('project-rona-awals');

export default {
  name: 'ModulPelingkupan',
  components: { TabelPelingkupan, MasterKomponen, Comment },
  data(){
    return {
      // master data
      component_types: [],
      project_stages: [],
      components: [], // master lokal
      hues: [], // master lokal
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
          label: 'Lainnya',
          value: 'Lainnya',
        },
      ],
      loadingMasterComponents: false,
      loadingMasterHues: false,
      loadingProjectStages: false,
      loadingComponentTypes: false,
    };
  },
  mounted() {
    this.getComponentTypes();
    this.getProjectStages();
    this.getComponentMasterData();
    this.getHueMasterData();
  },
  methods: {
    async initData(){

    },
    async getComponentTypes(){
      this.loadingComponentTypes = true;
      const ctResource = new Resource('component-types');
      await ctResource.list({}).then((res) => {
        this.component_types = res.data;
      }).catch((err) => {
        this.$message({
          message: 'Gagal memuat Master Data Kategori Komponen Lingkungan',
          type: 'error',
          duration: 5 * 1000,
        });
        console.log(err);
      }).finally(() => {
        this.loadingComponentTypes = false;
      });
    },
    async getProjectStages(){
      this.loadingProjectStages = true;
      const ctResource = new Resource('project-stages');
      await ctResource.list({}).then((res) => {
        this.project_stages = res.data;
      }).catch((err) => {
        this.$message({
          message: 'Gagal memuat Master Data Tahap Kegiatan',
          type: 'error',
          duration: 5 * 1000,
        });
        console.log(err);
      }).finally(() => {
        this.loadingProjectStages = false;
      });
    },
    async getComponentMasterData(){
      this.loadingMasterComponents = true;
      const idProject = parseInt(this.$route.params && this.$route.params.id);
      this.components = [];
      await projectComponentResource.list({
        id_project: idProject,
      }).then((res) => {
        this.components = res;
      }).catch((err) => {
        this.$message({
          message: 'Gagal memuat Master Data Komponen Kegiatan',
          type: 'error',
          duration: 5 * 1000,
        });
        console.log(err);
      })
        .finally(() => {
          this.loadingMasterComponents = false;
        });
    },
    async getHueMasterData(){
      this.loadingMasterHues = true;
      const idProject = parseInt(this.$route.params && this.$route.params.id);
      this.hues = [];
      await projectHueResource.list({
        id_project: idProject,
      }).then((res) => {
        this.hues = res;
        console.log('hues:', this.hues);
      }).catch((err) => {
        this.$message({
          message: 'Gagal memuat Master Data Komponen Lingkungan',
          type: 'error',
          duration: 5 * 1000,
        });
        console.log(err);
      }).finally(() => {
        this.loadingMasterHues = false;
      });
    },
    refreshData(){
      this.getProjectStages();
      this.getComponentTypes();
      this.getComponentMasterData();
      this.getHueMasterData();
    },
  },
};
</script>
<style>

.master-komponen .el-card__header {
      padding: 5px 0 !important;
      background: #39773b;
      color: #ffffff;

}

.scoupingModule .el-card__header {
      padding: 5px 0 !important;
      background: #216221;
      color: #ffffff;

}

.scoupingModule .el-card__body{
  padding: 8px 5px;
}

.master-komponen  .el-card__body {
  background: #f5f5f5;
}

.master-komponen .el-card__body p, .scoping p {

  margin: 0 0 0.2em 0;
  border: 1px solid #e0e0e0;
  border-radius:0.3em;
  padding: 0.5em;
  text-align: center;
  line-height:130%;
}

.scoping p {
  cursor: pointer;
}
.scoping p.highlighted {
  background: #eee;
  border-color: #aaa;
}

.scoping p.selected {
  background: #e9efe9;
  border-color: #afc6af;
  font-weight: bold;
  color: #216221;
}

.master-komponen .el-card__body .components-container {
  max-height: 300px;
  overflow-y: auto;
  padding: 5px 0;
  margin: 0 auto 0.6em;

}

.master-komponen .el-card__body .components-container p{
  text-align:center; background: #fff;
}

</style>

