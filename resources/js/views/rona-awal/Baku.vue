<template>
  <div class="filter-container">
    <el-button
      v-if="checkPermission(['manage rona awal']) || checkRole(['admin'])"
      class="filter-item"
      type="primary"
      icon="el-icon-plus"
      @click="handleCreate"
    >
      {{ 'Tambah Master Rona Lingkungan' }}
    </el-button>
    <el-row :gutter="32" style="margin-bottom: 1.5em">
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
    <rona-awal-table
      :loading="loading"
      :list="list"
      :page="listQuery.page"
      :limit="listQuery.limit"
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
    <rona-awal-dialog
      :rona="ronaAwal"
      :show="show"
      :options="componentOptions"
      @handleSubmitRonaAwal="handleSubmitRonaAwal"
      @handleCancelRonaAwal="handleCancelRonaAwal"
    />
  </div>
</template>

<script>
import checkPermission from '@/utils/permission';
import checkRole from '@/utils/role';
import Resource from '@/api/resource';
import RonaAwalTable from '@/views/rona-awal/components/RonaAwalTable.vue';
import Pagination from '@/components/Pagination';
import RonaAwalDialog from './components/RonaAwalDialog.vue';
const ronaAwalesource = new Resource('rona-awals');
// const componentResource = new Resource('components');

export default {
  name: 'FormalisedHue',
  components: {
    RonaAwalTable,
    Pagination,
    RonaAwalDialog,
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
        is_master: 1,
      },
      timeoutId: null,
      total: 0,
      ronaAwal: {},
      show: false,
      // componentOptions: [],
    };
  },
  created() {
    this.getList();
    // this.getComponentList();
  },
  methods: {
    checkPermission,
    checkRole,
    handleSubmitRonaAwal() {
      if (this.ronaAwal.id !== undefined) {
        ronaAwalesource
          .updateMultipart(this.ronaAwal.id, this.ronaAwal)
          .then((response) => {
            this.$message({
              type: 'success',
              message: 'Rona Lingkungan Berhasil Diupdate',
              duration: 5 * 1000,
            });
            this.show = false;
            this.ronaAwal = {};
            this.getList();
          })
          .catch((error) => {
            console.log(error);
          });
      } else {
        ronaAwalesource
          .store(this.ronaAwal)
          .then((response) => {
            this.$message({
              message:
                'Rona Lingkungan ' + this.ronaAwal.name + ' Berhasil Dibuat',
              type: 'success',
              duration: 5 * 1000,
            });
            this.show = false;
            this.ronaAwal = {};
            this.getList();
          })
          .catch((error) => {
            console.log(error);
          });
      }
    },
    /* async getComponentList() {
      const { data } = await componentResource.list({ limit: 1000 });
      this.componentOptions = data.map((i) => {
        return { value: i.id, label: i.name };
      });
    },*/
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
  },
};
</script>
