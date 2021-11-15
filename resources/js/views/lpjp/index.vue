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
      </div>
      <el-tabs v-model="activeName" type="card" @tab-click="handleClickTab">
        <el-tab-pane label="LPJP" name="lpjp">
          <lpjp-table
            :loading="loading"
            :list="list"
            @handleEditForm="handleEditForm($event)"
            @handleDelete="handleDelete($event)"
          />
        </el-tab-pane>
        <el-tab-pane label="LPJP Aktif" name="lpjpAktif">
          <lpjp-table
            :loading="loading"
            :list="listActive"
            @handleEditForm="handleEditForm($event)"
            @handleDelete="handleDelete($event)"
          />
        </el-tab-pane>
      </el-tabs>
    </el-card>
  </div>
</template>

<script>
import Resource from '@/api/resource';
import LpjpTable from '@/views/lpjp/components/LpjpTable.vue';
const lpjpResource = new Resource('lpjp');

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
      activeName: 'lpjp',
    };
  },
  created() {
    this.getList();
  },
  methods: {
    handleClickTab(tab, event) {
      if (tab.name === 'lpjpAktif') {
        this.getListActive();
      } else if (tab.name === 'penyusunAktif') {
        this.getListPenyusunActive();
      }
    },
    async getList() {
      this.loading = true;
      const { data } = await lpjpResource.list({});
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
  },
};
</script>
