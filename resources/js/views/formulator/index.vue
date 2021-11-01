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
          {{ 'Tambah Penyusun' }}
        </el-button>
      </div>
      <el-tabs v-model="activeName" type="card" @tab-click="handleClickTab">
        <el-tab-pane label="Penyusun" name="penyusun">
          <formulator-table
            :loading="loading"
            :list="list"
            @handleEditForm="handleEditForm($event)"
            @handleDelete="handleDelete($event)"
          />
        </el-tab-pane>
        <el-tab-pane label="Penyusun Aktif" name="penyusunAktif">
          <formulator-table
            :loading="loading"
            :list="list"
            @handleEditForm="handleEditForm($event)"
            @handleDelete="handleDelete($event)"
          />
        </el-tab-pane>
      </el-tabs>
      <pagination
        v-show="total > 0"
        :total="total"
        :page.sync="listQuery.page"
        :limit.sync="listQuery.limit"
        @pagination="handleFilter"
      />
    </el-card>
  </div>
</template>

<script>
import Resource from '@/api/resource';
import FormulatorTable from '@/views/formulator/components/FormulatorTable.vue';
import Pagination from '@/components/Pagination';
const formulatorResource = new Resource('formulators');

export default {
  name: 'FormulatorList',
  components: {
    FormulatorTable,
    Pagination,
  },
  data() {
    return {
      list: [],
      loading: true,
      activeName: 'penyusun',
      listQuery: {
        page: 1,
        limit: 10,
        active: '',
      },
      total: 0,
    };
  },
  created() {
    this.getList();
  },
  methods: {
    handleFilter() {
      this.getList();
    },
    handleClickTab(tab, event) {
      this.listQuery.page = 1;
      this.listQuery.limit = 10;
      if (tab.name === 'penyusunAktif') {
        this.listQuery.active = 'true';
        this.getListActive();
      } else {
        this.listQuery.active = '';
        this.getList();
      }
    },
    async getList() {
      this.loading = true;
      const { data, meta } = await formulatorResource.list(this.listQuery);
      this.list = data;
      this.total = meta.total;
      this.loading = false;
    },
    async getListActive() {
      this.loading = true;
      const { data, meta } = await formulatorResource.list(this.listQuery);
      this.list = data;
      this.total = meta.total;
      this.loading = false;
    },
    handleCreate() {
      this.$router.push({
        name: 'createFormulator',
        // eslint-disable-next-line object-curly-spacing
        params: { formulator: {} },
      });
    },
    handleEditForm(id) {
      const currentFormulator = this.list.find((element) => element.id === id);
      this.$router.push({
        name: 'editFormulator',
        params: { id, formulator: currentFormulator },
      });
    },
    handleDelete({ id, nama }) {
      this.$confirm(
        'apakah anda yakin akan menghapus ' + nama + '. ?',
        'Peringatan',
        {
          confirmButtonText: 'OK',
          cancelButtonText: 'Batal',
          type: 'warning',
        }
      )
        .then(() => {
          formulatorResource
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
  },
};
</script>
