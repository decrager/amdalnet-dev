<template>
  <div style="padding: 2em;">
    <p class="header">Tapak Proyek</p>
    <project-map v-if="data !== null" :id="data.id" />

    <p class="header">Alamat Kegiatan Utama</p>
    <p class="data">{{ data.project_address }}</p>

    <p class="header">Lokasi Kegiatan</p>
    <template v-for="addr in data.address">
      <p :key="'ph_add_' + addr.id" class="data">{{ addr.address || '-' }}, {{ addr.district || '-' }}, {{ addr.prov || '-' }} </p>
    </template>

    <p class="header">Deskripsi Lokasi</p>
    <p class="data">{{ data.location_desc }}</p>

    <p class="header">Surat Kesesuaian Tata Ruang</p>
    <p class="data"><el-link :href="data.ktr" target="_blank" icon="el-icon-download" type="primary">{{ file_ktr }}</el-link></p>

  </div>
</template>
<script>
import ProjectMap from './sections/Map.vue';
export default {
  name: 'ProjectLocation',
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

  mounted(){
    const splice = (this.data.ktr).split('/');
    this.file_ktr = splice[splice.length - 1];
  },
  methods: {

  },
};
</script>
