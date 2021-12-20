<template>
  <section id="kebijakan" class="kebijakan section_data pb-0">
    <div class="container">
      <el-row>
        <el-col :span="24">
          <h2 class="fw white mb-1-5">Kebijakan AMDALNET</h2>
        </el-col>
      </el-row>
      <el-row :gutter="20" class="mb-1">
        <el-col :span="12">
          <el-select v-model="value" placeholder="Select">
            <el-option
              v-for="item in options"
              :key="item.value"
              :label="item.label"
              :value="item.value"
            />
          </el-select>
        </el-col>
        <el-col :span="6" :offset="6">
          <el-input v-model="search" type="text" placeholder="Pencarian" />
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
            <span class="fz12 white fw">Nomor Peraturan</span>
            <i class="el-icon-d-caret white fz12 ml-0-3" />
          </div>
        </el-col>
        <el-col :span="8" class="text-center py1 cp">
          <div
            class="d-flex align-items-center justify-align-center"
            @click="handleFilter('TENTANG')"
          >
            <span class="fz12 white fw">Tentang</span>
            <i class="el-icon-d-caret white fz12 ml-0-3" />
          </div>
        </el-col>
        <el-col :span="5" class="text-center py1 cp">
          <div
            class="d-flex align-items-center justify-align-center"
            @click="handleFilter('TETAP')"
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
        :gutter="20"
        style="display: flex; align-items: center"
        class="bb bg-custom tb-hover"
      >
        <el-col :span="2" class="text-center py">
          <span class="fz12 white fw">1</span>
        </el-col>
        <el-col :span="6" class="py">
          <span class="fz12 white">UU RI No. 32 Tahun 2009</span>
        </el-col>
        <el-col :span="8" class="py">
          <span class="fz12 white">Undang - undang republik Indonesia tentang Perlindungan dan Pengelolaan Lunkung hidup</span>
        </el-col>
        <el-col :span="5" class="py text-center">
          <span class="fz12 white">03 Oktober 2021</span>
        </el-col>
        <el-col :span="3" class="py text-center">
          <a href="#" class="fz12 white cl-blue">link.pdf</a>
        </el-col>
      </el-row>
    </div>
  </section>
</template>

<script>
export default {
  name: 'Kebijakan',
  data() {
    return {
      options: [
        {
          value: 'Option1',
          label: 'Option1',
        },
      ],
      value: '',
      search: '',
    };
  },
  methods: {
    handleFilter(e) {
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
</style>
