<template>
  <div v-loading="loading" class="public-project-home">
    <el-card v-if="data !== null" type="box" style="margin-bottom: 2em; color: #fff; background-color:#041608; border-color: #041608;">
      <div slot="header" class="clearfix">
        <span style="font-weight: 500;">{{ data.initiator.name + ': ' + data.project_title }}</span>
      </div>
      <public-project-detail :data="data" :is-home="true" :is-public="true" />
    </el-card>

    <el-row :gutter="20">
      <el-col :span="6">
        <el-card type="box" style="color: #fff; background-color:#041608; border-color: #041608;">
          <public-project-initiator v-if="initiator !== null" :data="initiator" />
        </el-card>

      </el-col>
      <el-col :span="18">
        <el-card type="box" style="color: #fff; background-color:#041608; border-color: #041608;">
          <el-tabs type="card">
            <el-tab-pane label="Linimasa">
              <public-project-timeline :id="projectId" />
            </el-tab-pane>
            <el-tab-pane label="Lokasi">
              <public-project-location :data="data" />
            </el-tab-pane>
            <el-tab-pane label="Tim Penyusun">
              <project-formulator-team v-if="projectId > 0" :id="projectId" />
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
import PublicProjectDetail from './components/PublicProjectDetail.vue';
import PublicProjectInitiator from './components/PublicInitiator.vue';
import PublicProjectTimeline from './components/PublicTimeline.vue';
import PublicProjectLocation from './components/PublicLocation.vue';

import Resource from '@/api/resource';
const projectResource = new Resource('projects');

export default {
  name: 'ProjectPublicHome',
  components: {
    PublicProjectDetail,
    PublicProjectInitiator,
    PublicProjectTimeline,
    PublicProjectLocation,
  },
  props: {
    projectId: {
      type: Number,
      default: 0,
    },
  },
  data(){
    return {
      data: null,
      initiator: null,
      announcement_id: 0,
      loading: false,

    };
  },
  watch: {
    projectId: function(val){
      this.getData();
    },
  },
  methods: {
    async getData(){
      this.loading = true;
      this.data = null;
      await projectResource.list({ id: this.projectId, mode: this.isPublic ? 0 : 1 })
        .then((res) => {
          this.data = res;
          this.initiator = res.initiator;
          this.announcement_id = res.announcement_id;
        })
        .finally(() => {
          this.loading = false;
        });
    },
  },

};
</script>
<style lang="scss">
.public-project-home{
  color: #ffffff;

  .ph {

      p {
        line-height: 150% !important;
      }

      p.ph-header{
        font-weight: bold !important;
        margin-top: 1em;
        margin-bottom: 0.5em !important;
      }
      p.data{
          margin-top: 0.5em !important;
          margin-bottom: 1.5em !important;
      }
  }
  .el-timeline-item__content {
    color: #eeeeee;
  }

  .project-detail{
    padding: 2em;
    .context-bar-item {
      margin: 0.5em 0 1em;

      p {
      margin: 0 0 0 0 !important;
      }

      &.project {font-weight: 500;}
      &.project-initiator { font-weight: bold; margin-top: 2em !important;}

      &.project, &.project-initiator {
        p{line-height: 1.25em !important;}
        p:first-child { margin-bottom: 0.5em !important;}
      }
      &.highlight{
        p{
          font-weight: bold;
          text-align: right;
          margin-bottom: 2em;
        }
        .doc-type {
          display: inline-block; padding: 0.5em 1.5em; border-radius: 1em;
          color: white !important;
        }
      }

      .el-button.cb-ph-links {

        span {
          color: white !important;
          font-weight: bold !important;
        }
      }
    }

    p.data + p.data {
      margin: 0 !important;
    }
    ul.ph-activities{
      list-style-type: none;
      margin-left: 2em;
      li {
      }
    }
  }

}
</style>
