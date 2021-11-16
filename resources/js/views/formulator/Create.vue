<template>
  <div class="form-container" style="margin: 24px">
    <el-form
      ref="categoryForm"
      :model="currentFormulator"
      label-position="top"
      label-width="200px"
      style="max-width: 100%"
    >
      <el-row :gutter="32">
        <el-col :span="12">
          <el-form-item label="Status Keanggotaan" prop="statusKeanggotaan">
            <el-select
              v-model="currentFormulator.membership_status"
              placeholder="Pilih"
              style="width: 100%"
            >
              <el-option
                v-for="item in membershipStatusOptions"
                :key="item.value"
                :label="item.label"
                :value="item.value"
              />
            </el-select>
          </el-form-item>
        </el-col>
        <el-col :span="12">
          <el-form-item label="Nama Penyusun" prop="namaPenyusun">
            <el-input v-model="currentFormulator.name" />
          </el-form-item>
        </el-col>
      </el-row>
      <el-row :gutter="32">
        <el-col :span="12">
          <el-form-item label="Keahlian Penyusun" prop="keahlian Penyusun">
            <el-input v-model="currentFormulator.expertise" />
          </el-form-item>
        </el-col>
        <el-col :span="12">
          <el-form-item
            label="Nomor Sertifikasi Penyusun"
            prop="noSertiPenyusun"
          >
            <el-input v-model="currentFormulator.cert_no" />
          </el-form-item>
        </el-col>
      </el-row>
      <el-row :gutter="32">
        <el-col :span="12">
          <el-col :span="12">
            <el-form-item label="Email" prop="email">
              <el-input v-model="currentFormulator.email" />
            </el-form-item>
          </el-col>
          <el-col :span="12">
            <el-form-item
              label="Dokumen Sertifikat Penyusun"
              prop="dokSerPenyusun"
            >
              <el-upload
                class="upload-demo"
                :auto-upload="false"
                :on-change="handleUploadSertifikat"
                action="#"
                :show-file-list="false"
              >
                <el-button size="small" type="primary">Click to upload</el-button>
                <div slot="tip" class="el-upload__tip">
                  {{ sertifikatFileName || currentFormulator.cert_file }}
                </div>
              </el-upload>
            </el-form-item>
          </el-col>
        </el-col>
        <el-col :span="12">
          <el-form-item label="Nomor Registrasi Penyusun" prop="noRegPenyusun">
            <el-input v-model="currentFormulator.reg_no" />
          </el-form-item>
        </el-col>
      </el-row>
      <el-row :gutter="32">
        <el-col :span="12">
          <el-row :gutter="8">
            <el-col :span="12">
              <el-form-item label="Tanggal Ditetapkan" prop="tglDitetapkan">
                <el-date-picker
                  v-model="currentFormulator.date_start"
                  type="date"
                  placeholder="Pilih tanggal"
                  value-format="yyyy-MM-dd"
                  style="width: 100%"
                />
              </el-form-item>
            </el-col>
            <el-col :span="12">
              <el-form-item label="Terakhir Berlaku" prop="terakhirBerlaku">
                <el-date-picker
                  v-model="currentFormulator.date_end"
                  type="date"
                  placeholder="Pilih tanggal"
                  value-format="yyyy-MM-dd"
                  style="width: 100%"
                />
              </el-form-item>
            </el-col>
          </el-row>
        </el-col>
        <el-col :span="12">
          <el-col :span="12">
            <el-form-item label="CV Penyusun" prop="cvPenyusun">
              <el-upload
                class="upload-demo"
                :auto-upload="false"
                :on-change="handleUploadCv"
                action="#"
                :show-file-list="false"
              >
                <el-button size="small" type="primary">
                  Click to upload
                </el-button>
                <div slot="tip" class="el-upload__tip">
                  {{ cvFileName || currentFormulator.cv_file }}
                </div>
              </el-upload>
            </el-form-item>
          </el-col>
        </el-col>
      </el-row>
      <el-row :gutter="12">
        <el-col :span="12">
          <el-form-item label="Status Keanggotaan" prop="statusKeanggotaan">
            <el-select
              v-model="currentFormulator.id_institution"
              placeholder="Pilih"
              style="width: 100%"
            >
              <el-option
                v-for="item in afiliasiLembagaOption"
                :key="item.value"
                :label="item.label"
                :value="item.value"
              />
            </el-select>
          </el-form-item>
        </el-col>
        <el-col :span="12">
          <el-form-item label="LSP Penerbit" prop="lspPenerbit">
            <el-select
              v-model="currentFormulator.id_lsp"
              placeholder="Pilih"
              style="width: 100%"
            >
              <el-option
                v-for="item in lspPenerbitOption"
                :key="item.value"
                :label="item.label"
                :value="item.value"
              />
            </el-select>
          </el-form-item>
        </el-col>
      </el-row>
    </el-form>
    <div slot="footer" class="dialog-footer">
      <el-button @click="handleCancel()"> Batal </el-button>
      <el-button type="primary" @click="handleSubmit()"> Simpan </el-button>
    </div>
  </div>
