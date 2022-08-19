<template>
  <div>
    <el-card v-loading="loading">
      <el-form
        ref="checkAuthorityForm"
        v-loading="loadingSubmit"
        :model="authorityForm"
        :rules="authorityRules"
        label-position="top"
        style="max-width: 100%"
      >
        <el-form-item label="Kewenangan" prop="tukId">
          <el-select
            v-model="authorityForm.tukId"
            name="tukId"
            filterable
            placeholder="Cari TUK"
            style="width: 100%"
          >
            <el-option
              v-for="item in list"
              :key="item.id"
              :label="item.name"
              :value="item.id"
            />
          </el-select>
        </el-form-item>
        <el-form-item label="Keterangan" prop="notes">
          <el-input
            v-model="authorityForm.notes"
            name="notes"
            type="textarea"
            :rows="2"
          />
        </el-form-item>
        <div style="text-align: right">
          <el-button
            type="danger"
            :loading="loadingSubmit"
            @click="cancelSubmit"
          >
            Batal
          </el-button>
          <el-button
            type="success"
            :loading="loadingSubmit"
            @click="submitConfirmVisible = true"
          >
            Submit
          </el-button>
        </div>
      </el-form>
    </el-card>
    <el-dialog :visible.sync="submitConfirmVisible" width="30%">
      <p style="word-break: break-word; text-align: center">
        <b>
          Apakah anda yakin akan memindahkan rencana usaha dan kegiatan ke
          kewenangan lain?
        </b>
      </p>
      <div style="text-align: center">
        <el-button type="danger" @click="submitConfirmVisible = false">
          Batal
        </el-button>
        <el-button type="success" @click="handleSubmit">Ya</el-button>
      </div>
      <div class="confirm-alert">
        <i class="el-icon-warning-outline" style="font-size: 2em" />
        <small style="word-break: break-word; margin-left: 4px">
          Data rencana usaha dan kegiatan yang dipindahkan akan hilang dari
          dashboard TUK anda
        </small>
      </div>
    </el-dialog>
  </div>
</template>

<script>
import Resource from '@/api/resource';
const tukManagementResource = new Resource('tuk-management');

export default {
  name: 'ChangeAuthority',
  data() {
    return {
      list: [],
      loading: false,
      loadingSubmit: false,
      submitConfirmVisible: false,
      authorityForm: {
        tukId: null,
        notes: null,
      },
      authorityRules: {
        tukId: [
          { required: true, trigger: 'blur', message: 'TUK Wajib Dipilih' },
        ],
        notes: [
          {
            required: true,
            trigger: 'blur',
            message: 'Keterangan Wajib Diisi',
          },
        ],
      },
    };
  },
  created() {
    this.getTuk();
  },
  methods: {
    async getTuk() {
      this.loading = true;
      const data = await tukManagementResource.list({
        type: 'otherTuk',
        idProject: this.$route.params.id,
      });
      this.list = data.map((x) => {
        let name = `Tim Uji Kelayakan ${this.checkAuthority(
          x.authority,
          x.province_authority,
          x.district_authority
        )}`;

        if (x.team_number) {
          name += ` ${x.team_number}`;
        }

        return {
          id: x.id,
          name,
        };
      });
      this.list.sort((a, b) =>
        a.name > b.name ? 1 : b.name > a.name ? -1 : 0
      );
      this.loading = false;
    },
    checkAuthority(authority, province, district) {
      if (authority === 'Pusat') {
        return 'Pusat';
      } else if (authority === 'Provinsi') {
        if (province !== null) {
          return (
            'Provinsi ' +
            province.name
              .toLowerCase()
              .split(' ')
              .map((s) => s.charAt(0).toUpperCase() + s.substring(1))
              .join(' ')
          );
        }
      } else if (authority === 'Kabupaten/Kota') {
        if (district !== null) {
          return district.name
            .toLowerCase()
            .split(' ')
            .map((s) => s.charAt(0).toUpperCase() + s.substring(1))
            .join(' ');
        }
      }

      return '';
    },
    handleSubmit() {
      this.submitConfirmVisible = false;
      this.$refs.checkAuthorityForm.validate((valid) => {
        if (valid) {
          this.loadingSubmit = true;
          tukManagementResource
            .store({
              ...this.authorityForm,
              changeAuthority: 'true',
              idProject: this.$route.params.id,
            })
            .then((response) => {
              this.$message({
                type: 'success',
                message: 'Kewenangan Berhasil Diubah',
                duration: 5 * 1000,
              });
              this.loadingSubmit = false;
              this.$router.push({ path: '/project' });
            })
            // eslint-disable-next-line handle-callback-err
            .catch((err) => {
              this.loadingSubmit = false;
            });
        }
      });
    },
    cancelSubmit() {
      this.$refs['checkAuthorityForm'].resetFields();
    },
  },
};
</script>

<style scoped>
.confirm-alert {
  margin-top: 2em;
  background-color: #d93843;
  color: #fff;
  display: flex;
  align-items: center;
  padding: 3px;
  justify-content: space-between;
}
</style>
