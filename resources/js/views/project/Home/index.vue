<template>
  <div v-if="data !== null" class="app-container ph">

    <!-- <el-collapse v-model="mphDetail" >
      <el-collapse-item name="1">
        <div slot="title" class="clearfix">
          <span style="font-weight: 500;">{{ data.initiator.name + ': ' + data.project_title }}</span>
        </div>
        <project-detail :data="data" :is-home="true" />
      </el-collapse-item>
    </el-collapse> -->
    <el-card type="box" style="margin-bottom: 2em;">
      <div slot="header" class="clearfix">
        <span style="font-weight: 500;">{{ data.initiator.name + ': ' + data.project_title }}</span>
      </div>
      <project-detail :data="data" :is-home="true" :is-public="isPublic" />
    </el-card>

    <el-row :gutter="20">
      <el-col :span="6">
        <el-card type="box">
          <project-initiator v-if="initiator !== null" :data="initiator" />
        </el-card>

      </el-col>
      <el-col :span="18">
        <el-card type="box">
          <el-tabs type="card">
            <el-tab-pane label="Linimasa">
              <project-timeline v-if="data" :id="project_id" :marking="data.marking" />
            </el-tab-pane>
            <el-tab-pane label="Lokasi">
              <project-location :data="data" />
            </el-tab-pane>
            <el-tab-pane label="Tim Penyusun">
              <project-formulator-team v-if="project_id > 0" :id="project_id" />
            </el-tab-pane>
            <el-tab-pane label="SPT">
              <project-public-feedback v-if="announcement_id > 0" :id="announcement_id" />
              <!-- <project-public-consultation v-if="announcement_id > 0" :id="announcement_id" /> -->
            </el-tab-pane>
          </el-tabs>
        </el-card>
      </el-col>
    </el-row>
  </div>
</template>
<script>
import ProjectDetail from './components/ProjectDetail.vue';
import ProjectInitiator from './components/Initiator.vue';
import ProjectTimeline from './components/Timeline.vue';
import ProjectLocation from './components/Location.vue';
import ProjectFormulatorTeam from './components/FormulatorTeam.vue';
import ProjectPublicFeedback from './components/SPT.vue';

import Resource from '@/api/resource';
const projectResource = new Resource('projects');

export default {
  name: 'ProjectHome',
  components: {
    ProjectDetail,
    ProjectInitiator,
    ProjectTimeline,
    ProjectLocation,
    ProjectFormulatorTeam,
    ProjectPublicFeedback,
  },
  props: {
    id: {
      type: Number,
      default: 0,
    },
  },
  data() {
    return {
      initiator_id: 0,
      // project_id: 0,
      formulator_team_id: 0,
      announcement_id: 0,
      data: null,
      initiator: null,
      mphDetail: ['1'],
    };
  },
  computed: {
    isPublic: function(){
      if (this.$route.params && this.$route.params.id){
        return false;
      }
      return true;
    },
    project_id: function(){
      return this.isPublic ? this.id : (this.$route.params && this.$route.params.id);
    },
  },
  mounted(){
    // this.project_id = this.isPublic? this.id : this.$route.params && this.$route.params.id;
    this.getData();
  },
  methods: {
    async getData(){
      /* let project_id = 0;
      if (this.isPublic){
        project_id = this.id;
      } else {
        project_id = this.project_id;
      }*/

      this.data = null;
      await projectResource.list({ id: this.project_id, mode: this.isPublic ? 0 : 1 })
        .then((res) => {
          // console.log('Data Rumah Kegiatan:', res);
          this.data = res;
          this.initiator = res.initiator;
          this.announcement_id = res.announcement_id;
          // console.log('rumah kegiatan annoucement:', this.announcement_id);
          // this.processData();
        })
        .finally({

        });
      // console.log('Rumah Kegiatan ID: ', this.project_id);
    },
    processData() {
      if (this.data === null) {
        return false;
      }

      this.initiator = null;
      this.initiator = this.data.initiator;
    },
  },
};
</script>
<style lang="scss">

.ph {

    p {
      line-height: 150%;
    }

    p.header{
      font-weight: bold;
      margin-bottom: 0.5em;
    }
    p.data{
        margin-top: 0.5em;
        margin-bottom: 1.5em;
    }
}

</style>
