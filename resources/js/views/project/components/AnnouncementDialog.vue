<template>
  <el-dialog :title="'Publish Pengumuman'" :visible.sync="show" :close-on-click-modal="false" :show-close="false">
    <div class="form-container">
      <el-form ref="categoryForm" :model="announcement">
        <el-row :gutter="8">
          <el-col
            :span="8"
          ><el-form-item label="Nama Penanggung Jawab" prop="picName">
            <el-input v-model="announcement.pic_name" /> </el-form-item></el-col>
          <el-col
            :span="16"
          ><el-form-item label="Alamat Penanggung Jawab" prop="picAddress">
            <el-input v-model="announcement.pic_address" /> </el-form-item></el-col>
        </el-row>
        <el-row :gutter="8">
          <el-col
            :span="16"
          >
            <el-form-item label="Jenis Rencana Usaha/Kegiatan" prop="projectType">
              <el-input v-model="announcement.project_type" disabled />
            </el-form-item>
          </el-col>
          <el-col
            :span="8"
          >
            <el-form-item
              label="Skala/Besaran"
              prop="projectScale"
            ><el-input v-model="announcement.project_scale" disabled />
            </el-form-item>
          </el-col>
        </el-row>
        <el-form-item label="Lokasi rencana Atau Kegiatan" prop="projectLoc">
          <el-input v-model="announcement.project_location" disabled />
        </el-form-item>
        <el-form-item label="Bukti Pengumuman" prop="announcement">
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
        <el-form-item label="Dampak" prop="description">
          <el-input v-model="announcement.potential_impact" type="textarea" />
        </el-form-item>
        <el-row :gutter="8">
          <el-col :span="12">
            <el-form-item label="Tanggal Mulai" prop="description">
              <el-date-picker
                v-model="announcement.start_date"
                placeholder="Pick a Date"
                style="width: 100%"
                value-format="yyyy-M-d"
              />
            </el-form-item>
          </el-col>
          <el-col :span="12">
            <el-form-item label="Batas Akhir" prop="description">
              <el-date-picker
                v-model="announcement.end_date"
                placeholder="Pick a Date"
                style="width: 100%"
                value-format="yyyy-M-d"
              />
            </el-form-item>
          </el-col>
        </el-row>
        <el-row :gutter="8">
          <el-col :span="8">
            <el-form-item label="Nama Penerima SPT" prop="description">
              <el-input v-model="announcement.cs_name" />
            </el-form-item>
          </el-col>
          <el-col :span="16">
            <el-form-item label="Alamat Penerima SPT" prop="description">
              <el-input v-model="announcement.cs_address" /> </el-form-item></el-col>
        </el-row>
      </el-form>
      <div slot="footer" class="dialog-footer">
        <el-button @click="handleCancelAnnouncement()"> Cancel </el-button>
        <el-button type="primary" @click="handleSubmitAnnouncement()"> Confirm </el-button>
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
    };
  },
  methods: {
    handleSubmitAnnouncement() {
      this.$emit('handleSubmitAnnouncement', this.fileProof);
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
    },
  },
};
</script>
