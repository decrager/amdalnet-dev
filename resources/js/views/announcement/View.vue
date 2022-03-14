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
import { mapGetters } from 'vuex';
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
      userInfo: {},
    };
  },
  computed: {
    ...mapGetters({
      'userInfo': 'user',
      'userId': 'userId',
    }),
  },
  created() {
    this.setData();
    this.$store.dispatch('getStep', 2);
  },
  methods: {
    async setData(){
      // this.userInfo = await this.$store.dispatch('user/getInfo');
      if (!this.userInfo.email) {
        return false;
      }

      fetchInitiatorByEmail(this.userInfo.email)
        .then(response => {
          if (response.data.length > 0 || this.userInfo.email === 'admin@laravue.dev') {
            this.showFeedbackList = true;
            this.showPublicConsultation = true;
          } else {
            // this.showProjectDetail = true;
            this.showCreateFeedback = true;
          }
          if (this.userInfo.roles.includes('examiner')) {
            this.showFeedbackList = true;
            this.showPublicConsultation = false;
            this.showCreateFeedback = false;
          }
        })
        .catch(err => {
          console.log(err);
        });
    },
  },
};
</script>
