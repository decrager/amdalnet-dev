<template>
  <el-table
    v-loading="loading"
    :data="data"
    fit
    highlight-current-row
    :header-cell-style="{ background: '#6cc26f', color: 'white' }"
    style="width: 100%"
  >
    <el-table-column label="Dampak Penting Hipotetik">
      <template slot-scope="scope">
        <div v-if="scope.row.is_stage">
          <b>{{ scope.row.index }}. {{ scope.row.project_stage_name }}</b>
        </div>
        <div v-if="!scope.row.is_stage">
          {{ scope.row.index }}. {{ scope.row.change_type_name }} {{ scope.row.rona_awal_name }} akibat {{ scope.row.component_name }}
        </div>
      </template>
    </el-table-column>
    <el-table-column label="Data dan Informasi yang Relevan dan Dibutuhkan" align="left">
      <template slot-scope="scope">
        <div v-if="!scope.row.is_stage">
          <el-input v-model="scope.row.impact_study.required_information" type="textarea" :rows="2" :readonly="isAndal || !isFormulator" />
        </div>
      </template>
    </el-table-column>
    <el-table-column label="Metode Pengumpulan Data untuk Prakiraan" align="left">
      <template slot-scope="scope">
        <div v-if="!scope.row.is_stage">
          <el-input v-model="scope.row.impact_study.data_gathering_method" type="textarea" :rows="2" :readonly="isAndal || !isFormulator" />
        </div>
      </template>
    </el-table-column>
    <el-table-column label="Metode Analisis Data untuk Prakiraan" align="left">
      <template slot-scope="scope">
        <div v-if="!scope.row.is_stage">
          <el-input v-model="scope.row.impact_study.analysis_method" type="textarea" :rows="2" :readonly="isAndal || !isFormulator" />
        </div>
      </template>
    </el-table-column>
    <el-table-column label="Metode Prakiraan Dampak Penting" align="left">
      <template slot-scope="scope">
        <div v-if="!scope.row.is_stage">
          <el-input v-model="scope.row.impact_study.forecast_method" type="textarea" :rows="2" :readonly="isAndal || !isFormulator" />
        </div>
      </template>
    </el-table-column>
    <el-table-column label="Metode Evaluasi Dampak Penting" align="left">
      <template slot-scope="scope">
        <div v-if="!scope.row.is_stage">
          <el-input v-model="scope.row.impact_study.evaluation_method" type="textarea" :rows="2" :readonly="isAndal || !isFormulator" />
        </div>
      </template>
    </el-table-column>
  </el-table>
</template>

<script>

export default {
  name: 'MetodeStudiTable',
  props: {
    data: {
      type: Array,
      default: () => [],
    },
  },
  data() {
    return {
      loading: true,
    };
  },
  computed: {
    isAndal() {
      return this.$route.name === 'penyusunanAndal';
    },
    isFormulator() {
      return this.$store.getters.roles.includes('formulator');
    },
  },
  mounted() {
    this.getData();
  },
  methods: {
    getData() {
      this.loading = false;
    },
  },
};
</script>
