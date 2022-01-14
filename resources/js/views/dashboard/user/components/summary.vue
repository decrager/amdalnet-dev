<template>
  <div class="user-summary">
    <el-card class="box-card">
      <div slot="header" class="clearfix">
        <span>Persetujuan</span>
      </div>
      <!-- flexbox -->
      <el-skeleton v-if="isLoading" :rows="6" />
      <div v-else class="user-summary-cards">
        <!-- -->
        <el-row>
          <el-col :span="8">
            <el-card id="total" class="box-card" style="background: #0A2F08; color: white;">
              <span class="title">Total</span>
              <span class="value">{{ summary.total }}</span>
            </el-card>
          </el-col>
          <el-col :span="16">
            <el-row>
              <el-col :span="12">
                <!-- #638761 -->
                <el-card class="box-card" style="background: #347437; color: white;">
                  <span class="title">AMDAL</span>
                  <span class="value">{{ summary.amdal }}</span>
                </el-card>
              </el-col>
              <el-col :span="12">
                <!-- #61929d -->
                <el-card class="box-card" style="background: #449748; color: white;">
                  <span class="title">UKL-UPL</span>
                  <span class="value">{{ summary.uklupl }}</span>
                </el-card>
              </el-col>
            </el-row>
            <el-row>
              <el-col :span="12">
                <!-- #54abab -->
                <el-card class="box-card" style="background: #EB8A00; color: white;">
                  <span class="title">SPPL</span>
                  <span class="value">{{ summary.sppl }}</span>
                </el-card>
              </el-col>
              <el-col :span="12">
                <!-- #a5c5bc -->
                <el-card class="box-card" style="background: #FAC400; color: white;">
                  <span class="title smaller">Addendum Andal dan RKL RPL</span>
                  <span class="value" style="margin-top: 0.5em;">{{ summary.addendum }}</span>
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
const countResource = new Resource('proposal-count');
export default {
  name: 'UserSummary',
  props: {
    user: {
      type: Object,
      default: null,
    },
  },
  data() {
    return {
      isLoading: true,
      /* data: [
        { label: 'Total', value: 18 },
        { label: 'AMDAL', value: 3 },
        { label: 'UKL-UPL', value: 5 },
        { label: 'SPPL', value: 7 },
        { label: 'Adendum AMDAL', value: 0 },
        { label: 'Adendum UKL-UPL', value: 0 },
      ],*/
      // summary: { total: 18, amdal: 3, uklupl: 5, sppl: 7, addendum_uklupl: 0, addendum_amdal: 0 },
      summary: { total: 0, amdal: 0, uklupl: 0, sppl: 0, addendum: 0 },

    };
  },
  computed: {
    isFormulator(){
      return this.$store.getters.roles.includes('formulator');
    },
    isInitiator(){
      return this.$store.getters.roles.includes('initiator');
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
      let param = null;
      if (this.isInitiator) {
        param = { initiatorId: this.user.id };
      }
      if (this.isFormulator) {
        param = { formulatorId: this.user.id };
      }

      await countResource.list(param).then((res) => {
        let total = 0;
        let doc = res.find((e) => e.required_doc === 'AMDAL');
        if (doc) {
          this.summary.amdal = doc.total;
          total = total + doc.total;
        } else {
          this.summary.amdal = 0;
        }

        doc = res.find((e) => e.required_doc === 'UKL-UPL');
        if (doc) {
          this.summary.uklupl = doc.total;
          total = total + doc.total;
        } else {
          this.summary.uklupl = 0;
        }

        doc = res.find((e) => e.required_doc === 'SPPL');
        if (doc) {
          this.summary.sppl = doc.total;
          total = total + doc.total;
        } else {
          this.summary.sppl = 0;
        }

        doc = res.find((e) => e.required_doc === 'ADDENDUM');
        if (doc) {
          this.summary.addendum = doc.total;
          total = total + doc.total;
        } else {
          this.summary.addendum = 0;
        }

        this.summary.total = total;
        console.log('getCount: ', this.summary);
      }).finally();
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
