<template>
  <div>
    <div style="text-align: right; margin-bottom: 10px">
      <el-button :loading="loadingWorkspace" type="primary" @click="checkData">
        WORKSPACE
      </el-button>
    </div>

    <div
      v-if="isFormulator"
      role="alert"
      class="el-alert el-alert--error is-light"
      style="margin-bottom: 10px;"
    >
      <div class="el-alert__description">
        <p>
          Sebelum masuk ke <strong>Workspace</strong>, harap lengkapi terlebih dahulu semua isian pada halaman ini
        </p>
      </div>
    </div>

    <el-collapse v-model="activeName" :accordion="true">
      <el-collapse-item name="pelingkupan">
        <template slot="title" class="head-accordion">
          <span class="title">PELINGKUPAN</span>
        </template>
        <!-- <Pelingkupan v-if="activeName === 'pelingkupan'" /> -->
        <modul-pelingkupan v-if="activeName === 'pelingkupan'" :mode="1" />
      </el-collapse-item>
      <el-collapse-item name="matriks-identifikasi">
        <template slot="title" class="head-accordion">
          <span class="title">MATRIKS IDENTIFIKASI DAMPAK</span>
        </template>
        <MatriksIdentifikasiDampakTable
          v-if="activeName === 'matriks-identifikasi'"
        />
      </el-collapse-item>
      <el-collapse-item name="peta-batas">
        <template slot="title" class="head-accordion">
          <span class="title">PETA BATAS WILAYAH STUDI & PETA LAINNYA</span>
        </template>
        <PetaBatas v-if="activeName === 'peta-batas'" />
      </el-collapse-item>
      <el-collapse-item name="dampak-potensial">
        <template slot="title" class="head-accordion">
          <span class="title">
            EVALUASI DAMPAK POTENSIAL & DAMPAK PENTING HIPOTETIK
          </span>
        </template>
        <DampakHipotetikMD v-if="activeName === 'dampak-potensial'" :mode="1" />
        <!-- <DampakHipotetik v-if="activeName === 'dampak-potensial'" /> -->
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
        <MatriksDPHTable v-if="activeName === 'matriks-dampak'" />
      </el-collapse-item>
      <el-collapse-item name="bagan-alir-pelingkupan">
        <template slot="title" class="head-accordion">
          <span class="title">BAGAN ALIR PELINGKUPAN</span>
        </template>
        <BaganAlir v-if="activeName === 'bagan-alir-pelingkupan'" />
      </el-collapse-item>
      <el-collapse-item name="table-andal">
        <template slot="title" class="head-accordion">
          <span class="title">ANALISA DAMPAK LINGKUNGAN</span>
        </template>
        <!-- <TableAndal v-if="activeName === 'table-andal'" /> -->
        <MasterDetail v-if="activeName === 'table-andal'" />
      </el-collapse-item>
      <el-collapse-item name="bagan-alir-dampak">
        <template slot="title" class="head-accordion">
          <span class="title">BAGAN ALIR DAMPAK PENTING</span>
        </template>
        <bagan-alir-dampak v-if="activeName === 'bagan-alir-dampak'" />
      </el-collapse-item>
      <el-collapse-item name="evaluasi-holistik">
        <template slot="title" class="head-accordion">
          <span class="title">EVALUASI HOLISTIK</span>
        </template>
        <EvaluasiHolistik v-if="activeName === 'evaluasi-holistik'" />
      </el-collapse-item>
      <el-collapse-item name="lampiran">
        <template slot="title" class="head-accordion">
          <span class="title">LAMPIRAN</span>
        </template>
        <Lampiran v-if="activeName === 'lampiran'" />
      </el-collapse-item>
    </el-collapse>
  </div>
</template>

<script>
import { mapGetters } from 'vuex';
import Resource from '@/api/resource';
const andalComposingResource = new Resource('andal-composing');
// import TableAndal from '@/views/penyusunan-andal/components/Table';
import MasterDetail from '@/views/penyusunan-andal/components/MasterDetail';
import MatriksIdentifikasiDampakTable from '@/views/amdal/components/tables/MatriksIdentifikasiDampakTable.vue';
import PetaBatas from '@/views/penyusunan-andal/clone/PetaBatas.vue';
// import DampakHipotetik from '@/views/penyusunan-andal/clone/DpDPH.vue';
import DampakHipotetikMD from '@/views/amdal/components/DPDPH.vue';
import MetodeStudi from '@/views/amdal/components/MetodeStudi.vue';
import MatriksDPHTable from '@/views/amdal/components/tables/MatriksDPHTable.vue';
// import Pelingkupan from '@/views/amdal/components/Pelingkupan.vue';
import ModulPelingkupan from '@/views/amdal/pelingkupan/index.vue';
import BaganAlir from '@/views/penyusunan-andal/clone/BaganAlir.vue';
import BaganAlirDampak from '../components/BaganAlirDampak.vue';
import EvaluasiHolistik from '@/views/penyusunan-andal/components/EvaluasiHolistik.vue';
import Lampiran from '@/views/penyusunan-andal/components/Lampiran.vue';

export default {
  name: 'Andal',
  components: {
    // TableAndal,
    MasterDetail,
    MatriksIdentifikasiDampakTable,
    PetaBatas,
    // DampakHipotetik,
    DampakHipotetikMD,
    MetodeStudi,
    MatriksDPHTable,
    // Pelingkupan,
    ModulPelingkupan,
    BaganAlir,
    BaganAlirDampak,
    EvaluasiHolistik,
    Lampiran,
  },
  data() {
    return {
      activeName: '',
      loadingWorkspace: false,
    };
  },
  computed: {
    ...mapGetters({
      userInfo: 'user',
      userId: 'userId',
      markingStatus: 'markingStatus',
    }),
    isFormulator() {
      return this.$store.getters.roles.includes('formulator');
    },
  },
  methods: {
    async checkData() {
      this.loadingWorkspace = true;
      const andalData = await andalComposingResource.list({
        checkWorkspace: 'true',
        idProject: this.$route.params.id,
      });
      if (andalData) {
        this.getData();
      } else {
        this.$alert(
          'Silahkan Mengisi Data Analisa Dampak Lingkungan Terlebih Dahulu',
          {
            confirmButtonText: 'OK',
          }
        );
        this.loadingWorkspace = false;
      }
    },
    async getData() {
      await andalComposingResource.list({
        docs: 'true',
        idProject: this.$route.params.id,
      });
      this.loadingWorkspace = false;

      this.$router.push({
        name: 'projectWorkspace',
        params: {
          id: this.$route.params.id,
          filename: `${this.$route.params.id}-andal.docx`,
        },
      });
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
.comment-header p {
  font-weight: normal;
}
</style>
