<template>
  <div class="app-container">
    <el-button
      :loading="loadingSubmit"
      type="success"
      size="small"
      icon="el-icon-check"
      style="margin-bottom: 10px;"
      :disabled="isReadOnly"
      @click="!isReadOnly && handleSaveForm()"
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
              <div v-for="form in scope.row.env_manage_plan.forms" :key="form.num">
                <el-tooltip class="item" effect="dark" placement="top-start">
                  <div slot="content">
                    {{ form.description }}
                  </div>
                  <el-button
                    type="default"
                    size="medium"
                    :style="formButtonStyle(form)"
                    :disabled="isReadOnly"
                    @click="!isReadOnly && handleViewPlans(scope.row, form)"
                  >
                    <el-button
                      v-if="isFormulator"
                      type="danger"
                      size="mini"
                      icon="el-icon-close"
                      style="margin-left: 0px; margin-right: 10px;"
                      class="button-action-mini"
                      :disabled="isReadOnly"
                      @click="!isReadOnly && handleDeleteForm(scope.$index, form.id, form.num)"
                    />
                    <!-- <span>{{ trimForm(form.description) }}</span> -->
                    <span>{{ trimForm(form.description) }}</span>
                    <!-- <el-button
                      v-if="isFormulator"
                      type="default"
                      size="mini"
                      class="pull-right button-action-mini"
                      icon="el-icon-edit"
                      @click="handleEditComponent(comp.id)"
                    /> -->
                  </el-button>
                </el-tooltip>
              </div>
              <el-input v-model="scope.row.new_form" :disabled="isReadOnly" placeholder="Bentuk Pengelolaan..." type="textarea" :rows="2" />
              <el-button v-if="isFormulator" icon="el-icon-plus" circle style="margin-top:1em;display:block;" round :disabled="isReadOnly" @click="!isReadOnly && handleAddForm(scope.$index)" />
              <small
                v-if="checkError(scope.row.env_manage_plan.errors, 'forms')"
                style="color: #f56c6c; display: block"
              >
                Silahkan Isi Kolom Bentuk Pengelolaan
              </small>
            </div>
          </template>
        </el-table-column>
        <el-table-column label="Lokasi">
          <template slot-scope="scope">
            <div v-if="!scope.row.is_stage">
              <div v-for="location in scope.row.env_manage_plan.locations" :key="location.num">
                <el-tooltip class="item" effect="dark" placement="top-start">
                  <div slot="content">
                    {{ location.description }}
                  </div>
                  <el-button
                    type="default"
                    size="medium"
                    :style="formButtonStyle(location)"
                    :disabled="isReadOnly"
                    @click="!isReadOnly && handleViewPlans(scope.row, location)"
                  >
                    <el-button
                      v-if="isFormulator"
                      type="danger"
                      size="mini"
                      icon="el-icon-close"
                      style="margin-left: 0px; margin-right: 10px;"
                      class="button-action-mini"
                      :disabled="isReadOnly"
                      @click="!isReadOnly && handleDeleteLocation(scope.$index, location.id, location.num)"
                    />
                    <!-- <span>{{ trimForm(form.description) }}</span> -->
                    <span>{{ trimForm(location.description) }}</span>
                    <!-- <el-button
                      v-if="isFormulator"
                      type="default"
                      size="mini"
                      class="pull-right button-action-mini"
                      icon="el-icon-edit"
                      @click="handleEditComponent(comp.id)"
                    /> -->
                  </el-button>
                </el-tooltip>
              </div>
              <el-input v-model="scope.row.new_location" :disabled="isReadOnly" placeholder="Lokasi..." type="textarea" :rows="2" />
              <el-button v-if="isFormulator" icon="el-icon-plus" circle style="margin-top:1em;display:block;" round :disabled="isReadOnly" @click="!isReadOnly && handleAddLocation(scope.$index)" />
              <small
                v-if="checkError(scope.row.env_manage_plan.errors, 'locations')"
                style="color: #f56c6c; display: block"
              >
                Silahkan Isi Kolom Lokasi
              </small>
            </div>
          </template>
        </el-table-column>
        <el-table-column label="Periode">
          <template slot-scope="scope">
            <div v-if="!scope.row.is_stage">
              <el-input
                v-model="scope.row.env_manage_plan.period_number"
                :min="0"
                type="number"
                :max="100"
                :disabled="!isFormulator || isReadOnly"
                size="mini"
              />
              <small
                v-if="checkError(scope.row.env_manage_plan.errors, 'period_number')"
                style="color: #f56c6c; display: block"
              >
                Silahkan Isi Kolom Nomor Periode
              </small>
              x
              <el-select
                v-model="scope.row.env_manage_plan.period_description"
                placeholder="Pilihan"
                :disabled="!isFormulator || isReadOnly"
              >
                <el-option
                  v-for="item in periode"
                  :key="item.value"
                  :label="item.label"
                  :value="item.value"
                  :disabled="isReadOnly"
                />
              </el-select>
              <small
                v-if="checkError(scope.row.env_manage_plan.errors, 'period_description')"
                style="color: #f56c6c; display: block"
              >
                Silahkan Isi Kolom Deskripsi Periode
              </small>
            </div>
          </template>
        </el-table-column>
      </el-table-column>
      <el-table-column :key="iplhKey" label="Institusi Pengelola Lingkungan Hidup">
        <template slot-scope="scope">
          <div v-if="!scope.row.is_stage">
            <div>Pelaksana: <el-input v-model="scope.row.env_manage_plan.executor" :disabled="isReadOnly" /></div>
            <small
              v-if="checkError(scope.row.env_manage_plan.errors, 'executor')"
              style="color: #f56c6c; display: block"
            >
              Silahkan Isi Kolom Pelaksana
            </small>
            <div>Pengawas: <el-input v-model="scope.row.env_manage_plan.supervisor" :disabled="isReadOnly" /></div>
            <small
              v-if="checkError(scope.row.env_manage_plan.errors, 'supervisor')"
              style="color: #f56c6c; display: block"
            >
              Silahkan Isi Kolom Pengawas
            </small>
            <div>Penerima Laporan: <el-input v-model="scope.row.env_manage_plan.report_recipient" :disabled="isReadOnly" /></div>
            <small
              v-if="checkError(scope.row.env_manage_plan.errors, 'report_recipient')"
              style="color: #f56c6c; display: block"
            >
              Silahkan Isi Kolom Penerima Laporan
            </small>
          </div>
        </template>
      </el-table-column>
      <el-table-column :key="descKey" label="Keterangan">
        <template slot-scope="scope">
          <div v-if="!scope.row.is_stage">
            <el-input
              v-model="scope.row.env_manage_plan.description"
              type="textarea"
              :rows="4"
              :disabled="isReadOnly"
            />
            <small
              v-if="checkError(scope.row.env_manage_plan.errors, 'description')"
              style="color: #f56c6c; display: block"
            >
              Silahkan Isi Kolom Keterangan
            </small>
          </div>
        </template>
      </el-table-column>
    </el-table>
  </div>
