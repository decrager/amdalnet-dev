<template>
  <div>
    <el-input
      v-if="!isFormulator"
      v-model="comment"
      type="textarea"
      :rows="3"
      placeholder="Silahkan isi komentar"
    />
    <div v-if="!isFormulator" class="send-btn-container">
      <el-button
        :loading="loadingSubmit"
        type="primary"
        size="medium"
        @click="handleSubmit"
      >
        {{ 'Kirim' }}
      </el-button>
    </div>
    <div v-loading="loadingComments">
      <el-card
        v-for="com in comments"
        :key="com.id"
        style="margin-bottom: 10px"
      >
        <div class="comment-header">
          <p>{{ com.user.name }}</p>
          <p>{{ com.updated_at }}</p>
        </div>
        <div class="comment-body">
          {{ com.comment }}
        </div>
      </el-card>
    </div>
  </div>
</template>

<script>
import { mapGetters } from 'vuex';
import Resource from '@/api/resource';
const andalComposingResource = new Resource('andal-composing');

export default {
  name: 'Comment',
  data() {
    return {
      idProject: this.$route.params.id,
      comment: null,
      loadingSubmit: false,
      loadingComments: false,
      // userInfo: {},
      comments: [],
    };
  },
  computed: {
    ...mapGetters({
      'userInfo': 'user',
      'userId': 'userId',
    }),
    isFormulator() {
      return this.$store.getters.roles.includes('formulator');
    },
  },
  created() {
    this.getComments();
  },
  methods: {
    async getComments() {
      // await this.getUserInfo();
      const data = await andalComposingResource.list({
        idProject: this.idProject,
        idUser: this.userInfo.id,
        comment: 'true',
        role: this.$store.getters.roles.includes('formulator') ? 'false' : 'true',
      });
      this.comments = data;
    },
    async handleSubmit() {
      this.loadingSubmit = true;
      const data = await andalComposingResource.store({
        idProject: this.idProject,
        comment: this.comment,
        idUser: this.userInfo.id,
        type: 'comment',
      });
      this.comments.unshift(data);
      this.comment = null;
      this.$message({
        message: 'Komentar berhasil ditambahkan',
        type: 'success',
        duration: 5 * 1000,
      });
      this.loadingSubmit = false;
    },
    // async getUserInfo() {
    //   this.userInfo = await this.$store.dispatch('user/getInfo');
    // },
  },
};
</script>

<style scoped>
.send-btn-container {
  margin-top: 0.5rem;
  text-align: right;
  margin-bottom: 0.8rem;
}
.comment-body {
  font-size: 15px;
}
.comment-header {
  margin-bottom: 22px;
}
.comment-header p {
  font-size: 12px;
  padding: 0;
  margin: 0;
  margin-bottom: 1px;
  font-weight: lighter;
}
</style>
