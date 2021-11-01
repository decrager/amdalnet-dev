<template>
  <div class="app-container">
    <vsa-list>
      <vsa-item>
        <vsa-heading>
          RONA LINGKUNGAN AWAL
        </vsa-heading>
        <vsa-content>
          <rona-lingkungan-awal />
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
  name: 'UklUpl',
  components: {
    VsaList,
    VsaItem,
    VsaHeading,
    VsaContent,
    RonaLingkunganAwal,
  },
  data() {
    return {

    };
  },
  mounted() {

  },
  methods: {
    handleClick(tab, event) {
      console.log(tab, event);
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
