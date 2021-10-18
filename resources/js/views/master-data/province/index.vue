<template>
  <div class="app-container" style="padding: 24px">
    <div class="filter-container">
      <el-button
        class="filter-item"
        icon="el-icon-plus"
        size="mini"
        type="primary"
        @click="handleCreate"
      >
        {{ $t('table.add') + ' Provinsi' }}
      </el-button>
    </div>
    <!-- <el-input v-model="listQuery.name" :placeholder="$t('table.name')" style="width: 200px;" class="filter-item" @keyup.enter.native="handleFilter" /> -->
    <el-table
      v-for="list in lists"
      :key="list.id"
      v-loading="loading"
      border
      fit
      highlight-current-row
    >
      <el-table-column align="center" label="No." sortable>
        <span>{{ list.id }}</span>
      </el-table-column>
      <el-table-column align="center" label="Nama Provinsi" sortable>
        <span>{{ list.name }}</span>
      </el-table-column>
      <el-table-column align="center" label="Aksi">
        <el-button
          icon="el-icon-edit"
          size="mini"
          type="primary"
          @click="handleEditForm(list.id)"
        >
          Edit
        </el-button>
        <el-button
          icon="el-icon-delete"
          size="mini"
          type="danger"
          @click="handleDelete(list.id, list.name)"
        >
          Delete
        </el-button>
      </el-table-column>

    </el-table>
    <el-divider />
    <!-- <div :class="{'hidden':hidden}" class="pagination-container">
      <el-pagination
        :background="background"
        :current-page.sync="currentPage"
        :page-size.sync="pageSize"
        :layout="layout"
        :page-sizes="pageSizes"
        :total="total"
        v-bind="$attrs"
        @size-change="handleSizeChange"
        @current-change="handleCurrentChange"
      />
    </div> -->
  </div>
</template>
<script>
import axios from 'axios';

export default {
  data() {
    return {
      lists: [],
      loading: false,
      filtered: [],
      search: '',
      name: '',
    };
  },
  created() {
    this.getProvinces();
  },
  methods: {
    async getProvinces() {
      try {
        await axios.get('/api/provinces?page=1')
          .then((result) => {
            this.lists = result.data.data;
          }).catch((err) => {
            throw err.message;
          });
      } catch (e) {
        console.log(e.message);
      }
    },
    handleCreate() {

    },
    handleEditForm(id) {
    },
    handleDelete() {

    },
  },

};
</script>

<style>
.el-table th {
  background-color: #1BBF65;
  color: white;
}
</style>

<style scoped>
.pagination-container {
  background: #fff;
  padding: 32px 16px;
}
.pagination-container.hidden {
  display: none;
}
</style>
