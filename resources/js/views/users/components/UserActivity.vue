<template>
  <el-card v-if="user.name">
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
            <el-button
              type="primary"
              :disabled="user.role === 'admin'"
              @click="onSubmit"
            >
              Ubah
            </el-button>
            <el-button
              type="warning"
              :disabled="user.role === 'admin'"
              @click="showChangePassword"
            >
              Ubah Password
            </el-button>
          </el-form-item>
        </el-form>
        <el-dialog
          v-if="user.role !== 'admin'"
          title="Ubah Password"
          :visible.sync="changePasswordDialog"
        >
          <el-form
            ref="changePasswordForm"
            v-loading="changePasswordLoading"
            :model="password"
            :rules="passwordRules"
          >
            <el-form-item label="Masukkan Password Lama" prop="current">
              <el-input
                v-model="password.current"
                :type="passwordType.current"
                name="current"
              />
              <span class="show-pwd" @click="showPwd('current')">
                <svg-icon :icon-class="eyeIcon(passwordType.current)" />
              </span>
            </el-form-item>
            <el-form-item label="Masukkan Password Baru" prop="new">
              <el-input
                v-model="password.new"
                :type="passwordType.new"
                name="new"
              />
              <span class="show-pwd" @click="showPwd('new')">
                <svg-icon :icon-class="eyeIcon(passwordType.new)" />
              </span>
            </el-form-item>
            <el-form-item label="Konfirmasi Password Baru" prop="confirm">
              <el-input
                v-model="password.confirm"
                :type="passwordType.confirm"
                name="confirm"
              />
              <span class="show-pwd" @click="showPwd('confirm')">
                <svg-icon :icon-class="eyeIcon(passwordType.confirm)" />
              </span>
            </el-form-item>
            <el-form-item>
              <el-button
                type="danger"
                @click="changePasswordDialog = !changePasswordDialog"
              >
                Batal
              </el-button>
              <el-button
                :loading="changePasswordLoading"
                type="success"
                @click="onChangePasswordSubmit"
              >
                Simpan
              </el-button>
            </el-form-item>
          </el-form>
        </el-dialog>
      </el-tab-pane>
      <el-tab-pane
        v-if="user.initiatorData.email"
        v-loading="updating"
        label="Pemrakarsa"
        name="initiatorTab"
      >
        <el-form ref="initiatorForm" :model="user.initiatorData">
          <el-form-item label="Nama Pemrakarsa">
            <el-input v-model="user.initiatorData.name" />
          </el-form-item>
          <el-form-item label="Penanggung Jawab">
            <el-input v-model="user.initiatorData.pic" />
          </el-form-item>
          <el-form-item label="No. Telepon">
            <el-input v-model="user.initiatorData.phone" />
          </el-form-item>
          <el-form-item label="Alamat">
            <el-input v-model="user.initiatorData.address" />
          </el-form-item>
          <el-form-item
            v-if="user.initiatorData.user_type !== 'Pemerintah'"
            label="NIB"
          >
            <el-input v-model="user.initiatorData.nib" />
          </el-form-item>
          <el-form-item>
            <el-button type="primary" @click="onInitiatorSubmit">
              Ubah
            </el-button>
          </el-form-item>
        </el-form>
      </el-tab-pane>
      <el-tab-pane
        v-else-if="formulator.email"
        v-loading="updating"
        label="Penyusun"
        name="formulatorTab"
      >
        <div class="formulator-message">
          <p>
            <b>
              Apabila terdapat data yang tidak sesuai, silahkan menghubungi
              Admin Amdalnet
            </b>
          </p>
        </div>
        <el-form ref="formulatorForm" :model="formulator">
          <el-form-item label="Nama Penyusun">
            <el-input v-model="formulator.name" :readonly="true" />
          </el-form-item>
          <el-form-item label="Keahlian Penyusun">
            <el-select
              v-model="selectedExpertise"
              name="expertise"
              placeholder="Keahlian Penyusun"
              style="width: 100%"
              @change="handleChangeExpertise"
            >
              <el-option
                v-for="item in expertise"
                :key="item.value"
                :label="item.value"
                :value="item.value"
              />
            </el-select>
          </el-form-item>
          <el-form-item
            v-if="selectedExpertise === 'Ahli Lainnya'"
            label=""
            prop="otherExpertise"
          >
            <el-input
              v-model="formulator.expertise"
              name="otherExpertise"
              placeholder="Isi Keahlian"
            />
          </el-form-item>
          <el-form-item label="No. Sertifikat" class="certificate-label">
            <div
              style="
                display: flex;
                justify-content: space-between;
                align-items: center;
              "
            >
              <el-input v-model="formulator.cert_no" :readonly="true" />
              <el-upload
                action="#"
                :auto-upload="false"
                :on-change="handleUploadFormulatorCertificate"
                :show-file-list="false"
                class="upload-formulator-cert"
              >
                <el-button size="small" type="primary"> Upload </el-button>
                <div
                  v-if="certFormulator.name"
                  slot="tip"
                  class="el-upload__tip"
                >
                  {{ certFormulator.name }}
                </div>
              </el-upload>
              <el-button
                v-if="formulator.cert_file"
                size="small"
                type="warning"
                @click="download(formulator.cert_file)"
              >
                File Sertifikat
              </el-button>
            </div>
          </el-form-item>
          <el-form-item label="Tanggal Ditetapkan" prop="tglDitetapkan">
            <el-date-picker
              v-model="formulator.date_start"
              type="date"
              placeholder="Pilih tanggal"
              value-format="yyyy-MM-dd"
              style="width: 100%"
              :disabled="true"
            />
          </el-form-item>
          <el-form-item label="Terakhir Berlaku" prop="terakhirBerlaku">
            <el-date-picker
              v-model="formulator.date_end"
              type="date"
              placeholder="Pilih tanggal"
              value-format="yyyy-MM-dd"
              style="width: 100%"
              :disabled="true"
            />
          </el-form-item>
          <div style="margin-bottom: 20px">
            <label
              class="el-form-item--mediun el-form-item__label"
              style="float: none"
            >
              CV Penyusun
            </label>
            <div
              style="
                display: flex;
                justify-content: space-between;
                align-items: center;
              "
            >
              <el-input
                v-model="cvFormulator.name"
                :readonly="true"
                class="input-name-cv"
              />
              <el-upload
                class="upload-demo"
                :auto-upload="false"
                :on-change="handleUploadCv"
                action="#"
                :show-file-list="false"
              >
                <el-button size="small" type="primary"> Upload </el-button>
                <el-button
                  v-if="formulator.cv_file"
                  size="small"
                  type="warning"
                  @click="download(formulator.cv_file)"
                >
                  File CV
                </el-button>
              </el-upload>
            </div>
          </div>
          <el-form-item>
            <el-button type="primary" @click="checkFormulatorSubmit">
              Ubah
            </el-button>
          </el-form-item>
        </el-form>
      </el-tab-pane>
      <el-tab-pane
        v-else-if="user.lpjpData.email"
        v-loading="updating"
        label="LPJP"
        name="lpjpTab"
      >
        <el-form ref="lpjpForm" :model="lpjp">
          <el-form-item label="Nama LPJP">
            <el-input v-model="lpjp.name" />
          </el-form-item>
          <el-form-item label="PIC">
            <el-input v-model="lpjp.pic" />
          </el-form-item>
          <el-form-item label="No. Registrasi">
            <el-input v-model="lpjp.reg_no" />
          </el-form-item>
          <el-form-item label="No. Telepon">
            <el-input v-model="lpjp.phone_no" />
          </el-form-item>
          <el-form-item label="Tanggal Ditetapkan" prop="tglDitetapkan">
            <el-date-picker
              v-model="lpjp.date_start"
              type="date"
              placeholder="Pilih tanggal"
              value-format="yyyy-MM-dd"
              style="width: 100%"
            />
          </el-form-item>
          <el-form-item label="Terakhir Berlaku" prop="terakhirBerlaku">
            <el-date-picker
              v-model="lpjp.date_end"
              type="date"
              placeholder="Pilih tanggal"
              value-format="yyyy-MM-dd"
              style="width: 100%"
            />
          </el-form-item>
          <el-form-item>
            <el-button type="primary" @click="onLpjpSubmit"> Ubah </el-button>
          </el-form-item>
        </el-form>
      </el-tab-pane>
      <el-tab-pane
        v-else-if="user.expertData.email"
        v-loading="updating"
        label="Ahli"
        name="expertTab"
      >
        <el-form ref="expertForm" :model="user.expertData">
          <el-form-item label="Nama">
            <el-input v-model="user.expertData.name" />
          </el-form-item>
          <el-form-item label="Alamat">
            <el-input v-model="user.expertData.address" />
          </el-form-item>
          <el-form-item label="No. Gawai">
            <el-input v-model="user.expertData.mobile_phone_no" />
          </el-form-item>
          <el-form-item label="Keahlian">
            <el-input v-model="user.expertData.expertise" />
          </el-form-item>
          <el-form-item>
            <el-button type="primary" @click="onExpertSubmit"> Ubah </el-button>
          </el-form-item>
        </el-form>
      </el-tab-pane>
    </el-tabs>
  </el-card>
