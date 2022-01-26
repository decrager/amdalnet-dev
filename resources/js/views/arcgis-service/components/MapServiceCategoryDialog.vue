<template>
  <el-dialog :title="'Map Service Kategori'" :visible.sync="showCategory" :close-on-click-modal="false" :show-close="true">
    <div style="background-color: #eaeaea; padding: 10px; margin-bottom: 10px;">
      <h3>Tambah Kategori Baru</h3>
      <el-form ref="categoryForm" :model="createCategory">
        <el-row>
          <el-form-item label="Nama Kategori" prop="category_name">
            <el-input v-model="createCategory.category_name" type="text" />
          </el-form-item>
          <el-form-item label="Status" prop="active">
            <el-switch
              v-model="createCategory.active"
              active-color="#13ce66"
              inactive-color="#ff4949"
              active-text="Aktif"
              inactive-text="Tidak Aktif"
              :active-value="1"
              :inactive-value="0"
            />
          </el-form-item>

        </el-row>
        <el-row />
      </el-form>
      <div style="margin: 10px 0;">
        <el-button type="primary" @click="handleSubmitCategory()"> Tambah Kategori </el-button>
      </div>
    </div>
    <el-table
      v-loading="loading"
      :data="category"
      fit
      highlight-current-row
      :header-cell-style="{ background: '#3AB06F', color: 'white', border:'0' }"
    >
      <el-table-column label="No." width="54px">
        <template slot-scope="scope">
          <span>{{ scope.$index + 1 }}</span>
        </template>
      </el-table-column>

      <el-table-column label="Nama Kategori">
        <template slot-scope="scope">
          <span>{{ scope.row.category_name }}</span>
        </template>
      </el-table-column>

      <el-table-column label="Status">
        <template slot-scope="scope">
          <el-tag :type="scope.row.active === 1 ? 'success' : 'danger'">{{ scope.row.active === 1 ? 'Active' : 'Tidak Aktif' }}</el-tag>
        </template>
      </el-table-column>

      <el-table-column label="Aksi">
        <template slot-scope="scope">
          <el-button
            type="text"
            href="#"
            icon="el-icon-edit"
            @click="handleEditForm(scope.row.id)"
          >
            Ubah
          </el-button>
          <el-button
            type="text"
            href="#"
            icon="el-icon-delete"
            @click="handleDeleteCategory(scope.row.id, scope.row.category_name)"
          >
            Hapus
          </el-button>
        </template>
      </el-table-column>
    </el-table>

  </el-dialog>
</template>

<script>
import axios from 'axios';

export default {
  props: {
    createCategory: {
      type: Object,
      default: () => {},
    },
    showCategory: Boolean,
    category: {
      type: Object,
      default: () => [],
    },
  },
  methods: {
    handleSubmitCategory() {
      this.$emit('handleSubmitCategory');
    },
    handleDeleteCategory(id, category_name) {
      this.$confirm('Apakah anda yakin akan menghapus kategori ' + category_name + '. ?', 'Peringatan', {
        confirmButtonText: 'OK',
        cancelButtonText: 'Batal',
        type: 'warning',
      })
        .then(() => {
          axios.delete(`api/arcgis-service-category/${id}`)
            .then((response) => {
              this.$message({
                type: 'success',
                message: 'Hapus Selesai',
              });
              this.showCategory = false;
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