</template>

<script>
import Resource from '@/api/resource';
import axios from 'axios';
import { mapGetters } from 'vuex';
const impactIdtResource = new Resource('impact-identifications');
// const envManagePlanResource = new Resource('env-manage-plans');

export default {
  name: 'MatriksUklTable',
  data() {
    return {
      idProject: 0,
      currentPlanIdx: 0,
      newEnvManagePlan: {},
      data: [],
      deletedForm: [],
      deletedLocation: [],
      periode: [
        {
          label: 'per Hari',
          value: 'per Hari',
        },
        {
          label: 'per Minggu',
          value: 'per Minggu',
        },
        {
          label: 'per Bulan',
          value: 'per Bulan',
        },
        {
          label: 'per Tahun',
          value: 'per Tahun',
        },
      ],
      loading: true,
      splhKey: 1,
      iplhKey: 1,
      descKey: 1,
      loadingSubmit: false,
    };
  },
  computed: {
    ...mapGetters({
      markingStatus: 'markingStatus',
    }),

    isReadOnly() {
      const data = [
        'uklupl-mt.sent',
        'uklupl-mt.adm-review',
        'uklupl-mt.examination-invitation-drafting',
        'uklupl-mt.examination-invitation-sent',
        'uklupl-mt.examination',
        'uklupl-mt.examination-meeting',
        'uklupl-mt.ba-drafting',
        'uklupl-mt.ba-signed',
        'uklupl-mt.recommendation-drafting',
        'uklupl-mt.recommendation-signed',
        'uklupl-mr.pkplh-published',
        'uklupl-mt.pkplh-published',
      ];

      console.log({ workflow: this.markingStatus });

      return data.includes(this.markingStatus);
    },

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
      await axios.get('api/matriks-ukl-upl/table-ukl/' + this.idProject)
        .then(response => {
          this.data = response.data.map(x => {
            if (!x.is_stage) {
              if (x.env_manage_plan) {
                x.env_manage_plan.forms = x.env_manage_plan.forms.map((y, idx) => {
                  y.num = idx + 1;
                  return y;
                });
                x.env_manage_plan.locations = x.env_manage_plan.locations.map((y, idx) => {
                  y.num = idx + 1;
                  return y;
                });
              } else {
                x.env_manage_plan = {
                  id: null,
                  forms: [],
                  locations: [],
                  period_number: null,
                  period_description: null,
                  supervisor: null,
                  report_recipient: null,
                  executor: null,
                  description: null,
                  is_selected: true,
                };
              }
            }
            return x;
          });
          this.loading = false;
        });
    },
    trimForm(form) {
      return form.length < 10 ? form : form.substring(0, 9) + '...';
    },
    formButtonStyle(plan) {
      if (plan.is_selected) {
        return { backgroundColor: '#facd7a', color: '#000000', marginBottom: '5px' };
      } else {
        return { backgroundColor: '#ffffff', color: '#099C4B', marginBottom: '5px' };
      }
    },
    // handleViewPlans(imp, plan) {
    //   imp.env_manage_plan.forEach((p) => {
    //     p.is_selected = false;
    //   });
    //   plan.is_selected = true;
    //   this.reloadPlanData();
    // },
    arraySpanMethod({ row, column, rowIndex, columnIndex }) {
      if (row.is_stage) {
        return [1, 9];
      }
      return [1, 1];
    },
    handleSaveForm() {
      let errors = 0;
      const data = [...this.data];
      this.data = data.map(x => {
        if (x.env_manage_plan) {
          x.env_manage_plan.errors = {};
          if (!x.env_manage_plan.description || !x.env_manage_plan.executor || x.env_manage_plan.forms.length === 0 || x.env_manage_plan.locations.length === 0 || !x.env_manage_plan.period_number || !x.env_manage_plan.period_description || !x.env_manage_plan.report_recipient || !x.env_manage_plan.supervisor) {
            errors++;
          }

          const formsError = x.env_manage_plan.forms.filter((z) => Boolean(z.description)).length === 0;
          console.log('error form: ', formsError);

          if (formsError) {
            errors++;
          }

          const locationsError =
            x.env_manage_plan.locations.filter((z) => Boolean(z.description)).length === 0;
          console.log('error location: ', locationsError);

          if (locationsError) {
            errors++;
          }

          x.env_manage_plan.errors.description = Boolean(!x.env_manage_plan.description);
          x.env_manage_plan.errors.executor = Boolean(!x.env_manage_plan.executor);
          x.env_manage_plan.errors.period_number = Boolean(!x.env_manage_plan.period_number);
          x.env_manage_plan.errors.period_description = Boolean(!x.env_manage_plan.period_description);
          x.env_manage_plan.errors.report_recipient = Boolean(!x.env_manage_plan.report_recipient);
          x.env_manage_plan.errors.supervisor = Boolean(!x.env_manage_plan.supervisor);
          x.env_manage_plan.errors.forms = formsError;
          x.env_manage_plan.errors.locations = locationsError;
        }
        return x;
      });
      if (errors > 0) {
        this.$alert(
          'Silahkan lengkapi data matriks UKL',
          {
            confirmButtonText: 'OK',
          }
        );
      } else {
        impactIdtResource
          .store({
            env_manage_plan_data: this.data,
            deleted_form: JSON.stringify(this.deletedForm),
            deleted_location: JSON.stringify(this.deletedLocation),
          })
          .then((response) => {
            if (response.code === 200) {
              this.loadingSubmit = false;
              this.$message({
                message: 'Matriks UKL berhasil disimpan',
                type: 'success',
                duration: 5 * 1000,
              });
              this.getData();
              // this.$emit('handleCheckProjectMarking');
            }
          })
          .catch((err) => {
            this.loadingSubmit = false;
            this.$message({
              message: err.response.data.message,
              type: 'error',
              duration: 5 * 1000,
            });
          });
      }
    },
    addPlanToImpact(idImp, plan) {
      const idx = this.data.findIndex(imp => imp.id === idImp);
      if (idx >= 0) {
        this.data[idx].env_manage_plan.push(plan);
      }
    },
    removePlanFromImpact(idImp, idPlan) {
      const idx = this.data.findIndex(imp => imp.id === idImp);
      if (idx >= 0) {
        const idxPlan = this.data[idx].env_manage_plan.findIndex(p => p.id === idPlan);
        if (idxPlan >= 0) {
          this.data[idx].env_manage_plan.splice(idxPlan, 1);
        }
      }
    },
    handleAddForm(idx) {
      if (!this.data[idx].new_form) {
        return;
      }

      const data = this.data[idx].env_manage_plan.forms;
      this.data[idx].env_manage_plan.forms.push({
        num: data.length === 0 ? 1 : data[data.length - 1].num + 1,
        id: null,
        description: this.data[idx].new_form,
      });
      this.data[idx].new_form = null;
      // if (this.newEnvManagePlan[idImp] === undefined ||
      //   this.newEnvManagePlan[idImp] === null ||
      //   this.newEnvManagePlan[idImp].replace(/\s+/g, '').trim() === '') {
      //   this.$message({
      //     message: 'Bentuk Pengelolaan tidak boleh kosong',
      //     type: 'error',
      //     duration: 5 * 1000,
      //   });
      // } else {
      //   envManagePlanResource
      //     .store({
      //       id_impact_identifications: idImp,
      //       form: this.newEnvManagePlan[idImp],
      //     })
      //     .then((response) => {
      //       if (response.code === 200) {
      //         this.$message({
      //           message: 'Bentuk UKL berhasil disimpan',
      //           type: 'success',
      //           duration: 5 * 1000,
      //         });
      //         // add new env_manage_plan to this.data
      //         this.addPlanToImpact(parseInt(idImp), response.data);
      //         this.newEnvManagePlan[idImp] = '';
      //       }
      //     })
      //     .catch((err) => {
      //       this.$message({
      //         message: err.response.data.message,
      //         type: 'error',
      //         duration: 5 * 1000,
      //       });
      //     });
      // }
    },
    handleAddLocation(idx) {
      if (!this.data[idx].new_location) {
        return;
      }

      const data = this.data[idx].env_manage_plan.locations;
      this.data[idx].env_manage_plan.locations.push({
        num: data.length === 0 ? 1 : data[data.length - 1].num + 1,
        id: null,
        description: this.data[idx].new_location,
      });
      this.data[idx].new_location = null;
    },
    handleDeleteForm(idx, id, num) {
      if (id) {
        this.deletedForm.push(id);
      }
      this.data[idx].env_manage_plan.forms = [...this.data[idx].env_manage_plan.forms.filter(x => {
        return x.num !== num;
      })];
    },
    handleDeleteLocation(idx, id, num) {
      if (id) {
        this.deletedLocation.push(id);
      }
      this.data[idx].env_manage_plan.locations = [...this.data[idx].env_manage_plan.locations.filter(x => {
        return x.num !== num;
      })];
    },
    checkError(errors, key) {
      if (errors) {
        return errors[key];
      }

      return false;
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
