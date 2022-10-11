<template>
  <section id="kebijakan" class="kebijakan section_data pb-0">
    <div class="container">
      <el-row>
        <el-col :span="24">
          <h2 class="fw white mb-1-5">Daftar Klaster KBLI yang memiliki tingkat resiko Menengah Rendah berdasarkan PP 5 Th. 2021 dan diproses Lampiran PKPLH Otomatis melalui Sistem Informasi Amdalnet terintegrasi OSS RBA</h2>
        </el-col>
      </el-row>
      <template>
        <el-row :gutter="20" class="bb bg-custom">
          <el-col :span="1" class="text-center py1">
            <div class="d-flex align-items-center justify-align-center">
              <span class="fz12 white fw">No</span>
            </div>
          </el-col>
          <el-col :span="8" class="py1 cp">
            <div class="d-flex align-items-center" @click="handleSort(sort)">
              <span class="fz12 white fw">Cluster KBLI UKL-UPL Reskio Menengah Rendah</span>
            </div>
          </el-col>
          <el-col :span="8" class="text-center py1 cp">
            <div
              class="d-flex align-items-center justify-align-start"
              @click="handleSort(sort)"
            >
              <span class="fz12 white fw">SOP</span>
            </div>
          </el-col>
          <el-col :span="4" class="text-center py1 cp">
            <div
              class="d-flex align-items-center justify-align-start"
              @click="handleSort(sort)"
            >
              <span class="fz12 white fw">Persetujuan Teknis</span>
            </div>
          </el-col>
        </el-row>
        <el-row
          v-for="(dataCluster, index) in dataClusters"
          :key="dataCluster.no"
          :gutter="20"
          class="bg-custom w-el-row"
          style="margin-bottom: 3px;"
        >
          <el-col :span="1" class="text-center py">
            <span class="fz12 white fw ">{{ index + 1 }}</span>
          </el-col>
          <el-col :span="8" class="py cluster-col">
            <span class="fz12 white">
              <b>{{ dataCluster.cluster.judul }}</b><br>
              <u>{{ dataCluster.cluster.subjudul }}</u><br>
              {{ dataCluster.cluster.konten }}<br>
              <a
                class="fz12 white cl-blue buttonDownload"
                target="_blank"
                :href="dataCluster.cluster.link"
              >
                <span class="fa fa-download" />Template Formulir
              </a>
            </span>
          </el-col>
          <el-col :span="8" class="py sop-col">
            <span class="fz12 white">
              <ul
                v-for="(sop, sopIndex) in dataCluster.sop"
                :key="sopIndex"
                :gutter="20"
                style="list-style-type:disc;"
              >
                <li>{{ sop }}</li></ul>
            </span>
          </el-col>
          <el-col :span="7" class="py pertek-col-li">
            <span class="fz12 white">
              <ul
                v-for="(pertek, pertekIndex) in dataCluster.pertek"
                :key="pertekIndex"
                :gutter="20"
                style="list-style-type: none;"
              >
                <div>
                  <li style="padding-bottom: 5px;">
                    <a
                      :href="pertek.link"
                      target="_blank"
                      class="fz12 white cl-blue buttonDownload"
                      download
                    >
                      <i class="el-icon-download" /> {{ pertek.judul }}
                    </a>
                  </li>
                </div>
              </ul>
            </span>
          </el-col>
        </el-row>
      </template>
    </div>
  </section>
</template>

<script>
import axios from 'axios';
// import Pagination from '@/components/Pagination';
import dataCluster from '@/data/dataCluster';

