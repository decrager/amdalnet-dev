<template>
  <section id="kebijakan" class="kebijakan section_data pb-0">
    <div class="container">
      <el-row>
        <el-col :span="24">
          <h2 class="fw white mb-1-5">Kebijakan AMDALNET</h2>
        </el-col>
        <el-col :span="12">
          <el-select
            v-model="optionValue"
            placeholder="Select"
            @change="handleFilter()"
          >
            <el-option
              v-for="item in regulations"
              :key="item.id"
              :label="item.name"
              :value="item.id"
            />
          </el-select>
        </el-col>
        <el-col :span="6" :offset="6">
          <el-input
            v-model="keyword"
            type="text"
            placeholder="Pencarian"
            @keyup.native.enter="handleSearch()"
          />
        </el-col>
        <el-col :span="24">
          <template>
            <el-table :data="tableData" style="width: 100%">
              <el-table-column type="expand">
                <template slot-scope="props">
                  <div>Tgl Terbit : {{ props.row.tanggal }}</div>
                  <div>Download : {{ props.row.link }}</div>
                </template>
              </el-table-column>
              <el-table-column label="No" prop="no" width="70px" />
              <el-table-column
                label="Kebijakan PUU"
                prop="kebijakan"
                align="left"
              />
              <el-table-column label="Jenis PUU" prop="jenis" align="left" />
              <el-table-column
                label="Bidang Kegiatan"
                prop="bidang"
                align="left"
              />
              <el-table-column label="Tentang" prop="tentang" align="left" />
            </el-table>
          </template>
        </el-col>
      </el-row>
      <el-row :gutter="20" class="mb-1">
        <el-col :span="12">
          <el-select
            v-model="optionValue"
            placeholder="Select"
            @change="handleFilter()"
          >
            <el-option
              v-for="item in regulations"
              :key="item.id"
              :label="item.name"
              :value="item.id"
            />
          </el-select>
        </el-col>
        <el-col :span="6" :offset="6">
          <el-input
            v-model="keyword"
            type="text"
            placeholder="Pencarian"
            @keyup.native.enter="handleSearch()"
          />
        </el-col>
      </el-row>
      <template v-if="allData.length > 0">
        <el-row :gutter="20" class="bb bg-custom">
          <el-col :span="2" class="text-center py1">
            <div class="d-flex align-items-center justify-align-center">
              <span class="fz12 white fw">No</span>
            </div>
          </el-col>
          <el-col :span="6" class="py1 cp">
            <div class="d-flex align-items-center" @click="handleSort(sort)">
              <span class="fz12 white fw">Nomor Peraturan</span>
              <i class="el-icon-d-caret white fz12 ml-0-3" />
            </div>
          </el-col>
          <el-col :span="8" class="text-center py1 cp">
            <div
              class="d-flex align-items-center justify-align-center"
              @click="handleSort(sort)"
            >
              <span class="fz12 white fw">Tentang</span>
              <i class="el-icon-d-caret white fz12 ml-0-3" />
            </div>
          </el-col>
          <el-col :span="5" class="text-center py1 cp">
            <div
              class="d-flex align-items-center justify-align-center"
              @click="handleSort(sort)"
            >
              <span class="fz12 white fw">Ditetapkan</span>
              <i class="el-icon-d-caret white fz12 ml-0-3" />
            </div>
          </el-col>
          <el-col :span="3" class="text-center py1">
            <div class="d-flex align-items-center justify-align-center">
              <span class="fz12 white fw">Link</span>
            </div>
          </el-col>
        </el-row>
        <el-row
          v-for="(regulation, index) in allData"
          :key="regulation.id"
          :gutter="20"
          style="display: flex; align-items: center"
          class="bb bg-custom tb-hover"
        >
          <el-col :span="2" class="text-center py">
            <span class="fz12 white fw">{{ index + 1 }}</span>
          </el-col>
          <el-col :span="6" class="py">
            <span class="fz12 white">{{ regulation.regulation_no }}</span>
          </el-col>
          <el-col :span="8" class="py">
            <span class="fz12 white">{{ regulation.about }}</span>
          </el-col>
          <el-col :span="5" class="py text-center">
            <span class="fz12 white">{{ formatDateStr(regulation.set) }}</span>
          </el-col>
          <el-col :span="3" class="py text-center">
            <a
              :href="regulation.link"
              target="_blank"
              class="fz12 white cl-blue buttonDownload"
              download
            >
              <i class="el-icon-download" /> Download
            </a>
          </el-col>
        </el-row>
        <div class="block" style="text-align: right">
          <pagination
            v-show="total > 0"
            :auto-scroll="false"
            :total="total"
            :page.sync="listQuery.page"
            :limit.sync="listQuery.limit"
            @pagination="getAll"
          />
        </div>
      </template>
      <template v-else>
        <el-row :gutter="20">
          <el-col :xs="24" style="padding-top: 0.5rem; padding-bottom: 0.5rem">
            <el-alert
              title="Warning"
              type="warning"
              description="No Record Found"
            />
          </el-col>
        </el-row>
      </template>
    </div>
  </section>
</template>

<script>
import axios from 'axios';
import Pagination from '@/components/Pagination';

export default {
  name: 'Kebijakan',
  components: {
    Pagination,
  },
  data() {
    return {
      options: [
        {
          value: '1',
          label: 'Ketetapan Majelis Permusyawaratan Rakyat (Tap MPR)',
        },
        {
          value: '2',
          label: 'Undang - undang (UU)',
        },
        {
          value: '3',
          label: 'Peraturan Pemerintah (PP)',
        },
        {
          value: '4',
          label: 'Peraturan Presiden (Perpres)',
        },
        {
          value: '5',
          label: 'Peraturan Mentri (Permen)',
        },
        {
          value: '6',
          label: 'Peraturan Daerah (Perda) Provinsi',
        },
        {
          value: '7',
          label: 'Peraturan Daerah (Perda) Kabupaten/Kota',
        },
      ],
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
</style>
