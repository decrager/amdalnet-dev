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
        <el-row :gutter="32">
          <el-col :sm="24" :md="10">
            <el-input
              v-model="search"
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
      <sop-table
        :loading="loading"
        :list="list"
        @handleEditForm="handleEditForm($event)"
        @handleDelete="handleDelete($event)"
      />
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
      timeoutId: null,
      search: null,
    };
  },
  created() {
    this.getList();
  },
  methods: {
    async getList() {
      this.loading = true;
      const { data } = await sopResource.list({ search: this.search });
      this.list = data;
      this.loading = false;
    },
    handleCreate() {
      this.$router.push({
        name: 'createSop',
        // eslint-disable-next-line object-curly-spacing
        params: { sop: {} },
      });
    },
    inputSearch(val) {
      if (this.timeoutId) {
        clearTimeout(this.timeoutId);
      }
      this.timeoutId = setTimeout(() => {
        this.getList();
      }, 500);
    },
    async handleSearch() {
      await this.getList();
      this.search = null;
    },
  },
};
</script>
