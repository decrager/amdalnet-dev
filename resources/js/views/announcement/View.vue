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

export default {
  name: 'ViewForm',
  components: { AnnouncementDetail, CreateFeedbackSPT },
  data() {
    const showSPT = this.$store.getters.userId === 85;
    const showPD = this.$store.getters.userId !== 85;
    return {
      showProjectDetail: showPD,
      showCreateFeedback: showPD,
      showFeedbackList: showSPT,
      showPublicConsultation: showSPT,
    };
  },
};
</script>
