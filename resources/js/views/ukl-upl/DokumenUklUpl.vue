<template>
  <div class="app-container" style="padding: 24px">
    <el-card v-loading="loading">
      <workflow-ukl />
      <h2>
        Submit Formulir UKL UPL
        <span v-if="isFormulator">ke Pemrakarsa</span>
      </h2>
      <div>
        <!-- <el-button v-if="projects !== null" type="danger" @click="exportPdf">
          Export to .PDF
        </el-button>
        <a
          v-if="projects !== null"
          class="el-button el-button--primary el-button--medium"
          :href="projects"
          download
        >
          Export to .DOCX
        </a> -->
        <el-button :loading="loading" type="primary" @click="workspace">
          Workspace
        </el-button>
      </div>
      <el-row :gutter="20" style="margin-top: 20px">
        <el-col :sm="24" :md="14">
          <div class="grid-content bg-purple" />
          <iframe
            v-if="projects !== null"
            :src="
              'https://docs.google.com/gview?url=' + projects + '&embedded=true'
            "
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
import Resource from '@/api/resource';
import ReviewPenyusun from '@/views/review-dokumen/ReviewPenyusun';
import ReviewPemrakarsa from '@/views/review-dokumen/ReviewPemrakarsa';
const andalComposingResource = new Resource('andal-composing');
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
      idProject: 0,
      projects: null,
      loading: false,
      projectId: this.$route.params && this.$route.params.id,
      showDocument: true,
      projectName: '',
      userInfo: {
        roles: [],
      },
    };
  },
  computed: {
    ...mapGetters({
      'userInfo': 'user',
      'userId': 'userId',
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
    // this.userInfo = await this.$store.dispatch('user/getInfo');
    await this.getData();
  },
  methods: {
    async getData() {
      this.loading = true;
      const projectName = await axios.get(
        `/api/dokumen-ukl-upl/${this.$route.params.id}`
      );
      this.projects =
        window.location.origin + '/storage/workspace/' + projectName.data;
      this.projectName = projectName.data;
      this.loading = false;
    },
    async exportDocxPhpWord() {
      await andalComposingResource.list({
        idProject: this.$route.params.id,
        formulir: 'true',
      });
    },
    async exportPdf() {
      axios({
        url: `api/dokumen-ukl-upl-pdf/${this.$route.params.id}`,
        method: 'GET',
        responseType: 'blob',
      }).then((response) => {
        // const getHeaders = response.headers['content-disposition'].split('; ');
        // const getFileName = getHeaders[1].split('=');
        // const getName = getFileName[1].split('=');
        var fileURL = window.URL.createObjectURL(new Blob([response.data]));
        var fileLink = document.createElement('a');
        fileLink.href = fileURL;
        fileLink.setAttribute(
          'download',
          `${this.projectName.replace('.docx', '.pdf')}`
        );
        document.body.appendChild(fileLink);
        fileLink.click();
      });
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
