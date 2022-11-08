<template>
  <div class="app-container">
    <el-card>
      <div class="filter-container">
        <el-button
          v-if="checkPermission(['manage lsp']) || checkRole(['admin'])"
          class="filter-item"
          type="primary"
          icon="el-icon-plus"
          @click="handleCreate"
        >
          {{ 'Tambah LSP' }}
        </el-button>
        <el-row :gutter="32">
          <el-col :sm="24" :md="10">
            <el-input
              v-model="listQuery.search"
              suffix-icon="el-icon search"
              placeholder="Pencarian LSP..."
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
      <lsp-table
        :loading="loading"
        :list="list"
        @handleEditForm="handleEditForm($event)"
        @handleDelete="handleDelete($event)"
      />
      <pagination
        v-show="total > 0"
        :total="total"
        :page.sync="listQuery.page"
        :limit.sync="listQuery.limit"
        @pagination="handleFilter"
      />
      <!-- <el-tabs v-model="activeName" type="card" @tab-click="handleClickTab">
        <el-tab-pane label="LSP" name="lsp">
          <lsp-table
            v-if="activeName === 'lsp'"
            :loading="loading"
            :list="list"
            @handleEditForm="handleEditForm($event)"
            @handleDelete="handleDelete($event)"
          />
        </el-tab-pane>
        <pagination
          v-show="total > 0 && activeName === 'lsp'"
          :total="total"
          :page.sync="listQuery.page"
          :limit.sync="listQuery.limit"
          @pagination="handleFilter"
        />
        <el-tab-pane label="LSP Aktif" name="lspAktif">
          <lsp-table
            v-if="activeName === 'lspAktif'"
            :loading="loading"
            :list="list"

            @handleEditForm="handleEditForm($event)"
            @handleDelete="handleDelete($event)"
          />
          <pagination
            v-show="total > 0 && activeName === 'lspAktif'"
            :total="total"
            :page.sync="listQuery.page"
            :limit.sync="listQuery.limit"
            @pagination="handleFilter"
          />
        </el-tab-pane>
      </el-tabs> -->
    </el-card>
  </div>
</template>

<script>
import checkPermission from '@/utils/permission';
import checkRole from '@/utils/role';
import Resource from '@/api/resource';
import Pagination from '@/components/Pagination';
import LspTable from '@/views/lsp/components/LspTable.vue';
const lspResource = new Resource('lsp');

export default {
  name: 'LspList',
  components: {
    LspTable,
    Pagination,
  },
  data() {
    return {
      list: [],
      listActive: [],
      loading: true,
      activeName: 'lsp',
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
    checkPermission,
    checkRole,
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
      const { data, meta } = await lspResource.list({
        ...this.listQuery,
        active: this.activeName === 'lspAktif' ? '1' : '0',
      });
      this.list = data;
      this.total = meta.total;
      this.loading = false;
    },
    handleCreate() {
      this.$router.push({
        name: 'createLSP',
        // eslint-disable-next-line object-curly-spacing
        params: { lsp: {} },
      });
    },
    handleEditForm(id) {
      const currentLsp = this.list.find((category) => category.id === id);
      this.$router.push({
        name: 'editLsp',
        params: { id, lsp: currentLsp },
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
          lspResource
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
        this.listQuery.page = 1;
        this.listQuery.limit = 10;
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
