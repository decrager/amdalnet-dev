<template>
  <!-- <el-input
    v-model="textarea"
    type="textarea"
    :rows="25"
    placeholder="Please input"
  /> -->
  <iframe
    src="/document/Template_Dokumen_RKL_RPL.pdf"
    frameborder="0"
    width="100%"
    height="630px"
  />
</template>

<script>
import Resource from '@/api/resource';
const rklResource = new Resource('matriks-rkl');
const rplResource = new Resource('matriks-rpl');
import Docxtemplater from 'docxtemplater';
import PizZip from 'pizzip';
import PizZipUtils from 'pizzip/utils/index.js';
// import { saveAs } from 'file-saver';

export default {
  name: 'DocsFrame',
  data() {
    return {
      textarea: '',
      rkl: {},
      rpl: {},
      idProject: this.$route.params.id,
    };
  },
  created() {
    this.getData();
  },
  methods: {
    async getData() {
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
            .then((response) => {})
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
