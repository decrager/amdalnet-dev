<template>
  <div>
    <el-row :gutter="32">
      <el-col :md="12" :sm="24">
        <h4 align="center">UNDANGAN RAPAT</h4>
        <el-form
          v-loading="loading"
          label-position="top"
          label-width="200px"
          style="max-width: 100%"
        >
          <el-row :gutter="32">
            <el-col :sm="12" :md="12">
              <el-form-item label="Tanggal Rapat">
                <el-date-picker
                  v-model="meetings.meeting_date"
                  type="date"
                  value-format="yyyy-MM-dd"
                  placeholder="Pilih tanggal"
                  style="width: 100%"
                />
              </el-form-item>
            </el-col>
            <el-col :sm="12" :md="12">
              <el-form-item label="Pemrakarsa">
                <el-input v-model="meetings.initiator_name" :readonly="true" />
              </el-form-item>
            </el-col>
            <el-col :sm="12" :md="12">
              <el-form-item label="Waktu Rapat">
                <el-time-picker
                  v-model="meetings.meeting_time"
                  placeholder="Waktu Rapat"
                  format="HH:mm"
                  value-format="HH:mm"
                  style="width: 100%"
                />
              </el-form-item>
            </el-col>
            <el-col :sm="12" :md="12">
              <el-form-item label="Penanggung Jawab">
                <el-input v-model="meetings.person_responsible" readonly />
              </el-form-item>
            </el-col>
            <el-col :sm="12" :md="12">
              <el-form-item label="Tempat Rapat">
                <el-input v-model="meetings.location" />
              </el-form-item>
            </el-col>
            <el-col :sm="12" :md="12">
              <el-form-item label="Jabatan">
                <el-input v-model="meetings.position" />
              </el-form-item>
            </el-col>
            <el-col :sm="12" :md="12">
              <el-form-item label="Tim Uji Kelayakan">
                <el-select
                  v-model="meetings.id_feasibility_test_team"
                  placeholder="Pilih Tim"
                  style="width: 100%"
                  @change="handleChangeTimUji"
                >
                  <el-option
                    v-for="item in timUjiKelayakan"
                    :key="item.id"
                    :label="item.name"
                    :value="item.id"
                  />
                </el-select>
              </el-form-item>
            </el-col>
            <el-col :sm="12" :md="12">
              <el-form-item label="Nama Usaha/Kegiatan">
                <el-input v-model="meetings.project_name" readonly />
              </el-form-item>
            </el-col>
          </el-row>
        </el-form>
      </el-col>
      <el-col :md="12" :sm="24">
        <div style="text-align: right">
          <el-button
            :loading="loadingSubmit"
            type="primary"
            @click="handleSubmit"
          >
            Simpan Perubahan
          </el-button>
          <el-button :loading="loadingInvitationDocx" type="primary" @click="handleDownloadInvitation">Undangan Rapat</el-button>
          <el-button style="margin-top: 10px;" :loading="loadingSendInvitation" type="primary" @click="sendInvitation">Kirim Undangan Rapat</el-button>
        </div>
        <h5>Daftar Undangan</h5>
        <el-table
          v-loading="loadingTuk"
          :data="meetings.invitations"
          fit
          highlight-current-row
          :header-cell-style="{ background: '#3AB06F', color: 'white' }"
        >
          <el-table-column width="30px">
            <template slot-scope="scope">
              <span>{{ scope.$index + 1 }}</span>
            </template>
          </el-table-column>

          <el-table-column label="Peran">
            <template slot-scope="scope">
              <span v-if="scope.row.type == 'tuk'">
                {{ scope.row.role }} TUK
              </span>
              <el-select
                v-else
                v-model="scope.row.role"
                placeholder="Pilih Peran"
                style="width: 100%"
              >
                <el-option
                  v-for="item in peran"
                  :key="item.value"
                  :label="item.label"
                  :value="item.value"
                />
              </el-select>
            </template>
          </el-table-column>

          <el-table-column label="Nama Anggota">
            <template slot-scope="scope">
              <span v-if="scope.row.type == 'tuk'">{{ scope.row.name }}</span>
              <el-input v-else v-model="scope.row.name" />
            </template>
          </el-table-column>

          <el-table-column label="E-mail">
            <template slot-scope="scope">
              <div>
                <span v-if="scope.row.type == 'tuk'">{{ scope.row.email }}</span>
                <div v-else class="email-column">
                  <el-input v-model="scope.row.email" />
                  <el-button
                    type="text"
                    icon="el-icon-close"
                    style="display: block"
                    @click.prevent="deleteRow(scope.$index, scope.row.id)"
                  />
                </div>
              </div>
            </template>
          </el-table-column>
        </el-table>
        <div style="margin-top: 10px">
          <el-button icon="el-icon-plus" circle @click.prevent="addTableRow" />
        </div>
        <div v-if="meetings.type === 'update'" style="text-align: right">
          <el-button
            :loading="loadingDocx"
            type="primary"
            style="margin-top: 10px"
            @click="handleDownload"
          >
            Unduh Hasil Pemeriksaan Berkas Administrasi
          </el-button>
          <el-upload
            v-if="!meetings.file"
            :auto-upload="false"
            :on-change="handleUploadChange"
            :show-file-list="false"
            action=""
          >
            <el-button :loading="loadingUpload" type="primary" style="margin-top: 10px;">
              Unggah Validasi Hasil Pemeriksaan Berkas Administrasi
            </el-button>
          </el-upload>
          <small v-if="errors.dokumen_file" style="color: #f56c6c">
            <span
              v-for="(error, index) in errors.dokumen_file"
              :key="index"
            >
              {{ error }}
            </span>
          </small>
          <div v-if="meetings.file" style="text-align: right;">
            <el-button
              type="text"
              size="medium"
              icon="el-icon-download"
              style="color: blue"
              @click.prevent="download(meetings.file)"
            >
              {{ baFileName }}
            </el-button>
          </div>
        </div>
      </el-col>
    </el-row>
  </div>
