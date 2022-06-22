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
        <!--
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
            -->

        <!--
          <el-col
            :span="16"
          >
            <el-form-item label="Kegiatan Utama/Pendukung" prop="project_type">
              <el-input
                v-for="(sP,sPidx) in announcement.sub_project"
                :key="sPidx"
                v-model="announcement.sub_project[sPidx].name"
                disabled
                style="margin-bottom:6px;"
              />
            </el-form-item>
          </el-col>
          <el-col
            :span="8"
          >
            <el-form-item
              label="Skala/Besaran"
              prop="project_scale"
            ><el-input
              v-for="(scP,scPidx) in announcement.sub_project"
              :key="scPidx"
              v-model="announcement.sub_project[scPidx].scale"
              disabled
              style="margin-bottom:6px;"
            />
            </el-form-item>
          </el-col> -->

        <!--<el-form-item label="Lokasi Rencana Usaha dan/atau Kegiatan" prop="project_location">

          <el-input
            v-for="(location,idx) in announcement.project_location"
            :key="idx"
            v-model="announcement.project_location[idx].address"
            disabled
            style="margin-bottom: 6px;"
          />
        </el-form-item>
           -->
        <div style="padding: 1em 2em ; border: 1px solid #e0e0e0; border-radius: 0.5em; margin-bottom: 2em;">
          <div class="el-form-item">
            <p style="font-weight:bold;">Nama Rencana Usaha dan/atau Kegiatan</p>
            <p>{{ announcement.project_type }}</p>
          </div>
          <el-table
            :data="announcement.sub_project"
            :border="true"
            style="width: unset !important; margin: 1em auto 2em; "
          >
            <el-table-column
              type="index"
              width="50"
            />
            <el-table-column
              prop="name"
              label="Kegiatan Utama/Pendukung"
            />
            <el-table-column
              prop="scale"
              label="Besaran"
              align="center"
            />
          </el-table>
          <div>
            <p style="font-weight:bold;">Lokasi Rencana Usaha dan/atau Kegiatan</p>

            <template
              v-for="(location,idx) in announcement.project_location"
            >
              <p :key="idx">{{ idx+1 }}. {{ announcement.project_location[idx].address }}</p>
            </template>
          </div>
        </div>

        <el-form-item ref="fileProofUpload" label="Bukti Pengumuman (Max 1MB)" prop="fileProof">

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
        <el-form-item label="Dampak Potensial" prop="potential_impact">
          <el-input v-model="announcement.potential_impact" type="textarea" />
        </el-form-item>

        <el-row :gutter="8">
          <el-col :span="12">
            <el-form-item label="Pengumuman dimulai tanggal" prop="start_date">
              <el-date-picker
                v-model="announcement.start_date"
                placeholder="Tanggal Awal"
                style="width: 100%"
                value-format="yyyy-M-d"
                :picker-options="dateOptions"
                @change="setEndDate"
              />
            </el-form-item>
          </el-col>
          <el-col :span="12">

            <p style="font-weight:bold;">selama 15 hari, hingga tanggal</p>
            <p>{{ end_date || ((announcement.end_date && announcement.end_date !== '') ? (announcement.end_date.split(" "))[0] : '' ) }}</p>

            <!--
             <el-form-item label="selama 15 hari,  hingga" prop="end_date">
              <el-date-picker
                v-model="end_date"
                read-only
                placeholder="Tanggal Akhir"
                style="width: 100%"
                value-format="yyyy-M-d"
              />
            </el-form-item> -->
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

    </div>
    <el-dialog
      title="Publikasi Pengumuman"
      :visible.sync="confirmPublishDialog"
      append-to-body
      width="30%"
      center
    >
      <span> Lanjutkan publish Pengumuman untuk Rencana Usaha dan/atau Kegiatan <b>{{ announcement.type }}</b>? </span>
      <span slot="footer" class="dialog-footer">
        <el-button @click="confirmPublishDialog = false">Batal</el-button>
        <el-button type="primary" @click="handleSubmitAnnouncement()">Iya, Publish</el-button>
      </span>
    </el-dialog>

    <div slot="footer" class="dialog-footer">
      <el-row>
        <el-col :span="12" style="text-align: left;">
          <el-button type="error" @click="handleCancelAnnouncement()"> Batal </el-button>
          <el-button type="primary" plain @click="handleSaveAnnouncement()"> Simpan </el-button>
        </el-col>
        <el-col :span="12">
          <el-button type="primary" @click="doConfirmPublish"> Publish </el-button>
        </el-col>
      </el-row>
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
      dateOptions: {
        disabledDate: this.disabledPastDates,
      },
      yesterday: null,
      end_date: null,
      confirmPublishDialog: false,
    };
  },
  mounted(){
    console.log(this.announcement);
    const day = new Date();
    this.yesterday = day.setDate(day.getDate() - 1);
  },
  methods: {
    handleSaveAnnouncement(){
      this.$refs.announcement.validate(valid => {
        if (valid) {
          this.announcement.publish = false;
          this.$emit('handleSubmitAnnouncement', this.fileProof);
        } else {
          console.log('error submit!!');
          return false;
        }
      });
    },
    doConfirmPublish(){
      this.$refs.announcement.validate(valid => {
        if (valid) {
          this.confirmPublishDialog = true;
          // this.announcement.publish = true;
          // this.$emit('handleSubmitAnnouncement', this.fileProof);
        } else {
          this.confirmPublishDialog = false;
          // console.log('error submit!!');
          return false;
        }
      });
    },
    handleSubmitAnnouncement() {
      this.confirmPublishDialog = false;
      this.announcement.publish = true;
      this.$emit('handleSubmitAnnouncement', this.fileProof);

      /* this.$refs.announcement.validate(valid => {
        if (valid) {
          this.confirmPublishDialog = true;
          // this.announcement.publish = true;
          // this.$emit('handleSubmitAnnouncement', this.fileProof);
        } else {
          console.log('error submit!!');
          return false;
        }
      });*/
    },
    handleCancelAnnouncement() {
      // console.log(this.announcement.project_location);
      this.fileName = '';
      this.$refs.fileProofUpload.clearValidate();
      this.$refs.announcement.clearValidate();

      this.$emit('handleCancelAnnouncement');
    },
    checkProofFile() {
      document.querySelector('#proofFile').click();
    },
    checkProfFileSure(){
      if (document.querySelector('#proofFile').files[0].size > 1048576){
        this.showFileAlert();
        return;
      }
      this.fileName = document.querySelector('#proofFile').files[0].name;
      this.fileProof = document.querySelector('#proofFile').files[0];
      this.announcement.fileProof = this.fileProof;
      this.$refs.fileProofUpload.clearValidate(); //  Turn off verification
    },
    disabledPastDates(time){
      return time.getTime() < this.yesterday;
    },
    setEndDate(val){
      this.announcement.end_date = null;
      this.end_date = null;

      if (val === null) {
        return;
      }

      console.log(this.announcement.start_date);

      const day = new Date(Date.parse(this.announcement.start_date) + (14 * 86400000));
      const year = day.getFullYear();
      const month = day.getMonth() + 1;
      const date = day.getDate();

      this.announcement.end_date = year + '-' + month + '-' + date;
      this.end_date = this.announcement.end_date;
      console.log(this.announcement.end_date);
    },
  },
};
</script>
