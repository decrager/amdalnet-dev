<template>
  <div>
    <MasterAndal
      :list="list"
      :loading="loading"
      @showDetail="showDetail($event)"
    />
    <DetailAndal
      ref="detail"
      :list="list"
      :loading="loading"
      :loadingsubmit="loadingSubmit"
      @handleSubmit="handleSubmit"
    />
  </div>
</template>

<script>
import MasterAndal from '@/views/penyusunan-andal/master-detail/MasterAndal.vue';
import DetailAndal from '@/views/penyusunan-andal/master-detail/DetailAndal.vue';
import Resource from '@/api/resource';
const andalComposingResource = new Resource('andal-composing');

export default {
  name: 'MasterDetail',
  components: {
    MasterAndal,
    DetailAndal,
  },
  data() {
    return {
      list: [],
      idProject: this.$route.params.id,
      loading: false,
      loadingSubmit: false,
      lastTime: null,
    };
  },
  created() {
    this.getCompose();
    this.getLastTime();
  },
  methods: {
    async getCompose() {
      this.loading = true;
      this.list = await andalComposingResource.list({
        idProject: this.idProject,
      });
      this.loading = false;
    },
    async getLastTime() {
      this.lastTime = await andalComposingResource.list({
        lastTime: 'true',
        idProject: this.idProject,
      });
    },
    showDetail({ stage, id }) {
      this.$refs.detail.setActiveAndal(stage, id);
    },
    async handleSubmit() {
      this.loadingSubmit = true;
      const sendForm = this.list.filter((com) => com.type === 'subtitle');
      const time = await andalComposingResource.store({
        analysis: sendForm,
        type: this.lastTime ? 'update' : 'new',
      });
      this.loadingSubmit = false;
      this.lastTime = time;
      await this.getCompose();
      this.$message({
        message: 'Data is saved Successfully',
        type: 'success',
        duration: 5 * 1000,
      });
    },
  },
};
</script>
