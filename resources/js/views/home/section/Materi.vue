<template>
  <section id="kebijakan" class="kebijakan section_data pb-0">
    <div class="container">
      <el-row>
        <el-col :span="24">
          <h2 class="fw white mb-1-5">Materi AMDALNET</h2>
        </el-col>
      </el-row>
      <el-row :gutter="20" class="mb-1">
        <el-col :span="12">
          <el-select
            v-model="optionValue"
            placeholder="Show"
            style="width: 5.5rem"
            @change="handleFilter()"
          >
            <el-option
              v-for="item in options"
              :key="item.value"
              :label="item.label"
              :value="item.value"
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
      <el-row :gutter="20" class="bb bg-custom">
        <el-col :span="2" class="text-center py1">
          <div class="d-flex align-items-center justify-align-center">
            <span class="fz12 white fw">No</span>
          </div>
        </el-col>
        <el-col :span="6" class="py1 cp">
          <div class="d-flex align-items-center" @click="handleFilter('NOMOR')">
            <span class="fz12 white fw">Judul Materi</span>
            <i class="el-icon-d-caret white fz12 ml-0-3" />
          </div>
        </el-col>
        <el-col :span="8" class="text-center py1 cp">
          <div
            class="d-flex align-items-center justify-align-center"
            @click="handleFilter('TENTANG')"
          >
            <span class="fz12 white fw">Deskripsi</span>
            <i class="el-icon-d-caret white fz12 ml-0-3" />
          </div>
        </el-col>
        <el-col :span="5" class="text-center py1 cp">
          <div
            class="d-flex align-items-center justify-align-center"
            @click="handleFilter('TETAP')"
          >
            <span class="fz12 white fw">Tanggal Terbit</span>
            <i class="el-icon-d-caret white fz12 ml-0-3" />
          </div>
        </el-col>
        <el-col :span="3" class="text-center py1">
          <div class="d-flex align-items-center justify-align-center">
            <span class="fz12 white fw">Download</span>
          </div>
        </el-col>
      </el-row>
      <!-- <el-row
        v-for="(regulation, index) in allData"
        :key="regulation.id"
        :gutter="20"
        style="display: flex; align-items: center"
        class="bb bg-custom tb-hover"
      > -->
      <el-row
        :gutter="20"
        style="display: flex; align-items: center"
        class="bb bg-custom tb-hover"
      >
        <el-col :span="2" class="text-center py">
          <span class="fz12 white fw">1</span>
        </el-col>
        <el-col :span="6" class="py">
          <span class="fz12 white">Permenlhk NO P 23- 2018 Perubahan Izin Lingkungan di luar OSS</span>
        </el-col>
        <el-col :span="8" class="py">
          <span class="fz12 white">Materi Bimtek Hotel Mercure Ancol, 25 Juli 2019</span>
        </el-col>
        <el-col :span="5" class="py text-center">
          <span class="fz12 white">24 Jul 2019</span>
        </el-col>
        <el-col :span="3" class="py text-center">
          <a
            href="#"
            target="_blank"
            class="fz12 white cl-blue buttonDownload"
            download
          >
            <i class="el-icon-download" /> Download
          </a>
        </el-col>
      </el-row>
      <div class="block" style="text-align: right">
        <!-- <pagination
          v-show="total > 0"
          :auto-scroll="false"
          :total="total"
          :page.sync="listQuery.page"
          :limit.sync="listQuery.limit"
          @pagination="getAll"
        /> -->
      </div>
    </div>
  </section>
</template>

<script>
// import axios from 'axios';
// import Pagination from '@/components/Pagination';

export default {
  name: 'Materi',
  components: {
    // Pagination,
  },
  data() {
    return {
      options: [
        {
          value: 10,
          label: 10,
        },
        {
          value: 25,
          label: 25,
        },
        {
          value: 50,
          label: 50,
        },
        {
          value: 100,
          label: 100,
        },
      ],
      keyword: '',
      optionValue: '',
    };
  },
  created() {},
  methods: {
    handleFilter(e) {
      console.log(e);
    },
    handleSearch(e) {
      console.log(e);
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
.el-select {
  width: 3rem;
}
</style>
