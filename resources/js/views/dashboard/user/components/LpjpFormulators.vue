<template>
  <div>
    <div role="alert" class="message">
      <i class="el-alert__icon el-icon-warning is-big" style="align-self: center;" />
      <div><span><b>PERHATIAN</b></span>
        <p class="el-alert__description">
          <ul style="line-height: 25px;">
            <li>Terkait pembaruan masa berlaku penyusun dan upgrade sertifikat ATPA dan KTPA silahkan menghubungi <b>Admin LSP Penerbit Sertifikat</b></li>
            <li>Terkait pembaruan member penyusun (menambah atau menghapus) di LPJP silahkan menghubungi <b>Admin Pusfaster (<a href="mailto:pusfaster.bsilhk@gmail.com">pusfaster.bsilhk@gmail.com</a>)</b></li>
          </ul>
        </p>
      </div>
    </div>
    <el-card type="box-card">
      <div slot="header" class="clearfix">
        <span>Daftar Penyusun</span>
      </div>
      <el-table
        v-loading="loading"
        :data="data"
        style="margin: 1.5em auto;"
      >
        <el-table-column align="center" label="No." width="50px">
          <template slot-scope="scope">
            {{ scope.$index + 1 }}
          </template>
        </el-table-column>
        <el-table-column
          label="Nama"
          prop="name"
        />
        <el-table-column
          label="No Registrasi"
          prop="reg_no"
          align="center"
        />
        <el-table-column
          label="No Sertifikat"
          prop="cert_no"
          align="center"
        />
        <el-table-column
          label="Sertifikasi"
          prop="membership_status"
          align="center"
        />
        <el-table-column
          label="Status"
          align="center"
        >
          <template slot-scope="scope">
            {{ scope.row.is_active ? 'Aktif' : 'Non-aktif' }}
          </template>
        </el-table-column>
        <el-table-column
          label="File"
        >
          <template slot-scope="scope">
            <a v-if="scope.row.cert_file" :href="scope.row.cert_file"><i class="el-icon-download" />&nbsp;&nbsp;Serifikat</a>&nbsp;&nbsp; &nbsp;&nbsp;<a v-if="scope.row.cv_file" :href="scope.row.cv_file"><i class="el-icon-download" />&nbsp;&nbsp;CV</a>
          </template>
        </el-table-column>

      </el-table>
      <pagination
        v-show="total > 0"
        :total="total"
        :page.sync="query.page"
        :limit.sync="query.limit"
        @pagination="getData"
      />
    </el-card>
  </div>
</template>
<script>
import Resource from '@/api/resource';
import Pagination from '@/components/Pagination';
const formulatorResource = new Resource('dashboard/lpjp-formulators');

export default {
  name: 'LpjpFormulators',
  components: { Pagination },
  props: {
    user: {
      type: Object,
      default: () => {},
    },
  },
  data(){
    return {
      data: [],
      loading: false,
      query: {
        page: 1,
        limit: 15,
      },
      total: 0,
    };
  },
  mounted() {
    this.getData();
  },
  methods: {
    async getData(){
      this.loading = true;
      await formulatorResource.list({ lpjpId: this.user.id })
        .then((res) => {
          this.data = res.data;
          // this.total = res.meta.total;
        })
        .finally(() => {
          this.loading = false;
        });
    },
  },
};
</script>

<style lang="scss" scoped>
.message {
  margin-bottom: 10px;
  display: flex;
  padding: 5px;
  border-radius: 5px;
  background-color: #c6eafe;
  width: auto;
  padding: 0.7em;
}
.message p {
  margin: 0;
  padding: 0;
  color: #000000;
}
</style>
