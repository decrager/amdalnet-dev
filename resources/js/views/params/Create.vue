<template>
  <div class="app-container" style="padding: 24px">
    <el-form
      ref="categoryForm"
      :model="currentParam"
      label-position="top"
      label-width="200px"
      style="max-width: 100%"
    >
      <el-card>
        <el-row :gutter="20">
          <el-col :span="14">
            <div class="form-container">
              <el-form ref="paramsForm">
                <el-row>
                  <el-form-item label="Nama Parameter" prop="parameter_name">
                    <el-input
                      v-model="currentParam.parameter_name"
                      type="text"
                      placeholder="Nama Parameter"
                    />
                  </el-form-item>
                </el-row>
              </el-form>
              <el-form ref="paramsForm">
                <el-row>
                  <el-form-item label="Judul" prop="title">
                    <el-input
                      v-model="currentParam.title"
                      type="text"
                      placeholder="Judul"
                    />
                  </el-form-item>
                </el-row>
              </el-form>
              <el-form ref="paramsForm">
                <el-row>
                  <el-form-item label="Nilai" prop="value">
                    <el-input
                      v-model="currentParam.value"
                      type="text"
                      placeholder="Nilai"
                    />
                  </el-form-item>
                </el-row>
              </el-form>

              <el-form ref="paramsForm">
                <el-row>
                  <el-form-item
                    label="Apalah Nilai Berupa Angka ?"
                    prop="is_numeric"
                  >
                    <el-radio
                      v-model="currentParam.is_numeric"
                      label="1"
                      :checked="currentParam.is_numeric === true"
                      :value="true"
                    >Ya
                    </el-radio>

                    <el-radio
                      v-model="currentParam.is_numeric"
                      label="0"
                      :checked="currentParam.is_numeric === 1"
                      :value="true"
                    >Tidak
                    </el-radio>
                  </el-form-item>
                </el-row>
              </el-form>
            </div>
          </el-col>
        </el-row>
        <el-row>
          <el-col :span="14">
            <el-button
              class="filter-item"
              :icon="tableView ? 'el-icon-plus' : 'el-icon-edit'"
              type="primary"
              @click="handleSubmit()"
            >
              {{ tableView ? 'Tambah' : 'Ubah' }}
            </el-button>
            <el-table
              v-if="tableView"
              v-loading="loading"
              :data="list"
              fit
              highlight-current-row
              :header-cell-style="{
                background: '#3AB06F',
                color: 'white',
                border: '0',
              }"
            >
              <el-table-column label="No." width="54px">
                <template slot-scope="scope">
                  <span>{{ scope.$index + 1 }}</span>
                </template>
              </el-table-column>

              <el-table-column label="Nama Parameter">
                <template slot-scope="scope">
                  <span>{{ scope.row.parameter_name }}</span>
                </template>
              </el-table-column>

              <el-table-column label="Judul">
                <template slot-scope="scope">
                  <span>{{ scope.row.title }}</span>
                </template>
              </el-table-column>

              <el-table-column label="Nilai">
                <template slot-scope="scope">
                  <span>{{ scope.row.value }}</span>
                </template>
              </el-table-column>

              <el-table-column label="Aksi" width="200px">
                <template slot-scope="scope">
                  <el-button
                    type="text"
                    href="#"
                    icon="el-icon-edit"
                    @click="handleEditForm(scope.row)"
                  >
                    Ubah
                  </el-button>
                  <el-button
                    type="text"
                    href="#"
                    icon="el-icon-delete"
                    @click="handleDelete(scope.row)"
                  >
                    Hapus
                  </el-button>
                </template>
              </el-table-column>
            </el-table>
          </el-col>
          <el-col :span="14" class="parentButton">
            <el-button class="batal" type="" @click="handleCancle">
              {{ 'Batalkan' }}
            </el-button>
          </el-col>
        </el-row>
      </el-card>
    </el-form>
  </div>
</template>

<script>
import Resource from '@/api/resource';
const appParamResource = new Resource('app-params');

export default {
  name: 'CreateParam',
  props: {
    param: {
      type: Object,
      default: null,
    },
  },
  data() {
    return {
      currentParam: {},
      list: [],
      listView: [],
      listViewTitle: '',
      loading: true,
      listQuery: {
        page: 1,
        limit: 10,
      },
      total: 0,
      tableView: Boolean,
    };
  },

  mounted() {
    if (this.$route.params.appParams) {
      this.currentParam = this.$route.params.appParams;
      this.tableView = this.$route.params.tableView;
    }
    console.log(this.currentParam);
    this.getList();
    if (this.$router.currentRoute.name === 'updateParams') {
      this.tableView = false;
    } else {
      this.tableView = true;
    }
  },
  methods: {
    async getList() {
      this.loading = true;
      const { data, total } = await appParamResource.list(this.listQuery);
      this.list = data;
      this.total = total;
      this.loading = false;
    },
    handleSubmit() {
      const formData = new FormData();
      // eslint-disable-next-line no-undef
      _.each(this.currentParam, (value, key) => {
        formData.append(key, value);
      });
      console.log(this.currentParam.id);
      if (this.currentParam.id !== undefined) {
        appParamResource
          .updateMultipart(this.currentParam.id, formData)
          .then((response) => {
            this.$message({
              type: 'success',
              message: 'App Params has been updated successfully',
              duration: 5 * 1000,
            });
            this.$router.push('/master-data/params');
          })
          .catch((error) => {
            console.log(error);
          });
      } else {
        appParamResource
          .store(formData)
          .then((response) => {
            this.$message({
              message:
                'New App Param ' +
                this.currentParam.parameter_name +
                ' has been created successfully.',
              type: 'success',
              duration: 5 * 1000,
            });
            this.currentParam = {};
            this.$router.push('/master-data/params');
          })
          .catch((error) => {
            console.log(error);
          });
      }
    },
    handleEditForm(row) {
      console.log(row);
      const currentParam = this.list.find((element) => element.id === row.id);
      console.log(currentParam);
      this.$router.push({
        name: 'updateParams',
        params: { row, appParams: currentParam },
      });
    },
    handleDelete(rows) {
      console.log(rows);
      this.$confirm(
        'apakah anda yakin akan menghapus ' + rows.id + '. ?',
        'Peringatan',
        {
          confirmButtonText: 'OK',
          cancelButtonText: 'Batal',
          type: 'warning',
        }
      )
        .then(() => {
          appParamResource
            .destroy(rows.id)
            .then((response) => {
              this.$message({
                type: 'success',
                message: 'Hapus Selesai',
              });
              this.getList();
            })
            .catch((error) => {
              console.log(error);
            });
        })
        .catch(() => {
          this.$message({
            type: 'info',
            message: 'Hapus Digagalkan',
          });
        });
    },
    handleCancle() {
      this.$router.push('master-data/params');
    },
  },
};
</script>
<style scoped>
.el-form-item {
  margin-bottom: 0 !important;
}
.edit {
  background-color: #f19e02;
  padding: 0.5rem;
  display: inline-block;
  color: #fff;
}
.delete {
  background-color: #f50103;
  padding: 0.5rem;
  display: inline-block;
  color: #fff;
}
.filter-item {
  float: right;
  margin-bottom: 1rem;
  margin-top: 2rem;
  background-color: #3ab06f;
  color: #fff;
  font-weight: bold;
}
.batal {
  background-color: gray;
  color: #fff;
}
.parentButton {
  justify-content: right;
  display: flex;
  margin-top: 1rem;
}
</style>
