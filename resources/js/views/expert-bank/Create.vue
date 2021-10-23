<template>
  <div class="form-container" style="margin: 24px">
    <el-form
      ref="categoryForm"
      :model="currentExpertBank"
      label-position="top"
      label-width="200px"
      style="max-width: 100%"
    >
      <el-row :gutter="32">
        <el-col :span="12">
          <el-form-item label="Nama" prop="nama">
            <el-input v-model="currentExpertBank.name" />
          </el-form-item>
        </el-col>
        <el-col :span="12">
          <el-form-item label="Alamat" prop="alamat">
            <el-input v-model="currentExpertBank.address" />
          </el-form-item>
        </el-col>
      </el-row>
      <el-row :gutter="32">
        <el-col :span="12">
          <el-form-item label="Email" prop="email">
            <el-input v-model="currentExpertBank.email" />
          </el-form-item>
        </el-col>
        <el-col :span="12">
          <el-form-item
            label="No. HP"
            prop="noHp"
          >
            <el-input v-model="currentExpertBank.mobile_phone_no" />
          </el-form-item>
        </el-col>
      </el-row>
      <el-row :gutter="32">
        <el-col :span="12">
          <el-form-item
            label="Bidang Keahlian"
            prop="bidangKeahlian"
          >
            <el-input v-model="currentExpertBank.expertise" />
          </el-form-item>
        </el-col>
        <el-col :span="12">
          <el-form-item
            label="Instansi"
            prop="instansi"
          >
            <el-input v-model="currentExpertBank.institution" />
          </el-form-item>
        </el-col>
      </el-row>
      <el-row :gutter="32">
        <el-col :span="12">
          <el-form-item label="CV" prop="cv">
            <div
              style="
                    border: 1px solid #ccc;
                    border-radius: 4px;
                    height: 36px;
                  "
            >
              <el-button
                icon="el-icon-document-copy"
                type="primary"
                size="mini"
                style="margin-left: 15px"
                @click="checkCvFile"
              >Upload</el-button>
              <span>{{ cvFileName }}</span>
              <input
                id="cvFile"
                type="file"
                style="display: none"
                @change="checkCvFileSure"
              >
            </div>
          </el-form-item>
        </el-col>
        <el-col :span="12">
          <el-form-item label="Ijasah" prop="ijasah">
            <div
              style="
                    border: 1px solid #ccc;
                    border-radius: 4px;
                    height: 36px;
                  "
            >
              <el-button
                icon="el-icon-document-copy"
                type="primary"
                size="mini"
                style="margin-left: 15px"
                @click="checkIjasahFile"
              >Upload</el-button>
              <span>{{ ijasahFileName }}</span>
              <input
                id="ijasahFile"
                type="file"
                style="display: none"
                @change="checkIjasahFileSure"
              >
            </div>
          </el-form-item>
        </el-col>
      </el-row>
      <el-row :gutter="32">
        <el-col :span="12">
          <el-form-item label="Sertifikat Keahlian dari LUK" prop="certLuk">
            <div
              style="
                    border: 1px solid #ccc;
                    border-radius: 4px;
                    height: 36px;
                  "
            >
              <el-button
                icon="el-icon-document-copy"
                type="primary"
                size="mini"
                style="margin-left: 15px"
                @click="checkCertLukFile"
              >Upload</el-button>
              <span>{{ certLukFileName }}</span>
              <input
                id="certLukFile"
                type="file"
                style="display: none"
                @change="checkCertLukFileSure"
              >
            </div>
          </el-form-item>
        </el-col>
        <el-col :span="12">
          <el-form-item label="Sertifikat Keahlian diluar dari LUK" prop="certNonLuk">
            <div
              style="
                    border: 1px solid #ccc;
                    border-radius: 4px;
                    height: 36px;
                  "
            >
              <el-button
                icon="el-icon-document-copy"
                type="primary"
                size="mini"
                style="margin-left: 15px"
                @click="checkCertNonLukFile"
              >Upload</el-button>
              <span>{{ certNonLukFileName }}</span>
              <input
                id="certNonLukFile"
                type="file"
                style="display: none"
                @change="checkCertNonLukFileSure"
              >
            </div>
          </el-form-item>
        </el-col>
      </el-row>
      <el-row :gutter="32">
        <el-col :span="12">
          <el-form-item label="Status" prop="status">
            <el-radio v-model="currentExpertBank.status" :label="true">Aktif</el-radio>
            <el-radio v-model="currentExpertBank.status" :label="false">Non Aktif</el-radio>
          </el-form-item>
        </el-col>
        <el-col :span="12" />
      </el-row>

    </el-form>
    <div slot="footer" class="dialog-footer">
      <el-button @click="handleCancel()"> Cancel </el-button>
      <el-button type="primary" @click="handleSubmit()"> Confirm </el-button>
    </div>
  </div>
