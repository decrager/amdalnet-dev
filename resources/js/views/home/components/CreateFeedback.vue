<template>
  <el-dialog
    :title="'Berikan Tanggapan Baru'"
    :visible.sync="show"
    :close-on-click-modal="false"
    :show-close="false"
  >
    <form enctype="multipart/form-data" @submit.prevent="saveFeedback">
      <!-- eslint-disable-next-line vue/html-self-closing -->
      <input v-model="announcementId" type="hidden" />
      <el-form
        ref="feedForm"
        :model="form"
        style="max-width: 100%"
        label-position="top"
        :rules="rules"
      >
        <el-form-item label="Nama" prop="name">
          <el-input
            v-model="form.name"
            autocomplete="off"
            placeholder="Nama Lengkap"
          />
        </el-form-item>

        <div class="input__wrapper">
          <el-form-item label="No. Telepon / Handphone" prop="phone">
            <el-input v-model="form.phone" autocomplete="off" placeholder="">
              <template slot="prepend">+62</template>
            </el-input>
          </el-form-item>
          <el-form-item label="Email" prop="email">
            <el-input v-model="form.email" autocomplete="off" placeholder="" />
          </el-form-item>
        </div>

        <div class="input__wrapper">
          <el-form-item label="NIK" prop="id_card_number">
            <el-input v-model="form.id_card_number" autocomplete="off" />
          </el-form-item>
          <el-form-item label="Unggah Foto Selfie">
            <!-- eslint-disable-next-line vue/html-self-closing -->
            <input
              ref="file"
              type="file"
              class="el-input__inner"
              @change="handleFileUpload()"
            />
            <small v-if="errorSelfie" style="color: red">{{
              errorSelfie
            }}</small>
          </el-form-item>
        </div>

        <div class="input__wrapper">
          <el-form-item label="Peran">
            <el-select
              v-model="form.responder_type_id"
              placeholder="Pilih"
              class="select__peran"
            >
              <el-option
                v-for="item in responders"
                :key="item.id"
                :label="item.name"
                :value="item.id"
              />
            </el-select>
          </el-form-item>
          <el-form-item label="Gender" prop="comunity_gender">
            <el-select
              v-model="form.comunity_gender"
              placeholder="Pilih"
              class="select__peran"
            >
              <el-option
                v-for="item in genders"
                :key="item.id"
                :label="item.name"
                :value="item.id"
              />
            </el-select>
          </el-form-item>
        </div>

        <el-form-item label="Kekhawatiran">
          <el-input v-model="form.concern" type="textarea" autocomplete="off" />
        </el-form-item>
        <el-form-item label="Harapan">
          <el-input
            v-model="form.expectation"
            type="textarea"
            autocomplete="off"
          />
        </el-form-item>
        <el-form-item
          label="Kondisi Lingkungan di Dalam dan Sekitar Lokasi Tapak Proyek"
        >
          <el-input
            v-model="form.environment_condition"
            type="textarea"
            autocomplete="off"
          />
        </el-form-item>
        <el-form-item label="Nilai Lokal yang Berpotensi akan Terkena Dampak">
          <el-input
            v-model="form.local_impact"
            type="textarea"
            autocomplete="off"
          />
        </el-form-item>
        <el-form-item
          label="Berikan rating Anda untuk Rencana Usaha/Kegiatan ini"
        >
          <div class="rating">
            <span>Khawatir </span>
            <el-rate
              v-model="form.rating"
              :colors="['#99A9BF', '#F7BA2A', '#F7BA2A', '#F7BA2A', '#FF9900']"
              :max="5"
              style="align-self: flex-start"
            />
            <span> Harapan</span>
          </div>
        </el-form-item>
        <el-form-item style="margin-top: 10px">
          <el-button
            type="danger"
            size="mini"
            icon="el-icon-circle-close"
            @click="closeDialog()"
          >
            Tutup
          </el-button>

          <el-button
            :loading="loading"
            type="submit"
            size="mini"
            icon="el-icon-s-claim"
            @click="saveFeedback()"
          >
            Simpan
          </el-button>
        </el-form-item>
      </el-form>
    </form>
  </el-dialog>
</template>
<script>
// import Form from 'vue';
import axios from 'axios';
import _ from 'lodash';

