<template>
  <div class="ph" style="padding: 2em;">
    <p class="ph-header">Tapak Proyek</p>
    <project-map v-if="data !== null" :id="data.id" />

    <p class="ph-header">Alamat Kegiatan Utama</p>
    <p class="data">{{ data.project_address }}</p>

    <p class="ph-header">Lokasi Kegiatan</p>
    <template v-for="addr in data.address">
      <p :key="'ph_add_' + addr.id" class="data">{{ addr.address || '-' }}, {{ addr.district || '-' }}, {{ addr.prov || '-' }} </p>
    </template>

    <p class="ph-header">Deskripsi Lokasi</p>
    <div class="data" v-html="data.location_desc" />

    <p class="ph-header">Surat Kesesuaian Tata Ruang</p>
    <p class="data"><el-link :href="data.ktr" target="_blank" icon="el-icon-download" type="primary">{{ file_ktr }}</el-link></p>

  </div>
</template>
<script>
import ProjectMap from './sections/Map.vue';
export default {
  name: 'PublicProjectLocation',
  components: {
    ProjectMap,
  },
  props: {
    data: {
      type: Object,
      default: null,
    },
  },
  data() {
    return {
      file_ktr: '',
    };
  },
  watch: {
    data: {
      handler(val) {
        const splice = (this.data.ktr).split('/');
        this.file_ktr = splice[splice.length - 1];
      },
      deep: true,
    },
  },

  /* mounted(){
    const splice = (this.data.ktr).split('/');
    this.file_ktr = splice[splice.length - 1];
  }, */
  methods: {

  },
};
</script>
