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
            <el-button type="primary" :disabled="user.role === 'admin'" @click="onSubmit">
              Ubah
            </el-button>
          </el-form-item>
        </el-form>
      </el-tab-pane>
      <el-tab-pane v-if="user.initiatorData.email" v-loading="updating" label="Pemrakarsa" name="initiatorTab">
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
          <el-form-item label="NIB">
            <el-input v-model="user.initiatorData.nib" />
          </el-form-item>
          <el-form-item>
            <el-button type="primary" @click="onInitiatorSubmit">
              Ubah
            </el-button>
          </el-form-item>
        </el-form>
      </el-tab-pane>
      <el-tab-pane v-else-if="user.formulatorData.email" v-loading="updating" label="Penyusun" name="formulatorTab">
        <el-form ref="formulatorForm" :model="user.formulatorData">
          <el-form-item label="Nama Penyusun">
            <el-input v-model="user.formulatorData.name" />
          </el-form-item>
          <el-form-item label="Keahlian">
            <el-input v-model="user.formulatorData.expertise" />
          </el-form-item>
          <el-form-item label="No. Sertifikat">
            <el-input v-model="user.formulatorData.cert_no" />
          </el-form-item>
          <el-form-item label="NIP">
            <el-input v-model="user.formulatorData.nip" />
          </el-form-item>
          <el-form-item label="Tanggal Ditetapkan" prop="tglDitetapkan">
            <el-date-picker
              v-model="user.formulatorData.date_start"
              type="date"
              placeholder="Pilih tanggal"
              value-format="yyyy-MM-dd"
              style="width: 100%"
            />
          </el-form-item>
          <el-form-item label="Terakhir Berlaku" prop="terakhirBerlaku">
            <el-date-picker
              v-model="user.formulatorData.date_end"
              type="date"
              placeholder="Pilih tanggal"
              value-format="yyyy-MM-dd"
              style="width: 100%"
            />
          </el-form-item>
          <el-form-item>
            <el-button type="primary" @click="onFormulatorSubmit">
              Ubah
            </el-button>
          </el-form-item>
        </el-form>
      </el-tab-pane>
      <el-tab-pane v-else-if="user.lpjpData.email" v-loading="updating" label="Lpjp" name="lpjpTab">
        <el-form ref="lpjpForm" :model="user.lpjpData">
          <el-form-item label="Nama LPJP">
            <el-input v-model="user.lpjpData.name" />
          </el-form-item>
          <el-form-item label="PIC">
            <el-input v-model="user.lpjpData.pic" />
          </el-form-item>
          <el-form-item label="No. Registrasi">
            <el-input v-model="user.lpjpData.reg_no" />
          </el-form-item>
          <el-form-item label="No. Telepon">
            <el-input v-model="user.lpjpData.phone_no" />
          </el-form-item>
          <el-form-item label="Tanggal Ditetapkan" prop="tglDitetapkan">
            <el-date-picker
              v-model="user.lpjpData.date_start"
              type="date"
              placeholder="Pilih tanggal"
              value-format="yyyy-MM-dd"
              style="width: 100%"
            />
          </el-form-item>
          <el-form-item label="Terakhir Berlaku" prop="terakhirBerlaku">
            <el-date-picker
              v-model="user.lpjpData.date_end"
              type="date"
              placeholder="Pilih tanggal"
              value-format="yyyy-MM-dd"
              style="width: 100%"
            />
          </el-form-item>
          <el-form-item>
            <el-button type="primary" @click="onLpjpSubmit">
              Ubah
            </el-button>
          </el-form-item>
        </el-form>
      </el-tab-pane>
      <el-tab-pane v-else-if="user.expertData.email" v-loading="updating" label="Ahli" name="expertTab">
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
            <el-button type="primary" @click="onExpertSubmit">
              Ubah
            </el-button>
          </el-form-item>
        </el-form>
      </el-tab-pane>
    </el-tabs>
  </el-card>
</template>

<script>
import Resource from '@/api/resource';
const userResource = new Resource('users');
const initiatorResource = new Resource('initiators');
const formulatorResource = new Resource('formulators');
const lpjpResource = new Resource('lpjps');
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
    return {
      activeActivity: 'akunTab',
      updating: false,
    };
  },
  methods: {
    handleClick(tab, event) {
      console.log('Switching tab ', tab, event);
    },
    onExpertSubmit(){
      this.updating = true;

      const updatedExpert = this.user.expertData;

      expertResource
        .update(updatedExpert.id, updatedExpert)
        .then(response => {
          this.updating = false;
          this.$message({
            message: 'Detil Expert Berhasil Diubah',
            type: 'success',
            duration: 5 * 1000,
          });
        })
        .catch(error => {
          console.log(error);
          this.updating = false;
        });
    },
    onLpjpSubmit(){
      this.updating = true;

      const updatedLpjp = this.user.lpjpData;

      lpjpResource
        .update(updatedLpjp.id, updatedLpjp)
        .then(response => {
          this.updating = false;
          this.$message({
            message: 'Detil Lpjp Berhasil Diubah',
            type: 'success',
            duration: 5 * 1000,
          });
        })
        .catch(error => {
          console.log(error);
          this.updating = false;
        });
    },
    onFormulatorSubmit(){
      this.updating = true;

      const updatedFormulator = this.user.formulatorData;

      formulatorResource
        .update(updatedFormulator.id, updatedFormulator)
        .then(response => {
          this.updating = false;
          this.$message({
            message: 'Detil Penyusun Berhasil Diubah',
            type: 'success',
            duration: 5 * 1000,
          });
        })
        .catch(error => {
          console.log(error);
          this.updating = false;
        });
    },
    onInitiatorSubmit(){
      this.updating = true;

      const updatedInitiator = this.user.initiatorData;

      initiatorResource
        .update(updatedInitiator.id, updatedInitiator)
        .then(response => {
          this.updating = false;
          this.$message({
            message: 'Detil Pemrakarsa Berhasil Diubah',
            type: 'success',
            duration: 5 * 1000,
          });
        })
        .catch(error => {
          console.log(error);
          this.updating = false;
        });
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
