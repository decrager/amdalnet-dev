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
            <el-table-column type="expand">
              <template slot-scope="scope">
                <div class="expand-container">
                  <div>
                    <p><b>NIP: </b>{{ scope.row.nip ? scope.row.nip : '-' }}</p>
                    <p><b>Email: </b>{{ scope.row.email }}</p>
                    <p><b>Jenis Kelamin: </b>{{ scope.row.sex }}</p>
                    <p><b>No. Telepon: </b>{{ scope.row.phone }}</p>
                    <p>
                      <b>Alamat: </b>{{ scope.row.address }},
                      {{ scope.row.district.name | capitalize }},
                      {{ scope.row.province.name | capitalize }}
                    </p>
                  </div>
                </div>
              </template>
            </el-table-column>

            <el-table-column align="center" label="Nama" sortable prop="name" />
            <el-table-column align="center" label="Jabatan" sortable prop="position" />
            <el-table-column
              align="center"
              label="Instansi"
              sortable
              prop="institution"
            />
            <el-table-column align="center" label="NIK" sortable prop="nik" />
            <el-table-column align="center" label="TUK" sortable prop="team" />

            <el-table-column align="center" label="File">
              <template slot-scope="scope">
                <span v-if="scope.row.cv">
                  <el-button
                    type="text"
                    icon="el-icon-download"
                    @click="download(scope.row.cv)"
                  >
                    CV
                  </el-button>
                </span>
                <span v-else>-</span>
              </template>
            </el-table-column>

            <el-table-column
              v-if="checkPermission(['manage tuk member list']) || checkRole(['admin'])"
              align="center"
              label="Aksi"
            >
              <template slot-scope="scope">
                <el-button
                  type="text"
                  href="#"
                  icon="el-icon-edit"
                  @click="handleEdit(scope.row.id)"
                >
                  Ubah
                </el-button>
                <el-button
                  type="text"
                  href="#"
                  icon="el-icon-delete"
                  @click="handleDelete(scope.row.id, scope.row.name)"
                >
                  Hapus
                </el-button>
              </template>
            </el-table-column>
          </el-table>
        </div>
      </div>
    </div>
  </section>
</template>

<script>
import checkPermission from '@/utils/permission';
import checkRole from '@/utils/role';
// import EmployeeTable from '@/views/employee/components/EmployeeTable.vue';
// import Pagination from '@/components/Pagination';
import Resource from '@/api/resource';
const employeeTukResource = new Resource('employee-tuk');

export default {
  name: 'Employee',
  // components: {
  //   EmployeeTable,
  //   Pagination,
  // },
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
        this.listQuery.page = 1;
        this.listQuery.limit = 10;
        this.getData();
      }, 500);
    },
  },
};
</script>

<style scoped>
/* .expand-container {
  display: flex;
  justify-content: space-around;
} */
.expand-container div {
  width: 50%;
  padding: 1rem 3rem;
}
.expand-container__right {
  text-align: right;
}
</style>
