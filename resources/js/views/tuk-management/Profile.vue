<template>
  <div class="form-container" style="margin: 24px">
    <el-card v-loading="loading">
      <h3>Profil {{ team.name }}</h3>
      <el-form
        ref="categoryForm"
        label-position="top"
        label-width="200px"
        style="max-width: 100%"
      >
        <el-row :gutter="32">
          <el-col :sm="24" :md="6" align="center">
            <el-upload class="avatar-uploader" action="#" :disabled="true">
              <!-- eslint-disable-next-line vue/html-self-closing -->
              <img v-if="imageUrl" :src="imageUrl" class="avatar" />
              <i v-else class="el-icon-user avatar-uploader-icon" />
            </el-upload>
            <el-upload
              action="#"
              :show-file-list="false"
              :multiple="false"
              :auto-upload="false"
              :on-change="handleUpload"
            >
              <el-button type="warning" size="mini">Upload</el-button>
              <small
                v-if="errors.logo"
                style="color: #f56c6c; display: block; margin-top: 3px"
              >
                <span v-for="(error, index) in errors.logo" :key="index">{{
                  error
                }}</span>
              </small>
            </el-upload>
          </el-col>
          <el-col :sm="24" :md="18">
            <el-form-item label="Email">
              <el-input
                v-model="team.email"
                style="width: 100%"
                :class="{ 'is-error': errors.email }"
              />
              <small v-if="errors.email" style="color: #f56c6c">
                <span v-for="(error, index) in errors.email" :key="index">{{
                  error
                }}</span>
              </small>
            </el-form-item>
            <el-form-item label="No. Telepon">
              <el-input
                v-model="team.phone"
                style="width: 100%"
                :class="{ 'is-error': errors.phone }"
              />
              <small v-if="errors.phone" style="color: #f56c6c">
                <span v-for="(error, index) in errors.phone" :key="index">{{
                  error
                }}</span>
              </small>
            </el-form-item>
            <el-form-item label="Alamat">
              <el-input
                v-model="team.address"
                type="textarea"
                style="width: 100%"
                :class="{ 'is-error': errors.address }"
              />
              <small v-if="errors.address" style="color: #f56c6c">
                <span v-for="(error, index) in errors.address" :key="index">{{
                  error
                }}</span>
              </small>
            </el-form-item>
          </el-col>
        </el-row>
      </el-form>
      <div
        style="
          display: flex;
          justify-content: space-between;
          align-items: center;
          margin-bottom: 5px;
        "
      >
        <h4>Daftar Anggota Tim Uji Kelayakan</h4>
      </div>
      <MemberTable :loading="loadingMember" :list="listMember" />
      <div v-if="team.id" style="text-align: right; margin-top: 12px">
        <el-button
          :loading="loadingSubmit"
          type="warning"
          @click="handleSubmit"
        >
          Simpan
        </el-button>
      </div>
      <!-- <project-lpjp-table :selected-member="idLpjp" /> -->
    </el-card>
  </div>
</template>

<script>
import Resource from '@/api/resource';
const tukManagementResource = new Resource('tuk-management');
import MemberTable from '@/views/tuk-management/components/ProfileMemberTable.vue';

export default {
  name: 'ProfileTuk',
  components: {
    MemberTable,
  },
  props: {},
  data() {
    return {
      team: {},
      listMember: [],
      loadingSubmit: false,
      loading: false,
      loadingMember: false,
      members: [],
      imageUrl: '',
      imageRaw: null,
      errors: {},
      userInfo: {},
    };
  },
  created() {
    this.getTeamData();
  },
  methods: {
    async getTeamData() {
      this.loading = true;
      this.userInfo = await this.$store.dispatch('user/getInfo');
      this.team = await tukManagementResource.list({
        type: 'profile',
        email: this.userInfo.email,
      });
      this.imageUrl = this.team.logo;
      this.loading = false;
      await this.getMemberData();
    },
    async getMemberData() {
      this.loadingMember = true;
      this.listMember = await tukManagementResource.list({
        type: 'profileMember',
        email: this.userInfo.email,
      });
      this.loadingMember = false;
    },
    async handleSubmit() {
      this.errors = {};
      this.loadingSubmit = true;
      const formData = new FormData();
      formData.append('profile', 'true');
      formData.append('email', this.team.email);
      formData.append('phone', this.team.phone);
      formData.append('address', this.team.address);
      formData.append('logo', this.imageRaw);
      formData.append('idTeam', this.team.id);

      // === MEMBER ROLES === //
      const members = this.listMember
        .filter((x) => {
          if (x.type === 'luk_member') {
            if (x.role !== 'examiner-secretary') {
              return true;
            }
          }
        })
        .map((y) => {
          return {
            id: y.id,
            role: y.role,
          };
        });

      formData.append('members', JSON.stringify(members));

      const data = await tukManagementResource.store(formData);
      if (data.errors === null) {
        this.$message({
          message: 'Data berhasil disimpan',
          type: 'success',
          duration: 5 * 1000,
        });
        this.getTeamData();
      } else {
        this.errors = data.errors;
        window.scrollTo(0, 0);
      }
      this.loadingSubmit = false;
    },
    handleUpload(file) {
      this.imageUrl = URL.createObjectURL(file.raw);
      this.imageRaw = file.raw;
    },
  },
};
</script>

<style scoped>
.card-footer {
  margin-top: 2rem;
}
</style>

<style>
html {
  scroll-behavior: smooth;
}

.avatar-uploader .el-upload {
  border: 1px dashed #d9d9d9;
  border-radius: 50%;
  cursor: pointer;
  position: relative;
  overflow: hidden;
}
.avatar-uploader .el-upload:hover {
  border-color: #409eff;
}
.avatar-uploader-icon {
  font-size: 28px;
  color: #8c939d;
  width: 178px;
  height: 178px;
  line-height: 178px;
  text-align: center;
}
.avatar {
  width: 178px;
  height: 178px;
  display: block;
}
.is-error .el-input__inner,
.is-error .el-radio__inner,
.is-error .el-textarea__inner {
  border-color: #f56c6c;
}
</style>
