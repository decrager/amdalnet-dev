<template>
  <div class="app-container" style="padding: 24px">
    <el-card v-loading="loading">
      <workflow-ukl />
      <h2>
        Submit Formulir UKL UPL
        <span v-if="isFormulator">ke Pemrakarsa</span>
      </h2>
      <div>
        <el-button :loading="loading" type="primary" @click="workspace">
          Workspace
        </el-button>
      </div>
      <el-row :gutter="20" style="margin-top: 20px">
        <el-col :sm="24" :md="14">
          <div class="grid-content bg-purple" />
          <iframe
            v-if="pdfUrl !== null"
            :src="`https://docs.google.com/gview?url=${encodeURIComponent(
              pdfUrl
            )}&embedded=true`"
            width="100%"
            height="723px"
            frameborder="0"
          />
        </el-col>
        <el-col :sm="24" :md="10">
          <ReviewPenyusun v-if="isFormulator" :documenttype="'UKL UPL'" />
          <ReviewPemrakarsa v-if="isInitiator" :documenttype="'UKL UPL'" />
        </el-col>
      </el-row>
    </el-card>
  </div>
</template>

<script>
import { mapGetters } from 'vuex';
import ReviewPenyusun from '@/views/review-dokumen/ReviewPenyusun';
import ReviewPemrakarsa from '@/views/review-dokumen/ReviewPemrakarsa';
import axios from 'axios';
import WorkflowUkl from '@/components/WorkflowUkl';

export default {
  components: {
    WorkflowUkl,
    ReviewPenyusun,
    ReviewPemrakarsa,
  },
  data() {
    return {
      loading: false,
      projectName: '',
      pdfUrl: null,
      docxUrl: null,
    };
  },
  computed: {
    ...mapGetters({
      userInfo: 'user',
      userId: 'userId',
    }),
    isFormulator() {
      return this.userInfo.roles.includes('formulator');
    },
    isInitiator() {
      return this.userInfo.roles.includes('initiator');
    },
  },
  async created() {
    this.$store.dispatch('getStep', 5);
    await this.getData();
  },
  methods: {
    async getData() {
      this.loading = true;
      const data = await axios.get(
        `/api/dokumen-ukl-upl/${this.$route.params.id}`
      );
      this.docxUrl = data.data.docx_url;
      this.pdfUrl = data.data.pdf_url;
      this.projectName = data.data.file_name;
      this.loading = false;
    },
    workspace() {
      this.$router.push({
        name: 'projectWorkspace',
        params: {
          id: this.$route.params.id,
          filename: this.projectName,
        },
      });
    },
  },
};
</script>

<style scoped>
.body__section {
  display: flex;
  column-gap: 20px;
  margin-top: 20px;
}

.body__section.left__section {
  flex: 2;
}
.body__section.right__section {
  flex: 1;
}
.heading__comment {
  display: flex;
  column-gap: 15px;
}
.heading__comment.img__comment {
  flex: 0.5;
}
.heading__comment.name__comment {
  flex: 2;
}
.img__comment img {
  width: 32px;
  border-radius: 50%;
  border: 2px solid #099c4b;
}
</style>
