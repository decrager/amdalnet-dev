<template>
  <el-dialog :title="'Lihat Progres Dokumen Lingkungan'" :visible.sync="show">
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
      <div slot="footer" class="dialog-footer">
        <el-button type="primary" @click="cancel"> Close </el-button>
      </div>
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
      data: {},
    };
  },
  mounted() {
    this.getData();
  },
  methods: {
    cancel() {
      this.data = {};
      this.$emit('cancel');
    },
    async getData(){
      this.loading = true;
      await axios.get('api/tracking-document/' + this.project.id)
        .then(response => {
          this.data = response.data.data;
          this.loading = false;
        });
    },
  },
};
</script>

<style>
</style>
