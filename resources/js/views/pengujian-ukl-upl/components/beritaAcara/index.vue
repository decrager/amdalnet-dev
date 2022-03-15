<template>
  <div class="app-container">
    <el-card>
      <WorkflowUkl />
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
          v-if="reports.type === 'update'"
          :loading="loadingDocs"
          type="info"
          style="font-size: 0.8rem"
          @click="downloadDocx"
        >
          {{ 'Download File DOCX' }}
        </el-button>
      </div>
      <el-row :gutter="32">
        <el-col :sm="24" :md="24">
          <FormBerita :reports="reports" :loadingtuk="loadingTuk" />
        </el-col>
        <el-col :sm="24" :md="24">
          <DaftarHadir
            :invitations="reports.invitations"
            :reports="reports"
            :loadingtuk="loadingTuk"
            @deleteinvitation="deleteInvitation($event)"
            @updateuploadfile="updateUploadFile($event)"
            @handleChangeRole="handleChangeRole($event)"
          />
        </el-col>
      </el-row>
      <el-row v-if="reports.type === 'update'" :gutter="32">
        <el-col :span="16" :offset="4" align="center">
          <div
            v-if="reports.is_accepted === null"
            style="background-color: #e1e1e1; padding: 10px 0; margin-top: 5px"
          >
            <h3>Apakah UKL-UPL dapat dilanjutkan ke Uji Kelayakan?</h3>
            <div style="text-align: center">
              <el-button
                :loading="loadingAccept"
                type="primary"
                @click="acceptOrNot(true)"
              >
                Ya
              </el-button>
              <el-button
                :loading="loadingAccept"
                type="danger"
                @click="acceptOrNot(false)"
              >
                Tidak
              </el-button>
            </div>
          </div>
          <el-alert
            v-else
            :title="acceptedTitle"
            type="success"
            description="Terimakasih"
            show-icon
            center
            :closable="false"
          />
        </el-col>
      </el-row>
    </el-card>
  </div>
</template>

<script>
import Resource from '@/api/resource';
const meetingReportResource = new Resource('meet-report-rkl-rpl');
const institutionResource = new Resource('government-institution');
import FormBerita from '@/views/pengujian-ukl-upl/components/beritaAcara/FormBerita';
import DaftarHadir from '@/views/pengujian-ukl-upl/components/beritaAcara/DaftarHadir';
import WorkflowUkl from '@/components/WorkflowUkl';
import Docxtemplater from 'docxtemplater';
import PizZip from 'pizzip';
import PizZipUtils from 'pizzip/utils/index.js';
import { saveAs } from 'file-saver';

