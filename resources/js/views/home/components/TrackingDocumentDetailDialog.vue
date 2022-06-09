<template>
  <el-dialog
    :title="'Lacak Pengajuan Persetujuan Lingkungan'"
    :visible.sync="show"
    :before-close="handleClose"
    :close-on-click-modal="false"
    class="home-tracking-detail"
  >

    <div v-if="project" style="margin: auto 2em;line-height:130% !important; word-wrap:break-word!important;">
      <p style="font-weight:bold; margin-top:1em;">Rencana Usaha dan/atau Kegiatan</p>
      <p style="font-size:150%;">{{ project.project_title }}</p>
      <p style="font-weight:bold; margin-top:1em;">No Registrasi</p>
      <p>{{ (project.registration_no).toUpperCase() }} </p>
      <!-- <div style="margin-top:1em;"><p><span style="font-weight:bold">No Registrasi</span> {{ project.registration_no }}</p></div> -->
      <div style="margin-top:1.5em; padding: 1em; border: 1px solid #e0e0e0; border-radius: 1em;">
        <el-row :gutter="8">
          <el-col :span="10">
            <p style="font-weight:bold;">Jenis Dokumen</p>
            <p>{{ project.required_doc }}</p>
            <p style="font-weight:bold; margin-top:1em;">Risiko</p>
            <p>{{ project.risk_level }} </p>
            <p style="font-weight:bold; margin-top:1em;">Kewenangan</p>
            <p>{{ project.authority }} </p>
            <p style="font-weight:bold; margin-top:1em;">Lokasi</p>
            <p>{{ project.address[0].address }}<br>{{ project.address[0].district }} {{ project.address[0].prov }} </p>
            <p style="font-weight:bold; margin-top:1em;">Deskripsi</p>
            <section v-html="project.description" />

          </el-col>
          <el-col :span="14">
            <div v-loading="loading">
              <el-button type="info" :icon="reverse? 'el-icon-bottom': 'el-icon-top' " style="float:right;" circle @click="reverse = !reverse" />
              <p style="font-weight:bold;">Status</p> <!-- <p><el-button type="primary" size="mini" plain @click="showAll = !showAll"><span v-if="showAll">tampilkan semua tahapan</span><span v-else>tampilkan hingga tahapan terkini</span></el-button></p> -->
              <div style="margin-top: 1em;">
                <el-checkbox v-model="showAll">tampilkan seluruh tahapan</el-checkbox>
              </div>
              <div style="padding: 0.5em;">
                <el-timeline v-if="data.length > 0" style="margin-top: 2em;" :reverse="reverse">
                  <el-timeline-item
                    v-for="(activity, index) in (showAll ? data : data.filter(e => e.rank <= current_rank))"
                    :key="index"
                    :timestamp="activity.datetime"
                    size="large"
                    :type="activity.rank < current_rank ? (activity.rank === 1 ? 'info' : 'primary') : (activity.rank === current_rank ? (current_rank === data[0].rank ? 'primary' : 'warning') : 'default')"
                    :icon="activity.rank < current_rank ? (activity.rank === 1 ? 'el-icon-plus' : 'el-icon-check') : (activity.rank === current_rank ? (current_rank === data[0].rank ? 'el-icon-check' : 'el-icon-location') : '')"
                    placement="top"
                  >
                    <div>
                      <p>{{ activity.label || activity.to_place }} <br> <span v-if="activity.username" style="font-size:80%;">oleh {{ activity.username }}</span></p>
                    </div>
                  </el-timeline-item>
                </el-timeline>
              </div>
            </div>
          </el-col>
        </el-row>
      </div>
    </div>
    <div slot="footer" class="dialog-footer">
      <el-button type="primary" @click="cancel"> Tutup </el-button>
    </div>
  </el-dialog>
</template>

<script>
import axios from 'axios';

export default {
  name: 'TrackingDocumentDetailDialog',
  props: {
    show: Boolean,
    project: {
      type: Object,
      default: () => {},
    },
  },
  data() {
    return {
      loading: true,
      data: [],
      reverse: true,
      current_marking: 0,
      showAll: true,
      all: [],
    };
  },
  mounted() {
    this.getData();
  },
  methods: {
    cancel() {
      this.data = [];
      this.$emit('cancel');
    },
    async getData(){
      this.loading = true;
      this.data = [];
      this.current_rank = 0;
      // await axios.get('api/tracking-document/' + this.project.id)
      await axios.get('api/timeline?id=' + this.project.id)
        .then(response => {
          if (response.data && response.data.length > 0){
            this.data = response.data;
            if (this.project.marking !== null){
              const current_marking = this.data.find(e => e.to_place === this.project.marking);
              this.current_rank = current_marking.rank;
            }
          }
        }).finally(() => {
          this.loading = false;
        });
    },
    handleClose() {
      this.$emit('handleClose');
    },
  },
};
</script>
<style>
</style>
