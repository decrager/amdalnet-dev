<!-- eslint-disable vue/html-indent -->
<template>
  <el-row class="lpjp-list-card">
    <el-col :md="10" :sm="24">
      <el-input
        v-model="listQuery.search"
        suffix-icon="el-icon search"
        placeholder="Cari LPJP..."
        style="margin-bottom: 0.8em"
        @input="inputSearch"
      >
        <el-button slot="append" icon="el-icon-search" @click="handleSearch" />
      </el-input>
    </el-col>
    <el-col :md="24" :sm="24">
      <el-card type="box-card">
        <div slot="header" class="clearfix">
          <span>Daftar LPJP</span>
        </div>
        <el-table v-loading="loading" :data="data" style="margin: 1.5em auto">
          <el-table-column align="center" label="No." width="50px">
            <template slot-scope="scope">
              {{
                scope.$index +
                1 +
                listQuery.page * listQuery.limit -
                listQuery.limit
              }}
            </template>
          </el-table-column>
          <el-table-column
            label="No. Registrasi"
            sortable
            prop="reg_no"
            align="center"
          />
          <el-table-column
            label="Nama LPJP"
            sortable
            prop="name"
            align="center"
          />
          <el-table-column
            label="Alamat"
            sortable
            prop="address"
            align="center"
          />
          <el-table-column label="Sertifikasi" align="center">
            <template slot-scope="scope">
              <a v-if="scope.row.cert_file" :href="scope.row.cert_file">
                <i class="el-icon-download" />&nbsp;&nbsp;Serifikat
              </a>
            </template>
          </el-table-column>
          <el-table-column label="Status" align="center">
            <template slot-scope="scope">
              <el-tag
                :type="
                  calculateStatus(scope.row.date_start, scope.row.date_end)
                    | statusFilter
                "
              >
                {{ calculateStatus(scope.row.date_start, scope.row.date_end) }}
              </el-tag>
            </template>
          </el-table-column>
        </el-table>
        <pagination
          v-show="total > 0"
          :total="total"
          :page.sync="listQuery.page"
          :limit.sync="listQuery.limit"
          @pagination="getData"
        />
      </el-card>
    </el-col>
  </el-row>
</template>
<script>
import Resource from '@/api/resource';
import Pagination from '@/components/Pagination';
const lpjpResource = new Resource('lpjp');

export default {
  name: 'LpjpList',
  filters: {
    statusFilter(status) {
      const statusMap = {
        Aktif: 'success',
        NonAktif: 'danger',
      };
      return statusMap[status];
    },
  },
  components: { Pagination },
  data() {
    return {
      data: [],
      loading: false,
      listQuery: {
        page: 1,
        limit: 10,
        type: 'list',
        search: null,
      },
      total: 0,
      timeoutId: null,
    };
  },
  created() {
    this.getData();
  },
  methods: {
    async getData() {
      this.loading = true;
      const { data, meta } = await lpjpResource.list({
        ...this.listQuery,
        active: '0',
      });
      this.data = data;
      this.total = meta.total;
      this.loading = false;
    },
    calculateStatus(awal, akhir) {
      const tglAwal = new Date(awal);
      const tglAkhir = new Date(akhir);
      if (
        new Date().getTime() >= tglAwal.getTime() &&
        new Date().getTime() <= tglAkhir.getTime()
      ) {
        return 'Aktif';
      } else {
        return 'NonAktif';
      }
    },
    inputSearch(val) {
      if (this.timeoutId) {
        clearTimeout(this.timeoutId);
      }
      this.timeoutId = setTimeout(() => {
        this.listQuery.page = 1;
        this.listQuery.limit = 10;
        this.getData();
      }, 500);
    },
    async handleSearch() {
      this.listQuery.page = 1;
      this.listQuery.limit = 10;
      await this.getData();
      this.listQuery.search = null;
    },
  },
};
</script>

<style scoped>
.lpjp-list-card {
  margin-top: 1.5em;
}
</style>
