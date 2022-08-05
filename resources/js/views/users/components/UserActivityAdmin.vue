<template>
  <el-card>
    <el-tabs v-model="activeActivity" @tab-click="handleClick">
      <el-tab-pane v-loading="updating" label="Akun" name="akunTab">
        <el-form ref="userForm" :model="user">
          <el-form-item label="Name">
            <el-input v-model="user.name" :disabled="user.role === 'admin'" />
          </el-form-item>
          <el-form-item label="Email">
            <el-input v-model="user.email" :disabled="user.role === 'admin'" />
          </el-form-item>
          <el-form-item>
            <el-button type="primary" @click="onSubmit">
              Ubah
            </el-button>
            <!-- <el-button type="warning" :disabled="user.role === 'admin'" @click="showChangePassword">
              Ubah Password
            </el-button> -->
          </el-form-item>
        </el-form>
        <!-- <el-dialog title="Ubah Password" :visible.sync="changePasswordDialog">
          <el-form ref="changePasswordForm" v-loading="changePasswordLoading" :model="password" :rules="passwordRules">
            <el-form-item label="Masukkan Password Lama" prop="current">
              <el-input v-model="password.current" type="password" name="current" />
            </el-form-item>
            <el-form-item label="Masukkan Password Baru" prop="new">
              <el-input v-model="password.new" type="password" name="new" />
            </el-form-item>
            <el-form-item label="Konfirmasi Password Baru" prop="confirm">
              <el-input v-model="password.confirm" type="password" name="confirm" />
            </el-form-item>
            <el-form-item>
              <el-button type="danger" @click="changePasswordDialog = !changePasswordDialog">
                Batal
              </el-button>
              <el-button :loading="changePasswordLoading" type="success" @click="onChangePasswordSubmit">
                Simpan
              </el-button>
            </el-form-item>
          </el-form>
        </el-dialog> -->
      </el-tab-pane>
    </el-tabs>
  </el-card>
</template>

<script>
import Resource from '@/api/resource';
const userResource = new Resource('users');

export default {
  props: {
    user: {
      type: Object,
      default: () => {},
    },
  },
  data() {
    const validateConfirmPassword = (rule, value, callback) => {
      if (!value) {
        callback(new Error('Silahkan masukkan konfirmasi yang sesuai'));
      } else if (value !== this.password.new) {
        callback(new Error('Silahkan masukkan konfirmasi yang sesuai'));
      } else {
        callback();
      }
    };

    return {
      activeActivity: 'akunTab',
      updating: false,
      isPemerintah: true,
      loading: false,
      changePasswordDialog: false,
      changePasswordLoading: false,
      formulator: {},
      cvFormulator: {
        name: null,
        file: null,
      },
      password: {
        current: null,
        new: null,
        confirm: null,
      },
      passwordRules: {
        current: [
          {
            required: true,
            trigger: 'blur',
            message: 'Password lama wajib diisi',
          },
        ],
        new: [{ required: true, pattern: /^(?=.*\d)(?=.*[a-z])(?=.*[A-Z])(?=.*[a-zA-Z]).{8,}$/, message: 'minimal 8 karakter, harus mengandung minimal 1 huruf besar, 1 huruf kecil, dan 1 angka, Dapat berisi karakter khusus', trigger: 'blur' }],
        confirm: [{ required: true, trigger: 'blur', validator: validateConfirmPassword }],
      },
    };
  },
  methods: {
    handleClick(tab, event) {
      console.log('Switching tab ', tab, event);
    },
    showChangePassword() {
      this.password = {
        current: null,
        new: null,
        confirm: null,
      };
      this.changePasswordDialog = true;
      if (this.$refs.changePasswordForm) {
        this.$refs.changePasswordForm.resetFields();
      }
    },
    onSubmit() {
      this.updating = true;

      userResource
        .update(this.user.id, this.user)
        .then(response => {
          this.updating = false;
          this.$message({
            message: 'Informasi User Berhasil Di Ubah',
            type: 'success',
            duration: 5 * 1000,
          });
        })
        .catch(error => {
          console.log(error);
          this.updating = false;
        });
    },
    onChangePasswordSubmit() {
      this.$refs.changePasswordForm.validate((valid) => {
        if (valid) {
          this.changePasswordLoading = true;
          userResource
            .update(this.user.id, this.password)
            .then(response => {
              if (response.error) {
                this.$message({
                  message: response.error,
                  type: 'error',
                  duration: 5 * 1000,
                });
              } else {
                this.$message({
                  message: 'Password User Berhasil Di Ubah',
                  type: 'success',
                  duration: 5 * 1000,
                });
                this.changePasswordDialog = false;
              }
              this.changePasswordLoading = false;
            })
            // eslint-disable-next-line handle-callback-err
            .catch(error => {
              this.changePasswordLoading = false;
            });
        }
      });
    },
    convertBase64(file) {
      return new Promise((resolve, reject) => {
        const fileReader = new FileReader();
        fileReader.readAsDataURL(file);

        fileReader.onload = () => {
          resolve(fileReader.result);
        };

        fileReader.onerror = (error) => {
          reject(error);
        };
      });
    },
  },
};
</script>

<style lang="scss" scoped>
.user-activity {
  .user-block {
    .username, .description {
      display: block;
      margin-left: 50px;
      padding: 2px 0;
    }
    img {
      width: 40px;
      height: 40px;
      float: left;
    }
    :after {
      clear: both;
    }
    .img-circle {
      border-radius: 50%;
      border: 2px solid #d2d6de;
      padding: 2px;
    }
    span {
      font-weight: 500;
      font-size: 12px;
    }
  }
  .post {
    font-size: 14px;
    border-bottom: 1px solid #d2d6de;
    margin-bottom: 15px;
    padding-bottom: 15px;
    color: #666;
    .image {
      width: 100%;
    }
    .user-images {
      padding-top: 20px;
    }
  }
  .list-inline {
    padding-left: 0;
    margin-left: -5px;
    list-style: none;
    li {
      display: inline-block;
      padding-right: 5px;
      padding-left: 5px;
      font-size: 13px;
    }
    .link-black {
      &:hover, &:focus {
        color: #999;
      }
    }
  }
  .el-carousel__item h3 {
    color: #475669;
    font-size: 14px;
    opacity: 0.75;
    line-height: 200px;
    margin: 0;
  }

  .el-carousel__item:nth-child(2n) {
    background-color: #99a9bf;
  }

  .el-carousel__item:nth-child(2n+1) {
    background-color: #d3dce6;
  }
}
</style>

<style>
.input-name-cv input {
  width: 97%;
}
</style>
