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
      :span-method="arraySpanMethod"
      fit
      highlight-current-row
      :header-cell-style="{ background: '#6cc26f', color: 'white' }"
      style="width: 100%"
    >
      <el-table-column label="No." width="54px">
        <template slot-scope="scope">
          <div v-if="scope.row.is_stage">
            <b>{{ scope.row.project_stage_name }}</b>
          </div>
          <div v-if="!scope.row.is_stage">
            {{ scope.row.index }}.
          </div>
        </template>
      </el-table-column>
      <el-table-column label="Sumber Dampak">
        <template slot-scope="scope">
          <span v-if="!scope.row.is_stage">{{ scope.row.component_name }}</span>
        </template>
      </el-table-column>
      <el-table-column label="Jenis Dampak">
        <template slot-scope="scope">
          <span v-if="!scope.row.is_stage">{{ scope.row.change_type_name }} {{ scope.row.rona_awal_name }} akibat {{ scope.row.component_name }}</span>
        </template>
      </el-table-column>
      <el-table-column label="Besaran Dampak">
        <template slot-scope="scope">
          <span v-if="!scope.row.is_stage">{{ scope.row.unit }}</span>
        </template>
      </el-table-column>
      <el-table-column :key="splhKey" label="Standar Pengelolaan Lingkungan Hidup">
        <el-table-column label="Bentuk">
          <template slot-scope="scope">
            <div v-if="!scope.row.is_stage">
              <div v-for="plan in scope.row.env_monitor_plan" :key="plan.id">
                <el-button
                  type="default"
                  size="medium"
                  :style="formButtonStyle(plan)"
                  @click="handleViewPlans(scope.row, plan)"
                >
                  <el-button
                    v-if="isFormulator"
                    type="danger"
                    size="mini"
                    icon="el-icon-close"
                    style="margin-left: 0px; margin-right: 10px;"
                    class="button-action-mini"
                    @click="handleDeletePlan(plan.id)"
                  />
                  <span>{{ plan.form }}</span>
                  <!-- <el-button
                    v-if="isFormulator"
                    type="default"
                    size="mini"
                    class="pull-right button-action-mini"
                    icon="el-icon-edit"
                    @click="handleEditComponent(comp.id)"
                  /> -->
                </el-button>
              </div>
              <el-input v-model="newEnvMonitorPlan[scope.row.id]" placeholder="Bentuk Pemantauan..." type="textarea" :rows="2" />
              <el-button v-if="isFormulator" icon="el-icon-plus" circle style="margin-top:1em;display:block;" round @click="handleAddPlan(scope.row.id)" />
            </div>
          </template>
        </el-table-column>
        <el-table-column label="Lokasi">
          <template slot-scope="scope">
            <div v-if="!scope.row.is_stage">
              <div v-for="plan in scope.row.env_monitor_plan" :key="plan.id">
                <el-input
                  v-if="plan.is_selected"
                  v-model="plan.location"
                  type="textarea"
                  :rows="2"
                />
              </div>
            </div>
          </template>
        </el-table-column>
        <el-table-column label="Periode">
          <template slot-scope="scope">
            <div v-if="!scope.row.is_stage">
              <div v-for="plan in scope.row.env_monitor_plan" :key="plan.id">
                <el-input
                  v-if="plan.is_selected"
                  v-model="plan.period"
                  type="textarea"
                  :rows="2"
                />
              </div>
            </div>
          </template>
        </el-table-column>
      </el-table-column>
      <el-table-column :key="iplhKey" label="Institut Pengelolaan Lingkungan Hidup">
        <template slot-scope="scope">
          <div v-if="!scope.row.is_stage">
            <div v-for="plan in scope.row.env_monitor_plan" :key="plan.id">
              <div v-if="plan.is_selected">Pelaksana: <el-input v-model="plan.executor" /></div>
              <div v-if="plan.is_selected">Pengawas: <el-input v-model="plan.supervisor" /></div>
              <div v-if="plan.is_selected">Penerima Laporan: <el-input v-model="plan.report_recipient" /></div>
            </div>
          </div>
        </template>
      </el-table-column>
      <el-table-column :key="descKey" label="Keterangan">
        <template slot-scope="scope">
          <div v-if="!scope.row.is_stage">
            <div v-for="plan in scope.row.env_monitor_plan" :key="plan.id">
              <el-input
                v-if="plan.is_selected"
                v-model="plan.description"
                type="textarea"
                :rows="4"
              />
            </div>
          </div>
        </template>
      </el-table-column>
    </el-table>
  </div>
