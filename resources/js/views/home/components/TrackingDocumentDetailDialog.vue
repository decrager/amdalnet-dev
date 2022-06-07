<template>
  <el-dialog
    :title="'Lacak Pengajuan Persetujuan Lingkungan'"
    :visible.sync="show"
    :before-close="handleClose"
  >

    <div v-if="project">
      <h2>{{ project.project_title }}</h2>
      <div style="margin-top:1em;"><p><span style="font-weight:bold">No Registrasi</span> {{ project.registration_no }}</p></div>
      <div style="margin-top:1.5em; padding: 1em; border: 1px solid #e0e0e0; border-radius: 1em; line-height:130% !important; word-wrap:break-word!important;">

        <el-row :gutter="4">
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
              <p style="font-weight:bold;">Status</p>
              <div style="padding: 0.5em;">
                <el-timeline v-if="data.length > 0" style="margin-top: 2em;">
                  <el-timeline-item
                    v-for="(activity, index) in data"
                    :key="index"
                    :timestamp="activity.datetime"
                    size="large"
                    :type="(index === 0) ? 'primary': (index === (data.length - 1) ? 'info' : 'default' )"
                    placement="top"
                  >
                    <div>
                      <p>{{ activity.label || activity.to_place }} <br> <span style="font-size:80%;">oleh {{ activity.username }}</span> </p>
                    </div>
                  </el-timeline-item>
                </el-timeline>
              </div>

            </div>
          </el-col>
        </el-row>

      </div>
    </div>

    <!--
    <div v-loading="loading" class="form-container">
      <h3>Kegiatan {{ data.project_title }}</h3>
      <el-card>
        <div align="center">No Registrasi: {{ data.registration_no }}</div>
        <el-row :gutter="4">
          <el-col :span="12" :xs="24">
            <el-form
              label-position="left"
              label-width="200px"
              style="max-width: 300px"
            >
              <el-form-item label="Jenis Dokumen Lingkungan :">
                <div>{{ data.required_doc }}</div>
              </el-form-item>
              <el-form-item label="Risiko :">
                <div>{{ data.risk_level }}</div>
              </el-form-item>
              <el-form-item label="Kewenangan :">
                <div>{{ data.authority }}</div>
              </el-form-item>
              <el-form-item label="Lokasi Rencana Usaha dan/atau Kegiatan :">
                <div v-if="data.address[0] === undefined || data.address[0] === null">-</div>
                <div v-if="data.address[0] !== undefined">{{ data.address[0].address }}</div>
              </el-form-item>
              <el-form-item label="Deskripsi :">
                <div>{{ data.description }}</div>
              </el-form-item>
            </el-form>
          </el-col>
          <el-col :span="12" :xs="24">
            <el-card>
              <el-timeline>
                <el-timeline-item
                  v-for="(step, index) in data.timeline"
                  :key="index"
                  :icon="step.icon"
                  :type="step.type"
                  :color="step.color"
                  :size="step.size"
                  :timestamp="step.timestamp"
                >
                  {{ step.content }}
                </el-timeline-item>
              </el-timeline>
            </el-card>
          </el-col>
        </el-row>
      </el-card>

    </div> -->

    <div slot="footer" class="dialog-footer">
      <el-button type="primary" @click="cancel"> Close </el-button>
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
      // await axios.get('api/tracking-document/' + this.project.id)
      await axios.get('api/timeline?id=' + this.project.id)
        .then(response => {
          // this.data = response.data.data;
          this.data = response.data;
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
