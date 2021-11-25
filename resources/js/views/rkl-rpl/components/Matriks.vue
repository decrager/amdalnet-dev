<template>
  <div>
    <div style="text-align: right; margin-bottom: 10px">
      <el-button :loading="loadingWorkspace" type="primary" @click="getData">
        WORKSPACE
      </el-button>
    </div>
    <el-collapse v-model="activeName" accordion>
      <el-collapse-item name="1">
        <template slot="title">
          <span class="title">PENDEKATAN PENGELOLAAN LINGKUNGAN</span>
        </template>
        <ManageApproach v-if="activeName === '1'" />
      </el-collapse-item>
      <el-collapse-item name="2">
        <template slot="title">
          <span class="title">
            MATRIKS RENCANA PENGELOLAAN LINGKUNGAN (RKL)
          </span>
        </template>
        <TableRKL v-if="activeName === '2'" />
      </el-collapse-item>
      <el-collapse-item name="3">
        <template slot="title">
          <span class="title">MATRIKS RENCANA PEMANTAUAN LINGKUNGAN (RPL)</span>
        </template>
        <TableRPL v-if="activeName === '3'" />
      </el-collapse-item>
    </el-collapse>
  </div>
</template>

<script>
import Resource from '@/api/resource';
const rklResource = new Resource('matriks-rkl');
const rplResource = new Resource('matriks-rpl');
import ManageApproach from '@/views/rkl-rpl/components/matriks-table/ManageApproach';
import TableRKL from '@/views/rkl-rpl/components/matriks-table/TableRKL';
import TableRPL from '@/views/rkl-rpl/components/matriks-table/TableRPL';
import Docxtemplater from 'docxtemplater';
import PizZip from 'pizzip';
import PizZipUtils from 'pizzip/utils/index.js';

export default {
  name: 'Matriks',
  components: {
    ManageApproach,
    TableRKL,
    TableRPL,
  },
  data() {
    return {
      activeName: '0',
      loadingWorkspace: false,
    };
  },
  methods: {
    async getData() {
      this.loadingWorkspace = true;
      const dataRKL = await rklResource.list({
        docs: 'true',
        idProject: this.$route.params.id,
      });
      const dataRPL = await rplResource.list({
        docs: 'true',
        idProject: this.$route.params.id,
      });
      this.rkl = dataRKL;
      this.rpl = dataRPL;
      this.exportDocx();
    },
    exportDocx() {
      PizZipUtils.getBinaryContent(
        '/template_rkl_rpl.docx',
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
            a_pra_konstruksi_rkl: this.rkl.poinA[0].data,
            a_konstruksi_rkl: this.rkl.poinA[1].data,
            a_operasi_rkl: this.rkl.poinA[2].data,
            a_pasca_operasi_rkl: this.rkl.poinA[3].data,
            b_pra_konstruksi_rkl: this.rkl.poinB[0].data,
            b_konstruksi_rkl: this.rkl.poinB[1].data,
            b_operasi_rkl: this.rkl.poinB[2].data,
            b_pasca_operasi_rkl: this.rkl.poinB[3].data,
            a_pra_konstruksi_rpl: this.rpl.poinA[0].data,
            a_konstruksi_rpl: this.rpl.poinA[1].data,
            a_operasi_rpl: this.rpl.poinA[2].data,
            a_pasca_operasi_rpl: this.rpl.poinA[3].data,
            b_pra_konstruksi_rpl: this.rpl.poinB[0].data,
            b_konstruksi_rpl: this.rpl.poinB[1].data,
            b_operasi_rpl: this.rpl.poinB[2].data,
            b_pasca_operasi_rpl: this.rpl.poinB[3].data,
          });

          const out = doc.getZip().generate({
            type: 'blob',
            mimeType:
              'application/vnd.openxmlformats-officedocument.wordprocessingml.document',
          });

          const formData = new FormData();
          formData.append('docx', out);
          formData.append('workspace', 'true');
          formData.append('idProject', this.$route.params.id);

          rklResource
            .store(formData)
            .then((response) => {
              this.$router.push({
                name: 'projectWorkspace',
                params: {
                  id: this.$route.params.id,
                  filename: `${this.$route.params.id}-rkl-rpl.docx`,
                },
              });
            })
            .catch((error) => {
              console.log(error);
            });

          // saveAs(out, 'rkl-rpl.docx');
        }
      );
    },
  },
};
</script>

<style>
.el-collapse-item__header {
  /* background-color: #296d36; */
  background-color: #1e5128;
  padding-left: 10px;
  font-size: large;
  font-weight: bold;
  color: rgb(196, 196, 196);
}
.el-collapse-item__content {
  padding-top: 10px;
}
</style>