</template>

<script>
import Resource from '@/api/resource';
import axios from 'axios';
const impactIdtResource = new Resource('impact-identifications');
const envMonitorPlanResource = new Resource('env-monitor-plans');

export default {
  name: 'MatriksUplTable',
  data() {
    return {
      idProject: 0,
      currentPlanIdx: 0,
      newEnvMonitorPlan: {},
      data: [],
      loading: true,
      splhKey: 1,
      iplhKey: 1,
      descKey: 1,
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
    reloadPlanData() {
      this.splhKey = this.splhKey + 1;
      this.iplhKey = this.iplhKey + 1;
      this.descKey = this.descKey + 1;
    },
    async getData() {
      this.loading = true;
      this.idProject = parseInt(this.$route.params && this.$route.params.id);
      await axios.get('api/matriks-ukl-upl/table-upl/' + this.idProject)
        .then(response => {
          this.data = response.data;
          this.loading = false;
        });
    },
    formButtonStyle(plan) {
      if (plan.is_selected) {
        return { backgroundColor: '#facd7a', color: '#000000', marginBottom: '5px' };
      } else {
        return { backgroundColor: '#ffffff', color: '#099C4B', marginBottom: '5px' };
      }
    },
    handleViewPlans(imp, plan) {
      imp.env_monitor_plan.forEach((p) => {
        p.is_selected = false;
      });
      plan.is_selected = true;
      this.reloadPlanData();
    },
    arraySpanMethod({ row, column, rowIndex, columnIndex }) {
      if (row.is_stage) {
        return [1, 9];
      }
      return [1, 1];
    },
    handleSaveForm() {
      impactIdtResource
        .store({
          env_monitor_plan_data: this.data,
        })
        .then((response) => {
          if (response.code === 200) {
            this.$message({
              message: 'Matriks UPL berhasil disimpan',
              type: 'success',
              duration: 5 * 1000,
            });
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
    handleAddPlan(idImp) {
      if (this.newEnvMonitorPlan[idImp] === null ||
        this.newEnvMonitorPlan[idImp].replace(/\s+/g, '').trim() === '') {
        this.$message({
          message: 'Bentuk Pemantauan tidak boleh kosong',
          type: 'error',
          duration: 5 * 1000,
        });
      } else {
        envMonitorPlanResource
          .store({
            id_impact_identifications: idImp,
            form: this.newEnvMonitorPlan[idImp],
          })
          .then((response) => {
            if (response.code === 200) {
              this.$message({
                message: 'Bentuk UPL berhasil disimpan',
                type: 'success',
                duration: 5 * 1000,
              });
              this.getData();
            }
          })
          .catch((err) => {
            this.$message({
              message: err.response.data.message,
              type: 'error',
              duration: 5 * 1000,
            });
          });
      }
    },
    handleDeletePlan(id) {
      envMonitorPlanResource
        .destroy(id)
        .then((response) => {
          if (response.code === 200) {
            this.$message({
              message: 'Bentuk UPL berhasil dihapus',
              type: 'success',
              duration: 5 * 1000,
            });
            this.getData();
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
<style scoped>

.button-action-mini {
  margin-left: 20px;
  padding: 1px 1px;
}

.button-medium {
  padding: 5px 5px 5px 5px;
}

.button-comp-active {
  background-color: #facd7a;
}

.button-comp-inactive {
  background-color: white;
}

</style>
