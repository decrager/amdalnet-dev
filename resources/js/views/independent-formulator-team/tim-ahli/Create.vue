<template>
  <div class="form-container" style="margin: 24px">
    <el-card>
      <h3>Tambah Tenaga Ahli</h3>
      <el-form
        ref="categoryForm"
        :model="currentExpert"
        label-position="top"
        label-width="200px"
        style="max-width: 100%"
      >
        <el-row :gutter="32">
          <el-col :sm="12" :md="12">
            <el-form-item label="Status Peserta" prop="statusPeserta">
              <el-select
                v-model="currentExpert.status"
                placeholder="Status Peserta"
                style="width: 100%"
              >
                <el-option
                  v-for="item in status"
                  :key="item.value"
                  :label="item.label"
                  :value="item.value"
                />
              </el-select>
            </el-form-item>
            <el-form-item label="Nama Tenaga Ahli" prop="namaTenagaAhli">
              <el-input
                v-model="currentExpert.name"
                placeholder="Nama Tenaga Ahli"
              />
            </el-form-item>
          </el-col>
          <el-col :sm="12" :md="12">
            <el-form-item label="Keahlian" prop="keahlian">
              <el-input
                v-model="currentExpert.expertise"
                placeholder="Keahlian"
              />
            </el-form-item>
            <el-form-item label="CV" prop="cv">
              <el-upload
                class="upload-demo"
                :auto-upload="false"
                :on-change="handleUploadChange"
                action="#"
                :show-file-list="false"
              >
                <el-button size="small" type="primary">
                  Click to upload
                </el-button>
                <span style="padding-left: 10px">{{
                  fileName || currentExpert.cv
                }}</span>
              </el-upload>
            </el-form-item>
          </el-col>
        </el-row>
      </el-form>
      <div class="card-footer" style="text-align: right">
        <el-button type="primary" @click="handleSubmit()"> Simpan </el-button>
      </div>
      <h4>Nama Anggota</h4>
      <members-table
        :list="experts"
        :loading="loading"
        :page="listQuery.page"
        :limit="listQuery.limit"
        @handleUpdate="handleUpdate($event)"
        @handleDelete="handleDelete($event)"
      />
      <pagination
        v-show="total > 0"
        :total="total"
        :page.sync="listQuery.page"
        :limit.sync="listQuery.limit"
        @pagination="handleFilter"
      />
    </el-card>
  </div>
</template>

<script>
import Resource from '@/api/resource';
import MembersTable from '@/views/independent-formulator-team/tim-ahli/components/members-table';
import Pagination from '@/components/Pagination';
const expertResource = new Resource('environmental-experts');

export default {
  name: 'CreatePenyusun',
  components: {
    MembersTable,
    Pagination,
  },
  data() {
    return {
      currentExpert: {},
      status: [
        {
          value: 'Tenaga Ahli',
          label: 'Tenaga Ahli',
        },
        {
          value: 'Asisten',
          label: 'Asisten',
        },
      ],
      teamMembers: [],
      experts: [],
      loading: true,
      listQuery: {
        page: 1,
        limit: 10,
      },
      total: 0,
      fileUpload: null,
      fileName: null,
      currentExpertRules: {
        statusPeserta: [
          { required: true, trigger: 'change', message: 'Data Belum Dipilih' },
        ],
        namaTenagaAhli: [
          { required: true, trigger: 'blur', message: 'Data Belum Diisi' },
        ],
        keahlian: [
          { required: true, trigger: 'blur', message: 'Data Belum Diiisi' },
        ],
        cv: [
          { required: true, trigger: 'change', message: 'Data Belum Dipilih' },
        ],
      },
    };
  },
  created() {
    this.getExperts();
  },
  //   mounted() {
  //     if (this.$route.params.formulator) {
  //       this.currentExpert = this.$route.params.formulator;
  //     }
  //   },
  methods: {
    handleFilter() {
      this.getExperts();
    },
    async getExperts() {
      this.loading = true;
      const { data, meta } = await expertResource.list(this.listQuery);
      this.experts = data;
      this.total = meta.total;
      this.loading = false;
    },
    handleUploadChange(file, fileList) {
      this.fileName = file.name;
      this.fileUpload = file.raw;
    },
    handleCancel() {
      //   this.$router.push('/master/formulator');
    },
    handleSubmit() {
      this.currentExpert.cv = this.fileUpload;

      // make form data because we got file
      const formData = new FormData();

      // eslint-disable-next-line no-undef
      _.each(this.currentExpert, (value, key) => {
        formData.append(key, value);
      });

      if (this.currentExpert.id !== undefined) {
        expertResource
          .updateMultipart(this.currentLpjp.id, formData)
          .then((response) => {
            this.$message({
              type: 'success',
              message: 'Expert has been created successfully',
              duration: 5 * 1000,
            });
            this.getExperts();
          })
          .catch((error) => {
            console.log(error);
          });
      } else {
        expertResource
          .store(formData)
          .then((response) => {
            this.$message({
              message:
                'New Expert ' +
                this.currentExpert.name +
                ' has been created successfully.',
              type: 'success',
              duration: 5 * 1000,
            });
            this.currentExpert = {};
            console.log('tes lagi');
            this.getExperts();
          })
          .catch((error) => {
            console.log(error);
          });
      }
    },
    handleDelete({ id, name }) {
      this.$confirm(
        'apakah anda yakin akan menghapus ' + name + '. ?',
        'Peringatan',
        {
          confirmButtonText: 'OK',
          cancelButtonText: 'Batal',
          type: 'warning',
        }
      )
        .then(() => {
          expertResource
            .destroy(id)
            .then((response) => {
              this.$message({
                type: 'success',
                message: 'Hapus Selesai',
              });
              this.getExperts();
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
    handleUpdate(updateExpert) {
      // make form data because we got file
      const formData = new FormData();

      // eslint-disable-next-line no-undef
      _.each(updateExpert, (value, key) => {
        formData.append(key, value);
      });
      expertResource
        .updateMultipart(updateExpert.id, formData)
        .then((response) => {
          this.$message({
            type: 'success',
            message: 'Expert has been updated successfully',
            duration: 5 * 1000,
          });
          this.getExperts();
          console.log(response);
        })
        .catch((error) => {
          console.log(error);
        });
    },
  },
};
</script>

<style scoped>
.card-footer {
  margin-top: 2rem;
}
.button-grey {
  background-color: #b2b0b0;
  color: black;
}
.button-plus {
  height: 3.5rem;
}
.button-table-container {
  margin-top: 1rem;
  text-align: right;
}
</style>
