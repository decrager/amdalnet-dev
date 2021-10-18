<template>
  <div class="createPost-container">
    <el-form ref="postForm" :model="postForm" class="form-container">
      <div class="createPost-main-container">
        <el-row>
          <el-col :span="12">
            <el-form-item label="Judul" prop="name">
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
              prop="description"
              style="margin-bottom: 30px"
              label="Saran Pendapat Tanggapan"
            >
              <tinymce
                ref="editor"
                v-model="postForm.description"
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
      </div>
    </el-form>
    <div slot="footer" class="dialog-footer">
      <!-- <el-button @click="handleCancel()"> Batal </el-button> -->
      <el-button type="primary" @click="handleSubmit()"> Kirim </el-button>
    </div>
  </div>
</template>

<script>
import Tinymce from '@/components/Tinymce';
import Resource from '@/api/resource';
import Upload from '@/components/Upload/SingleImage';
const feedbackResource = new Resource('feedbacks');
const responderTypeResource = new Resource('responder_types');

const defaultForm = {
  announcement_id: 0,
  name: '',
  id_card_number: '',
  phone: '',
  email: '',
  photo_filepath: '',
  description: '',
  responder_type_id: 1,
};

export default {
  name: 'FeedbackDetail',
  components: { Tinymce, Upload },
  props: {
    isEdit: {
      type: Boolean,
      default: false,
    },
  },
  data() {
    return {
      postForm: Object.assign({}, defaultForm),
      responderTypes: [],
    };
  },
  created() {
    if (this.isEdit) {
      const id = this.$route.params && this.$route.params.id;
      this.fetchData(id);
    } else {
      this.postForm = Object.assign({}, defaultForm);
      // get announcement ID from query
      this.postForm.announcement_id = this.$route.query.announcement_id;
    }
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
    handleCancel() {
      this.$router.push('/announcement/view/' + this.$route.query.announcement_id);
    },
    handleSubmit() {
      console.log(this.postForm);
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
    },
  },
};
</script>

<style rel="stylesheet/scss" lang="scss" scoped>
@import "~@/styles/mixin.scss";
.createPost-container {
  position: relative;
  .createPost-main-container {
    padding: 0 45px 20px 50px;
    .postInfo-container {
      position: relative;
      @include clearfix;
      margin-bottom: 10px;
      .postInfo-container-item {
        float: left;
      }
    }
  }
  .word-counter {
    width: 40px;
    position: absolute;
    right: -10px;
    top: 0px;
  }
}
</style>
<style>
.createPost-container label.el-form-item__label {
  text-align: left;
}
</style>
