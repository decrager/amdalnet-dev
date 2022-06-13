<!-- eslint-disable vue/html-indent -->
<template>
  <div style="margin-top: 30px">
    <el-button :loading="loadingAll" type="warning" @click="downloadAll">
      Unduh Semua File
    </el-button>
    <el-table
      v-loading="loading"
      :data="list"
      :border="false"
      fit
      highlight-current-row
      :header-cell-style="{ background: '#3AB06F', color: 'white' }"
      style="margin-top: 15px"
    >
      <el-table-column align="center" label="No" width="50px">
        <template slot-scope="scope">
          <span>{{ scope.row.no }}</span>
        </template>
      </el-table-column>

      <el-table-column align="center" label="Lampiran">
        <template slot-scope="scope">
          <span>{{ scope.row.name }}</span>
        </template>
      </el-table-column>

      <el-table-column align="center" label="File">
        <template slot-scope="scope">
          <span v-if="scope.row.file === null">-</span>
          <span v-else-if="scope.row.file === 'undefined'">{{ '' }}</span>
          <a
            v-else-if="
              scope.row.generate &&
              scope.row.name === 'a. Saran, Pendapat dan Tanggapan Masyarakat'
            "
            href="#"
            class="link-lampiran"
            style="color: #0000ee"
            @click.prevent="downloadPDFSPT"
          >
            {{ loadingPDFSPT ? 'Loading...' : 'Unduh File PDF' }}
          </a>
          <a
            v-else-if="
              scope.row.generate && scope.row.name === 'b. Konsultasi Publik'
            "
            href="#"
            class="link-lampiran"
            style="color: #0000ee"
            @click.prevent="downloadPublicConsultation"
          >
            {{ loadingPublicConsultation ? 'Loading...' : 'Unduh File PDF' }}
          </a>
          <a
            v-else
            :href="scope.row.file"
            class="link-lampiran"
            :download="scope.row.name + '.pdf'"
          >
            Unduh File PDF
          </a>
        </template>
      </el-table-column>
    </el-table>
  </div>
</template>

<script>
import Resource from '@/api/resource';
import axios from 'axios';
const kaReviewResource = new Resource('ka-reviews');

export default {
  name: 'Lampiran',
  data() {
    return {
      list: [],
      loading: false,
      loadingAll: false,
      loadingPDFSPT: false,
      loadingPublicConsultation: false,
    };
  },
  created() {
    this.getAttachment();
  },
  methods: {
    async getAttachment() {
      this.loading = true;
      this.list = await kaReviewResource.list({
        idProject: this.$route.params.id,
        isAndal: 'false',
        attachment: 'true',
      });
      this.loading = false;
    },
    async downloadPDFSPT() {
      this.loadingPDFSPT = true;

      axios({
        url: `api/testing-verification`,
        method: 'GET',
        responseType: 'blob',
        params: {
          idProject: this.$route.params.id,
          spt: 'true',
        },
      })
        .then((response) => {
          this.loadingPDFSPT = false;
          if (!this.loadingPublicConsultation) {
            this.loadingAll = false;
          }
          var fileURL = window.URL.createObjectURL(new Blob([response.data]));
          var fileLink = document.createElement('a');
          fileLink.href = fileURL;
          fileLink.setAttribute('download', 'Rekap SPT Masyarakat.pdf');
          document.body.appendChild(fileLink);
          fileLink.click();
        })
        .catch((err) => {
          err = '';
          this.loadingPDFSPT = false;
          if (!this.loadingPublicConsultation) {
            this.loadingAll = false;
          }
        });
    },
    downloadPublicConsultation() {
      this.loadingPublicConsultation = true;
      axios
        .get(
          `/api/public-consultations?idProject=${this.$route.params.id}&dokumen=true`,
          {
            responseType: 'blob',
          }
        )
        .then((response) => {
          const fileUrl = window.URL.createObjectURL(response.data);
          const fileLink = document.createElement('a');

          fileLink.href = fileUrl;
          fileLink.setAttribute('download', 'Hasil Konsultasi Publik.pdf');
          document.body.appendChild(fileLink);

          fileLink.click();
          this.loadingPublicConsultation = false;
          if (!this.loadingPDFSPT) {
            this.loadingAll = false;
          }
        })
        .catch((error) => {
          console.log(error.response.data);
          this.loadingPublicConsultation = false;
          if (!this.loadingPDFSPT) {
            this.loadingAll = false;
          }
        });
    },
    downloadAll() {
      document.querySelectorAll('.link-lampiran').forEach((el) => {
        el.click();
        this.loadingAll = true;
      });
    },
  },
};
</script>
