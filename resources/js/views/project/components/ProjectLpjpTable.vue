<template>
  <el-table
    :data="projects"
    fit
    highlight-current-row
    :header-cell-style="{ background: '#3AB06F', color: 'white' }"
  >
    <el-table-column type="expand" class="row-detail">
      <template>
        <div class="post">
          <div>
            <h3>Daftar Penyusun</h3>
            <el-table
              :data="jumlahAnggota"
              fit
              style="width: 100%"
              highlight-current-row
              :header-cell-style="{ background: '#099C4B', color: 'white' }"
            >
              <el-table-column label="No." width="54px">
                <template slot-scope="scope">
                  <span>{{ scope.$index + 1 }}</span>
                </template>
              </el-table-column>

              <el-table-column label="Nama">
                <template slot-scope="scope">
                  <span>{{ scope.row.formulator_name }}</span>
                </template>
              </el-table-column>

              <el-table-column label="No. Registrasi">
                <template slot-scope="scope">
                  <span>{{ scope.row.reg_no }}</span>
                </template>
              </el-table-column>

              <el-table-column label="Sertifikat">
                <template slot-scope="scope">
                  <span>{{ scope.row.membership_status }}</span>
                </template>
              </el-table-column>

              <el-table-column label="Jabatan">
                <template slot-scope="scope">
                  <span>{{ scope.row.position }}</span>
                </template>
              </el-table-column>

              <el-table-column label="Keahlian">
                <template slot-scope="scope">
                  <span>{{ scope.row.expertise }}</span>
                </template>
              </el-table-column>

              <el-table-column label="File">
                <template slot-scope="scope">
                  <span>{{ scope.row.cv_file }}</span>
                </template>
              </el-table-column>

              <el-table-column>
                <template>
                  <el-button type="danger" title="Hapus Daftar Penyusun" size="mini"><i class="ri-close-line" /></el-button>
                </template>
              </el-table-column>
            </el-table>
          </div>

          <div>
            <h3>Daftar Tenaga Ahli</h3>
            <el-table
              :data="jumlahTa"
              fit
              style="width: 100%"
              highlight-current-row
              :header-cell-style="{ background: '#099C4B', color: 'white' }"
            >
              <el-table-column label="No." width="54px">
                <template slot-scope="scope">
                  <span>{{ scope.$index + 1 }}</span>
                </template>
              </el-table-column>

              <el-table-column label="Nama">
                <template slot-scope="scope">
                  <span>{{ scope.row.formulator_name }}</span>
                </template>
              </el-table-column>

              <el-table-column label="Posisi">
                <template slot-scope="scope">
                  <el-select
                    v-model="scope.row.position"
                    placeholder="Pilih Jabatan"
                    style="width: 100%"
                  >
                    <el-option
                      v-for="item in position"
                      :key="item.value"
                      :label="item.label"
                      :value="item.value"
                    />
                  </el-select>
                </template>
              </el-table-column>

              <el-table-column label="Keahlian">
                <template slot-scope="scope">
                  <span>{{ scope.row.expertise }}</span>
                </template>
              </el-table-column>

              <el-table-column label="File">
                <template slot-scope="scope">
                  <el-button v-if="scope.row.cv_file" size="mini" type="primary" @click="downloadCvTa(scope.row.cv_file)"><i class="ri-file-download-line" /></el-button>
                  <el-button v-else size="mini" type="success">Upload CV</el-button>
                </template>
              </el-table-column>

              <el-table-column>
                <template>
                  <el-button type="danger" title="Hapus Daftar Tenaga Ahli" size="mini"><i class="ri-close-line" /></el-button>
                </template>
              </el-table-column>
            </el-table>
            <el-button type="warning" size="mini" style="float:right; margin: 10px 0;">Simpan</el-button>
          </div>

        </div>
      </template>
    </el-table-column>
    <el-table-column label="No." width="54px">
      <template slot-scope="scope">
        <span>{{ scope.$index + 1 }}</span>
      </template>
    </el-table-column>

    <el-table-column label="Nama Kegiatan">
      <template slot-scope="scope">
        <span>{{ scope.row.project_title }}</span>
      </template>
    </el-table-column>

    <el-table-column label="Nama Tim">
      <template slot-scope="scope">
        <span>{{ scope.row.lpjp_name ? scope.row.lpjp_name : '-' }}</span>
      </template>
    </el-table-column>

    <el-table-column label="Tim Penyusun">
      <template slot-scope="scope">
        <span>{{ scope.row.formulator_name ? scope.row.formulator_name : '-' }}</span>
      </template>
    </el-table-column>

  </el-table>
</template>

<script>
import axios from 'axios';

export default {
  props: {
    project: {
      type: Object,
      default: () => {},
    },
  },
  data() {
    return {
      position: [
        {
          value: 'Ketua',
          label: 'Ketua',
        },
        {
          value: 'Anggota',
          label: 'Anggota',
        },
      ],
      projects: [],
      jumlahAnggota: [],
      jumlahTa: [],
      projectId: this.$route.params && this.$route.params.id,
      curUser: this.$store.getters.userId,
      curUserName: this.$store.getters.name,
    };
  },
  mounted() {
    this.getProjects();
  },
  methods: {
    getProjects() {
      axios.get('api/lpjp-teams', {
        params: {
          id: this.projectId,
        },
      })
        .then((response) => {
          this.projects = response.data.data;
          this.jumlahAnggota = response.data.jumlah;
          this.jumlahTa = response.data.ta;
        });
    },
    download(url) {
      window.open(url, '_blank').focus();
    },
    handleDelete(num) {
      this.$emit('handleDelete', { num });
    },
    downloadCvTa(url) {
      window.open(window.location.origin + url);
    },
  },
};
</script>

<style lang="scss" scoped>
.entity-block {
  display: inline-block;

  .name, .description {
    display: block;
    margin-left: 50px;
    padding: 2px 0;
  }
  .action {
    display:  inline-block;
    padding-right: 5%;
  }
  img {
    width: 40px;
    height: 40px;
    float: left;
  }
  :after {
    clear: both;
  }
  .img-circle {
    border-radius: 50%;
    border: 2px solid #d2d6de;
    padding: 2px;
  }
  span {
    font-weight: 500;
    font-size: 12px;
  }

}
.post {
  font-size: 14px;
  margin-bottom: 15px;
  padding-right: 20px;
  padding-left: 5%;
  color: #666;
  .image {
    width: 100%;
  }
  .user-images {
    padding-top: 20px;
  }
  .title {
    display: block;
    padding: 2px 0;
  }
}
.list-inline {
  padding-left: 0;
  margin-left: -5px;
  list-style: none;
  li {
    display: inline-block;
    padding-right: 5px;
    padding-left: 5px;
    font-size: 13px;
  }
  .link-black {
    &:hover, &:focus {
      color: #999;
    }
  }
}
</style>