</template>

<script>
import Resource from '@/api/resource';
import Docxtemplater from 'docxtemplater';
import PizZip from 'pizzip';
import PizZipUtils from 'pizzip/utils/index.js';
import { saveAs } from 'file-saver';
const undanganRapatResource = new Resource('test-meet-rkl-rpl');

export default {
  name: 'UndanganRapat',
  data() {
    return {
      timUjiKelayakan: [],
      peran: [
        {
          label: 'Tenaga Ahli',
          value: 'Tenaga Ahli',
        },
        {
          label: 'Masyarakat',
          value: 'Masyarakat',
        },
      ],
      loadingTuk: false,
      meetings: {},
      idProject: this.$route.params.id,
      loading: false,
      loadingSubmit: false,
      loadingDocx: false,
      loadingUpload: false,
      errors: {},
      loadingSendInvitation: false,
      docxData: {},
      invitationDocxData: {},
      loadingInvitationDocx: false,
      out: '',
      outInvitation: '',
    };
  },
  computed: {
    baFileName() {
      const arrName = this.meetings.file.split('/');
      return arrName[arrName.length - 1];
    },
  },
  created() {
    this.getTimUjiKelayakan();
    this.getMeetings();
  },
  methods: {
    async getMeetings() {
      this.loading = true;
      const data = await undanganRapatResource.list({
        idProject: this.idProject,
      });
      this.meetings = data;
      this.loading = false;
    },
    async handleSubmit() {
      this.loadingSubmit = true;
      await undanganRapatResource.store({
        idProject: this.idProject,
        meetings: this.meetings,
      });
      this.$message({
        message: 'Data sukses tersimpan',
        type: 'success',
        duration: 5 * 1000,
      });
      this.getMeetings();
      this.loadingSubmit = false;
    },
    async getTimUjiKelayakan() {
      const data = await undanganRapatResource.list({
        expert_bank_team: 'true',
      });
      this.timUjiKelayakan = data.map((x) => {
        let name = '';
        const team_number = x.team_number ? x.team_number : '';

        if (x.authority === 'Pusat') {
          name = 'Tim Uji Kelayakan Pusat';
        } else if (x.authority === 'Provinsi') {
          if (x.province_authority) {
            name = `Tim Uji Kelayakan Provinsi ${this.capitalize(
              x.province_authority.name
            )} ${team_number}`;
          }
        } else if (x.authority === 'Kabupaten/Kota') {
          if (x.district_authority) {
            name = `Tim Uji Kelayakan ${this.capitalize(
              x.district_authority.name
            )} ${team_number}`;
          }
        }

        return {
          id: x.id,
          name,
        };
      });
    },
    async handleChangeTimUji(val) {
      this.loadingTuk = true;
      const data = await undanganRapatResource.list({
        tuk_member: 'true',
        tuk_id: val,
      });
      this.meetings.invitations = data;
      this.loadingTuk = false;
    },
    addTableRow() {
      this.meetings.invitations.push({
        id: null,
        role: null,
        name: null,
        email: null,
        type: 'other',
        type_member: 'other',
      });
    },
    capitalize(mySentence) {
      const words = mySentence.split(' ');

      const newWords = words
        .map((word) => {
          return (
            word.toLowerCase()[0].toUpperCase() +
            word.toLowerCase().substring(1)
          );
        })
        .join(' ');
      return newWords;
    },
    async handleDownload() {
      this.loadingDocx = true;
      const data = await undanganRapatResource.list({
        dokumen: 'true',
        idProject: this.idProject,
      });
      this.docxData = data;
      const a = document.createElement('a');
      a.href = window.location.origin + `/storage/adm/berkas-adm-ar-${data}.docx`;
      a.setAttribute('download', `berkas-adm-ar-${data}.docx`);
      a.click();
      this.loadingDocx = false;
    },
    async handleDownloadInvitation() {
      this.loadingInvitationDocx = true;
      const data = await undanganRapatResource.list({
        meetingInvitation: 'true',
        idProject: this.idProject,
      });
      this.invitationDocxData = data;
      const a = document.createElement('a');
      a.href = window.location.origin + `/storage/meet-inv/andal-rkl-rpl-${data}.docx`;
      a.setAttribute('download', `andal-rkl-rpl-${data}.docx`);
      a.click();
      this.loadingInvitationDocx = false;
    },
    exportDocx() {
      PizZipUtils.getBinaryContent(
        '/template_berkas_adm_ar_yes.docx',
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
            project_title: this.docxData.project_title,
            pemrakarsa: this.docxData.pemrakarsa,
            project_description: this.docxData.project_description,
            project_location: this.docxData.project_location,
            meeting_time: this.docxData.meeting_time,
            docs_date: this.docxData.docs_date,
            ketua_tuk: this.docxData.ketua_tuk,
            notes: this.docxData.notes,
            tata_ruang_yes: this.docxData.forms.tata_ruang_yes,
            tata_ruang_no: this.docxData.forms.tata_ruang_no,
            tata_ruang_ket: this.docxData.forms.tata_ruang_ket,
            persetujuan_awal_yes: this.docxData.forms.persetujuan_awal_yes,
            persetujuan_awal_no: this.docxData.forms.persetujuan_awal_no,
            persetujuan_awal_ket: this.docxData.forms.persetujuan_awal_ket,
            hasil_penapisan_yes: this.docxData.forms.hasil_penapisan_yes,
            hasil_penapisan_no: this.docxData.forms.hasil_penapisan_no,
            hasil_penapisan_ket: this.docxData.forms.hasil_penapisan_ket,
            surat_penyusun_yes: this.docxData.forms.surat_penyusun_yes,
            surat_penyusun_no: this.docxData.forms.surat_penyusun_no,
            surat_penyusun_ket: this.docxData.forms.surat_penyusun_ket,
            sertifikasi_penyusun_yes:
              this.docxData.forms.sertifikasi_penyusun_yes,
            sertifikasi_penyusun_no:
              this.docxData.forms.sertifikasi_penyusun_no,
            sertifikasi_penyusun_ket:
              this.docxData.forms.sertifikasi_penyusun_ket,
            peta_yes: this.docxData.forms.peta_yes,
            peta_no: this.docxData.forms.peta_no,
            peta_ket: this.docxData.forms.peta_ket,
            konsul_publik_yes: this.docxData.forms.konsul_publik_yes,
            konsul_publik_no: this.docxData.forms.konsul_publik_no,
            konsul_publik_ket: this.docxData.forms.konsul_publik_ket,
            cv_penyusun_yes: this.docxData.forms.cv_penyusun_yes,
            cv_penyusun_no: this.docxData.forms.cv_penyusun_no,
            cv_penyusun_ket: this.docxData.forms.cv_penyusun_ket,
            sistematika_penyusunan_yes:
              this.docxData.forms.sistematika_penyusunan_yes,
            sistematika_penyusunan_no:
              this.docxData.forms.sistematika_penyusunan_no,
            sistematika_penyusunan_ket:
              this.docxData.forms.sistematika_penyusunan_ket,
            pertek_yes: this.docxData.forms.pertek_yes,
            pertek_no: this.docxData.forms.pertek_no,
            pertek_ket: this.docxData.forms.pertek_ket,
            peta_titik_yes: this.docxData.forms.peta_titik_yes,
            peta_titik_no: this.docxData.forms.peta_titik_no,
            peta_titik_ket: this.docxData.forms.peta_titik_ket,
            tim_penyusun: this.docxData.tim_penyusun,
            anggota_penyusun: this.docxData.anggota_penyusun,
            meeting_invitations: this.docxData.meeting_invitations,
          });

          const out = doc.getZip().generate({
            type: 'blob',
            mimeType:
              'application/vnd.openxmlformats-officedocument.wordprocessingml.document',
          });

          this.out = out;
          saveAs(this.out, `${this.docxData.project_title.toLowerCase()}.docx`);
          this.loadingDocx = false;
        }
      );
    },
    exportInvitationDocx() {
      PizZipUtils.getBinaryContent(
        '/template_undangan_rapat_arr.docx',
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
            project_title: this.invitationDocxData.project_title,
            project_address: this.invitationDocxData.project_address,
            pemrakarsa: this.invitationDocxData.pemrakarsa,
            pemrakarsa_address: this.invitationDocxData.pemrakarsa_address,
            docs_date: this.invitationDocxData.docs_date,
            meeting_date: this.invitationDocxData.meeting_date,
            meeting_time: this.invitationDocxData.meeting_time,
            meeting_location: this.invitationDocxData.meeting_location,
            authority_big_check: this.invitationDocxData.authority_big_check,
            authority_big: this.invitationDocxData.authority_big,
            tuk_address: this.invitationDocxData.tuk_address,
            authority: this.invitationDocxData.authority,
            ketua_tuk_name: this.invitationDocxData.ketua_tuk_name,
            ketua_tuk_nip: this.invitationDocxData.ketua_tuk_nip,
            tuk_member: this.invitationDocxData.tuk_member,
            pakar: this.invitationDocxData.pakar,
          });

          const out = doc.getZip().generate({
            type: 'blob',
            mimeType:
              'application/vnd.openxmlformats-officedocument.wordprocessingml.document',
          });

          this.outInvitation = out;
          saveAs(this.outInvitation, `${this.invitationDocxData.project_title.toLowerCase()}.docx`);
          this.loadingInvitationDocx = false;
        }
      );
    },
    async handleUploadChange(file, fileList) {
      this.loadingUpload = true;
      const formData = new FormData();
      formData.append('idProject', this.idProject);
      formData.append('dokumen_file', file.raw);
      formData.append('file', 'true');
      const data = await undanganRapatResource.store(formData);
      this.errors = data.errors === null ? {} : data.errors;
      this.loadingUpload = false;
      if (data.errors === null) {
        this.meetings.file = data.name;
        this.$message({
          message: 'Dokumen sukses diupload',
          type: 'success',
          duration: 5 * 1000,
        });
      }
    },
    async sendInvitation() {
      this.loadingSendInvitation = true;
      try {
        const response = await undanganRapatResource.store({
          invitation: 'true',
          idProject: this.idProject,
        });
        if (response.error === 0) {
          this.$message({
            message: 'Undangan Berhasil Dikirim',
            type: 'success',
            duration: 5 * 1000,
          });
        } else if (response.error === 1) {
          this.$message({
            message: 'Undangan Gagal Dikirim',
            type: 'error',
            duration: 5 * 1000,
          });
        }
      } catch (error) {
        this.$message({
          message: 'Undangan Gagal Dikirim',
          type: 'error',
          duration: 5 * 1000,
        });
      }
      this.loadingSendInvitation = false;
    },
    download(url) {
      window.open(url, '_blank').focus();
    },
    deleteRow(idx, id) {
      if (id) {
        this.meetings.deleted_invitations.push(id);
      }

      this.meetings.invitations.splice(idx, 1);
    },
  },
};
</script>

<style scoped>
.email-column {
  display: flex;
  justify-content: space-between;
  align-items: center;
}
</style>

<style>
.upload-demo {
  font-size: 0.8rem;
  background: #e2cd39;
  padding: 8px;
  color: white;
  border-radius: 3px;
  margin-top: 10px;
  width: fit-content;
}
</style>
