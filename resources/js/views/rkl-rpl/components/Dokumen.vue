<template>
  <div class="app-container" style="padding: 24px">
    <el-card v-loading="loading">
      <h2>
        Submit Dokumen RKL RPL
        <span v-if="isFormulator">ke Pemrakarsa</span>
      </h2>
      <div>
        <a
          v-if="showDocument"
          class="btn-docx"
          :href="'/storage/workspace/' + documentName"
          :download="title"
        >
          Export to .DOCX
        </a>
      </div>
      <el-row :gutter="20" style="margin-top: 20px">
        <el-col :sm="24" :md="14">
          <div class="grid-content bg-purple" />
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
          <ReviewPenyusun v-if="isFormulator" :documenttype="'RKL RPL'" />
          <ReviewPemrakarsa v-if="isInitiator" :documenttype="'RKL RPL'" />
        </el-col>
      </el-row>
    </el-card>
  </div>
</template>

<script>
import Resource from '@/api/resource';
import ReviewPenyusun from '@/views/review-dokumen/ReviewPenyusun';
import ReviewPemrakarsa from '@/views/review-dokumen/ReviewPemrakarsa';
const rklResource = new Resource('matriks-rkl');

export default {
  components: {
    ReviewPenyusun,
    ReviewPemrakarsa,
  },
  data() {
    return {
      idProject: 0,
      projects: '',
      title: '',
      loading: false,
      loadingPDF: false,
      projectId: this.$route.params && this.$route.params.id,
      out: '',
      showDocument: false,
      userInfo: {
        roles: [],
      },
    };
  },
  computed: {
    isFormulator() {
      return this.userInfo.roles.includes('formulator');
    },
    isInitiator() {
      return this.userInfo.roles.includes('initiator');
    },
    documentName() {
      return `${this.$route.params.id}-rkl-rpl.docx`;
    },
  },
  async created() {
    this.userInfo = await this.$store.dispatch('user/getInfo');
    await this.getData();
  },
  methods: {
    async getData() {
      this.loading = true;
      this.title = await rklResource.list({
        idProject: this.$route.params.id,
        projectName: 'true',
      });
      this.projects =
        window.location.origin + `/storage/workspace/${this.documentName}`;
      this.showDocument = true;
      this.loading = false;
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
