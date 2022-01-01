<template>
  <div class="app-container">
    <el-button
      type="success"
      size="small"
      icon="el-icon-check"
      style="margin-bottom: 10px;"
      @click="handleSaveForm()"
    >
      Simpan Perubahan
    </el-button>
    <el-table
      v-loading="loading"
      :data="data"
      fit
      highlight-current-row
      :header-cell-style="{ background: '#6cc26f', color: 'white' }"
      :span-method="arraySpanMethod"
      style="width: 100%"
    >
      <el-table-column label="Komponen Dampak">
        <template slot-scope="scope">
          <div v-if="scope.row.is_stage">
            <b>{{ scope.row.project_stage_name }}</b>
          </div>
          <div v-if="!scope.row.is_stage">
            <el-select
              v-model="scope.row.id_change_type"
              placeholder="Perubahan"
            >
              <el-option
                v-for="item of changeTypes"
                :key="item.id"
                :label="item.name"
                :value="item.id"
              />
            </el-select>
            <template v-if="scope.row.id_change_type === 0">
              <el-input v-model="scope.row.change_type_name" placeholder="Please input text..." :readonly="!isFormulator" />
            </template>
          </div>
        </template>
      </el-table-column>
      <el-table-column label="Komponen Rona Lingkungan Awal" align="left">
        <template slot-scope="scope">
          <span v-if="!scope.row.is_stage">{{ scope.row.rona_awal_name }}</span>
        </template>
      </el-table-column>
      <el-table-column label="" align="left">
        <template slot-scope="scope">
          <span v-if="!scope.row.is_stage">akibat</span>
        </template>
      </el-table-column>
      <el-table-column label="Sumber Dampak" align="left">
        <template slot-scope="scope">
          <span v-if="!scope.row.is_stage">{{ scope.row.component_name }}</span>
        </template>
      </el-table-column>
      <el-table-column label="Besaran Dampak" align="left">
        <template slot-scope="scope">
          <el-input v-if="!scope.row.is_stage" v-model="scope.row.unit" type="textarea" :rows="2" />
        </template>
      </el-table-column>
    </el-table>
  </div>
</template>

<script>
import Resource from '@/api/resource';
import axios from 'axios';
const impactIdtResource = new Resource('impact-identifications');
const changeTypeResource = new Resource('change-types');

export default {
  name: 'JenisBesaranDampakTable',
  data() {
    return {
      loading: true,
      idProject: 0,
      data: [],
      changeTypes: [],
    };
  },
  computed: {
    isFormulator() {
      return this.$store.getters.roles.includes('formulator');
    },
  },
  mounted() {
    this.getData();
  },
  methods: {
    async getData() {
      const { data } = await changeTypeResource.list({});
      data.push({
        id: 0,
        name: 'Lainnya',
      });
      this.changeTypes = data;
      this.idProject = parseInt(this.$route.params && this.$route.params.id);
      await axios.get('api/besaran-dampak/list/' + this.idProject)
        .then(response => {
          this.data = response.data;
          this.loading = false;
        });
    },
    arraySpanMethod({ row, column, rowIndex, columnIndex }) {
      return row.is_stage ? [1, 5] : [1, 1];
    },
    handleSaveForm() {
      impactIdtResource
        .store({
          unit_data: this.data,
        })
        .then((response) => {
          if (response.code === 200) {
            this.$message({
              message: 'Jenis dan besaran dampak berhasil disimpan',
              type: 'success',
              duration: 5 * 1000,
            });
            this.$emit('handleEnableSimpanLanjutkan');
          }
        })
        .catch((err) => {
          this.$message({
            message: err.response.data.message,
            type: 'error',
            duration: 5 * 1000,
          });
        });
    },
  },
};
</script>
