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
          {{ 'Tambah SOP' }}
        </el-button>

        <sop-table
          :loading="loading"
          :list="list"
          @handleEditForm="handleEditForm($event)"
          @handleDelete="handleDelete($event)"
        />
      </div>
    </el-card>
  </div>
</template>

<script>
import Resource from '@/api/resource';
import SopTable from '@/views/master-sop/components/SopTable.vue';
const sopResource = new Resource('sops');

export default {
  name: 'SopList',
  components: {
    SopTable,
  },
  data() {
    return {
      list: [],
      listActive: [],
      loading: true,
      activeName: 'sop',
    };
  },
  created() {
    this.getList();
  },
  methods: {
    async getList() {
      this.loading = true;
      const { data } = await sopResource.list({});
      this.list = data;
      this.loading = false;
    },
    handleCreate() {
      this.$router.push({
        name: 'createSop',
        params: { sop: {}},
      });
    },
  },
};
</script>
