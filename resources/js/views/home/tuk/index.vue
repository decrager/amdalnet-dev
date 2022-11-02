<!-- eslint-disable vue/html-indent -->
<template>
  <section id="lpjp-table-section" class="lpjp-table-section section">
    <div class="container">
      <h2 class="section__title announce__title lpjp-table-section__title">
        Daftar TUK
      </h2>
      <div class="list-of-projects">
        <div
          style="
            margin-bottom: 1em;
            display: flex;
            justify-content: space-between;
            align-items: center;
          "
        >
          <el-table
            v-loading="loading"
            :data="list"
            fit
            highlight-current-row
            :header-cell-style="{ background: '#3AB06F', color: 'white' }"
          >
            <el-table-column
            type="index"
            width="50"
            label="No"
            />
            <el-table-column align="center" label="TUK" sortable prop="authority" />
            <el-table-column align="center" label="Jumlah Validator" sortable prop="count" />
          </el-table>
        </div>
        <pagination
              v-show="total > 0"
              :total="total"
              :page.sync="listQuery.page"
              :limit.sync="listQuery.limit"
              @pagination="handleFilter"
          />
      </div>
    </div>
  </section>
</template>

<script>
import checkPermission from '@/utils/permission';
import checkRole from '@/utils/role';
import Pagination from '@/components/Pagination';
import Resource from '@/api/resource';
const LandingTukResource = new Resource('tuk');

export default {
  name: 'LandingTuk',
  components: {
    Pagination,
  },
  data() {
    return {
      list: [],
      loading: false,
      listQuery: {
        page: 1,
        limit: 10,
        type: 'list',
        search: null,
      },
      total: 0,
      timeoutId: null,
    };
  },
  created() {
    this.getData();
  },
  methods: {
    checkPermission,
    checkRole,
    handleFilter() {
      this.getData();
    },
    async handleSearch() {
      this.listQuery.page = 1;
      this.listQuery.limit = 10;
      await this.getData();
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
    async getData() {
      this.loading = true;
      const { data } = await LandingTukResource.list(this.listQuery);
      this.list = data;
      this.tukName(data);
      this.loading = false;
    },
    handleSubmitRoute() {
      this.$router.push({ name: 'createEmployeeTuk' });
    },
    tukName(data) {
      if (data.authority === 'Pusat') {
        return 'Tim Uji Kelayakan Pusat';
      } else {
        return `Tim Uji Kelayakan ${data}`;
      }
    },
    capitalize(mySentence) {
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
    },
  },
};
</script>

  <style scoped>
  .expand-container {
    display: flex;
    justify-content: space-around;
  }
  .expand-container div {
    width: 50%;
    padding: 1rem 3rem;
  }
  .expand-container__right {
    text-align: right;
  }
  </style>
