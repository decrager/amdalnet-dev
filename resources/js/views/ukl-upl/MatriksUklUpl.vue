<template>
  <div class="app-container" style="padding: 24px">
    <el-card>
      <h2>Matriks UKL UPL</h2>
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
        <vsa-item :init-active="uklActive">
          <vsa-heading>
            MATRIKS UKL
          </vsa-heading>
          <vsa-content>
            <matriks-ukl />
          </vsa-content>
        </vsa-item>
        <vsa-item :init-active="uplActive">
          <vsa-heading>
            MATRIKS UPL
          </vsa-heading>
          <vsa-content>
            <matriks-upl />
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
import MatriksUkl from './components/MatriksUkl.vue';
import MatriksUpl from './components/MatriksUpl.vue';
import Resource from '@/api/resource';
const impactIdtResource = new Resource('impact-identifications');

export default {
  name: 'MatriksUklUpl',
  components: {
    VsaList,
    VsaItem,
    VsaHeading,
    VsaContent,
    MatriksUkl,
    MatriksUpl,
  },
  data() {
    return {
      isSubmitEnabled: false,
      vsaListKey: 0,
      uklActive: true,
      uplActive: false,
    };
  },
  mounted() {
    this.checkImpactIdentificationData();
  },
  methods: {
    async checkImpactIdentificationData() {
      // check if nominal & units has been filled
      // if not, show error popup, then redirect to formulir page
      const idProject = parseInt(this.$route.params && this.$route.params.id);
      const { data } = await impactIdtResource.list({
        id_project: idProject,
      });
      var completed = 0;
      data.map((imp) => {
        if (imp.id_change_type !== null && imp.id_unit !== null && imp.nominal !== null) {
          completed++;
        }
      });
      if (completed < data.length) {
        this.$message({
          message: 'Mohon isi formulir UKL-UPL terlebih dahulu',
          type: 'error',
          duration: 5 * 1000,
        });
        this.$router.push({ path: '/ukl-upl/' + idProject + '/formulir' });
      }
    },
    handleReloadVsaList(tab) {
      this.vsaListKey = this.vsaListKey + 1;
      if (tab === 1) {
        this.uklActive = true;
        this.uplActive = false;
      } else if (tab === 2) {
        this.uklActive = false;
        this.uplActive = true;
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
