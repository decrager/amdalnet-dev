<template>
  <div class="form-container" style="padding: 24px">
    <el-form
      ref="postForm"
      :model="postForm"
      style="max-width: 100%"
      label-position="top"
      label-width="200px"
    >
      <el-row>
        <el-col :span="12">
          <el-form-item label="Nama" prop="name">
            <el-input v-model="postForm.name" />
          </el-form-item>
        </el-col>
      </el-row>
      <el-row>
        <el-col :span="8">
          <el-form-item label="Peran" prop="responder_type_id">
            <el-select
              v-model="postForm.responder_type_id"
              placeholder="Select"
              style="width: 100%"
            >
              <el-option
                v-for="item in responderTypes"
                :key="item.value"
                :label="item.label"
                :value="item.value"
              />
            </el-select>
          </el-form-item>
        </el-col>
      </el-row>
      <el-row :gutter="24">
        <el-col :span="16" :xs="24">
          <el-form-item
            prop="concern"
            style="margin-bottom: 30px"
            label="Kekhawatiran"
          >
            <tinymce
              ref="editorC"
              v-model="postForm.concern"
              :height="200"
            />
          </el-form-item>
        </el-col>
      </el-row>
      <el-row :gutter="24">
        <el-col :span="16" :xs="24">
          <el-form-item
            prop="expectation"
            style="margin-bottom: 30px"
            label="Harapan"
          >
            <tinymce
              ref="editorE"
              v-model="postForm.expectation"
              :height="200"
            />
          </el-form-item>
        </el-col>
      </el-row>
      <el-row>
        <el-col :span="12">
          <el-form-item label="NIK" prop="id_card_number">
            <el-input v-model="postForm.id_card_number" />
          </el-form-item>
        </el-col>
      </el-row>
      <el-row>
        <el-col :span="12">
          <el-form-item label="No. Telepon/Handphone" prop="phone">
            <el-input v-model="postForm.phone" />
          </el-form-item>
        </el-col>
      </el-row>
      <el-row>
        <el-col :span="12">
          <el-form-item label="Email" prop="email">
            <el-input v-model="postForm.email" />
          </el-form-item>
        </el-col>
      </el-row>
      <el-row>
        <el-col :span="24">
          <el-form-item prop="photo_filepath" style="margin-bottom: 30px;">
            <Upload v-model="postForm.photo_filepath" />
          </el-form-item>
        </el-col>
      </el-row>
      <el-row v-if="isEdit">
        <el-col :span="8">
          <el-form-item label="Relevan" prop="is_relevant">
            <el-select
              v-model="postForm.is_relevant"
              placeholder="Select"
              style="width: 100%"
            >
              <el-option
                v-for="item in relevantChoices"
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
      <el-button type="primary" @click="handleSubmit()"> Confirm </el-button>
    </div>
  </div>
</template>

<script>
import Tinymce from '@/components/Tinymce';
import Resource from '@/api/resource';
import Upload from '@/components/Upload/SingleImage';
const feedbackResource = new Resource('feedbacks');
const responderTypeResource = new Resource('responder-types');

const defaultForm = {
  announcement_id: 0,
  name: '',
  id_card_number: '',
  phone: '',
  email: '',
  photo_filepath: '',
  concern: '',
  expectation: '',
  responder_type_id: 1,
  is_relevant: false,
  is_relevant_str: '',
  set_relevant_by: 0,
};

export default {
  name: 'FeedbackForm',
  components: { Tinymce, Upload },
  props: {
    isEdit: {
      type: Boolean,
      default: false,
    },
  },
  data() {
    return {
      id: 0,
      postForm: Object.assign({}, defaultForm),
      responderTypes: [],
      relevantChoices: [],
    };
  },
  created() {
    if (this.isEdit) {
      const id = this.$route.params && this.$route.params.id;
      this.id = id;
      this.fetchData(id);
    } else {
      this.postForm = Object.assign({}, defaultForm);
      // get announcement ID from query
      this.postForm.announcement_id = this.$route.query.announcement_id;
    }
    this.relevantChoices = [
      { value: true, label: 'Ya' },
      { value: false, label: 'Tidak' },
    ];
    this.fetchResponderTypes();
  },
  methods: {
    async fetchData(id) {
      const data = await feedbackResource.get(id);
      this.postForm = data;
    },
    async fetchResponderTypes() {
      const { data } = await responderTypeResource.list({});
      this.responderTypes = data.map((i) => {
        return { value: i.id, label: i.name };
      });
    },
    handleSubmit() {
      if (this.isEdit) {
        if (this.postForm.is_relevant) {
          this.postForm.set_relevant_by = this.$store.getters.userId;
        }
        feedbackResource
          .update(this.id, this.postForm)
          .then(response => {
            this.$message({
              message: 'SPT berhasil diedit',
              type: 'success',
              duration: 5 * 1000,
            });
            // console.log(response);
            this.$router.push('/feedback/edit/' + response.data.id);
          })
          .catch(error => {
            console.log(error);
          });
      } else {
        feedbackResource
          .store(this.postForm)
          .then(response => {
            this.$message({
              message: 'SPT berhasil dikirim',
              type: 'success',
              duration: 5 * 1000,
            });
            // console.log(response);
            this.$router.push('/feedback/edit/' + response.data.id);
          })
          .catch(error => {
            console.log(error);
          });
      }
    },
  },
};
</script>

