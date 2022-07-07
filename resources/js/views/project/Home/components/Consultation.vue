<template>
  <div v-loading="loading" style="margin:2em;">
    <h3>Hasil Konsultasi Publik</h3>
    <div v-if="data" style="padding:1em; border: 1px solid #e0e0e0; border-radius: 0.3em;">
      <el-row :gutter="20">
        <el-col :span="8">
          <p style="font-weight:bold;">Tanggal Konsultasi Publik</p>
          <p> {{ data.event_date }} </p>
        </el-col>
        <el-col :span="16">
          <p style="font-weight:bold;">Rangkuman Deskriptif atas Harapan Masyarakat</p>
          <section v-html="data.positive_feedback_summary" />

          <p style="font-weight:bold;">Rangkuman Deskriptif atas Kekhawatiran Masyarakat</p>
          <section v-html="data.negative_feedback_summary" />
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
    };
  },
  mounted(){
    this.getData();
  },
  methods: {
    async getData(){
      this.loading = true;
      await publicConsultations.list({ idProject: this.id })
        .then((res) => {
          this.data = res;
        })
        .finally(() => {
          this.loading = false;
        });
    },
  },
};
</script>
