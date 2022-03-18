<template>
  <div class="app-container">
    <el-card>
      <div class="filter-container">
        <el-row :gutter="32">
          <el-col :sm="24" :md="10">
            <el-input
              v-model="listQuery.search"
              suffix-icon="el-icon search"
              placeholder="Pencarian kegiatan..."
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
      <TukProjectTable
        :list="list"
        :loading="loading"
        :page="listQuery.page"
        :limit="listQuery.limit"
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
import TukProjectTable from '@/views/tuk-project/components/TukProjectTable';
import Pagination from '@/components/Pagination';
import Resource from '@/api/resource';
const tukProjectResource = new Resource('tuk-project');

export default {
  name: 'TukProject',
  components: {
    TukProjectTable,
    Pagination,
  },
  data() {
    return {
      loading: false,
      list: [],
      listQuery: {
        page: 1,
        limit: 10,
        search: null,
      },
      total: 0,
    };
  },
  created() {
    this.getData();
  },
  methods: {
    handleFilter() {
      this.getData();
    },
    async getData() {
      this.loading = true;
      const { data, total } = await tukProjectResource.list(this.listQuery);
      this.list = data;
      this.total = total;
      this.loading = false;
    },
    async handleSearch() {
      this.listQuery.page = 1;
      this.listQuery.limit = 10;
      await this.getData();
      this.listQuery.search = null;
    },
  },
};
</script>