export default {
  name: 'Kebijakan',
  components: {
    // Pagination,
  },
  data() {
    return {
      value: '',
      search: '',
      allData: [],
      regulations: [],
      total: 0,
      listQuery: {
        page: 1,
        limit: 10,
      },
      keyword: '',
      optionValue: null,
      sort: 'ASC',
      tableData: [
        {
          no: '1000',
          kebijakan: 'PP Nomor 22 Tahun 2021',
          jenis: 'Peraturan Pemerintah',
          bidang: 'Lingkungan Hidup',
          tentang:
            'Penyelenggaraan Perlindungan dan Pegelolaan Lingkungan Hidup',
          tanggal: '01 Mar 2021',
          link: 'https://amdal.menlhk.go.id/amdal_site/uploads/kebijakan/PP Nomor 22 Tahun 2021.pdf',
        },
      ],
      dataClusters: dataCluster,
    };
  },
  created() {
    this.getAll();
    this.getRegulations();
  },
  methods: {
    formatDateStr(date) {
      const today = new Date(date);
      var bulan = [
        'Januari',
        'Februari',
        'Maret',
        'April',
        'Mei',
        'Juni',
        'Juli',
        'Agustus',
        'September',
        'Oktober',
        'November',
        'Desember',
      ];
      const year = today.getFullYear();
      const month = today.getMonth();
      const day = today.getDate();
      const monthID = bulan[month];
      const finalDate = `${day} ${monthID} ${year}`;
      return finalDate;
    },
    getAll(search, sort) {
      axios
        .get(
          `/api/policys?keyword=${this.keyword}&page=${this.listQuery.page}&sort=${this.sort}`
        )
        .then((response) => {
          this.allData = response.data.data;
          this.total = response.data.total;
        });
    },
    getRegulations() {
      axios.get(`/api/regulations`).then((response) => {
        this.regulations = response.data;
      });
    },
    handleSearch() {
      this.getAll(this.keyword);
    },
    handleFilter() {
      axios
        .get(
          `/api/policys?type=${this.optionValue}&page=${this.listQuery.page}`
        )
        .then((response) => {
          this.allData = response.data.data;
          this.total = response.data.total;
        });
    },
    handleSort() {
      if (this.sort === 'ASC') {
        this.sort = 'DESC';
      } else {
        this.sort = 'ASC';
      }
      this.getAll(null, this.sort);
    },
    GetFilename(url) {
      if (url) {
        var m = url.toString().match(/.*\/(.+?)\./);
        if (m && m.length > 1) {
          return m[1];
        }
      }
      return '';
    },
  },
};
</script>

<style scoped>
#kebijakan {
  background-color: #133715;
  padding-top: 11rem;
  padding-bottom: 11rem;
}
.fz12 {
  font-size: 12px;
}
.fw {
  font-weight: bold;
}
.white {
  color: #fff;
}
.text-center {
  text-align: center;
}

.py {
  padding-top: 0.5rem;
  padding-bottom: 0.5rem;
}
.py1 {
  padding-top: 1rem;
  padding-bottom: 1rem;
}
.bb {
  border-bottom: 1px solid #2b3d31;
}
.br {
  border-right: 1px solid #2b3d31;
}
.bg-custom {
  background-color: #041608;
}
.cl-blue {
  color: #204f67;
}
.tb-hover:hover {
  background-color: #133715;
}
.tb-hover:hover a {
  color: #fff;
}
.mb-1-5 {
  margin-bottom: 1.5rem;
}
.mb-1 {
  margin-bottom: 1rem;
}
.d-flex {
  display: flex;
}
.align-items-center {
  align-items: center;
}
.justify-align-center {
  justify-content: center;
}
.ml-0-3 {
  margin-left: 0.3rem;
}
.cp {
  cursor: pointer;
}
.pb-0 {
  padding-bottom: 0;
}
.buttonDownload {
  display: inline-block;
  background: #099c4b;
  padding: 0.4rem;
  color: #fff;
  border-radius: 10px;
}
.el-table__header-wrapper {
  background-color: red;
}
.fz9{font-size: 9px;}

.w-el-row {
  margin-left: -10px;
  margin-right: -10px;
  display: flex;
}
.tb-hover:hover {
  background-color: #133715;
}
.cluster-col {
  padding: 10px;
  padding-right: 20px;
  text-align: justify;
}
.sop-col {
  padding-left: 25px;
  padding-right: 25px;
  text-align: justify;
}
.sop-col-li {
  padding-bottom: 5px;
}
.pertek-col-li {
  padding-left: 25px;
  padding-bottom: 5px;
}
</style>