export default {
  name: 'CreateFeedback',
  props: {
    feedback: {
      type: Object,
      default: () => {},
    },
    show: Boolean,
    announcementId: {
      type: Number,
      default: 0,
    },
  },
  data() {
    return {
      data: {},
      loading: false,
      form: {
        name: null,
        id_card_number: null,
        phone: null,
        email: null,
        responder_type_id: null,
        concern: null,
        expectation: null,
        announcement_id: 0,
        rating: null,
        comunity_gender: null,
      },
      rules: {
        name: [
          { required: true, message: 'Nama wajib diisi', trigger: 'blur' },
        ],
        comunity_gender: [
          { required: true, message: 'Gender wajib dipilih', trigger: 'blur' },
        ],
        // peran: [{ required: true, message: 'Peran wajib diisi', trigger: 'blur' }],
        id_card_number: [
          { required: true, message: 'NIK wajib diisi' },
          {
            pattern: /([1-9][0-9])(\d{4})(\d{6})(\d{4})/,
            message: 'NIK tidak valid',
            trigger: ['blur', 'change'],
          },
        ],
        email: [
          {
            required: true,
            message: 'Alamat email wajib diisi',
            trigger: 'blur',
          },
          {
            type: 'email',
            message: 'Format alamat email tidak benar',
            trigger: ['blur', 'change'],
          },
        ],
        phone: [
          { required: true, message: 'Nomor Telepon wajib diisi' },
          {
            pattern: /\D*([2-9]\d{2})(\D*)([2-9]\d{2})(\D*)(\d{2})\D*/,
            message: 'Nomor Telepon tidak valid',
            trigger: ['blur', 'change'],
          },
        ],
      },
      responders: [],
      errorMessage: null,
      photo_filepath: null,
      genders: [
        {
          id: '1',
          name: 'Laki-laki',
        },
        {
          id: '2',
          name: 'Perempuan',
        },
      ],
      errorSelfie: null,

      // rules: {
      //   phone: [
      //    { required: true, pattern: /\D*([2-9]\d{2})(\D*)([2-9]\d{2})(\D*)(\d{4})\D*/, message: 'Masukan Data Yang Sesuai', trigger: 'blur' },
      //  ],
      //  email: [
      //    { required: true, pattern: /^(([^<>()[\]\\.,;:\s@"]+(\.[^<>()[\]\\.,;:\s@"]+)*)|(".+"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/, message: 'Masukan Data Yang Sesuai', trigger: 'blur' },
      //  ],
      // },
    };
  },
  async created() {
    await this.getResponderType();
  },
  methods: {
    getAnnouncement() {
      axios.get('/api/announcements').then((response) => {
        return response.data.data;
      });
    },
    closeDialog() {
      // this.show = false;
      this.$emit('handleCloseDialog');
      this.resetForm();
    },
    async getResponderType() {
      await axios.get('api/responder-types').then((response) => {
        this.responders = response.data.data.filter((x) => x.id !== 2);
      });
    },

    handleFileUpload() {
      this.errorSelfie = null;
      this.photo_filepath = null;
      if (this.$refs.file.files[0].size > 1048576) {
        this.errorSelfie = 'Ukuran File Tidak Boleh Melebihi 1 MB';
        return;
      }
      if (!this.isFileImage(this.$refs.file.files[0])) {
        this.errorSelfie = 'File yang diunggah Wajib Berformat Gambar';
        return;
      }
      this.photo_filepath = this.$refs.file.files[0];
    },
    async saveFeedback() {
      // validasi dulu
      this.$refs.feedForm.validate((valid) => {
        if (valid && this.errorSelfie === null) {
          this.loading = true;
          const formData = new FormData();
          formData.append('photo_filepath', this.photo_filepath);
          formData.append('name', this.form.name);
          formData.append('id_card_number', this.form.id_card_number);
          formData.append('phone', this.form.phone);
          formData.append('email', this.form.email);
          formData.append('responder_type_id', this.form.responder_type_id);
          formData.append('concern', this.form.concern);
          formData.append('expectation', this.form.expectation);
          formData.append(
            'environment_condition',
            this.form.environment_condition
          );
          formData.append('local_impact', this.form.local_impact);
          formData.append('rating', this.form.rating);
          formData.append('announcement_id', this.announcementId);
          formData.append('community_gender', this.form.comunity_gender);

          _.each(this.formData, (value, key) => {
            formData.append(key, value);
          });

          const headers = { 'Content-Type': 'multipart/form-data' };
          axios
            .post('api/feedbacks', formData, { headers })
            .then(() => {
              this.closeDialog();
              this.loading = false;
              this.getAnnouncement();
            })
            .catch((error) => {
              this.errorMessage = error.message;
              this.loading = false;
              console.error('There was an error!', error);
            });
        } else {
          console.log('error submit!!');
          return false;
        }
      });
    },
    isFileImage(file) {
      return file && file['type'].split('/')[0] === 'image';
    },
    resetForm() {
      this.$refs['feedForm'].resetFields();
      this.errorSelfie = null;
      this.photo_filepath = null;
      this.form.responder_type_id = null;
      this.form.comunity_gender = null;
      this.form.concern = null;
      this.form.expectation = null;
      this.form.environment_condition = null;
      this.form.local_impact = null;
      this.form.rating = null;
    },
  },
};
</script>

<style scoped>
.input__wrapper {
  display: grid;
  grid-template-columns: 2fr 2fr;
  column-gap: 2rem;
}

.label__peran {
  font-weight: 700;
  line-height: 36px;
}

.select__peran {
  width: 100%;
}

.rating {
  display: flex;
  flex-direction: row;
  margin-bottom: 20px;
  align-items: center;
}
</style>
