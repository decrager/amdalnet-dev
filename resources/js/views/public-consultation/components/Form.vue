<template>
  <div>
    <div><h2>Hasil Konsultasi Publik</h2></div>
    <el-form
      ref="postForm"
      :model="postForm"
      style="max-width: 100%"
      label-position="top"
      label-width="200px"
    >
      <el-row :gutter="4">
        <el-col :span="4" :xs="24">
          <el-form-item label="Tanggal" prop="event_date">
            <el-date-picker
              v-model="postForm.event_date"
              type="date"
              placeholder="Pick a date"
              style="width: 100%;"
            />
          </el-form-item>
        </el-col>
        <el-col :span="4" :xs="24">
          <el-form-item label="Jumlah Peserta" prop="participant">
            <el-input v-model="postForm.participant" />
          </el-form-item>
        </el-col>
        <el-col :span="6" :xs="24">
          <el-form-item label="Lokasi Tempat Konsultasi Publik" prop="location">
            <el-input v-model="postForm.location" />
          </el-form-item>
        </el-col>
        <el-col :span="10" :xs="24">
          <el-form-item label="Alamat Tempat Pelaksanaan Konsultasi Publik" prop="address">
            <el-input v-model="postForm.address" />
          </el-form-item>
        </el-col>
      </el-row>
      <el-row :gutter="4">
        <el-col :span="12" :xs="24">
          <el-form-item
            label="Rangkuman Deskriptif Saran/Pendapat/Tanggapan Masyarakat yang Mendukung Usaha/Kegiatan"
            prop="positive_feedback_summary"
          >
            <tinymce
              ref="editorP"
              v-model="postForm.positive_feedback_summary"
              :height="200"
            />
          </el-form-item>
        </el-col>
        <el-col :span="12" :xs="24">
          <el-form-item
            label="Rangkuman Deskriptif Saran/Pendapat/Tanggapan Masyarakat yang Menolak Usaha/Kegiatan"
            prop="negative_feedback_summary"
          >
            <tinymce
              ref="editorN"
              v-model="postForm.negative_feedback_summary"
              :height="200"
            />
          </el-form-item>
        </el-col>
      </el-row>
      <el-row :gutter="4">
        <el-col :span="12" :xs="24">
          <el-form-item label="Foto Dokumentasi" prop="docs">
            <div class="components-container">
              <div class="editor-container">
                <Dropzone
                  id="uploadPhoto"
                  url="https://httpbin.org/post"
                  @dropzone-removedFile="dropzoneR"
                  @dropzone-success="dropzoneS"
                />
              </div>
            </div>
          </el-form-item>
        </el-col>
        <el-col :span="12" :xs="24">
          <el-form-item label="Dokumen Pendukung" prop="docs">
            <el-row :gutter="4">
              <el-col :span="2" :xs="12">No.</el-col>
              <el-col :span="5" :xs="12">Tipe Dokumen</el-col>
              <el-col :span="5" :xs="12">File</el-col>
            </el-row>
            <el-row>
              <el-col :span="2" :xs="12">1.</el-col>
              <el-col :span="5" :xs="12">Berita Acara</el-col>
              <el-col :span="5" :xs="12">
                <el-upload
                  class="upload-demo"
                  :auto-upload="false"
                  :on-change="handleUploadBA"
                  action="#"
                  :show-file-list="true"
                >
                  <el-button
                    size="small"
                    type="primary"
                  >Upload</el-button>
                </el-upload>
              </el-col>
            </el-row>
            <el-row>
              <el-col :span="2" :xs="12">2.</el-col>
              <el-col :span="5" :xs="12">Daftar Hadir</el-col>
              <el-col :span="5" :xs="12">
                <el-upload
                  class="upload-demo"
                  :auto-upload="false"
                  :on-change="handleUploadDH"
                  action="#"
                  :show-file-list="true"
                >
                  <el-button
                    size="small"
                    type="primary"
                  >Upload</el-button>
                </el-upload>
              </el-col>
            </el-row>
            <el-row>
              <el-col :span="2" :xs="12">3.</el-col>
              <el-col :span="5" :xs="12">Pengumuman</el-col>
              <el-col :span="5" :xs="12">
                <el-upload
                  class="upload-demo"
                  :auto-upload="false"
                  :on-change="handleUploadP"
                  action="#"
                  :show-file-list="true"
                >
                  <el-button
                    size="small"
                    type="primary"
                  >Upload</el-button>
                </el-upload>
              </el-col>
            </el-row>
            <el-row>
              <el-col :span="2" :xs="12">4.</el-col>
              <el-col :span="5" :xs="12">Undangan</el-col>
              <el-col :span="5" :xs="12">
                <el-upload
                  class="upload-demo"
                  :auto-upload="false"
                  :on-change="handleUploadU"
                  action="#"
                  :show-file-list="true"
                >
                  <el-button
                    size="small"
                    type="primary"
                  >Upload</el-button>
                </el-upload>
              </el-col>
            </el-row>
          </el-form-item>
        </el-col>
      </el-row>
    </el-form>
    <div slot="footer" class="dialog-footer" align="right">
      <el-button type="primary" @click="handleSubmit()"> Continue </el-button>
    </div>
  </div>
</template>

<script>
// import Resource from '@/api/resource';
import Tinymce from '@/components/Tinymce';
import Dropzone from '@/components/Dropzone';
// import Upload from '@/components/Upload/SingleImage';

const defaultForm = {
  event_date: '',
  participant: 0,
  location: '',
  address: '',
  positive_feedback_summary: '',
  negative_feedback_summary: '',
  photos: [],
  docs: [],
};

export default {
  name: 'PublicConsultationForm',
  components: { Tinymce, Dropzone },
  data() {
    return {
      postForm: Object.assign({}, defaultForm),
    };
  },
  mounted() {

  },
  methods: {
    handleSubmit() {

    },
    dropzoneS(file) {
      this.postForm.photos.push(file);
      console.log(this.postForm.photos);
    },
    dropzoneR(file) {
      this.postForm.photos = this.postForm.photos.filter(ph => ph !== file);
      console.log(this.postForm.photos);
    },
    handleUploadBA(file, fileList) {
      this.postForm.docs.push(file);
      console.log(this.postForm.docs);
    },
    handleUploadDH(file, fileList) {
      this.postForm.docs.push(file);
      console.log(this.postForm.docs);
    },
    handleUploadP(file, fileList) {
      this.postForm.docs.push(file);
      console.log(this.postForm.docs);
    },
    handleUploadU(file, fileList) {
      this.postForm.docs.push(file);
      console.log(this.postForm.docs);
    },
  },
};
</script>
