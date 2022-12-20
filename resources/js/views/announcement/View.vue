<template>
  <div class="app-container" style="padding: 24px">
    <el-card>
      <workflowUkl v-if="isInitiator && requiredDoc === true" />
      <workflow v-if="isInitiator && requiredDoc === false" />
      <announcement-detail
        :show-project-detail="showProjectDetail"
        :show-feedback-list="showFeedbackList"
        :show-public-consultation="showPublicConsultation"
      />
      <div v-if="showCreateFeedback && isInitiator">
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
import WorkflowUkl from '@/components/WorkflowUkl';
import CreateFeedbackSPT from './components/CreateFeedbackSPT.vue';
import Resource from '@/api/resource';
const projectResource = new Resource('projects');
// import { fetchInitiatorByEmail } from '@/api/klhk-role';

export default {
  name: 'ViewForm',
  components: { AnnouncementDetail, CreateFeedbackSPT, Workflow, WorkflowUkl },
  data() {
    return {
      showProjectDetail: false,
      showCreateFeedback: false,
      showFeedbackList: false,
      requiredDoc: false,
      showPublicConsultation: false,
      // userInfo: {},
    };
  },
  computed: {
    ...mapGetters({
      'userInfo': 'user',
      'userId': 'userId',
    }),
    isInitiator() {
      this.data();
      return this.userInfo.roles.includes('initiator');
    },
  },
  created() {
    // this.setData();
    this.$store.dispatch('getStep', 2);
  },
  methods: {
    data() {
      projectResource.get(this.$route.query.idProject).then(res => {
        const data = res.required_doc;
        if (data === 'UKL-UPL') {
          this.requiredDoc = true;
          return this.requiredDoc;
        } else {
          this.requiredDoc = false;
          return this.requiredDoc;
        }
      });
    },
    // async setData(){
    //   // this.userInfo = await this.$store.dispatch('user/getInfo');
    //   if (!this.userInfo.email) {
    //     return false;
    //   }

    //   fetchInitiatorByEmail(this.userInfo.email)
    //     .then(response => {
    //       if (response.data.length > 0 || this.userInfo.email === 'admin@laravue.dev') {
    //         this.showFeedbackList = true;
    //         this.showPublicConsultation = true;
    //       } else {
    //         // this.showProjectDetail = true;
    //         this.showCreateFeedback = true;
    //       }
    //       if (this.userInfo.roles.includes('examiner')) {
    //         this.showFeedbackList = true;
    //         this.showPublicConsultation = false;
    //         this.showCreateFeedback = false;
    //       }
    //     })
    //     .catch(err => {
    //       console.log(err);
    //     });
    // },
  },
};
</script>
