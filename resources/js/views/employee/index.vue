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
          {{ 'Tambah Anggota TUK' }}
        </el-button>
        <el-row :gutter="32">
          <el-col :sm="24" :md="10">
            <el-input
              v-model="listQuery.search"
              suffix-icon="el-icon search"
              placeholder="Pencarian anggota..."
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
      <EmployeeTable
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
import EmployeeTable from '@/views/employee/components/EmployeeTable.vue';
import Pagination from '@/components/Pagination';
import Resource from '@/api/resource';
const employeeTukResource = new Resource('employee-tuk');

export default {
  name: 'Employee',
  components: {
    EmployeeTable,
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
      const { data, total } = await employeeTukResource.list(this.listQuery);
      this.list = data.map((x) => {
        let team = '-';
        if (x.feasibility_test_team_member !== null) {
          if (x.feasibility_test_team_member.feasibility_test_team !== null) {
            team = this.tukName(
              x.feasibility_test_team_member.feasibility_test_team
            );
          }
        }

        x.team = team;
        return x;
      });
      this.total = total;
      this.loading = false;
    },
    handleSubmitRoute() {
      this.$router.push({ name: 'createEmployeeTuk' });
    },
    handleDelete({ id, nama }) {
      this.$confirm(
        'apakah anda yakin akan menghapus ' + nama + '. ?',
        'Peringatan',
        {
          confirmButtonText: 'OK',
          cancelButtonText: 'Batal',
          type: 'warning',
        }
      )
        .then(() => {
          employeeTukResource
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
    },
    tukName(data) {
      if (data.authority === 'Pusat') {
        return 'Tim Uji Kelayakan Pusat';
      } else if (data.authority === 'Provinsi') {
        return `Tim Uji Kelayakan ${this.capitalize(
          data.province_authority.name
        )}`;
      } else if (data.authority === 'Kabupaten/Kota') {
        return `Tim Uji Kelayakan ${this.capitalize(
          data.district_authority.name
        )}`;
      }

      return '-';
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
    inputSearch(val) {
      if (this.timeoutId) {
        clearTimeout(this.timeoutId);
      }
      this.timeoutId = setTimeout(() => {
        this.getData();
      }, 500);
    },
  },
};
</script>