</template>

<script>
import { mapGetters } from 'vuex';
import Resource from '@/api/resource';
const userResource = new Resource('users');
const initiatorResource = new Resource('initiators');
const formulatorResource = new Resource('formulators');
const lpjpResource = new Resource('lpjp');
const expertResource = new Resource('expert-banks');

export default {
  props: {
    user: {
      type: Object,
      default: () => {
        return {
          name: '',
          email: '',
          avatar: '',
          roles: [],
        };
      },
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
      lpjp: {},
      cvFormulator: {
        name: null,
        file: null,
      },
      certFormulator: {
        name: null,
        file: null,
      },
      password: {
        current: null,
        new: null,
        confirm: null,
      },
      passwordType: {
        current: 'password',
        new: 'password',
        confirm: 'password',
      },
      selectedExpertise: null,
      expertise: [
        {
          value: 'Ahli Mutu Udara',
        },
        {
          value: 'Ahli Mutu Air',
        },
        {
          value: 'Ahli Mutu Tanah',
        },
        {
          value: 'Ahli Keanekaragaman Hayati',
        },
        {
          value: 'Ahli Kehutanan',
        },
        {
          value: 'Ahli Sosial',
        },
        {
          value: 'Ahli Kesehatan Masyarakat',
        },
        {
          value: 'Ahli Transportasi',
        },
        {
          value: 'Ahli Geologi',
        },
        {
          value: 'Ahli Hidrogeologi',
        },
        {
          value: 'Ahli Hidrologi',
        },
        {
          value: 'Ahli Kelautan',
        },
        {
          value: 'Ahli Lainnya',
        },
      ],
      passwordRules: {
        current: [
          {
            required: true,
            trigger: 'blur',
            message: 'Password lama wajib diisi',
          },
        ],
        new: [
          {
            required: true,
            pattern: /^(?=.*\d)(?=.*[a-z])(?=.*[A-Z])(?=.*[a-zA-Z]).{8,}$/,
            message:
              'minimal 8 karakter, harus mengandung minimal 1 huruf besar, 1 huruf kecil, dan 1 angka, Dapat berisi karakter khusus',
            trigger: 'blur',
          },
        ],
        confirm: [
          {
            required: true,
            trigger: 'blur',
            validator: validateConfirmPassword,
          },
        ],
      },
    };
  },
  computed: {
    ...mapGetters({
      userInfo: 'user',
      userId: 'userId',
    }),
  },
  created() {
    this.getFormulator();
    this.getLpjp();
  },
  methods: {
    handleClick(tab, event) {
      console.log('Switching tab ', tab, event);
    },
    async getLpjp() {
      this.lpjp = await lpjpResource.list({
        byUserId: 'true',
        email: this.userInfo.email,
      });
    },
    async getFormulator() {
      this.formulator = await formulatorResource.list({
        byUserId: 'true',
        email: this.userInfo.email,
      });
      const checkExpertise = this.expertise.find(
        (x) => x.value === this.formulator.expertise
      );

      // === CHECK EXPERTISE === //
      if (checkExpertise) {
        this.selectedExpertise = this.formulator.expertise;
      } else {
        this.selectedExpertise = 'Ahli Lainnya';
      }

      if (this.formulator.cv_file) {
        const nameArray = this.formulator.cv_file.split('/');
        this.cvFormulator.name = nameArray[nameArray.length - 1];
      }
    },
    handleChangeExpertise(data) {
      if (data !== 'Ahli Lainnya') {
        this.formulator.expertise = data;
      } else {
        this.formulator.expertise = null;
      }
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
    onExpertSubmit() {
      this.updating = true;

      const updatedExpert = this.user.expertData;

      expertResource
        .update(updatedExpert.id, updatedExpert)
        .then((response) => {
          this.updating = false;
          this.$message({
            message: 'Detil Expert Berhasil Diubah',
            type: 'success',
            duration: 5 * 1000,
          });
        })
        .catch((error) => {
          console.log(error);
          this.updating = false;
        });
    },
    onLpjpSubmit() {
      this.updating = true;

      const updatedLpjp = this.lpjp;

      lpjpResource
        .update(updatedLpjp.id, updatedLpjp)
        .then((response) => {
          this.updating = false;
          this.$message({
            message: 'Detil Lpjp Berhasil Diubah',
            type: 'success',
            duration: 5 * 1000,
          });
        })
        .catch((error) => {
          console.log(error);
          this.updating = false;
        });
    },
    checkFormulatorSubmit() {
      this.$confirm('apakah anda yakin akan menyimpan data ?', {
        confirmButtonText: 'OK',
        cancelButtonText: 'Batal',
        type: 'warning',
      })
        .then(() => {
          this.onFormulatorSubmit();
        })
        .catch(() => {});
    },
    async onFormulatorSubmit() {
      this.updating = true;

      const formData = new FormData();
      formData.append('expertise', this.formulator.expertise);
      formData.append('profile', true);

      if (this.cvFormulator.file) {
        formData.append(
          'cv_file',
          await this.convertBase64(this.cvFormulator.file)
        );
      }

      if (this.certFormulator.file) {
        formData.append(
          'cert_file',
          await this.convertBase64(this.certFormulator.file)
        );
      }

      formulatorResource
        .updateMultipart(this.formulator.id, formData)
        .then((response) => {
          this.updating = false;
          this.$message({
            message: 'Detil Penyusun Berhasil Diubah',
            type: 'success',
            duration: 5 * 1000,
          });
          this.cvFormulator.file = null;
          this.certFormulator.file = null;
          this.certFormulator.name = null;
          this.formulator.cv_file = response.cv_file;
          this.formulator.cert_file = response.cert_file;
        })
        .catch((error) => {
          console.log(error);
          this.updating = false;
        });
    },
    onInitiatorSubmit() {
      this.updating = true;

      const updatedInitiator = this.user.initiatorData;

      initiatorResource
        .update(updatedInitiator.id, updatedInitiator)
        .then((response) => {
          this.updating = false;
          this.$message({
            message: 'Detil Pemrakarsa Berhasil Diubah',
            type: 'success',
            duration: 5 * 1000,
          });
        })
        .catch((error) => {
          console.log(error);
          this.updating = false;
        });
    },
    onSubmit() {
      this.updating = true;

      userResource
        .update(this.user.id, this.user)
        .then((response) => {
          if (response.error) {
            this.$message({
              message: response.error,
              type: 'error',
              duration: 5 * 1000,
            });
          } else {
            this.$message({
              message: 'Informasi User Berhasil Di Ubah',
              type: 'success',
              duration: 5 * 1000,
            });
          }
          this.updating = false;
        })
        .catch((error) => {
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
            .then((response) => {
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
            .catch((error) => {
              this.changePasswordLoading = false;
            });
        }
      });
    },
    handleUploadFormulatorCertificate(file, fileList) {
      if (file.raw.size > 5242880) {
        this.$alert('File Yang Diupload Melebihi 5 MB', {
          confirmButtonText: 'OK',
        });
        return;
      }

      this.certFormulator.name = file.name;
      this.certFormulator.file = file.raw;
    },
    handleUploadCv(file, fileList) {
      if (file.raw.size > 10485760) {
        this.$alert('File Yang Diupload Melebihi 10 MB', {
          confirmButtonText: 'OK',
        });
        return;
      }

      const extension = file.name.split('.');
      if (extension[extension.length - 1].toLowerCase() !== 'pdf') {
        this.$alert('File harus berformat PDF', '', {
          center: true,
        });
        return;
      }

      this.cvFormulator.name = file.name;
      this.cvFormulator.file = file.raw;
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
    download(url) {
      window.open(url, '_blank').focus();
    },
    showPwd(pwdType) {
      if (this.passwordType[pwdType] === 'password') {
        this.passwordType[pwdType] = '';
      } else {
        this.passwordType[pwdType] = 'password';
      }
    },
    eyeIcon(passType) {
      if (passType === 'password') {
        return 'eye-open';
      }

      return 'eye';
    },
  },
};
</script>

<style lang="scss" scoped>
.user-activity {
  .user-block {
    .username,
    .description {
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
      &:hover,
      &:focus {
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

  .el-carousel__item:nth-child(2n + 1) {
    background-color: #d3dce6;
  }
}
.formulator-message {
  background-color: #c4c4c4;
  width: fit-content;
  padding: 0.7em;
}
.formulator-message p {
  margin: 0;
  padding: 0;
}
</style>

<style>
.input-name-cv {
  width: 80%;
}
.input-name-cv input {
  width: 97%;
}
.certificate-label label {
  display: contents;
}
.certificate-label input {
  width: 97%;
}
.upload-formulator-cert {
  margin-right: 1em;
}
.show-pwd {
  position: absolute;
  right: 10px;
  top: 2.3em;
  font-size: 16px;
  color: #889aa4;
  cursor: pointer;
  user-select: none;
}
</style>
