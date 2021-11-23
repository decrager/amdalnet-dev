<template>
  <div class="app-container" style="padding: 24px">
    <el-card>
      <workflow />
      <h2>Formulir Kerangka Acuan</h2>
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
        <vsa-item :init-active="scopingActive">
          <vsa-heading>
            PELINGKUPAN
          </vsa-heading>
          <vsa-content>
            <pelingkupan />
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
        <vsa-item :init-active="dampakPotensialActive">
          <vsa-heading>
            DAMPAK POTENSIAL
          </vsa-heading>
          <vsa-content>
            <dampak-potensial
              @handleReloadVsaList="handleReloadVsaList"
            />
          </vsa-content>
        </vsa-item>
        <vsa-item :init-active="dampakPentingHipotetikActive">
          <vsa-heading>
            DAMPAK PENTING HIPOTETIK
          </vsa-heading>
          <vsa-content>
            <dampak-penting-hipotetik
              @handleReloadVsaList="handleReloadVsaList"
            />
          </vsa-content>
        </vsa-item>
        <vsa-item :init-active="metodeStudiActive">
          <vsa-heading>
            METODE STUDI
          </vsa-heading>
          <vsa-content>
            <metode-studi
              @handleReloadVsaList="handleReloadVsaList"
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
import Pelingkupan from './components/Pelingkupan.vue';
import MatrikIdentifikasiDampak from './components/MatrikIdentifikasiDampak.vue';
import DampakPotensial from './components/DampakPotensial.vue';
import DampakPentingHipotetik from './components/DampakPentingHipotetik.vue';
import MetodeStudi from './components/MetodeStudi.vue';
import Workflow from '@/components/Workflow';

export default {
  name: 'FormulirUklUpl',
  components: {
    VsaList,
    VsaItem,
    VsaHeading,
    VsaContent,
    Pelingkupan,
    MatrikIdentifikasiDampak,
    DampakPotensial,
    DampakPentingHipotetik,
    MetodeStudi,
    Workflow,
  },
  data() {
    return {
      idProject: 0,
      isSubmitEnabled: false,
      vsaListKey: 0,
      scopingActive: true,
      matriksActive: false,
      dampakPotensialActive: false,
      dampakPentingHipotetikActive: false,
      metodeStudiActive: false,
    };
  },
  mounted() {
    this.setProjectId();
    this.$store.dispatch('getStep', 3);
  },
  methods: {
    setProjectId(){
      const id = this.$route.params && this.$route.params.id;
      this.idProject = id;
    },
    handleSaveForm() {
      const id = this.$route.params && this.$route.params.id;
      this.$router.push({
        name: 'DokumenUklUpl',
        params: id,
      });
    },
    handleEnableSubmitForm() {
      this.isSubmitEnabled = true;
    },
    handleReloadVsaList(tab) {
      this.vsaListKey = this.vsaListKey + 1;
      this.scopingActive = false;
      this.matriksActive = false;
      this.dampakPotensialActive = false;
      this.dampakPentingHipotetikActive = false;
      this.metodeStudiActive = false;
      if (tab === 'pelingkupan') {
        this.scopingActive = true;
      } else if (tab === 'matriks-identifikasi-dampak') {
        this.matriksActive = true;
      } else if (tab === 'dampak-potensial') {
        this.dampakPotensialActive = true;
      } else if (tab === 'dampak-penting-hipotetik') {
        this.dampakPentingHipotetikActive = true;
      } else if (tab === 'metode-studi') {
        this.metodeStudiActive = true;
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
  --vsa-highlight-color: #1e5128;
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
    color: white;
}

h2 {
  display:inline-block;
  margin-block-start: 0em;
}

</style>
