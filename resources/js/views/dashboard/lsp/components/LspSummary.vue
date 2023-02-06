
<template>
  <div class="user-summary">
    <el-card class="box-card">
      <div slot="header" class="clearfix">
        <span>Jumlah Penyusun</span>
      </div>
      <el-skeleton v-if="isLoading || (summary === null)" :rows="6" />
      <div v-else class="user-summary-cards">
        <el-row>
          <el-col :span="24">
            <el-row>
              <el-col :span="8">
                <el-card class="box-card" style="background: #347437; color: white;">
                  <span class="title">Total Penyusun</span>
                  <span class="value">{{ summary.total }}</span>
                </el-card>
              </el-col>
              <el-col :span="8">
                <el-card class="box-card" style="background: #347437; color: white;">
                  <span class="title">Aktif</span>
                  <span class="value">{{ summary.active }}</span>
                </el-card>
              </el-col>
              <el-col :span="8">
                <el-card class="box-card" style="background: #449748; color: white;">
                  <span class="title">NonAktif</span>
                  <span class="value">{{ summary.nonActive }}</span>
                </el-card>
              </el-col>
            </el-row>
            <el-row>
              <el-col :span="8">
                <el-card class="box-card" style="background: #347437; color: white;">
                  <span class="title">Sertifikasi</span>
                  <span class="value">{{ summary.sertifikasi }}</span>
                </el-card>
              </el-col>
              <el-col :span="8">
                <el-card class="box-card" style="background: #EB8A00; color: white;">
                  <span class="title">KTPA</span>
                  <span class="value">{{ summary.ktpa }}</span>
                </el-card>
              </el-col>
              <el-col :span="8">
                <el-card class="box-card" style="background: #FAC400; color: white;">
                  <span class="title smaller">ATPA</span>
                  <span class="value">{{ summary.atpa }}</span>
                </el-card>
              </el-col>
            </el-row>
          </el-col>
        </el-row>
      </div>
    </el-card>
  </div>
</template>
<script>
import Resource from '@/api/resource';
const countResource = new Resource('lsp-count');
export default {
  name: 'LspSummary',
  props: {
    user: {
      type: Object,
      default: null,
    },
  },
  data() {
    return {
      isLoading: true,
      summary: null,
    };
  },
  computed: {
    isFormulator(){
      return this.$store.getters.roles.includes('formulator');
    },
    isInitiator(){
      return this.$store.getters.roles.includes('initiator');
    },
    isLPJP(){
      return this.$store.getters.roles.includes('lpjp');
    },
    isLSP(){
      return this.$store.getters.roles.includes('lsp');
    },
    isExaminer(){
      return this.$store.getters.roles[0].split('-')[0] === 'examiner';
    },
  },
  watch: {
    user: function(val) {
      console.log('user? ', val);
      this.getCount();
    },
  },
  mounted() {
    this.isLoading = false;
    console.log('user Summary');
  },
  methods: {
    async getCount() {
      this.isLoading = true;
      let param = null;
      if (this.isInitiator) {
        param = { initiatorId: this.user.id };
      }
      if (this.isFormulator) {
        param = { formulatorId: this.user.id };
      }
      if (this.isLPJP) {
        param = { lpjpId: this.user.id };
      }
      if (this.isLSP) {
        param = { lspId: this.user.id };
      }

      await countResource.list(param).then((res) => {
        // let total = 0;
        const summary = {
          total: 0, active: 0, nonActive: 0, sertifikasi: 0, ktpa: 0, atpa: 0,
        };
        let doc1 = 0;
        let doc2 = 0;
        let doc3 = 0;

        // Jumlah Penyusun Total
        doc1 = res.data_status.find((e) => e.membership_status === 'ATPA');
        doc2 = res.data_status.find((e) => e.membership_status === 'KTPA');
        doc3 = res.data_status.find((e) => e.membership_status === 'TA');
        if (!doc1) {
          if (!doc2) {
            summary.total = doc3.total;
          }
          summary.total = doc2.total + doc3.total;
        }
        if (!doc2) {
          if (!doc3) {
            summary.total = doc1.total;
          }
          summary.total = doc1.total + doc3.total;
        }
        if (!doc3) {
          summary.total = doc1.total + doc2.total;
        } else {
          summary.total = doc1.total + doc2.total + doc3.total;
        }

        // Jumlah Penyusun Aktif
        doc1 = res.data_active.find((e) => e.membership_status === 'ATPA');
        doc2 = res.data_active.find((e) => e.membership_status === 'KTPA');
        doc3 = res.data_active.find((e) => e.membership_status === 'TA');
        if (!doc1) {
          if (!doc2) {
            summary.active = doc3.total;
          }
          summary.active = doc2.total + doc3.total;
        }
        if (!doc2) {
          if (!doc3) {
            summary.active = doc1.total;
          }
          summary.active = doc1.total + doc3.total;
        }
        if (!doc3) {
          summary.active = doc1.total + doc2.total;
        } else {
          summary.active = doc1.total + doc2.total + doc3.total;
        }

        // Jumlah Penyusun Tidak Aktif
        doc1 = res.data_non_active.find((e) => e.membership_status === 'ATPA');
        doc2 = res.data_non_active.find((e) => e.membership_status === 'KTPA');
        doc3 = res.data_non_active.find((e) => e.membership_status === 'TA');
        if (!doc1) {
          if (!doc2) {
            summary.nonActive = doc3.total;
          }
          summary.nonActive = doc2.total + doc3.total;
        }
        if (!doc2) {
          if (!doc3) {
            summary.nonActive = doc1.total;
          }
          summary.nonActive = doc1.total + doc3.total;
        }
        if (!doc3) {
          summary.nonActive = doc1.total + doc2.total;
        } else {
          summary.nonActive = doc1.total + doc2.total + doc3.total;
        }

        // Jumlah Penyusun Sertifikasi
        doc1 = res.data_status.find((e) => e.membership_status === 'ATPA');
        doc2 = res.data_status.find((e) => e.membership_status === 'KTPA');
        if (!doc1) {
          if (!doc2) {
            summary.sertifikasi = 0;
          }
          summary.sertifikasi = doc2.total;
        } else {
          summary.sertifikasi = doc1.total + doc2.total;
        }

        // Jumlah Penyusun KTPA
        doc1 = res.data_status.find((e) => e.membership_status === 'KTPA');
        if (!doc1) {
          summary.ktpa = 0;
        } else {
          summary.ktpa = doc1.total;
        }

        // Jumlah Penyusun ATPA
        doc1 = res.data_status.find((e) => e.membership_status === 'ATPA');
        if (!doc1) {
          summary.ktpa = 0;
        } else {
          summary.atpa = doc1.total;
        }

        // summary.total = total;
        this.summary = summary;
        this.isLoading = false;
        console.log('getCount: ', this.summary);
      }).finally((f) => {
        this.isLoading = false;
      });
    },
  },
};
</script>
<style lang="scss" scoped>

.user-summary-cards {
  .el-card {
    height: 150px;
    margin:0;
    padding:0;
    overflow: hidden;
    overflow: -moz-hidden-unscrollable;

    span {
      display:block;
      text-align: center;
    }

    .title {
      font-weight: bold;

    }
    .value {
      margin-top: 0.8em;
      font-size: 250%;
    }
    .smaller {
      font-size: 86%;
    }

    &#total {
      height: 300px;
      .value {
        position: relative;
        top: 80px;
        font-weight: bolder;
      }
    }
  }
}

</style>
