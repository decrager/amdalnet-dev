<template>
  <section id="kebijakan" class="kebijakan section_data pb-0">
    <div class="container">
      <el-row>
        <el-col :span="24">
          <h2 class="fw white mb-1-5">Daftar Persetujuan Lingkungan</h2>
        </el-col>
      </el-row>
      <el-row>
        <el-col :span="24">
          <div role="alert" class="el-alert el-alert--info is-light mb-1-5">
            <div class="el-alert__content">
              <p class="el-alert__description" style="font-weight: bold;">
                Apabila anda memerlukan file persetujuan lingkungan (SKKL/PKPLH) silahkan kirimkan permintaan informasi ke Direktorat PDLUK KLHK melalui email <a href="mailto:amdalnet@menlhk.go.id">amdalnet@menlhk.go.id</a>
              </p>
            </div>
          </div>
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
              v-for="(item, i) in tableData"
              :key="i"
              :label="item.name"
              :value="item.name"
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
      <!-- <template v-if="allData.length > 0"> -->
      <div v-loading="loading">
        <el-row
          :gutter="20"
          class="bb bg-custom"
          style="display: flex; align-items: center"
        >
          <el-col :span="2" class="text-center py1">
            <div class="d-flex align-items-center justify-align-center">
              <span class="fz12 white fw">No</span>
            </div>
          </el-col>
          <el-col :span="4" class="py1 cp">
            <div class="d-flex align-items-center" @click="handleSort('pemarkasa_name')">
              <span class="fz12 white fw">Nama Pemrakarsa Usaha/Kegiatan</span>
              <i class="el-icon-d-caret white fz12 ml-0-3" />
            </div>
          </el-col>
          <el-col :span="5" class="text-center py1 cp">
            <div
              class="d-flex align-items-center justify-align-start"
              @click="handleSort('kegiatan_name')"
            >
              <span class="fz12 white fw">
                Nama Usaha/Kegiatan (SKKL/Izin Lingkungan)
              </span>
              <i class="el-icon-d-caret white fz12 ml-0-3" />
            </div>
          </el-col>
          <el-col :span="4" class="text-center py1 cp">
            <div
              class="d-flex align-items-center justify-align-start"
              @click="handleSort('sk_number')"
            >
              <span class="fz12 white fw">Nomor SK</span>
              <i class="el-icon-d-caret white fz12 ml-0-3" />
            </div>
          </el-col>
          <el-col :span="4" class="text-center py1">
            <div class="d-flex align-items-center justify-align-start">
              <span class="fz12 white fw">Tanggal Berlaku SK</span>
            </div>
          </el-col>
          <el-col :span="5" class="text-center py1">
            <div class="d-flex align-items-center justify-align-start">
              <span class="fz12 white fw">Penerbit SK</span>
            </div>
          </el-col>
          <!-- <el-col :span="2" class="text-center py1">
            <div class="d-flex align-items-center justify-align-center">
              <span class="fz12 white fw">File</span>
            </div>
          </el-col> -->
        </el-row>
        <el-row
          v-for="(ijin, index) in allData"
          :key="ijin.id"
          :gutter="20"
          style="display: flex; align-items: center"
          class="bb bg-custom tb-hover"
        >
          <el-col :span="2" class="text-center py">
            <span class="fz12 white fw">{{ index + 1 + listQuery.page * listQuery.limit - listQuery.limit }}</span>
          </el-col>
          <el-col :span="4" class="py">
            <span class="fz12 white">{{ ijin.pemarkasa_name }}</span>
          </el-col>
          <el-col :span="5" class="py">
            <span class="fz12 white">{{ ijin.kegiatan_name }}</span>
          </el-col>
          <el-col :span="4" class="py">
            <span class="fz12 white">{{ ijin.sk_number }}</span>
          </el-col>
          <el-col :span="4" class="py1">
            <span class="fz12 white">{{ formatDateStr(ijin.date) }}</span>
          </el-col>
          <el-col :span="5" class="py1">
            <span class="fz12 white">{{ ijin.publisher }}</span>
          </el-col>
          <!-- <el-col :span="2" class="py text-center">
            <a
              v-if="ijin.file"
              :href="ijin.file"
              target="_blank"
              class="fz9 white cl-blue buttonDownload"
              download
            >
              <i class="el-icon-download" /> Download
            </a>
            <span v-else>-</span>
          </el-col> -->
        </el-row>
      </div>
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
      <!-- </template> -->
      <!-- <template v-else>
        <el-row :gutter="20">
          <el-col :xs="24" style="padding-top: 0.5rem; padding-bottom: 0.5rem">
            <el-alert
              title="Warning"
              type="warning"
              description="No Record Found"
            />
          </el-col>
        </el-row>
      </template> -->
    </div>
  </section>
</template>

<script>
import axios from 'axios';
import Pagination from '@/components/Pagination';

export default {
  name: 'Ijin',
  components: {
    Pagination,
  },
  data() {
    return {
      value: '',
      search: '',
      allData: [],
      regulations: [],
      loading: false,
      total: 0,
      listQuery: {
        page: 1,
        limit: 10,
      },
      keyword: '',
      optionValue: null,
      sort: 'DESC',
      orderBy: 'date',
      tableData: [
        {
          name: 'Semua',
        },
        {
          name: 'Pusat',
        },
        {
          name: 'Provinsi',
        },
        {
          name: 'Kab/Kota',
        },
      ],
    };
  },
  created() {
    this.getAll();
    // this.getKewenangan();
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
      this.loading = true;
      axios
        .get(
          `/api/environmental-permit?keyword=${this.keyword}&page=${this.listQuery.page}&sort=${this.sort}&limit=${this.listQuery.limit}&orderBy=${this.orderBy}`
        )
        .then((response) => {
          this.allData = response.data.data;
          this.total = response.data.total;
          this.loading = false;
        // eslint-disable-next-line handle-callback-err
        }).catch((err) => {
          this.loading = false;
        });
    },
    async handleSearch() {
      this.orderBy = 'date';
      this.sort = 'desc';
      await this.getAll(this.keyword);
    },
    handleFilter() {
      this.orderBy = 'date';
      this.sort = 'desc';
      axios
        .get(
          `/api/environmental-permit?keyword=${
            this.optionValue === 'Semua' ? '' : this.optionValue
          }&page=${this.listQuery.page}`
        )
        .then((response) => {
          this.allData = response.data.data;
          this.total = response.data.total;
        });
    },
    handleSort(val) {
      this.orderBy = val;
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
.fz9 {
  font-size: 9px;
}
</style>
