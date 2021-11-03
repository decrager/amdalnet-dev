<template>
  <div>
    <announcement-detail
      :show-project-detail="showProjectDetail"
      :show-feedback-list="showFeedbackList"
      :show-public-consultation="showPublicConsultation"
    />
    <div v-if="showCreateFeedback" style="padding: 24px" class="app-container">
      <h2>Saran/Tanggapan untuk Kegiatan</h2>
      <CreateFeedbackSPT />
    </div>
  </div>
</template>

<script>
import AnnouncementDetail from './components/Detail';
import CreateFeedbackSPT from './components/CreateFeedbackSPT.vue';
import { fetchInitiatorByEmail } from '@/api/klhk-role';

export default {
  name: 'ViewForm',
  components: { AnnouncementDetail, CreateFeedbackSPT },
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
