<template>
  <div v-if="data !== null" class="app-container ph">

    <el-card type="box" style="margin-bottom: 20px;">
      <div slot="header" class="clearfix">
        <span style="font-weight: 500;">{{ data.initiator.name + ': ' + data.project_title }}</span>
      </div>
      <project-detail :data="data" :is-home="true" />
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
              <project-timeline />
            </el-tab-pane>
            <el-tab-pane label="Lokasi">
              <project-location :data="data" />
            </el-tab-pane>
            <el-tab-pane label="Tim Penyusun">
              <project-formulator-team v-if="project_id > 0" :id="project_id" />
            </el-tab-pane>
            <el-tab-pane label="SPT">
              <project-public-feedback v-if="announcement_id > 0" :id="announcement_id" />
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
  data() {
    return {
      initiator_id: 0,
      project_id: 0,
      formulator_team_id: 0,
      announcement_id: 0,
      data: null,
      initiator: null,
    };
  },
  mounted(){
    this.project_id = this.$route.params && this.$route.params.id;
    this.getData();
  },
  methods: {
    async getData(){
      // const project_id = this.$route.params && this.$route.params.id;
      this.data = null;
      await projectResource.list({ id: this.project_id })
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
