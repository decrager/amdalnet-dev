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
          {{ 'Tambah Map Service' }}
        </el-button>
        <el-button
          class="filter-item"
          type="success"
          icon="el-icon-view"
          @click="handleCategory"
        >
          {{ 'Lihat Kategori' }}
        </el-button>
      </div>
      <map-service-table
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
      <map-service-create
        :component="component"
        :show="show"
        :category="category"
        :provinces="provinces"
        @handleSubmitComponent="handleSubmitComponent"
        @handleCancelComponent="handleCancelComponent"
      />

      <map-service-category-dialog
        :create-category="createCategory"
        :show-category="showCategory"
        :category="category"
        @handleSubmitCategory="handleSubmitCategory"
        @handleCancelCategory="handleCancelCategory"
      />
    </el-card>
  </div>
</template>

<script>
import Pagination from '@/components/Pagination';
import MapServiceTable from './components/MapServiceTable.vue';
import MapServiceCreate from './components/MapServiceCreate.vue';
import MapServiceCategoryDialog from './components/MapServiceCategoryDialog.vue';

import axios from 'axios';

export default {
  components: {
    Pagination,
    MapServiceTable,
    MapServiceCreate,
    MapServiceCategoryDialog,
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
      showCategory: false,
      category: [],
      component: {},
      componentCreate: {},
      provinces: [],
      createCategory: {},
    };
  },
  created() {
    this.getList();
    this.getKategory();
    this.getProvinsi();
  },
  methods: {
    handleCancelComponent(){
      this.component = {};
      this.show = false;
    },
    handleCancelCategory(){
      this.showCategory = false;
    },
    handleSubmitCategory() {
      if (this.createCategory.id !== undefined) {
        axios.patch(`api/arcgis-service-category/${this.createCategory.id}`, this.createCategory)
          .then((response) => {
            this.$message({
              type: 'success',
              message: 'Map Service Kategori Berhasil Diupdate',
              duration: 5 * 1000,
            });
            this.showCategory = false;
            this.getKategory();
          })
          .catch((error) => {
            console.log(error);
          });
      } else {
        axios.post('api/arcgis-service-category', this.createCategory)
          .then((response) => {
            console.log(response);
            this.$message({
              message:
                'Map Service Kategori Berhasil Dibuat',
              type: 'success',
              duration: 5 * 1000,
            });
            this.showCategory = false;
            this.createCategory = {};
            this.getKategory();
          })
          .catch((error) => {
            console.log(error);
          });
      }
    },
    handleSubmitComponent(){
      if (this.component.id !== undefined) {
        axios.patch(`api/arcgis-service/${this.component.id}`, this.component)
          .then((response) => {
            this.$message({
              type: 'success',
              message: 'Map Service Berhasil Diupdate',
              duration: 5 * 1000,
            });
            this.show = false;
            this.getList();
          })
          .catch((error) => {
            console.log(error);
          });
      } else {
        axios.post('api/arcgis-service', this.component)
          .then((response) => {
            this.$message({
              message:
                'Map Service Berhasil Dibuat',
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
    handleFilter() {
      this.getList();
    },
    getProvinsi() {
      axios.get('api/provinces')
        .then((response) => {
          this.provinces = response.data.data;
        });
    },
    getKategory() {
      axios.get('api/arcgis-service-categories')
        .then((response) => {
          this.category = response.data;
        });
    },
    async getList() {
      this.loading = true;
      axios.get('api/arcgis-services')
        .then((response) => {
          this.list = response.data.data;
          this.total = response.data.total;
        });
      this.loading = false;
    },
    handleCreate() {
      this.component = {};
      this.show = true;
    },
    handleCategory() {
      this.showCategory = true;
    },
    handleEditForm(id) {
      this.component = this.list.find((element) => element.id === id);
      this.show = true;
    },
    handleDelete({ id, url_service }) {
      this.$confirm('apakah anda yakin akan menghapus ' + url_service + '. ?', 'Peringatan', {
        confirmButtonText: 'OK',
        cancelButtonText: 'Batal',
        type: 'warning',
      })
        .then(() => {
          axios.delete(`api/arcgis-service/${id}`)
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
