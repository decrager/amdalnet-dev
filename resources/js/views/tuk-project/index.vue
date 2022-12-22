<template>
  <div class="app-container">
    <el-card v-if="checkRole(['examiner-secretary'])">
      <div class="filter-container">
        <el-row :gutter="32">
          <el-col :sm="24" :md="10">
            <el-input
              v-model="listQuery.search"
              suffix-icon="el-icon search"
              placeholder="Pencarian kegiatan..."
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
      <TukProjectTable
        :list="list"
        :loading="loading"
        :page="listQuery.page"
        :limit="listQuery.limit"
        :marking="markingOptions"
      />
      <pagination
        v-show="total > 0"
        :total="total"
        :page.sync="listQuery.page"
        :limit.sync="listQuery.limit"
        @pagination="handleFilter"
      />
    </el-card>
    <el-card v-else>
      <div style="text-align: center;">
        <h3>Halaman Ini Diperuntukkan bagi Kepala Sekretariat TUK</h3>
      </div>
    </el-card>
  </div>
</template>

<script>
import TukProjectTable from '@/views/tuk-project/components/TukProjectTable';
import checkRole from '@/utils/role';
import Pagination from '@/components/Pagination';
import Resource from '@/api/resource';
const tukProjectResource = new Resource('tuk-project');
const workflowStateResource = new Resource('workflow-state');

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
      markingOptions: [],
      listQuery: {
        page: 1,
        limit: 10,
        search: null,
        orderBy: 'created_at',
        order: 'DESC',
      },
      timeoutId: null,
      total: 0,
    };
  },
  created() {
    this.getMarking();
    this.getData();
  },
  methods: {
    checkRole,
    handleFilter() {
      this.getData();
    },
    async getMarking() {
      const data = await workflowStateResource.list({ options: 'true' });
      this.markingOptions = data;
    },
    async getData() {
      if (!this.checkRole(['examiner-secretary'])) {
        return false;
      }

      this.loading = true;
      const { data, total } = await tukProjectResource.list(this.listQuery);
      this.list = data.map((x) => {
        x.initiator_name = x.initiator.name;
        x.complete_address = this.getAddress(x.address);
        return x;
      });
      this.total = total;
      this.loading = false;
    },
    async handleSearch() {
      this.listQuery.page = 1;
      this.listQuery.limit = 10;
      await this.getData();
      this.listQuery.search = null;
    },
    inputSearch(val) {
      if (this.timeoutId) {
        clearTimeout(this.timeoutId);
      }
      this.timeoutId = setTimeout(() => {
        this.listQuery.page = 1;
        this.listQuery.limit = 10;
        this.getData();
      }, 500);
    },
    getAddress(address) {
      if (address) {
        if (address.length > 0) {
          return `${address[0].address}, ${this.capitalize(
            address[0].district
          )}, Provinsi ${this.capitalize(address[0].prov)}`;
        }
      }

      return '';
    },
    capitalize(mySentence) {
      if (mySentence) {
        const words = mySentence.split(' ');
        const newWords = words
          .map((word) => {
            return (
              word.toLowerCase()[0].toUpperCase() +
              word.toLowerCase().substring(1)
            );
          })
          .join(' ');
        return newWords;
      } else {
        return '';
      }
    },
  },
};
</script>