</template>

<script>
import Resource from '@/api/resource';
const formulatorResource = new Resource('formulators');

export default {
  name: 'CreatePenyusun',
  data() {
    return {
      currentFormulator: {},
      sertifikatFileUpload: null,
      sertifikatFileName: null,
      cvFileUpload: null,
      cvFileName: null,
      membershipStatusOptions: [
        {
          value: 'KTPA',
          label: 'Ketua Tim Penyusun Amdal (KTPA)',
        },
        {
          value: 'ATPA',
          label: 'Anggota Tim Penyusun Amdal (ATPA)',
        },
      ],
      afiliasiLembagaOption: [
        {
          value: 0,
          label: 'Tidak Terafiliasi',
        },
        {
          value: 1,
          label: 'CV. Amdal Abadi',
        },
      ],
      lspPenerbitOption: [
        {
          value: 0,
          label: 'LSP-LH INKALINDO',
        },
        {
          value: 1,
          label: 'LSP-LH INTAKINDO',
        },
        {
          value: 2,
          label: 'LSP-LHKI',
        },
        {
          value: 3,
          label: 'LSP-TLIP',
        },
      ],
    };
  },
  mounted() {
    console.log(this.$route.params.formulator);
    if (this.$route.params.formulator) {
      this.currentFormulator = this.$route.params.formulator;
    }
  },
  methods: {
    handleCancel() {
      this.$router.push('/master/formulator');
    },
    handleSubmit() {
      this.currentFormulator.cv_penyusun = this.sertifikatFileUpload;
      this.currentFormulator.file_sertifikat = this.cvFileUpload;

      // make form data because we got file
      const formData = new FormData();

      // eslint-disable-next-line no-undef
      _.each(this.currentFormulator, (value, key) => {
        formData.append(key, value);
      });

      if (this.currentFormulator.id !== undefined) {
        formulatorResource
          .updateMultipart(this.currentFormulator.id, formData)
          .then((response) => {
            this.$message({
              type: 'success',
              message: 'Penyusun Berhasil di Update',
              duration: 5 * 1000,
            });
            this.$router.push('/master-data/formulator');
          })
          .catch((error) => {
            console.log(error);
          });
      } else {
        formulatorResource
          .store(formData)
          .then((response) => {
            this.$message({
              message:
                'Penyusun Baru atas nama ' +
                this.currentFormulator.name +
                ' Berhasil Dibuat',
              type: 'success',
              duration: 5 * 1000,
            });
            this.currentFormulator = {};
            this.$router.push('/master-data/formulator');
          })
          .catch((error) => {
            console.log(error);
          });
      }
    },
    handleUploadSertifikat(file, fileList) {
      this.sertifikatFileName = file.name;
      this.sertifikatFileUpload = file.raw;
    },
    handleUploadCv(file, fileList) {
      this.cvFileName = file.name;
      this.cvFileUpload = file.raw;
    },
  },
};
</script>
