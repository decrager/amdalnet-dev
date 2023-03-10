<!-- eslint-disable vue/html-indent -->
<template>
  <div style="margin-top: 30px">
    <el-button :loading="loadingAll" type="warning" :disabled="isRequiredDocUklUpl" @click="downloadAll">
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

      <el-table-column align="left" label="Lampiran">
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
              scope.row.name === ' Saran, Pendapat dan Tanggapan Masyarakat'
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
              scope.row.generate && scope.row.name === 'Konsultasi Publik'
            "
            href="#"
            class="link-lampiran"
            style="color: #0000ee"
            @click.prevent="downloadPublicConsultation"
          >
            {{ loadingPublicConsultation ? 'Loading...' : 'Unduh File PDF' }}
          </a>
          <a
            v-else-if="scope.row.data_pendukung"
            href="#"
            class="link-lampiran"
            @click.prevent="
              downloadExternalFile(
                scope.row.file_name,
                scope.row.file,
                scope.$index
              )
            "
          >
            {{
              loadingExternalFile[`loading-${scope.$index}`]
                ? 'Loading...'
                : 'Unduh File'
            }}
          </a>
          <a
            v-else-if="scope.row.name.includes('versi')"
            :href="scope.row.file"
            class="link-lampiran"
            style="color: inherit;"
          >
            {{
              loadingExternalFile[`loading-${scope.$index}`]
                ? 'Loading...'
                : 'Unduh File PDF'
            }}
          </a>
          <a
            v-else
            href="#"
            class="link-lampiran"
            @click.prevent="
              downloadExternalFile(
                scope.row.file_name
                  ? scope.row.file_name
                  : scope.row.name + '.pdf',
                scope.row.file,
                scope.$index
              )
            "
          >
            {{
              loadingExternalFile[`loading-${scope.$index}`]
                ? 'Loading...'
                : 'Unduh File'
            }}
            <span v-if="!loadingExternalFile[`loading-${scope.$index}`]">
              {{
                scope.row.file_name
                  ? scope.row.file_name
                      .split('.')
                      [scope.row.file_name.split('.').length - 1].toUpperCase()
                  : 'PDF'
              }}
            </span>
          </a>
        </template>
      </el-table-column>
    </el-table>
  </div>
</template>

<script>
import { mapGetters } from 'vuex';
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
      loadingExternalFile: {},
      loadingPublicConsultation: false,
    };
  },
  computed: {
    ...mapGetters({
      requiredDoc: 'requiredDoc',
    }),
    isRequiredDocUklUpl() {
      return this.requiredDoc === 'AMDAL';
    },
  },
  created() {
    this.getAttachment();
  },
  methods: {
    async getAttachment() {
      this.loading = true;
      const list = await kaReviewResource.list({
        idProject: this.$route.params.id,
        isAndal: 'false',
        attachment: 'true',
      });

      for (let i = 0; i < list.length; i++) {
        if (!(list[i].file === null || list[i].file === 'undefined')) {
          this.loadingExternalFile[`loading-${i}`] = false;
        }
      }

      this.list = list;
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
    downloadExternalFile(name, filePath, idx) {
      this.loadingExternalFile[`loading-${idx}`] = true;
      axios
        .get(`/api/ka-reviews?downloadExternalFile=true&filepath=${filePath}`, {
          responseType: 'blob',
        })
        .then((response) => {
          const fileUrl = window.URL.createObjectURL(response.data);
          const fileLink = document.createElement('a');

          fileLink.href = fileUrl;
          fileLink.setAttribute('download', name);
          document.body.appendChild(fileLink);

          fileLink.click();
          this.loadingExternalFile[`loading-${idx}`] = false;
        })
        // eslint-disable-next-line handle-callback-err
        .catch((error) => {
          this.loadingExternalFile[`loading-${idx}`] = false;
        });
    },
    downloadAll() {
      document.querySelectorAll('.link-lampiran').forEach((el) => {
        el.click();
      });
    },
  },
};
</script>
