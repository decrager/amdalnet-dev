<template>
  <div class="app-container">
    <div class="filter-container">
      <el-button
        class="filter-item"
        type="primary"
        icon="el-icon-plus"
        @click="handleCreate"
      >
        {{ 'Tambah Komponen' }}
      </el-button>
    </div>
    <component-table
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
  name: 'ComponentList',
  components: {
    Pagination,
    ComponentTable,
    ComponentDialog,
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
      show: false,
      componentOptions: [],
      component: {},
    };
  },
  created() {
    this.getList();
    this.getProjectStage();
  },
  methods: {
    handleCancelComponent(){
      this.component = {};
      this.show = false;
    },
    handleSubmitComponent(){
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
              message:
                'Komponen ' +
                this.component.name +
                ' Berhasil Dibuat',
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
    handleDelete({ id, name }) {
      this.$confirm('Hapus Komponen ' + name + '. ?', 'Warning', {
        confirmButtonText: 'OK',
        cancelButtonText: 'Batal',
        type: 'warning',
      })
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
  },
};
</script>
