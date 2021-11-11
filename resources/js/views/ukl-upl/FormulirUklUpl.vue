<template>
  <div class="app-container" style="padding: 24px">
    <el-card>
      <h2>Formulir UKL UPL</h2>
      <span>
        <el-button
          class="pull-right"
          type="success"
          size="small"
          icon="el-icon-check"
          :disabled="!isSubmitEnabled"
          @click="handleSaveForm()"
        >
          Simpan & Lanjutkan
        </el-button>
      </span>
      <vsa-list :key="vsaListKey">
        <vsa-item :init-active="ronaActive">
          <vsa-heading>
            RONA LINGKUNGAN AWAL
          </vsa-heading>
          <vsa-content>
            <rona-lingkungan-awal
              @handleReloadVsaList="handleReloadVsaList"
            />
          </vsa-content>
        </vsa-item>
        <vsa-item :init-active="matriksActive">
          <vsa-heading>
            MATRIKS IDENTIFIKASI DAMPAK
          </vsa-heading>
          <vsa-content>
            <matrik-identifikasi-dampak
              @handleReloadVsaList="handleReloadVsaList"
            />
          </vsa-content>
        </vsa-item>
        <vsa-item :init-active="besaranActive">
          <vsa-heading>
            JENIS DAN BESARAN DAMPAK
          </vsa-heading>
          <vsa-content>
            <besaran-dampak
              @handleEnableSubmitForm="handleEnableSubmitForm"
            />
          </vsa-content>
        </vsa-item>
      </vsa-list>
    </el-card>
  </div>
</template>

<script>

import {
  VsaList,
  VsaItem,
  VsaHeading,
  VsaContent,
} from 'vue-simple-accordion';
import 'vue-simple-accordion/dist/vue-simple-accordion.css';
import RonaLingkunganAwal from './components/RonaLingkunganAwal.vue';
import MatrikIdentifikasiDampak from './components/MatrikIdentifikasiDampak.vue';
import BesaranDampak from './components/BesaranDampak.vue';

export default {
  name: 'FormulirUklUpl',
  components: {
    VsaList,
    VsaItem,
    VsaHeading,
    VsaContent,
    RonaLingkunganAwal,
    MatrikIdentifikasiDampak,
    BesaranDampak,
  },
  data() {
    return {
      idProject: 0,
      isSubmitEnabled: false,
      vsaListKey: 0,
      ronaActive: true,
      matriksActive: false,
      besaranActive: false,
    };
  },
  mounted() {
    this.setProjectId();
  },
  methods: {
    setProjectId(){
      const id = this.$route.params && this.$route.params.id;
      this.idProject = id;
    },
    handleSaveForm() {
      this.$router.push({ path: '/ukl-upl/' + this.idProject + '/matriks' });
    },
    handleEnableSubmitForm() {
      this.isSubmitEnabled = true;
    },
    handleReloadVsaList(tab) {
      this.vsaListKey = this.vsaListKey + 1;
      if (tab === 1) {
        this.ronaActive = true;
        this.matriksActive = false;
        this.besaranActive = false;
      } else if (tab === 2) {
        this.ronaActive = false;
        this.matriksActive = true;
        this.besaranActive = false;
      } else if (tab === 3) {
        this.ronaActive = false;
        this.matriksActive = false;
        this.besaranActive = true;
      }
    },
  },
};
</script>

<style>
.vsa-list {
  /* CSS Variables */
  --vsa-max-width: 100%;
  --vsa-min-width: 300px;
  --vsa-heading-padding: 1rem 1rem;
  --vsa-text-color: rgba(55, 55, 55, 1);
  --vsa-highlight-color: #abd4ff;
  --vsa-bg-color: rgba(255, 255, 255, 1);
  --vsa-border-color: rgba(0, 0, 0, 0.2);
  --vsa-border-width: 1px;
  --vsa-border-style: solid;
  --vsa-border: var(--vsa-border-width) var(--vsa-border-style) var(--vsa-border-color);
  --vsa-content-padding: 1rem 1rem;
  --vsa-default-icon-size: 1;

  display: block;
  max-width: var(--vsa-max-width);
  min-width: var(--vsa-min-width);
  width: 100%;

  /* Reset the list styles */
  padding: 0;
  margin: 0;
  list-style: none;

  border: var(--vsa-border);
  color: var(--vsa-text-color);
  background-color: var(--vsa-bg-color);
}
.vsa-list [hidden]{
  display:none
}

.vsa-item__content{
  margin:0;
  padding:var(--vsa-content-padding);
  overflow: auto;
}

.vsa-item__trigger:focus,.vsa-item__trigger:hover{
    outline:none;
    background-color:var(--vsa-highlight-color);
    color: black;
}

h2 {
  display:inline-block;
  margin-block-start: 0em;
}

</style>
