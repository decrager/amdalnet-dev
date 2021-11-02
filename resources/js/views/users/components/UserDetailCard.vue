<template>
  <el-card v-if="user.name">
    <div class="user-profile">
      <div class="user-avatar box-center">
        <el-upload
          class="avatar-uploader"
          action="#"
          :show-file-list="false"
          :on-change="handleAvatarUploadChange"
          :before-upload="beforeAvatarUpload"
          :auto-upload="false"
        >
          <img
            v-if="user.avatar"
            :src="user.avatar"
            class="avatar"
          >
          <i v-else class="el-icon-plus avatar-uploader-icon" />
        </el-upload>
        <!-- <pan-thumb :image="user.avatar" :height="'100px'" :width="'100px'" :hoverable="false" /> -->
      </div>
      <div class="box-center">
        <div class="user-name text-center">
          {{ user.name }}
        </div>
        <div v-if="user.initiatorData.phone" class="user-role text-center text-muted">
          {{ user.initiatorData.phone }}
        </div>
        <div class="user-role text-center text-muted">
          {{ user.email }}
        </div>
      </div>
      <!-- <div class="box-social">
        <el-table :data="social" :show-header="false">
          <el-table-column prop="name" label="Name" />
          <el-table-column label="Count" align="left" width="100">
            <template slot-scope="scope">
              {{ scope.row.count | toThousandFilter }}
            </template>
          </el-table-column>
        </el-table>
      </div>
      <div class="user-follow">
        <el-button type="primary" style="width: 100%"> Follow </el-button>
      </div> -->
    </div>
  </el-card>
</template>

<script>
// import PanThumb from '@/components/PanThumb';
import Resource from '@/api/resource';
const userResource = new Resource('uploadAvatar');

export default {
  components: {},
  props: {
    user: {
      type: Object,
      default: () => {
        return {
          name: '',
          email: '',
          avatar: '',
          roles: [],
          initiatorData: {},
        };
      },
    },
  },
  data() {
    return {
      imageUrl: '',
      avatarUploadUrl: '',
      social: [
        {
          name: 'Followers',
          count: 1235,
        },
        {
          name: 'Following',
          count: 23512,
        },
        {
          name: 'Friends',
          count: 7242,
        },
      ],
    };
  },
  beforeUpdate() {
    this.avatarUploadUrl = 'api/uploadAvatar/' + this.user.id;
  },
  methods: {
    getRole() {
      const roles = this.user.roles.map((value) =>
        this.$options.filters.uppercaseFirst(value)
      );
      console.log(this.user);
      return roles.join(' | ');
    },
    handleAvatarSuccess(res, file) {
      this.imageUrl = URL.createObjectURL(file.raw);
      // this.user.avatarFile = file.raw;
    },
    handleAvatarUploadChange(file, fileList) {
      this.imageUrl = URL.createObjectURL(file.raw);

      // add file to multipart
      const formData = new FormData();
      formData.append('file', file.raw);

      userResource
        .updateMultipart(this.user.id, formData)
        .then(response => {
          this.updating = false;
          this.$message({
            message: 'Berhasil Mengganti Gambar Profil',
            type: 'success',
            duration: 5 * 1000,
          });
        })
        .catch(error => {
          console.log(error);
          this.updating = false;
        });
    },
    beforeAvatarUpload(file) {
      const isJPG = file.type === 'image/jpeg';
      const isLt2M = file.size / 1024 / 1024 < 2;

      if (!isJPG) {
        this.$message.error('Avatar picture must be JPG format!');
      }
      if (!isLt2M) {
        this.$message.error('Avatar picture size can not exceed 2MB!');
      }
      return isJPG && isLt2M;
    },
  },
};
</script>

<style lang="scss" scoped>
.user-profile {
  .user-name {
    font-weight: bold;
  }
  .box-center {
    padding-top: 10px;
  }
  .user-role {
    padding-top: 10px;
    font-weight: 400;
    font-size: 14px;
  }
  .box-social {
    padding-top: 30px;
    .el-table {
      border-top: 1px solid #dfe6ec;
    }
  }
  .user-follow {
    padding-top: 20px;
  }
}

.avatar-uploader .el-upload {
  border: 1px dashed #d9d9d9;
  border-radius: 6px;
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
</style>
