<template>
  <div>
    <div><h3 style="color: #3ab06f">Hasil Konsultasi Publik</h3></div>
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
              style="width: 100%"
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
          <el-form-item
            label="Alamat Tempat Pelaksanaan Konsultasi Publik"
            prop="address"
          >
            <el-input v-model="postForm.address" />
          </el-form-item>
        </el-col>
      </el-row>
      <el-row :gutter="4">
        <el-col :span="12" :xs="24">
          <el-form-item
            label="Rangkuman Deskriptif atas Harapan Masyarakat"
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
            label="Rangkuman Deskriptif atas Kekhawatiran Masyarakat"
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
              <div style="margin-top: 10px">
                <div
                  v-for="photo in doc_photo"
                  :key="photo.id"
                  class="photo-list"
                >
                  <a :href="photo.filepath" target="_blank" class="photo-link">
                    {{ photo.name }}
                  </a>
                  <a
                    href="#"
                    style="color: #706060"
                    @click.prevent="deleteDocPhoto(photo.id)"
                  >
                    <i class="el-icon-close" />
                    Hapus
                  </a>
                </div>
              </div>
            </div>
          </el-form-item>
        </el-col>
        <el-col :span="12" :xs="24">
          <el-form-item label="Dokumen Pendukung" prop="docs">
            <el-row :gutter="4">
              <el-col :span="2" :xs="12">No.</el-col>
              <el-col :span="12" :xs="12">Tipe Dokumen</el-col>
              <el-col :span="5" :xs="12">File</el-col>
            </el-row>
            <el-row>
              <el-col :span="2" :xs="12">1.</el-col>
              <el-col :span="12" :xs="12">Berita Acara Pelaksanaan</el-col>
              <el-col :span="5" :xs="12">
                <el-upload
                  v-if="!baPelDone"
                  class="upload-demo"
                  :auto-upload="false"
                  :on-change="handleUploadBA"
                  action="#"
                  :show-file-list="false"
                >
                  <el-button size="small" type="primary">Upload</el-button>
                </el-upload>
                <el-button v-else size="small" type="primary">Done</el-button>
              </el-col>
            </el-row>
            <el-row>
              <el-col :span="2" :xs="12">2.</el-col>
              <el-col :span="12" :xs="12">
                Berita Acara Penunjukan Wakil Masyarakat
              </el-col>
              <el-col :span="5" :xs="12">
                <el-upload
                  v-if="!baPenWakDone"
                  class="upload-demo"
                  :auto-upload="false"
                  :on-change="handleUploadBA2"
                  action="#"
                  :show-file-list="false"
                >
                  <el-button size="small" type="primary">Upload</el-button>
                </el-upload>
                <el-button v-else size="small" type="primary">Done</el-button>
              </el-col>
            </el-row>
            <el-row>
              <el-col :span="2" :xs="12">3.</el-col>
              <el-col :span="12" :xs="12">Daftar Hadir</el-col>
              <el-col :span="5" :xs="12">
                <el-upload
                  v-if="!daftarHadirDone"
                  class="upload-demo"
                  :auto-upload="false"
                  :on-change="handleUploadDH"
                  action="#"
                  :show-file-list="false"
                >
                  <el-button size="small" type="primary">Upload</el-button>
                </el-upload>
                <el-button v-else size="small" type="primary">Done</el-button>
              </el-col>
            </el-row>
            <el-row>
              <el-col :span="2" :xs="12">4.</el-col>
              <el-col :span="12" :xs="12">Pengumuman</el-col>
              <el-col :span="5" :xs="12">
                <el-upload
                  v-if="!pengumumanDone"
                  class="upload-demo"
                  :auto-upload="false"
                  :on-change="handleUploadP"
                  action="#"
                  :show-file-list="false"
                >
                  <el-button size="small" type="primary">Upload</el-button>
                </el-upload>
                <el-button v-else size="small" type="primary">Done</el-button>
              </el-col>
            </el-row>
            <el-row>
              <el-col :span="2" :xs="12">5.</el-col>
              <el-col :span="12" :xs="12">Undangan</el-col>
              <el-col :span="5" :xs="12">
                <el-upload
                  v-if="!undanganDone"
                  class="upload-demo"
                  :auto-upload="false"
                  :on-change="handleUploadU"
                  action="#"
                  :show-file-list="false"
                >
                  <el-button size="small" type="primary">Upload</el-button>
                </el-upload>
                <el-button v-else size="small" type="primary">Done</el-button>
              </el-col>
            </el-row>
          </el-form-item>
        </el-col>
      </el-row>
    </el-form>
    <div slot="footer" class="dialog-footer" align="right">
      <el-button type="danger" @click="handleBack()"> Kembali </el-button>
      <el-button
        :loading="loading_submit"
        type="warning"
        @click="checkSubmit(false)"
      >
        Simpan Sementara
      </el-button>
      <el-button
        :loading="loading_submit"
        type="primary"
        @click="checkSubmit(true)"
      >
        Simpan & Lanjutkan
      </el-button>
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
const projectResource = new Resource('projects');
const publicConsultations = new Resource('public-consultations');

