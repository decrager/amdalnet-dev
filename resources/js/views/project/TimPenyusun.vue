<template>
  <div>
    <div v-if="loading" class="form-container" style="margin: 24px">
      <el-card v-loading="loading" />
    </div>
    <TimPenyusunAmdal
      v-else-if="project.required_doc === 'AMDAL'"
      :project="project"
    />
    <TimPenyusunUKLUPL v-else-if="project.required_doc === 'UKL-UPL'" />
  </div>
</template>

<script>
import Resource from '@/api/resource';
import TimPenyusunAmdal from '@/views/project/components/TimPenyusunAmdal';
import TimPenyusunUKLUPL from '@/views/project/components/TimPenyusunUKLUPL';
const formulatorTeamsResource = new Resource('formulator-teams');

export default {
  components: {
    TimPenyusunAmdal,
    TimPenyusunUKLUPL,
  },
  data() {
    return {
      loading: false,
      project: {},
    };
  },
  created() {
    this.getProject();
  },
  methods: {
    async getProject() {
      this.loading = true;
      this.project = await formulatorTeamsResource.list({
        type: 'project',
        idProject: this.$route.params.id,
      });
      this.loading = false;
    },
  },
};
</script>

<style scoped>
.card-footer {
  margin-top: 2rem;
}
</style>
