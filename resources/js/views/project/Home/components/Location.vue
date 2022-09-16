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
    <Tinymce
      v-model="data.location_desc"
      output-format="html"
      :menubar="''"
      :image="false"
      :toolbar="[
        'bold italic underline bullist numlist  preview undo redo fullscreen',
      ]"
      :height="150"
    />

    <p class="header">Surat Kesesuaian Tata Ruang</p>
    <p class="data"><el-link :href="data.ktr" target="_blank" icon="el-icon-download" type="primary">{{ file_ktr }}</el-link></p>

    <div style="text-align: right;">
      <el-button :loading="loading" type="primary" @click="handleSave">Simpan</el-button>
    </div>

  </div>
</template>
<script>
import Tinymce from '@/components/Tinymce';
import ProjectMap from './sections/Map.vue';
import Resource from '@/api/resource';
const projectResource = new Resource('projects');

export default {
  name: 'ProjectLocation',
  components: {
    ProjectMap,
    Tinymce,
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
      loading: false,
    };
  },

  mounted(){
    const splice = (this.data.ktr).split('/');
    this.file_ktr = splice[splice.length - 1];
  },
  methods: {
    async handleSave() {
      this.loading = true;
      await projectResource.update(this.$route.params.id, {
        projectHome: 'true',
        type: 'locationDesc',
        locationDesc: this.data.location_desc,
      });
      this.$message({
        message: 'Data berhasil disimpan',
        type: 'success',
        duration: 5 * 1000,
      });
      this.loading = false;
    },
  },
};
</script>
