<!-- eslint-disable vue/html-indent -->
<template>
  <div class="app-container" style="padding: 24px">
    <el-card v-loading="loading">
      <h2>
        Submit Dokumen ANDAL RKL RPL
        <span v-if="isFormulator">ke Pemrakarsa</span>
      </h2>
      <el-row :gutter="20" style="margin-top: 20px">
        <el-col :sm="24" :md="14">
          <div style="margin-bottom: 8px">
            <a
              v-if="showDocumentAndal"
              class="btn-docx"
              :href="'/storage/workspace/' + documentNameAndal"
              :download="title"
            >
              Export Dokumen ANDAL to .DOCX
            </a>
          </div>
          <iframe
            v-if="showDocumentAndal"
            :src="
              'https://docs.google.com/gview?url=' +
              projectsAndal +
              '&embedded=true'
            "
            width="100%"
            height="723px"
            frameborder="0"
          />
          <div style="margin-top: 10px; margin-bottom: 8px">
            <a
              v-if="showDocument"
              class="btn-docx"
              :href="'/storage/workspace/' + documentName"
              :download="title"
            >
              Export Dokumen RKL RPL to .DOCX
            </a>
          </div>
          <iframe
            v-if="showDocument"
            :src="
              'https://docs.google.com/gview?url=' + projects + '&embedded=true'
            "
            width="100%"
            height="723px"
            frameborder="0"
          />
        </el-col>
        <el-col :sm="24" :md="10">
          <ReviewPenyusun v-if="isFormulator" :documenttype="'ANDAL RKL RPL'" />
          <ReviewPemrakarsa
            v-if="isInitiator"
            :documenttype="'ANDAL RKL RPL'"
          />
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
const rklResource = new Resource('matriks-rkl');
const andalComposingResource = new Resource('andal-composing');

export default {
  components: {
    ReviewPenyusun,
    ReviewPemrakarsa,
  },
  data() {
    return {
      idProject: 0,
      projects: '',
      projectsAndal: '',
      title: '',
      titleAndal: '',
      loading: false,
      loadingPDF: false,
      projectId: this.$route.params && this.$route.params.id,
      out: '',
      showDocument: false,
      showDocumentAndal: false,
      // userInfo: {
      //   roles: [],
      // },
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
    documentName() {
      return `${this.$route.params.id}-rkl-rpl.docx`;
    },
    documentNameAndal() {
      return `${this.$route.params.id}-andal.docx`;
    },
  },
  async created() {
    this.loading = true;
    // this.userInfo = await this.$store.dispatch('user/getInfo');
    await this.getDataAndal();
    await this.getData();
    this.loading = false;
  },
  methods: {
    async getData() {
      this.title = await rklResource.list({
        idProject: this.$route.params.id,
        projectName: 'true',
      });
      this.projects =
        window.location.origin + `/storage/workspace/${this.documentName}`;
      this.showDocument = true;
    },
    async getDataAndal() {
      this.titleAndal = await andalComposingResource.list({
        idProject: this.$route.params.id,
        projectName: 'true',
      });
      this.projectsAndal =
        window.location.origin + `/storage/workspace/${this.documentNameAndal}`;
      this.showDocumentAndal = true;
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
.btn-docx {
  padding: 10px 20px;
  font-size: 14px;
  border-radius: 4px;
  color: #ffffff;
  background-color: #216221;
  display: inline-block;
  line-height: 1;
  white-space: nowrap;
  cursor: pointer;
  border: 1px solid #216221;
  -webkit-appearance: none;
  text-align: center;
  box-sizing: border-box;
  outline: none;
  margin: 0;
  transition: 0.1s;
  font-weight: 400;
}
</style>
