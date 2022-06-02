<template>
  <div class="app-container" style="padding: 24px">
    <h1 style="margin-bottom: 25px">SPPL {{ project.project_title }}</h1>
    <el-card>
      <tr>
        <td><h4>Nama Instansi pemerintah</h4></td>
        <td><b>&nbsp;&nbsp;:&nbsp;&nbsp;</b></td>
        <td>{{ initiator.name }}</td>
      </tr>
      <tr>
        <td><h4>Nama Penanggung Jawab</h4></td>
        <td><b>&nbsp;&nbsp;:&nbsp;&nbsp;</b></td>
        <td>{{ initiator.pic }}</td>
      </tr>
      <tr>
        <td><h4>Jabatan</h4></td>
        <td><b>&nbsp;&nbsp;:&nbsp;&nbsp;</b></td>
        <td>{{ initiator.pic_role }}</td>
      </tr>
      <tr>
        <td><h4>Alamat</h4></td>
        <td><b>&nbsp;&nbsp;:&nbsp;&nbsp;</b></td>
        <td>{{ initiator.address }}</td>
      </tr>
      <tr>
        <td><h4>Nomor Telepon</h4></td>
        <td><b>&nbsp;&nbsp;:&nbsp;&nbsp;</b></td>
        <td>{{ initiator.phone }}</td>
      </tr>
      <tr>
        <td><h4>Bidang Kegiatan</h4></td>
        <td><b>&nbsp;&nbsp;:&nbsp;&nbsp;</b></td>
        <td>
          {{
            Array.prototype.map
              .call(authorities, function (item) {
                return item.project;
              })
              .join(',')
          }}
        </td>
      </tr>
    </el-card>
    <el-card style="margin-top: 25px; margin-bottom: 25px" :loading="loading">
      <div slot="header" style="text-align: center">
        <span>Pengelolaan dan Pemantauan Jenis Kegiatan yang akan Dilakukan</span>
      </div>
      <editor
        v-model="project.ppjk"
        :menubar="''"
        :image="false"
        :height="150"
        :toolbar="[
          'bold italic underline bullist numlist  preview undo redo fullscreen',
        ]"
      />
    </el-card>
    <el-row :gutter="20">
      <el-col :span="16">
        <el-button v-if="project.isppjk === '1'" type="success" style="width: 200px" :loading="loadingSppl || loading" @click="generateSPPL">
          Cetak SPPL
        </el-button>
        <span v-else>&nbsp;</span>
      </el-col>
      <el-col :span="8">
        <el-row type="flex" justify="end">
          <el-button
            :disabled="project.isppjk === '1'"
            type="success"
            style="width: 200px"
            :loading="loading"
            @click="onSave()"
          >Simpan</el-button>
          <el-button :disabled="project.isppjk === '1'" type="warning" :loading="loading" style="width: 200px" @click="onSubmit()">Submit</el-button>
        </el-row>
      </el-col>
    </el-row>
  </div>
</template>

<script>
import Resource from '@/api/resource';
import editor from '@/components/Tinymce';
import PizZip from 'pizzip';
import PizZipUtils from 'pizzip/utils/index.js';
import Docxtemplater from 'docxtemplater';
import { saveAs } from 'file-saver';
const projectResource = new Resource('projects');
const initiatorResource = new Resource('initiators');
const authoritiesResource = new Resource('project-authorities');
const ppjkResource = new Resource('project-ppjk');

export default {
  components: { editor },
  data() {
    return {
      project: {},
      initiator: {},
      authorities: [],
      loading: false,
      loadingSppl: true,
    };
  },
  async created() {
    console.log(this.$route.params.id);
    this.project = await projectResource.get(this.$route.params.id);
    this.initiator = await initiatorResource.get(this.project.id_applicant);
    this.authorities = await authoritiesResource.list({
      idProject: this.$route.params.id,
    });
    this.loadingSppl = false;
    if (this.project.isppjk === '1'){
      window.tinymce.activeEditor.getBody().setAttribute('contenteditable', false);
    }
  },
  methods: {
    generateSPPL(){
      const bidArr = Array.prototype.map
        .call(this.authorities, function(item) {
          return item.project;
        }).join(',');
      PizZipUtils.getBinaryContent(
        '/template_sppl_pem.docx',
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
            name: this.initiator.name,
            address: this.initiator.address,
            phone: this.initiator.phone,
            pic: this.initiator.pic,
            pic_role: this.initiator.pic_role,
            bidang: bidArr,
            ppjk: this.removeTagHtml(this.project.ppjk),
          });

          const out = doc.getZip().generate({
            type: 'blob',
            mimeType: 'application/vnd.openxmlformats-officedocument.wordprocessingml.document',
          });

          saveAs(out, 'SPPL-' + this.project.project_title + '.docx');
          // this.docOutput = out;
        }
      );
    },
    onSubmit(){
      this.$confirm('Apakah anda yakin akan mengajukan dokumen SPPL?. Dokumen yang sudah diajukan, tidak dapat diubah kembali', 'Perhatian', {
        confirmButtonText: 'Ya',
        cancelButtonText: 'Tidak',
        type: 'warning',
        center: true,
      }).then(() => {
        this.loading = true;
        this.project.isppjk = '1';
        ppjkResource.storeId(this.$route.params.id, this.project).then((response) => {
          this.$message({
            message:
              `Pengelolaan dan Pemantauan Jenis Kegiatan yang akan Dilakukan Berhasil Disubmit`,
            type: 'success',
            duration: 5 * 1000,
          });
          this.project = response;
          this.loading = false;
          this.$router.push('/project');
        }).catch((error) => {
          this.loading = false;
          console.log(error);
        });
      }).catch(() => {
        this.$message({
          type: 'info',
          message: 'Submit dibatalkan',
        });
      });
    },
    onSave() {
      this.loading = true;

      ppjkResource.storeId(this.$route.params.id, this.project).then((response) => {
        this.$message({
          message:
              `Pengelolaan dan Pemantauan Jenis Kegiatan yang akan Dilakukan Berhasil Disimpan`,
          type: 'success',
          duration: 5 * 1000,
        });
        this.loading = false;
      })
        .catch((error) => {
          this.loading = false;
          console.log(error);
        });
    },
    removeTagHtml(element) {
      return element.replace(/<[^>]*>?/gm, '');
    },
  },
};
</script>
<style>
.el-card__header {
  background-color: #143b17;
  color: white;
}
</style>
