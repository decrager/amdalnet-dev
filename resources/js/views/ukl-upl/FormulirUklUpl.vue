<template>
  <div class="app-container">
    <el-form
      ref="postForm"
      :model="postForm"
      label-position="top"
      label-width="200px"
    >
      <vsa-list>
        <vsa-item>
          <vsa-heading>
            RONA LINGKUNGAN AWAL
          </vsa-heading>
          <vsa-content>
            <rona-lingkungan-awal
              @handleSaveRonaAwalData="handleSaveRonaAwalData"
              @handleSaveComponents="handleSaveComponents"
              @handleSaveRonaAwals="handleSaveRonaAwals"
              @handleUpdateComponents="handleUpdateComponents"
              @handleUpdateRonaAwals="handleUpdateRonaAwals"
            />
          </vsa-content>
        </vsa-item>
        <vsa-item>
          <vsa-heading>
            MATRIKS IDENTIFIKASI DAMPAK
          </vsa-heading>
          <vsa-content>
            This is the content
          </vsa-content>
        </vsa-item>
        <vsa-item>
          <vsa-heading>
            JENIS DAN BESARAN DAMPAK
          </vsa-heading>
          <vsa-content>
            This is the content
          </vsa-content>
        </vsa-item>
      </vsa-list>
    </el-form>
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

export default {
  name: 'FormulirUklUpl',
  components: {
    VsaList,
    VsaItem,
    VsaHeading,
    VsaContent,
    RonaLingkunganAwal,
  },
  data() {
    return {
      postForm: {
        id_project: 0,
        rona_awal: {
          components: [],
          rona_awals: [],
        },
        mappings: {},
      },
    };
  },
  mounted() {
    this.setProjectId();
    console.log(this.postForm);
  },
  methods: {
    setProjectId(){
      const id = this.$route.params && this.$route.params.id;
      this.postForm.id_project = id;
    },
    handleClick(tab, event) {
      console.log(tab, event);
    },
    handleSaveRonaAwalData(data) {
      this.postForm.rona_awal = data;
      console.log(this.postForm);
    },
    async handleSaveComponents(data){
      this.postForm.rona_awal.components = await data;
      console.log(this.postForm.rona_awal);
    },
    async handleSaveRonaAwals(data){
      this.postForm.rona_awal.rona_awals = await data;
      console.log(this.postForm.rona_awal);
    },
    handleUpdateComponents(data){
      this.postForm.rona_awal.components = data;
    },
    handleUpdateRonaAwals(data){
      this.postForm.rona_awal.rona_awals = data;
    },
    handleSaveForm() {
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
  --vsa-highlight-color: rgba(85, 119, 170, 1);
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
.vsa-list [hidden]{display:none}
</style>
