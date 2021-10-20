<template>
  <div class="app-container">
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
        <lpjp-table
          :loading="loading"
          :list="list"
          @handleEditForm="handleEditForm($event)"
          @handleDelete="handleDelete($event)"
        />
      </el-tab-pane>
      <el-tab-pane label="Penyusun Aktif" name="penyusunAktif">
        <lpjp-table
          :loading="loading"
          :list="listActive"
          @handleEditForm="handleEditForm($event)"
          @handleDelete="handleDelete($event)"
        />
      </el-tab-pane>
    </el-tabs>
  </div>
</template>

<script>
import Resource from '@/api/resource';
import LpjpTable from '@/views/lpjp/components/LpjpTable.vue';
const formulatorResource = new Resource('formulators');

export default {
  name: 'LpjpList',
  components: {
    LpjpTable,
  },
  data() {
    return {
      list: [],
      listActive: [],
      loading: true,
      activeName: 'penyusun',
    };
  },
  created() {
    this.getList();
  },
  methods: {
    handleClickTab(tab, event) {
      if (tab.name === 'penyusunAktif') {
        this.getListActive();
      }
    },
    async getList() {
      this.loading = true;
      const { data } = await formulatorResource.list({});
      this.list = data;
      this.loading = false;
    },
    getListActive() {
      this.listActive = this.list.filter((item) => {
        const tglAwal = new Date(item.date_start);
        const tglAkhir = new Date(item.date_end);

        return (
          new Date().getTime() >= tglAwal.getTime() &&
          new Date().getTime() <= tglAkhir.getTime()
        );
      });
    },
    handleCreate() {
      this.$router.push({
        name: 'createFormulators',
        params: { formulator: {}},
      });
    },
    handleEditForm(id) {
      const currentFormulator = this.list.find((element) => element.id === id);
      this.$router.push({
        name: 'editFormulator',
        params: { id, formulator: currentFormulator },
      });
    },
    handleDelete({ id, name }) {
      this.$confirm(
        'Hapus Formulator ' + name + '. ?',
        'Warning',
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
