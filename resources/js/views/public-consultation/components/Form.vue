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
                  :show-file-list="false"
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
                  :show-file-list="false"
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
                  :show-file-list="false"
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
                  :show-file-list="false"
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
import Resource from '@/api/resource';
import Tinymce from '@/components/Tinymce';
import Dropzone from '@/components/Dropzone';
import axios from 'axios';
import _ from 'lodash';

const announcementResource = new Resource('announcements');

const defaultForm = {
  announcement_id: 0,
  project_id: 0,
  event_date: '',
  participant: 0,
  location: '',
  address: '',
  positive_feedback_summary: '',
  negative_feedback_summary: '',
  doc_files: [],
  doc_metadatas: [],
  doc_berita_acara: {},
  doc_daftar_hadir: {},
  doc_pengumuman: {},
  doc_undangan: {},
};

export default {
  name: 'PublicConsultationForm',
  components: { Tinymce, Dropzone },
  data() {
    return {
      postForm: Object.assign({}, defaultForm),
      userId: 0,
    };
  },
  mounted() {
    const annId = this.$route.params && this.$route.params.id;
    this.postForm.announcement_id = annId;
    this.userId = this.$store.getters.userId;
    this.getProjectId(annId);
  },
  methods: {
    async getProjectId(annId) {
      const data = await announcementResource.get(annId);
      this.postForm.project_id = data.project_id;
    },
    async handleSubmit() {
      console.log(this.postForm);
      const headers = { 'Content-Type': 'multipart/form-data' };
      const formData = new FormData();
      formData.append('announcement_id', this.postForm.announcement_id);
      formData.append('project_id', this.postForm.project_id);
      formData.append('event_date', this.postForm.event_date.toISOString());
      formData.append('participant', this.postForm.participant);
      formData.append('location', this.postForm.location);
      formData.append('address', this.postForm.address);
      formData.append('positive_feedback_summary', this.postForm.positive_feedback_summary);
      formData.append('negative_feedback_summary', this.postForm.negative_feedback_summary);
      formData.append('doc_files', JSON.stringify(this.postForm.doc_files));
      formData.append('doc_metadatas', JSON.stringify(this.postForm.doc_metadatas));
      formData.append('doc_berita_acara', this.postForm.doc_berita_acara);
      formData.append('doc_daftar_hadir', this.postForm.doc_daftar_hadir);
      formData.append('doc_pengumuman', this.postForm.doc_pengumuman);
      formData.append('doc_undangan', this.postForm.doc_undangan);

      _.each(this.formData, (value, key) => {
        formData.append(key, value);
      });
      this.$message({
        message: 'Mengunggah file...',
        type: 'info',
      });
      await axios
        .post('api/public-consultations', formData, { headers })
        .then((response) => {
          console.log(response);
        })
        .catch(error => {
          this.$message({
            message: error.message,
            type: 'error',
            duration: 5 * 1000,
          });
        })
        .finally(() => {
          this.$message({
            message: 'Konsultasi Publik berhasil disimpan',
            type: 'success',
            duration: 5 * 1000,
          });
        });
    },
    dropzoneS(file) {
      this.postForm.doc_files.push(file);
      this.postForm.doc_metadatas.push(this.createDocJson('Foto Dokumentasi', file));
    },
    dropzoneR(file) {
      this.postForm.doc_files = this.postForm.doc_files.filter(d => d !== file);
      this.postForm.doc_metadatas = this.postForm.doc_metadatas.filter(m => m.name !== file.name);
    },
    createDocJson(doc_type, file) {
      return {
        doc_type: doc_type,
        filename: file.name,
        uploaded_by: this.userId,
      };
    },
    handleUploadBA(file, fileList) {
      this.postForm.doc_files.push(file);
      this.postForm.doc_metadatas.push(this.createDocJson('Berita Acara', file));
      this.postForm.doc_berita_acara = file.raw;
    },
    handleUploadDH(file, fileList) {
      this.postForm.doc_files.push(file);
      this.postForm.doc_metadatas.push(this.createDocJson('Daftar Hadir', file));
      this.postForm.doc_daftar_hadir = file.raw;
    },
    handleUploadP(file, fileList) {
      this.postForm.doc_files.push(file);
      this.postForm.doc_metadatas.push(this.createDocJson('Pengumuman', file));
      this.postForm.doc_pengumuman = file.raw;
    },
    handleUploadU(file, fileList) {
      this.postForm.doc_files.push(file);
      this.postForm.doc_metadatas.push(this.createDocJson('Undangan', file));
      this.postForm.doc_undangan = file.raw;
    },
  },
};
</script>