export default {
  name: 'BeritaAcara',
  components: {
    FormBerita,
    DaftarHadir,
    WorkflowUkl,
  },
  data() {
    return {
      idProject: this.$route.params.id,
      loadingSubmit: false,
      loadingTuk: false,
      loadingAccept: false,
      reports: {},
      docs: {},
      loadingDocs: false,
      showDocument: false,
      projects: '',
      out: '',
      governmentInstitutions: [],
    };
  },
  computed: {
    acceptedTitle() {
      if (this.reports.is_accepted) {
        return 'UKL UPL Dilanjutkan ke Uji Kelayakan';
      } else {
        return 'UKL UPL Tidak Dilanjutkan ke Uji Kelayakan';
      }
    },
  },
  async created() {
    this.$store.dispatch('getStep', 5);
    this.loadingTuk = true;
    this.idProject = this.$route.params.id;
    await this.getGovernmentInstitutions();
    await this.getReports();
    this.loadingTuk = false;
  },
  methods: {
    async downloadDocx() {
      this.loadingDocs = true;
      const data = await meetingReportResource.list({
        idProject: this.$route.params.id,
        docs: 'true',
        uklUpl: 'true',
      });
      this.docs = data;
      const a = document.createElement('a');
      a.href =
        window.location.origin + `/storage/ba-ukl-upl/ba-ukl-upl-${data}.docx`;
      a.setAttribute('download', `ba-ukl-upl-${data}.docx`);
      a.click();
      this.loadingDocs = false;
    },
    exportDocx() {
      PizZipUtils.getBinaryContent(
        '/template_berita_acara_uu.docx',
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
            project_title: this.docs.project_title,
            project_title_big: this.docs.project_title_big,
            pemrakarsa: this.docs.pemrakarsa,
            pemrakarsa_big: this.docs.pemrakarsa_big,
            pic: this.docs.pic,
            pic_position: this.docs.pic_position,
            ketua_tuk_position: this.docs.ketua_tuk_position,
            authority: this.docs.authority,
            authority_big: this.docs.authority_big,
            tuk_member: this.docs.tuk_member,
            notes: this.docs.notes,
            ketua_tuk_name: this.docs.ketua_tuk_name,
            meeting_date: this.docs.meeting_date,
            meeting_location: this.docs.meeting_location,
            year: this.docs.year,
          });

          const out = doc.getZip().generate({
            type: 'blob',
            mimeType:
              'application/vnd.openxmlformats-officedocument.wordprocessingml.document',
          });

          this.out = out;

          saveAs(
            this.out,
            `ba-ukl-upl-${this.docs.project_title.toLowerCase()}.docx`
          );
          this.loadingDocs = false;
        }
      );
    },
    async getReports() {
      const data = await meetingReportResource.list({
        idProject: this.idProject,
        uklUpl: 'true',
      });
      if (data.type !== 'notexist') {
        const invitations = data.invitations.map((x) => {
          let institution_options = [];

          if (this.isGovernmentInstitution(x.role)) {
            institution_options = this.governmentInstitutions.filter(
              (y) => y.institution_type === x.role
            );
          }

          return {
            id: x.id,
            role: x.role,
            name: x.name,
            email: x.email,
            type: x.type,
            id_government_institution: x.id_government_institution,
            institution_options: institution_options,
            institution: x.institution,
          };
        });
        data.invitations = invitations;
        this.reports = data;
      }
    },
    async getGovernmentInstitutions() {
      this.governmentInstitutions = await institutionResource.list({
        meeting: 'true',
      });
    },
    isGovernmentInstitution(role) {
      return (
        role === 'Kementerian' ||
        role === 'Lembaga' ||
        role === 'Pemerintah Provinsi' ||
        role === 'Pemerintah Kabupaten/Kota'
      );
    },
    handleChangeRole({ val, idx }) {
      if (this.isGovernmentInstitution(val)) {
        const institution = this.governmentInstitutions.filter(
          (x) => x.institution_type === val
        );
        this.reports.invitations[idx].institution_options = institution;
      }
    },
    async handleSubmit() {
      if (this.reports.type === undefined) {
        return;
      }

      this.loadingSubmit = true;
      await meetingReportResource.store({
        idProject: this.idProject,
        reports: this.reports,
        uklUpl: 'true',
      });
      this.loadingTuk = true;
      await this.getReports();
      this.$message({
        message: 'Data sukses tersimpan',
        type: 'success',
        duration: 5 * 1000,
      });
      this.loadingSubmit = false;
      this.loadingTuk = false;
    },
    deleteInvitation({ id, personType }) {
      this.reports.invitations = this.reports.invitations.filter((inv) => {
        return !(inv.id === id && inv.type === personType);
      });
    },
    updateUploadFile({ name }) {
      this.reports.file = name;
    },
    acceptOrNot(accept) {
      this.$confirm('Apakah anda yakin ?', 'Warning', {
        confirmButtonText: 'Ya',
        cancelButtonText: 'Tidak',
        type: 'warning',
      })
        .then(() => {
          this.loadingAccept = true;
          meetingReportResource.store({
            accept: 'true',
            idProject: this.$route.params.id,
            documentType: 'ukl-upl',
            isAccepted: accept,
          });
          this.loadingAccept = false;
          this.reports.is_accepted = accept;
        })
        .catch(() => {
          this.loadingAccept = false;
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
