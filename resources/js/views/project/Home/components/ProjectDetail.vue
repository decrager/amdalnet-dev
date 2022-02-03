<template>
  <div v-if="data !== null" class="project-detail">
    <el-row :gutter="20">
      <el-col :span="4">
        <div class="block" style="text-align: center;">
          <el-image
            style="width: 100%; height: 150px"
            :src="data.initiator.logo"
            fit="contain"
          >
            <div slot="error" class="image-slot">
              <i class="el-icon-picture-outline" style="font-size: 500%; line-height: 230%;" />
            </div>
          </el-image>
        </div>
      </el-col>
      <el-col :span="14">
        <div class="context-bar-item project">
          <p>{{ data.project_title }}</p>
          <p>{{ data.project_address }}</p>
        </div>
        <div class="context-bar-item project-initiator">
          <p>{{ data.initiator.name || '' }}</p>
          <p>{{ data.initiator.address }}</p>
        </div>
      </el-col>
      <el-col :span="6">
        <div class="context-bar-item highlight">
          <p><span :class="'doc-type '+ data.required_doc">{{ data.required_doc }}</span></p>
          <p style="margin-top: 1.5em !important;"><span style=" font-weight:normal;">Registration No:</span><br>
            <span style="font-size:1.1em;">{{ (data.registration_no || '').toUpperCase() }}</span></p>
        </div>
      </el-col>
    </el-row>
    <hr style="border-color: #ccc; ">
    <div class="context-bar-item" v-html="data.description" />
    <el-row :gutter="30">
      <el-col :span="20">
        <p class="header">Dampak Potensial</p>
        <p class="data">{{ data.potential_impact }}</p>

        <p class="header">Sifat Kegiatan</p>
        <p class="data"> {{ data.project_type || '-' }}</p>

        <p class="header">Kegiatan Utama</p>
        <project-activities :data="utama" />

        <p class="header">Kegiatan Pendukung</p>
        <project-activities :data="pendukung" />

      </el-col>
      <el-col :span="4">
        <template v-for="link in linkData">
          <p :key="data.id+ '_link_'+ link.label">
            <el-button
              :class="'button-' + data.required_doc+ ' cb-ph-link'"
              round
              style=" display: block !important; width: 100% !important; padding: 1em !important; color: white !important; font-weight:bold !important;"
            >
              {{ link.label }}
            </el-button>
          </p>
        </template>
        <p v-if="isHome !== true"><el-button
          round
          plain
          style="display: block !important; width: 100% !important; padding: 1em !important; font-weight: bold !important"
          @click="$router.push('/project/home/' + data.id)"
        >Rumah Kegiatan</el-button></p>
      </el-col>
    </el-row>
  </div>
</template>
<script>
import ProjectActivities from './sections/Activities.vue';
export default {
  name: 'ProjectDetail',
  components: { ProjectActivities },
  props: {
    data: {
      type: Object,
      default: null,
    },
    isHome: {
      type: Boolean,
      default: false,
    },
  },
  data() {
    return {
      utama: [],
      pendukung: [],
      linkData: [
        {
          label: 'Penapisan',
          route: '',
        },
        {
          label: 'Pengumuman',
          route: '',
        },
        {
          label: 'Konsultasi Publik',
          route: '',
        },
        {
          label: 'Pelingkupan',
          route: '',
        },
        {
          label: 'Workspace',
          route: '',
        },
      ],
    };
  },
  mounted() {
    this.utama = (this.data.list_sub_project).filter((e) => e.type === 'utama');
    this.pendukung = (this.data.list_sub_project).filter((e) => e.type === 'pendukung');
  },
};
</script>
<style lang="scss" scoped>
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
}
</style>
