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
          {{ 'Tambah Tim Penyusun' }}
        </el-button>
      </div>
      <formulator-team-table
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
    </el-card>
  </div>
</template>

<script>
import Resource from '@/api/resource';
import FormulatorTeamTable from '@/views/formulator-team/components/FormulatorTeamTable.vue';
import Pagination from '@/components/Pagination';
const formulatorTeamResource = new Resource('formulator-teams');

export default {
  name: 'FormulatorTeamList',
  components: {
    FormulatorTeamTable,
    Pagination,
  },
  data() {
    return {
      list: [],
      loading: true,
      listQuery: {
        page: 1,
        limit: 10,
      },
      total: 0,
    };
  },
  created() {
    // this.getList();
    this.list = [
      {
        id: 1,
        name: 'Test Penyusun RBA',
        teamTotal: '3 Anggota',
        created_at: '2021-07-05',
      },
      {
        id: 2,
        name: 'Tim Test Adit',
        teamTotal: '3 Anggota',
        created_at: '2021-04-19',
      },
    ];
    this.loading = false;
  },
  methods: {
    handleFilter() {
      this.getList();
    },
    async getList() {
      this.loading = true;
      const { data, meta } = await formulatorTeamResource.list(this.listQuery);
      this.list = data;
      this.total = meta.total;
      this.loading = false;
    },
    handleCreate() {
      this.$router.push({
        name: 'createFormulatorTeam',
        // eslint-disable-next-line object-curly-spacing
        params: { formulatorTeam: {} },
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
          formulatorTeamResource
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
