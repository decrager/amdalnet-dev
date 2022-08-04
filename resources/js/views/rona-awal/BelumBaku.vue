<template>
  <div class="filter-container">
    <el-row :gutter="32" style="margin-bottom: 1.5em;">
      <el-col :sm="24" :md="10">
        <el-input
          v-model="listQuery.search"
          suffix-icon="el-icon search"
          placeholder="Pencarian..."
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
    <rona-awal-tidak-baku-table
      :loading="loading"
      :list="list"
      :page="listQuery.page"
      :limit="listQuery.limit"
      @handleEditForm="handleEditForm($event)"
      @handleDelete="handleDelete($event)"
      @setMaster="setMaster($event)"
    />
    <pagination
      v-show="total > 0"
      :total="total"
      :page.sync="listQuery.page"
      :limit.sync="listQuery.limit"
      @pagination="handleFilter"
    />
    <rona-awal-tidak-baku-dialog
      :show="showDialog"
      :component="ronaAwal"
      @close="closeDialog"
    />
  </div>
</template>

<script>
import Resource from '@/api/resource';
import RonaAwalTidakBakuTable from '@/views/rona-awal/components/RonaAwalTidakBakuTable.vue';
import RonaAwalTidakBakuDialog from './components/RonaAwalTidakBakuDialog.vue';
import Pagination from '@/components/Pagination';
const ronaAwalesource = new Resource('rona-awals');
// const componentResource = new Resource('components');

export default {
  name: 'NonFormalisedHue',
  components: {
    RonaAwalTidakBakuTable,
    Pagination,
    RonaAwalTidakBakuDialog,
  },
  props: {
    componentOptions: {
      type: Array,
      default: () => [],
    },
  },
  data() {
    return {
      list: [],
      loading: true,
      listQuery: {
        page: 1,
        limit: 10,
        search: null,
        is_master: 0,
      },
      timeoutId: null,
      total: 0,
      ronaAwal: {},
      showDialog: false,
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
      const { data, total } = await ronaAwalesource.list(this.listQuery);
      this.list = data;
      this.total = total;
      this.loading = false;
    },
    handleCreate() {
      this.show = true;
    },
    handleCancelRonaAwal() {
      this.ronaAwal = {};
      this.show = false;
    },
    handleEditForm(id) {
      this.ronaAwal = this.list.find((element) => element.id === id);
      this.show = true;
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
          ronaAwalesource
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
    setMaster(id){
      this.ronaAwal = this.list.find(c => c.id === id);
      console.log(this.ronaAwal);
      this.showDialog = true;
    },
    closeDialog(status){
      this.showDialog = false;
      if (status) {
        this.handleFilter();
      }
    },
  },
};
</script>
