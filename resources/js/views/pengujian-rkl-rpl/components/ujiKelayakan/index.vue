<template>
  <div class="app-container">
    <el-card>
      <WorkFlow />
      <div class="filter-container">
        <h3>TABEL UJI KELAYAKAN</h3>
        <el-button
          v-if="!isFormulator"
          :loading="loadingSubmit"
          class="filter-item"
          type="primary"
          style="font-size: 0.8rem"
          @click="handleSubmit"
        >
          {{ 'Simpan Perubahan' }}
        </el-button>
      </div>
      <el-table
        v-loading="loading"
        :data="list.detail"
        fit
        highlight-current-row
        :header-cell-style="{ background: '#3AB06F', color: 'white' }"
      >
        <el-table-column label="No" width="50px">
          <template slot-scope="scope">
            <span>{{ scope.$index + 1 }}</span>
          </template>
        </el-table-column>

        <el-table-column label="Kriteria Kelayakan">
          <template slot-scope="scope">
            <span v-html="scope.row.description" />
          </template>
        </el-table-column>

        <el-table-column align="center" label="Rekomendasi Ahli">
          <template slot-scope="scope">
            <el-input
              v-model="scope.row.expert_notes"
              type="textarea"
              :rows="2"
              :disabled="isFormulator"
            />
          </template>
        </el-table-column>
      </el-table>
      <el-row :gutter="32">
        <el-col :sm="24" :md="12">
          <h4>Kesimpulan</h4>
          <el-input
            v-model="list.conclusion"
            type="textarea"
            :rows="3"
            :readonly="isFormulator"
          />
        </el-col>
      </el-row>
    </el-card>
  </div>
</template>

<script>
import Resource from '@/api/resource';
import WorkFlow from '@/components/Workflow';
const feasibilityResource = new Resource('feasibility-test');

export default {
  name: 'UjiKelayakan',
  components: {
    WorkFlow,
  },
  data() {
    return {
      list: [],
      kelayakan: [
        {
          label: 'Layak',
          value: 'Layak',
        },
        {
          label: 'Tidak Layak',
          value: 'Tidak Layak',
        },
      ],
      idProject: this.$route.params.id,
      loading: false,
      loadingSubmit: false,
    };
  },
  computed: {
    isFormulator() {
      return this.$store.getters.roles.includes('formulator');
    },
  },
  async created() {
    this.$store.dispatch('getStep', 6);
    this.getFeasibility();
  },
  methods: {
    async getFeasibility() {
      this.loading = true;
      const data = await feasibilityResource.list({
        idProject: this.idProject,
      });
      this.list = data;
      this.loading = false;
    },
    async handleSubmit() {
      this.loadingSubmit = true;
      await feasibilityResource.store({
        feasibility: this.list,
        document_type:
          this.$route.name === 'pengujianRKLRPL' ? 'rkl-rpl' : 'ukl-upl',
      });
      this.loadingSubmit = false;
      this.$message({
        message: 'Data is saved Successfully',
        type: 'success',
        duration: 5 * 1000,
      });
      this.getFeasibility();
    },
  },
};
</script>

<style scoped>
.filter-container {
  display: flex;
  justify-content: space-between;
  align-items: center;
}
</style>
