<template>
  <el-card type="box-card">
    <div slot="header" class="clearfix">
      <span>Daftar Penyusun</span>
    </div>
    <el-table
      v-loading="loading"
      :data="data"
      style="margin: 2em auto;"
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
          {{ scope.row.active ? 'Aktif' : 'Non-aktif' }}
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
  </el-card>
</template>
<script>
import Resource from '@/api/resource';
const formulatorResource = new Resource('formulators');

export default {
  name: 'LpjpFormulators',
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
        })
        .finally(() => {
          this.loading = false;
        });
    },
  },
};
</script>