const defaultForm = {
  id: null,
  announcement_id: 0,
  project_id: 0,
  event_date: '',
  participant: 0,
  location: '',
  address: '',
  positive_feedback_summary: '',
  negative_feedback_summary: '',
  is_publish: false,
  doc_photo_files: [],
  doc_photo_metadatas: [],
  doc_files: [],
  doc_metadatas: [],
  doc_daftar_hadir: {},
  doc_pengumuman: {},
  doc_undangan: {},
  doc_ba_pelaksanaan: {},
  doc_ba_penunjukan_wakil_masyarakat: {},
};

export default {
  name: 'PublicConsultationForm',
  components: { Tinymce, Dropzone },
  data() {
    return {
      postForm: Object.assign({}, defaultForm),
      userId: 0,
      currentProject: {},
      baPelDone: false,
      baPenWakDone: false,
      daftarHadirDone: false,
      pengumumanDone: false,
      undanganDone: false,
      doc_photo: [],
      deleted_photo: [],
      loading_submit: false,
    };
  },
  created() {},
  async mounted() {
    const annId = this.$route.params && this.$route.params.id;
    this.postForm.announcement_id = annId;
    this.userId = this.$store.getters.userId;
    await this.getProjectDetail(annId);
    await this.getPublicConsultation();
  },
  methods: {
    async getPublicConsultation() {
      console.log('aa', this.currentProject);
      const data = await publicConsultations.list({
        idProject: this.currentProject.id,
      });
      this.postForm.id = data.id;
      // this.postForm.announcement_id = data.announcement_id;
      this.postForm.event_date = data.event_date;
      this.postForm.participant = data.participant;
      this.postForm.location = data.location;
      this.postForm.address = data.address;
      this.postForm.positive_feedback_summary = data.positive_feedback_summary;
      this.postForm.negative_feedback_summary = data.negative_feedback_summary;
      this.postForm.is_publish = data.is_publish;

      this.doc_photo = data.docs
        .filter((doc) => {
          const docJson = JSON.parse(doc.doc_json);
          if (docJson.doc_type === 'Foto Dokumentasi') {
            return true;
          }

          return false;
        })
        .map((doc, idx) => {
          const docJson = JSON.parse(doc.doc_json);

          return {
            id: doc.id,
            filepath: docJson.filepath,
            name: `Foto Dokumentasi ${idx + 1}`,
          };
        });
    },
    async getProjectDetail(annId) {
      const data = await announcementResource.get(annId);
      this.postForm.project_id = data.project_id;

      const project = await projectResource.get(data.project_id);
      this.currentProject = project;
    },
    handleBack() {
      this.$router.push({
        name: 'publishProject',
        params: { project: this.currentProject },
      });
    },
    checkSubmit(isPublish) {
      let message = '';
      if (isPublish) {
        message = 'Data yang sudah disimpan, tidak dapat diubah lagi.';
        this.postForm.is_publish = true;
      } else {
        message = 'Data akan disimpan sementara.';
        this.postForm.is_publish = false;
      }
      this.$confirm(`Apakah anda yakin ? ${message}`, 'Warning', {
        confirmButtonText: 'Ya',
        cancelButtonText: 'Tidak',
        type: 'warning',
      }).then(() => {
        this.handleSubmit();
      });
    },
    async handleSubmit() {
      this.loading_submit = true;
      const headers = { 'Content-Type': 'multipart/form-data' };
      const formData = new FormData();
      formData.append('id', this.postForm.id);
      formData.append(
        'data_type',
        this.postForm.id === undefined ? 'new' : 'update'
      );
      formData.append('announcement_id', this.postForm.announcement_id);
      formData.append('project_id', this.postForm.project_id);
      formData.append(
        'event_date',
        typeof this.postForm.event_date === 'object'
          ? this.postForm.event_date.toISOString()
          : new Date(this.postForm.event_date).toISOString()
      );
      formData.append('participant', this.postForm.participant);
      formData.append('location', this.postForm.location);
      formData.append('address', this.postForm.address);
      formData.append(
        'positive_feedback_summary',
        this.postForm.positive_feedback_summary
      );
      formData.append(
        'negative_feedback_summary',
        this.postForm.negative_feedback_summary
      );
      formData.append('doc_files', JSON.stringify(this.postForm.doc_files));
      formData.append(
        'doc_metadatas',
        JSON.stringify(this.postForm.doc_metadatas)
      );
      formData.append(
        'doc_berita_acara_pelaksanaan',
        this.postForm.doc_ba_pelaksanaan
      );
      formData.append(
        'doc_berita_acara_penunjukan_wakil_masyarakat',
        this.postForm.doc_ba_penunjukan_wakil_masyarakat
      );
      formData.append('doc_daftar_hadir', this.postForm.doc_daftar_hadir);
      formData.append('doc_pengumuman', this.postForm.doc_pengumuman);
      formData.append('doc_undangan', this.postForm.doc_undangan);
      formData.append('is_publish', this.postForm.is_publish);
      formData.append('deleted_photo', JSON.stringify(this.deleted_photo));

      for (let i = 0; i < this.postForm.doc_photo_files.length; i++) {
        formData.append(`file-${i}`, this.postForm.doc_photo_files[i]);
      }
      formData.append(
        'doc_photo_metadatas',
        JSON.stringify(this.postForm.doc_photo_metadatas)
      );

      _.each(this.formData, (value, key) => {
        formData.append(key, value);
      });
      // this.$message({
      //   message: 'Mengunggah file...',
      //   type: 'info',
      // });
      await axios
        .post('api/public-consultations', formData, { headers })
        .then((response) => {
          var msg = '';
          var msg_type = '';
          if (response.status === 200) {
            msg = 'Konsultasi Publik berhasil disimpan';
            msg_type = 'success';
          } else {
            msg = 'Error ' + response.status;
            msg_type = 'error';
          }
          this.$message({
            message: msg,
            type: msg_type,
            duration: 5 * 1000,
          });
          this.getPublicConsultation().then(() => {
            this.deleted_photo = [];
            this.$emit('updatepc', {
              id: this.postForm.id,
              isPublish: this.postForm.is_publish,
            });
            this.loading_submit = false;
          });
        })
        .catch((error) => {
          console.log(error.message);
          this.$message({
            message: 'Terjadi kesalahan pada server',
            type: 'error',
            duration: 5 * 1000,
          });
        });
    },
    dropzoneS(file) {
      this.postForm.doc_photo_files.push(file);
      this.postForm.doc_photo_metadatas.push(
        this.createDocJson('Foto Dokumentasi', file)
      );
    },
    dropzoneR(file) {
      this.postForm.doc_photo_files = this.postForm.doc_photo_files.filter(
        (d) => d !== file
      );
      this.postForm.doc_photo_metadatas =
        this.postForm.doc_photo_metadatas.filter(
          (m) => m.filename !== file.name
        );
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
      this.postForm.doc_metadatas.push(
        this.createDocJson('Berita Acara Pelaksanaan', file)
      );
      this.postForm.doc_ba_pelaksanaan = file.raw;
      this.baPelDone = true;
    },
    handleUploadBA2(file, fileList) {
      this.postForm.doc_files.push(file);
      this.postForm.doc_metadatas.push(
        this.createDocJson('Berita Acara Penunjukan Wakil Masyarakat', file)
      );
      this.postForm.doc_ba_penunjukan_wakil_masyarakat = file.raw;
      this.baPenWakDone = true;
    },
    handleUploadDH(file, fileList) {
      this.postForm.doc_files.push(file);
      this.postForm.doc_metadatas.push(
        this.createDocJson('Daftar Hadir', file)
      );
      this.postForm.doc_daftar_hadir = file.raw;
      this.daftarHadirDone = true;
    },
    handleUploadP(file, fileList) {
      this.postForm.doc_files.push(file);
      this.postForm.doc_metadatas.push(this.createDocJson('Pengumuman', file));
      this.postForm.doc_pengumuman = file.raw;
      this.pengumumanDone = true;
    },
    handleUploadU(file, fileList) {
      this.postForm.doc_files.push(file);
      this.postForm.doc_metadatas.push(this.createDocJson('Undangan', file));
      this.postForm.doc_undangan = file.raw;
      this.undanganDone = true;
    },
    deleteDocPhoto(id) {
      this.doc_photo = [...this.doc_photo].filter((doc) => doc.id !== id);
      this.deleted_photo.push(id);
    },
  },
};
</script>

<style scoped>
.photo-link {
  color: #186cac;
}
.photo-link:hover {
  text-decoration: underline;
}
.photo-list {
  display: flex;
  align-items: center;
  justify-content: space-between !important;
}
</style>
