<template>
  <div class="app-container" style="padding: 24px">
    <el-card>
      <div class="filter-container">
        <el-button
          class="filter-item"
          type="primary"
          icon="el-icon-plus"
          @click="handleCreate"
        >
          {{ 'Tambah Bank Ahli' }}
        </el-button>
      </div>
      <expert-bank-table
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
import ExpertBankTable from '@/views/expert-bank/components/ExpertBankTable.vue';
import Pagination from '@/components/Pagination';
const expertBankResource = new Resource('expert-banks');

export default {
  name: 'FormulatorList',
  components: {
    ExpertBankTable,
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
    this.getList();
  },
  methods: {
    handleFilter() {
      this.getList();
    },
    async getList() {
      this.loading = true;
      const { data, meta } = await expertBankResource.list(this.listQuery);
      this.list = data;
      this.total = meta.total;
      this.loading = false;
    },
    handleCreate() {
      this.$router.push({
        name: 'createExpertBank',
        params: { expertBank: {}},
      });
    },
    handleEditForm(id) {
      const currentExpertBank = this.list.find((element) => element.id === id);
      console.log(currentExpertBank);
      this.$router.push({
        name: 'editExpertBank',
        params: { id, expertBank: currentExpertBank },
      });
    },
    handleDelete({ id, nama }) {
      this.$confirm('apakah anda yakin akan menghapus ' + nama + '. ?', 'Peringatan', {
        confirmButtonText: 'OK',
        cancelButtonText: 'Batal',
        type: 'warning',
      })
        .then(() => {
          expertBankResource
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
