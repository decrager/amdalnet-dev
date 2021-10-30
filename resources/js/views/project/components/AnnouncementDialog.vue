<template>
  <el-dialog :title="'Publish Pengumuman'" :visible.sync="show" :close-on-click-modal="false" :show-close="false">
    <div class="form-container">
      <el-form ref="announcement" :model="announcement" :rules="announcementRules">
        <el-row :gutter="8">
          <el-col
            :span="8"
          ><el-form-item label="Nama Penanggung Jawab" prop="pic_name">
            <el-input v-model="announcement.pic_name" /> </el-form-item></el-col>
          <el-col
            :span="16"
          ><el-form-item label="Alamat Penanggung Jawab" prop="pic_address">
            <el-input v-model="announcement.pic_address" /> </el-form-item></el-col>
        </el-row>
        <el-row :gutter="8">
          <el-col
            :span="16"
          >
            <el-form-item label="Jenis Rencana Usaha/Kegiatan" prop="project_type">
              <el-input v-model="announcement.project_type" disabled />
            </el-form-item>
          </el-col>
          <el-col
            :span="8"
          >
            <el-form-item
              label="Skala/Besaran"
              prop="project_scale"
            ><el-input v-model="announcement.project_scale" disabled />
            </el-form-item>
          </el-col>
        </el-row>
        <el-form-item label="Lokasi rencana Atau Kegiatan" prop="project_location">
          <el-input v-model="announcement.project_location" disabled />
        </el-form-item>
        <el-form-item ref="fileProofUpload" label="Bukti Pengumuman" prop="fileProof">
          <el-col :span="24"><div
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
              @click="checkProofFile"
            >Upload</el-button>
            <span>{{ fileName }}</span>
            <input
              id="proofFile"
              type="file"
              style="display: none"
              @change="checkProfFileSure"
            >
          </div></el-col>
        </el-form-item>
        <el-form-item label="Dampak" prop="potential_impact">
          <el-input v-model="announcement.potential_impact" type="textarea" />
        </el-form-item>
        <el-row :gutter="8">
          <el-col :span="12">
            <el-form-item label="Tanggal Mulai" prop="start_date">
              <el-date-picker
                v-model="announcement.start_date"
                placeholder="Pilih Hari"
                style="width: 100%"
                value-format="yyyy-M-d"
              />
            </el-form-item>
          </el-col>
          <el-col :span="12">
            <el-form-item label="Batas Akhir" prop="end_date">
              <el-date-picker
                v-model="announcement.end_date"
                placeholder="Pilih Hari"
                style="width: 100%"
                value-format="yyyy-M-d"
              />
            </el-form-item>
          </el-col>
        </el-row>
        <el-row :gutter="8">
          <el-col :span="8">
            <el-form-item label="Nama Penerima SPT" prop="cs_name">
              <el-input v-model="announcement.cs_name" />
            </el-form-item>
          </el-col>
          <el-col :span="16">
            <el-form-item label="Alamat Penerima SPT" prop="cs_address">
              <el-input v-model="announcement.cs_address" /> </el-form-item></el-col>
        </el-row>
      </el-form>
      <div slot="footer" class="dialog-footer">
        <el-button @click="handleCancelAnnouncement()"> Batal </el-button>
        <el-button type="primary" @click="handleSubmitAnnouncement()"> Simpan </el-button>
      </div>
    </div>
  </el-dialog>
</template>

<script>
export default {
  name: 'AnnouncementDialog',
  props: {
    announcement: {
      type: Object,
      default: () => {},
    },
    show: Boolean,
  },
  data(){
    return {
      fileName: 'File Not Found',
      announcementRules: {
        pic_name: [{ required: true, trigger: 'blur', message: 'Data Belum Diisi' }],
        pic_address: [{ required: true, trigger: 'blur', message: 'Data Belum Diisi' }],
        project_type: [{ required: true, trigger: 'change', message: 'Data Belum Dipilih' }],
        project_scale: [{ required: true, trigger: 'blur', message: 'Data Belum Diisi' }],
        project_location: [{ required: true, trigger: 'blur', message: 'Data Belum Diisi' }],
        potential_impact: [{ required: true, trigger: 'blur', message: 'Data Belum Diisi' }],
        start_date: [{ required: true, trigger: 'blur', message: 'Data Belum Diisi' }],
        end_date: [{ required: true, trigger: 'blur', message: 'Data Belum Diisi' }],
        cs_name: [{ required: true, trigger: 'blur', message: 'Data Belum Diisi' }],
        cs_address: [{ required: true, trigger: 'blur', message: 'Data Belum Diisi' }],
        fileProof: [{ required: true, trigger: 'change', message: 'Data Belum Diinput' }],
      },
    };
  },
  methods: {
    handleSubmitAnnouncement() {
      this.$refs.announcement.validate(valid => {
        if (valid) {
          this.$emit('handleSubmitAnnouncement', this.fileProof);
        } else {
          console.log('error submit!!');
          return false;
        }
      });
    },
    handleCancelAnnouncement() {
      this.$emit('handleCancelAnnouncement');
    },
    checkProofFile() {
      document.querySelector('#proofFile').click();
    },
    checkProfFileSure(){
      this.fileName = document.querySelector('#proofFile').files[0].name;
      this.fileProof = document.querySelector('#proofFile').files[0];
      this.announcement.fileProof = this.fileProof;
      this.$refs.fileProofUpload.clearValidate(); //  Turn off verification
    },
  },
};
</script>