</template>

<script>
import Resource from '@/api/resource';
const expertBankResource = new Resource('expert-banks');

export default {
  name: 'CreatePenyusun',
  data() {
    return {
      currentExpertBank: {},
      sertifikatFileUpload: null,
      sertifikatFileName: null,
      cvFileUpload: null,
      cvFileName: null,
      certLukFileUpload: null,
      certLukFileName: null,
      certNonLukFileUpload: null,
      certNonLukFileName: null,
      ijasahFileName: null,
      ijasahFileUpload: null,
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
    if (this.$route.params.expertBank) {
      this.currentExpertBank = this.$route.params.expertBank;
    }
  },
  methods: {
    checkCvFile() {
      document.querySelector('#cvFile').click();
    },
    checkCvFileSure(e) {
      this.cvFileName = e.target.files[0].name;
      this.cvFileUpload = e.target.files[0];
    },
    checkCertLukFile() {
      document.querySelector('#certLukFile').click();
    },
    checkCertLukFileSure(e) {
      this.certLukFileName = e.target.files[0].name;
      this.certLukFileUpload = e.target.files[0];
    },
    checkCertNonLukFile() {
      document.querySelector('#certNonLukFile').click();
    },
    checkCertNonLukFileSure(e) {
      this.certNonLukFileName = e.target.files[0].name;
      this.certNonLukFileUpload = e.target.files[0];
    },
    checkIjasahFile() {
      document.querySelector('#ijasahFile').click();
    },
    checkIjasahFileSure(e) {
      this.ijasahFileName = e.target.files[0].name;
      this.ijasahFileUpload = e.target.files[0];
    },
    handleCancel() {
      this.$router.push('/master-data/expert-bank');
    },
    handleSubmit() {
      this.currentExpertBank.certLukFileUpload = this.certLukFileUpload;
      this.currentExpertBank.certNonLukFileUpload = this.certNonLukFileUpload;
      this.currentExpertBank.ijasahFileUpload = this.ijasahFileUpload;
      this.currentExpertBank.cvFileUpload = this.cvFileUpload;

      console.log(this.currentExpertBank);

      // make form data because we got file
      const formData = new FormData();

      // eslint-disable-next-line no-undef
      _.each(this.currentExpertBank, (value, key) => {
        formData.append(key, value);
      });

      if (this.currentExpertBank.id !== undefined) {
        expertBankResource
          .updateMultipart(this.currentExpertBank.id, formData)
          .then((response) => {
            this.$message({
              type: 'success',
              message: 'Bank Ahli Berhasil di Update',
              duration: 5 * 1000,
            });
            this.$router.push('/master-data/formulator');
          })
          .catch((error) => {
            console.log(error);
          });
      } else {
        expertBankResource
          .store(formData)
          .then((response) => {
            this.$message({
              message:
                'Bank Ahli atas nama ' +
                this.currentExpertBank.name +
                ' Berhasil Dibuat',
              type: 'success',
              duration: 5 * 1000,
            });
            this.currentExpertBank = {};
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
  },
};
</script>
