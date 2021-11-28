<template>
  <div>
    <div style="text-align: right; margin-bottom: 10px">
      <el-button :loading="loadingWorkspace" type="primary" @click="getData">
        WORKSPACE
      </el-button>
    </div>
    <el-collapse v-model="activeName" :accordion="true">
      <el-collapse-item name="pelingkupan">
        <template slot="title" class="head-accordion">
          <span class="title">PELINGKUPAN</span>
        </template>
        <Pelingkupan v-if="activeName === 'pelingkupan'" />
      </el-collapse-item>
      <el-collapse-item name="matriks-identifikasi">
        <template slot="title" class="head-accordion">
          <span class="title">MATRIKS IDENTIFIKASI DAMPAK</span>
        </template>
        <MatrikIdentifikasiDampak
          v-if="activeName === 'matriks-identifikasi'"
        />
      </el-collapse-item>
      <el-collapse-item name="peta-batas">
        <template slot="title" class="head-accordion">
          <span class="title">PETA BATAS WILAYAH STUDI & PETA LAINNYA</span>
        </template>
      </el-collapse-item>
      <el-collapse-item name="dampak-potensial">
        <template slot="title" class="head-accordion">
          <span class="title">DAMPAK POTENSIAL & DAMPAK PENTING HIPOTETIK</span>
        </template>
      </el-collapse-item>
      <el-collapse-item name="metode-studi">
        <template slot="title" class="head-accordion">
          <span class="title">METODE STUDI</span>
        </template>
        <MetodeStudi v-if="activeName === 'metode-studi'" />
      </el-collapse-item>
      <el-collapse-item name="matriks-dampak">
        <template slot="title" class="head-accordion">
          <span class="title">MATRIKS DAMPAK PENTING HIPOTETIK</span>
        </template>
        <MatriksDampakPentingHipotetik v-if="activeName === 'matriks-dampak'" />
      </el-collapse-item>
      <el-collapse-item name="bagan-alir-pelingkupan">
        <template slot="title" class="head-accordion">
          <span class="title">BAGAN ALIR PELINGKUPAN</span>
        </template>
        <BaganAlir />
      </el-collapse-item>
      <el-collapse-item name="table-andal">
        <template slot="title" class="head-accordion">
          <span class="title">ANALISA DAMPAK LINGKUNGAN</span>
        </template>
        <TableAndal v-if="activeName === 'table-andal'" />
      </el-collapse-item>
      <el-collapse-item name="bagan-alir-dampak">
        <template slot="title" class="head-accordion">
          <span class="title">BAGAN ALIR DAMPAK PENTING</span>
        </template>
        <bagan-alir-dampak />
      </el-collapse-item>
    </el-collapse>
  </div>
</template>

<script>
import Resource from '@/api/resource';
const andalComposingResource = new Resource('andal-composing');
import TableAndal from '@/views/penyusunan-andal/components/Table';
import MatrikIdentifikasiDampak from '@/views/ukl-upl/components/MatrikIdentifikasiDampak.vue';
import MetodeStudi from '@/views/ukl-upl/components/MetodeStudi.vue';
import MatriksDampakPentingHipotetik from '@/views/ukl-upl/components/MatriksDampakPentingHipotetik.vue';
import Pelingkupan from '@/views/ukl-upl/components/Pelingkupan.vue';
import BaganAlir from '@/views/ukl-upl/components/BaganAlir.vue';
import BaganAlirDampak from '../components/BaganAlirDampak.vue';

export default {
  name: 'Andal',
  components: {
    TableAndal,
    MatrikIdentifikasiDampak,
    MetodeStudi,
    MatriksDampakPentingHipotetik,
    Pelingkupan,
    BaganAlir,
    BaganAlirDampak,
  },
  data() {
    return {
      activeName: '',
      loadingWorkspace: false,
    };
  },
  methods: {
    async getData() {
      this.loadingWorkspace = true;
      await andalComposingResource.list({
        docs: 'true',
        idProject: this.$route.params.id,
      });

      this.$router.push({
        name: 'projectWorkspace',
        params: {
          id: this.$route.params.id,
          filename: `${this.$route.params.id}-andal.docx`,
        },
      });
    },
    handleReloadVsaList(tab) {
      // this.accordionKey = this.accordionKey + 1;
      // if (tab === 'pelingkupan') {
      //   this.activeName = '1';
      // } else if (tab === 'matriks-identifikasi-dampak') {
      //   this.activeName = '2';
      // } else if (tab === 'peta-batas') {
      //   this.activeName = '3';
      // } else if (tab === 'dampak-penting') {
      //   this.activeName = '4';
      // } else if (tab === 'metode-studi') {
      //   this.activeName = '5';
      // } else if (tab === 'matriks-dph') {
      //   this.activeName = '6';
      // } else if (tab === 'bagan-alir') {
      //   this.activeName = '7';
      // }
    },
  },
};
</script>

<style>
.el-collapse-item__header {
  /* background-color: #296d36; */
  background-color: #1e5128;
  padding-left: 10px;
  font-size: large;
  font-weight: bold;
  color: rgb(196, 196, 196);
}
.el-collapse-item__content {
  padding-top: 10px;
}
</style>
