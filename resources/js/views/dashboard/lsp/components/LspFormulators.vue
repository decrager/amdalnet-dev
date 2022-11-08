<template>
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
</template>
<script>
import Resource from '@/api/resource';
import Pagination from '@/components/Pagination';
const formulatorResource = new Resource('dashboard/lsp-formulators');

export default {
  name: 'LspFormulators',
  components: { Pagination },
  props: {
    user: {
      type: Object,
      default: null,
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
      await formulatorResource.list({ lspId: this.user.id })
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
