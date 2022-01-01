<template>
  <div>
    <div class="filter-container" align="right">
      <el-button
        :loading="loadingSubmit"
        type="primary"
        style="font-size: 0.8rem"
        @click="handleSubmit"
      >
        {{ 'Simpan Perubahan' }}
      </el-button>
      <el-button
        :loading="loadingDocs"
        type="info"
        style="font-size: 0.8rem"
        @click="downloadDocx"
      >
        {{ 'Download File DOCX' }}
      </el-button>
    </div>
    <el-row :gutter="32">
      <el-col :sm="24" :md="12">
        <FormBerita :reports="reports" :loadingtuk="loadingTuk" />
        <DaftarHadir
          :invitations="reports.invitations"
          :loadingtuk="loadingTuk"
          @deleteinvitation="deleteInvitation($event)"
        />
      </el-col>
      <el-col
        v-loading="loadingDocs"
        :sm="24"
        :md="12"
        :class="{ 'mt-col': loadingDocs }"
      >
        <iframe
          v-if="showDocument"
          :src="
            'https://docs.google.com/gview?url=' + projects + '&embedded=true'
          "
          width="100%"
          height="723px"
          frameborder="0"
        />
      </el-col>
    </el-row>
  </div>
</template>

<script>
import Resource from '@/api/resource';
const meetingReportResource = new Resource('meet-report-rkl-rpl');
import FormBerita from '@/views/pengujian-rkl-rpl/components/beritaAcara/FormBerita';
import DaftarHadir from '@/views/pengujian-rkl-rpl/components/beritaAcara/DaftarHadir';
import Docxtemplater from 'docxtemplater';
import PizZip from 'pizzip';
import PizZipUtils from 'pizzip/utils/index.js';
import { saveAs } from 'file-saver';

export default {
  name: 'BeritaAcara',
  components: {
    FormBerita,
    DaftarHadir,
  },
  data() {
    return {
      // projects: [],
      idProject: this.$route.params.id,
      loadingSubmit: false,
      loadingTuk: false,
      reports: {},
      docs: {},
      loadingDocs: false,
      showDocument: false,
      projects: '',
      out: '',
    };
  },
  created() {
    this.handleChange(this.idProject);
    this.getData();
  },
  methods: {
    async getData() {
      this.loadingDocs = true;
      const data = await meetingReportResource.list({
        idProject: this.$route.params.id,
        docs: 'true',
      });
      this.docs = data;
      this.exportDocx();
    },
    downloadDocx() {
      saveAs(
        this.out,
        `ba-rkl-rpl-${this.docs.project_title.toLowerCase()}.docx`
      );
    },
    exportDocx() {
      PizZipUtils.getBinaryContent(
        '/template_berita_acara.docx',
        (error, content) => {
          if (error) {
            throw error;
          }
          const zip = new PizZip(content);
          const doc = new Docxtemplater(zip, {
            paragraphLoop: true,
            linebreaks: true,
          });
          doc.render({
            document_type_big: this.docs.document_type_big,
            document_type: this.docs.document_type,
            project_title_big: this.docs.project_title_big,
            project_address_big: this.docs.project_address_big,
            pemrakarsa_big: this.docs.pemrakarsa_big,
            meeting_date: this.docs.meeting_date,
            meeting_location: this.docs.meeting_location,
            pemrakarsa: this.docs.pemrakarsa,
            pic: this.docs.pic,
            position: this.docs.position,
            leader: this.docs.leader,
            team_member: this.docs.team_member,
            project_title: this.docs.project_title,
            project_address: this.docs.project_address,
            pra_konstruksi: this.docs.pra_konstruksi,
            konstruksi: this.docs.konstruksi,
            operasi: this.docs.operasi,
            pasca_operasi: this.docs.pasca_operasi,
            meeting_lead: this.docs.meeting_lead,
            meeting_lead_institution: this.docs.meeting_lead_institution,
            kewenangan_big: 'PUSAT',
            kewenangan: 'Pusat',
            summary: '',
            year: '2021',
            jabatan_ketua_tuk: '',
          });

          const out = doc.getZip().generate({
            type: 'blob',
            mimeType:
              'application/vnd.openxmlformats-officedocument.wordprocessingml.document',
          });

          const formData = new FormData();
          formData.append('docx', out);
          formData.append('formulir', 'true');
          formData.append('idProject', this.$route.params.id);

          meetingReportResource
            .store(formData)
            .then((response) => {
              this.showDocument = true;
              this.projects =
                window.location.origin +
                `/storage/formulir/ba-rkl-rpl-${this.docs.project_title.toLowerCase()}.docx`;
              this.loadingDocs = false;
            })
            .catch((error) => {
              console.log(error);
            });

          this.out = out;
        }
      );
    },
    async getReports() {
      const data = await meetingReportResource.list({
        idProject: this.idProject,
      });
      if (data.type !== 'notexist') {
        this.reports = data;
      }
    },
    async handleChange(val) {
      this.idProject = val;
      this.loadingTuk = true;
      await this.getReports();
      this.loadingTuk = false;
    },
    async handleSubmit() {
      if (this.reports.type === undefined) {
        return;
      }

      this.loadingSubmit = true;
      await meetingReportResource.store({
        idProject: this.idProject,
        reports: this.reports,
      });
      await this.handleChange(this.idProject);
      this.$message({
        message: 'Data is saved Successfully',
        type: 'success',
        duration: 5 * 1000,
      });
      this.loadingSubmit = false;
    },
    deleteInvitation({ id, personType }) {
      this.reports.invitations = this.reports.invitations.filter((inv) => {
        return !(inv.id === id && inv.type === personType);
      });
    },
  },
};
</script>

<style>
.mt-col {
  margin-top: 100px;
}
</style>
