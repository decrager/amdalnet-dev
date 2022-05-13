<template>
  <div class="app-container" style="padding: 24px">
    <el-card>
      <div class="filter-container">
        <el-button
          class="filter-item"
          type="primary"
          icon="el-icon-plus"
          @click="handleSubmitRoute"
        >
          {{ 'Tambah TUK' }}
        </el-button>
        <el-row :gutter="32">
          <el-col :sm="24" :md="10">
            <el-input
              v-model="listQuery.search"
              suffix-icon="el-icon search"
              placeholder="Pencarian tim uji kelayakan..."
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
      <TukTable
        :list="list"
        :loading="loading"
        @handleDelete="handleDelete($event)"
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
import TukTable from '@/views/tuk-management/components/TukTable.vue';
import Pagination from '@/components/Pagination';
import Resource from '@/api/resource';
const tukManagementResource = new Resource('tuk-management');

export default {
  name: 'TUKManagement',
  components: {
    TukTable,
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
    handleFilter() {
      this.getData();
    },
    async getData() {
      this.loading = true;
      const { data, total } = await tukManagementResource.list(this.listQuery);
      let no = 1;
      this.list = data.map((x) => {
        let name = `Tim Uji Kelayakan ${this.checkAuthority(
          x.authority,
          x.province_authority,
          x.district_authority
        )}`;

        name = this.capitalize(name);

        if (x.team_number) {
          name += ` ${x.team_number}`;
        }

        x.name = name;
        x.no =
          no +
          this.listQuery.page * this.listQuery.limit -
          this.listQuery.limit;
        no++;
        return x;
      });
      this.total = total;
      this.loading = false;
    },
    handleSubmitRoute() {
      this.$router.push({ name: 'createTuk' });
    },
    handleDelete({ id, nama }) {
      this.$confirm(
        'Apakah anda yakin akan menghapus ' + nama + '. ?',
        'Peringatan',
        {
          confirmButtonText: 'OK',
          cancelButtonText: 'Batal',
          type: 'warning',
        }
      )
        .then(() => {
          tukManagementResource
            .destroy(id)
            .then((response) => {
              this.$message({
                type: 'success',
                message: 'Hapus Selesai',
              });
              this.getData();
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
    async handleSearch() {
      this.listQuery.page = 1;
      this.listQuery.limit = 10;
      await this.getData();
      this.listQuery.search = null;
    },
    checkAuthority(authority, province, district) {
      if (authority === 'Pusat') {
        return 'Pusat';
      } else if (authority === 'Provinsi') {
        if (province !== null) {
          return 'Provinsi ' + province.name;
        }
      } else if (authority === 'Kabupaten/Kota') {
        if (district !== null) {
          return district.name;
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
      }

      return '';
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
  },
};
</script>
