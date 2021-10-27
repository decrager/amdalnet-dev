<template>
  <el-dialog :title="'Berikan Tanggapan Baru'" :visible.sync="show" :close-on-click-modal="false" :show-close="false">
    <form enctype="multipart/form-data" @submit.prevent="saveFeedback">
      <input v-model="announcementId" type="hidden">
      <el-form :model="form" style="max-width: 100%" label-position="top">
        <el-form-item label="Nama">
          <el-input v-model="form.name" autocomplete="off" />
        </el-form-item>

        <div class="input__wrapper">
          <el-form-item label="No. Telepon / Handphone">
            <el-input v-model="form.phone" autocomplete="off" />
          </el-form-item>
          <el-form-item label="Email">
            <el-input v-model="form.email" autocomplete="off" />
          </el-form-item>
        </div>

        <div class="input__wrapper">
          <el-form-item label="NIK">
            <el-input v-model="form.id_card_number" autocomplete="off" />
          </el-form-item>
          <div>
            <p class="label__peran">Unggah Foto Selfie</p>
            <input ref="file" type="file" class="el-input__inner" @change="handleFileUpload()">
          </div>
        </div>

        <div>
          <p class="label__peran">Peran</p>
          <el-select v-model="form.responder_type_id" placeholder="Pilih" class="select__peran">
            <el-option
              v-for="item in responders"
              :key="item.id"
              :label="item.name"
              :value="item.id"
            />
          </el-select>
        </div>

        <el-form-item label="Kekhawatiran">
          <el-input v-model="form.concern" type="textarea" autocomplete="off" />
        </el-form-item>
        <el-form-item label="Harapan">
          <el-input v-model="form.expectation" type="textarea" autocomplete="off" />
        </el-form-item>
        <el-form-item label="Berikan rating Anda untuk Rencana Usaha/Kegiatan ini">
          <span>1 = Sangat tidak Setuju/Mendukung</span>
          <el-rate
            v-model="form.rating"
            :colors="['#99A9BF', '#F7BA2A', '#F7BA2A', '#F7BA2A', '#FF9900']"
            :max="5"
            style="margin-top:8px;"
          />
          <span>5 = Sangat Setuju/Mendukung</span>
        </el-form-item>

        <el-button
          type="danger"
          size="mini"
          icon="el-icon-circle-close"
          @click="closeDialog()"
        >
          Tutup
        </el-button>

        <el-button
          type="submit"
          size="mini"
          icon="el-icon-s-claim"
          @click="saveFeedback()"
        >
          Simpan
        </el-button>
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
  data(){
    return {
      data: {},
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
      },
      responders: [],
      errorMessage: null,
      photo_filepath: null,
    };
  },
  async created() {
    await this.getResponderType();
  },
  methods: {
    getAnnouncement() {
      axios.get('/api/announcements')
        .then(response => {
          return response.data.data;
        });
    },
    closeDialog() {
      this.show = false;
    },
    async getResponderType() {
      await axios.get('api/responder-types')
        .then(response => {
          this.responders = response.data.data;
        });
    },

    handleFileUpload(){
      this.photo_filepath = this.$refs.file.files[0];
    },
    async saveFeedback() {
      const formData = new FormData();
      formData.append('photo_filepath', this.photo_filepath);
      formData.append('name', this.form.name);
      formData.append('id_card_number', this.form.id_card_number);
      formData.append('phone', this.form.phone);
      formData.append('email', this.form.email);
      formData.append('responder_type_id', this.form.responder_type_id);
      formData.append('concern', this.form.concern);
      formData.append('expectation', this.form.expectation);
      formData.append('rating', this.form.rating);
      formData.append('announcement_id', this.announcementId);

      _.each(this.formData, (value, key) => {
        formData.append(key, value);
      });

      const headers = { 'Content-Type': 'multipart/form-data' };
      await axios
        .post('api/feedbacks', formData, { headers })
        .then(() => {
          this.closeDialog();
          this.getAnnouncement();
        })
        .catch(error => {
          this.errorMessage = error.message;
          console.error('There was an error!', error);
        });
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
</style>
