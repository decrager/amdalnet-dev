<template>
  <div v-loading="loading" style="margin:2em;">
    <h3>Hasil Konsultasi Publik</h3>
    <div v-if="data" style="padding:1em; border: 1px solid #e0e0e0; border-radius: 0.3em;">
      <el-row :gutter="20">
        <el-col :span="8">
          <p class="p-header">Tanggal</p>
          <p class="p-value"> {{ data.event_date }} </p>
          <p class="p-header">Jumlah Partisipan</p>
          <p class="p-value"> {{ data.participant }} </p>
          <p class="p-header">Lokasi</p>
          <p class="p-value"> {{ data.location }} </p>
          <p class="p-header">Alamat</p>
          <p class="p-value"> {{ data.address }} </p>
        </el-col>
        <el-col :span="16">
          <p class="p-header">Rangkuman Deskriptif atas Harapan Masyarakat</p>
          <section v-html="data.positive_feedback_summary" />

          <p class="p-header">Rangkuman Deskriptif atas Kekhawatiran Masyarakat</p>
          <section v-html="data.negative_feedback_summary" />

          <div v-if="docs.length > 0">
            <p class="p-header" style="margin-top: 2em !important;">Dokumen</p>
            <p v-for="(doc, i) in docs" :key="i+'_key_'+id">
              <el-link :href="doc.url" target="_blank" icon="el-icon-download" type="primary">{{ doc.doc_type }}</el-link>
            </p>
          </div>
        </el-col>
      </el-row>
    </div>
  </div>
</template>
<script>
import Resource from '@/api/resource';
const publicConsultations = new Resource('public-consultations');

export default {
  name: 'ProjectPublicConsultation',
  props: {
    id: {
      type: Number,
      default: 0,
    },
  },
  data(){
    return {
      data: null,
      loading: false,
      docs: [],
    };
  },
  mounted(){
    this.getData();
  },
  methods: {
    async getData(){
      this.loading = true;
      // this.docs = [];
      await publicConsultations.list({ idProject: this.id })
        .then((res) => {
          this.data = res;
          this.getDocs();
        })
        .finally(() => {
          this.loading = false;
        });
    },
    getDocs() {
      this.docs = [];
      const docs = this.data.docs.map((doc) => {
        return { ...JSON.parse(doc.doc_json), url: doc.file };
      });
      this.docs = docs;
    },
  },
};
</script>
<style scoped>
p {  line-height: 1.2em; margin: 0 0 0.3em 0 !important;}
p.p-header {
  font-weight: bold;
}

p.p-value {
  margin-bottom: 1em !important;
}
</style>
