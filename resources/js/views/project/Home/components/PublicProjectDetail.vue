<template>
  <div v-if="data !== null" class="project-detail ph">
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
        <p class="ph-header">Dampak Potensial</p>
        <p class="data">{{ data.potential_impact }}</p>

        <p class="ph-header">Sifat Kegiatan</p>
        <!-- <p class="data"> {{ data.project_type || '-' }} </p> -->
        <p class="data"> {{ [ (data.project_type || '-'), ('Kewenangan ' + (data.authority || '-')), (data.status || '-'), (data.study_approach || '-')].join(', ') }} </p>

        <p class="ph-header">Kegiatan Utama</p>
        <public-project-activities :data="(this.data.list_sub_project).filter((e) => e.type === 'utama')" />

        <p class="ph-header">Kegiatan Pendukung</p>
        <public-project-activities :data="(this.data.list_sub_project).filter((e) => e.type === 'pendukung')" />

      </el-col>
      <el-col :span="4">
        <!--
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
        >Rumah Kegiatan</el-button></p> -->
      </el-col>
    </el-row>
  </div>
</template>
<script>
import PublicProjectActivities from './sections/PublicActivities.vue';
export default {
  name: 'PublicProjectDetail',
  components: { PublicProjectActivities },
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
