<template>
  <div>
    <MasterAndal
      :list="list"
      :loading="loading"
      @showDetail="showDetail($event)"
      @backuplist="backupList"
    />
    <DetailAndal
      ref="detail"
      :list="list"
      :loading="loading"
      :loadingsubmit="loadingSubmit"
      @handleSubmit="handleSubmit"
      @backuplist="backupList"
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
      listBackup: [],
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
      const data = await andalComposingResource.list({
        idProject: this.idProject,
      });
      this.list = [...data];
      this.listBackup = data.map((x) => {
        const newObj = {};
        // eslint-disable-next-line no-undef
        _.each(x, (value, key) => {
          if (key === 'important_trait') {
            const newIt = value.map((y) => {
              return { ...y };
            });
            newObj[key] = newIt;
          } else {
            newObj[key] = value;
          }
        });

        return newObj;
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
      this.destroyDetail();
    },
    async handleSubmit() {
      this.loadingSubmit = true;
      const sendForm = this.list.filter((com) => com.type === 'subtitle');
      const time = await andalComposingResource.store({
        analysis: sendForm,
        type: this.lastTime ? 'update' : 'new',
        idProject: this.$route.params.id,
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
    backupList() {
      this.list = this.listBackup.map((x) => {
        const newObj = {};
        // eslint-disable-next-line no-undef
        _.each(x, (value, key) => {
          if (key === 'important_trait') {
            const newIt = value.map((y) => {
              return { ...y };
            });
            newObj[key] = newIt;
          } else {
            newObj[key] = value;
          }
        });

        return newObj;
      });
    },
    destroyDetail() {
      this.$refs.detail.destroyForAWhile();
    },
  },
};
</script>
