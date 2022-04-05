<template>
  <div class="app-container">
    <el-card>
      <div class="filter-container">
        <el-button
          class="filter-item"
          type="primary"
          icon="el-icon-plus"
          @click="handleCreate"
        >
          {{ 'Tambah LPJP' }}
        </el-button>
        <el-row :gutter="32">
          <el-col :sm="24" :md="10">
            <el-input
              v-model="listQuery.search"
              suffix-icon="el-icon search"
              placeholder="Pencarian LPJP..."
              @input="inputSearch"
            >
              <el-button
                slot="append"
                icon="el-icon-search"
                @click="handleSearch"
              />
            </el-input>
          </el-col>
        </el-row>
      </div>
      <el-tabs v-model="activeName" type="card" @tab-click="handleClickTab">
        <el-tab-pane label="LPJP" name="lpjp">
          <lpjp-table
            v-if="activeName === 'lpjp'"
            :loading="loading"
            :list="list"
            @handleEditForm="handleEditForm($event)"
            @handleDelete="handleDelete($event)"
          />
        </el-tab-pane>
        <pagination
          v-show="total > 0 && activeName === 'lpjp'"
          :total="total"
          :page.sync="listQuery.page"
          :limit.sync="listQuery.limit"
          @pagination="handleFilter"
        />
        <el-tab-pane label="LPJP Aktif" name="lpjpAktif">
          <lpjp-table
            v-if="activeName === 'lpjpAktif'"
            :loading="loading"
            :list="list"
            @handleEditForm="handleEditForm($event)"
            @handleDelete="handleDelete($event)"
          />
          <pagination
            v-show="total > 0 && activeName === 'lpjpAktif'"
            :total="total"
            :page.sync="listQuery.page"
            :limit.sync="listQuery.limit"
            @pagination="handleFilter"
          />
        </el-tab-pane>
      </el-tabs>
    </el-card>
  </div>
</template>

<script>
import Resource from '@/api/resource';
import Pagination from '@/components/Pagination';
import LpjpTable from '@/views/lpjp/components/LpjpTable.vue';
const lpjpResource = new Resource('lpjp');

export default {
  name: 'LpjpList',
  components: {
    LpjpTable,
    Pagination,
  },
  data() {
    return {
      list: [],
      listActive: [],
      loading: true,
      activeName: 'lpjp',
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
    this.getList();
  },
  methods: {
    handleFilter() {
      this.getList();
    },
    handleClickTab() {
      this.listQuery = {
        page: 1,
        limit: 10,
        type: 'list',
        search: null,
      };
      this.total = 0;
      this.getList();
    },
    async getList() {
      this.loading = true;
      const { data, meta } = await lpjpResource.list({
        ...this.listQuery,
        active: this.activeName === 'lpjpAktif' ? '1' : '0',
      });
      this.list = data;
      this.total = meta.total;
      this.loading = false;
    },
    handleCreate() {
      this.$router.push({
        name: 'createLpjp',
        // eslint-disable-next-line object-curly-spacing
        params: { lpjp: {} },
      });
    },
    handleEditForm(id) {
      const currentLpjp = this.list.find((category) => category.id === id);
      this.$router.push({
        name: 'editLpjp',
        params: { id, lpjp: currentLpjp },
      });
    },
    handleDelete({ id, nama }) {
      this.$confirm(
        'apakah anda yakin akan menghapus ' + nama + '. ?',
        'Peringatan',
        {
          confirmButtonText: 'OK',
          cancelButtonText: 'Cancel',
          type: 'warning',
        }
      )
        .then(() => {
          lpjpResource
            .destroy(id)
            .then((response) => {
              this.$message({
                type: 'success',
                message: 'Hapus Selesai',
              });
              this.getList();
            })
            .catch((error) => {
              console.log(error);
            });
        })
        .catch(() => {
          this.$message({
            type: 'info',
            message: 'Hapus Digagalkan',
          });
        });
    },
    inputSearch(val) {
      if (this.timeoutId) {
        clearTimeout(this.timeoutId);
      }
      this.timeoutId = setTimeout(() => {
        this.getList();
      }, 500);
    },
    async handleSearch() {
      this.listQuery.page = 1;
      this.listQuery.limit = 10;
      await this.getList();
      this.listQuery.search = null;
    },
  },
};
</script>
