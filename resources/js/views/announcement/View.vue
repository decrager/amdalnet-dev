<template>
  <div class="app-container" style="padding: 24px">
    <el-card>
      <workflow />
      <announcement-detail
        :show-project-detail="showProjectDetail"
        :show-feedback-list="showFeedbackList"
        :show-public-consultation="showPublicConsultation"
      />
      <div v-if="showCreateFeedback">
        <h2>Saran/Tanggapan untuk Kegiatan</h2>
        <CreateFeedbackSPT />
      </div>
    </el-card>
  </div>
</template>

<script>
import AnnouncementDetail from './components/Detail';
import Workflow from '@/components/Workflow';
import CreateFeedbackSPT from './components/CreateFeedbackSPT.vue';
import { fetchInitiatorByEmail } from '@/api/klhk-role';

export default {
  name: 'ViewForm',
  components: { AnnouncementDetail, CreateFeedbackSPT, Workflow },
  data() {
    return {
      showProjectDetail: false,
      showCreateFeedback: false,
      showFeedbackList: false,
      showPublicConsultation: false,
    };
  },
  mounted() {
    this.setData();
    this.$store.dispatch('getStep', 2);
  },
  methods: {
    async setData(){
      const user = await this.$store.dispatch('user/getInfo');
      fetchInitiatorByEmail(user.email)
        .then(response => {
          if (response.data.length > 0 || user.email === 'admin@laravue.dev') {
            this.showFeedbackList = true;
            this.showPublicConsultation = true;
          } else {
            // this.showProjectDetail = true;
            this.showCreateFeedback = true;
          }
        })
        .catch(err => {
          console.log(err);
        });
    },
  },
};
</script>
