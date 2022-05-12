<template>
  <div>
    <div class="filter-container">
      <el-button
        class="filter-item"
        type="primary"
        icon="el-icon-plus"
        @click="handleCreate"
      >
        {{ 'Tambah Komponen' }}
      </el-button>
      <el-row :gutter="32">
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
    </div>
    <component-table
      :loading="loading"
      :list="list"
      :page="listQuery.page"
      :limit="listQuery.limit"
      :options="componentOptions"
      @sort="onTableSort"
      @projectStageFilter="onProjectStageFilter"
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
    <component-dialog
      :component="component"
      :show="show"
      :options="componentOptions"
      @handleSubmitComponent="handleSubmitComponent"
      @handleCancelComponent="handleCancelComponent"
    />
  </div>
</template>
<script>
import Resource from '@/api/resource';
import Pagination from '@/components/Pagination';
import ComponentTable from './components/ComponentTable.vue';
import ComponentDialog from './components/ComponentDialog.vue';

const componentResource = new Resource('components');
const projectStageResource = new Resource('project-stages');

export default {
  name: 'StandardisedComponent',
  components: {
    Pagination,
    ComponentTable,
    ComponentDialog,
  },
  props: {
    componentOptions: {
      type: Array,
      default: () => [],
    },
  },
  data() {
    return {
      activeName: 'standardised',
      list: [],
      nonFormal: [],
      loading: true,
      listQuery: {
        page: 1,
        limit: 10,
        search: null,
        is_master: 1,
      },
      timeoutId: null,
      total: 0,
      show: false,
      // componentOptions: [],
      component: {},
    };
  },
  created() {
    this.getList();
    // this.getProjectStage();
  },
  methods: {
    handleCancelComponent() {
      this.component = {};
      this.show = false;
    },
    handleSubmitComponent() {
      if (this.component.id !== undefined) {
        componentResource
          .updateMultipart(this.component.id, this.component)
          .then((response) => {
            this.$message({
              type: 'success',
              message: 'Komponen Berhasil Diupdate',
              duration: 5 * 1000,
            });
            this.show = false;
            this.component = {};
            this.getList();
          })
          .catch((error) => {
            console.log(error);
          });
      } else {
        componentResource
          .store(this.component)
          .then((response) => {
            this.$message({
              message: 'Komponen ' + this.component.name + ' Berhasil Dibuat',
              type: 'success',
              duration: 5 * 1000,
            });
            this.show = false;
            this.component = {};
            this.getList();
          })
          .catch((error) => {
            console.log(error);
          });
      }
    },
    async getProjectStage() {
      const { data } = await projectStageResource.list({ limit: 1000 });
      this.componentOptions = data.map((i) => {
        return { value: i.id, label: i.name };
      });
    },
    handleFilter() {
      this.getList();
    },
    async getList() {
      this.loading = true;
      const { data, total } = await componentResource.list(this.listQuery);
      this.list = data;
      this.total = total;
      this.loading = false;
    },
    handleCreate() {
      this.component = {};
      this.show = true;
    },
    handleEditForm(id) {
      this.component = this.list.find((element) => element.id === id);
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
          componentResource
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
    onProjectStageFilter(val) {
      // console.log('projectStageFilter: ', val);
      // this.listQuery.filter = val; idProjectStage
      this.listQuery.idProjectStage = val;
      this.handleFilter();
    },
    onTableSort(sort) {
      this.listQuery.order = sort.order;
      this.listQuery.orderBy = sort.prop;
      this.handleFilter();
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
