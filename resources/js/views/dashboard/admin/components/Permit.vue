<template>
  <el-card v-loading="loading" class="box-card" style="margin-left:1.5em; min-height: 490px;">
    <!-- <el-button type="info" icon="el-icon-tickets" v-show="isChart" class="top-right" @click="isChart = false" circle></el-button>
    <el-button type="info" icon="el-icon-s-help" v-show="!isChart" class="top-right" @click="isChart = true" circle></el-button> -->
    <div style="margin: 0 0 1em 0; font-weight:bold;">Kewenangan Izin</div>
    <pie-chart v-if="isChart" />
    <div v-else>
      <div v-if="head.show" class="item">
        <el-row id="auth-central">
          <!-- <el-col :span="4">
            <i class="square">&nbsp;</i>
          </el-col> -->
          <el-col :span="21">
            <span class="title">Pusat</span>
            <span class="value">{{ head.total }}</span>
          </el-col>
          <el-col :span="3">
            <el-button icon="el-icon-right" circle />
          </el-col>
        </el-row>
      </div>
      <div v-if="province.show" class="item">
        <el-row id="auth-provincial">
          <!-- <el-col :span="4">
            <i class="square">&nbsp;</i>
          </el-col> -->
          <el-col :span="21">
            <span class="title">Provinsi</span>
            <span class="value">{{ province.total }}</span>
          </el-col>
          <el-col :span="3">
            <el-button icon="el-icon-right" circle />
          </el-col>
        </el-row>
      </div>
      <div v-if="district.show" class="item">
        <el-row id="auth-municipal">
          <!-- <el-col :span="4">
            <i class="square">&nbsp;</i>
          </el-col> -->
          <el-col :span="21">
            <span class="title">Kabupaten/Kota</span>
            <span class="value">{{ district.total }}</span>
          </el-col>
          <el-col :span="3">
            <el-button icon="el-icon-right" circle />
          </el-col>
        </el-row>
      </div>
    </div>
  </el-card>
</template>
<script>
import { mapGetters } from 'vuex';
import axios from 'axios';
import PieChart from './PieChart.vue';
export default {
  name: 'PermitByRegion',
  components: { PieChart },
  data() {
    return {
      isChart: false,
      head: {},
      province: {},
      district: {},
      loading: false,
      userInfo: {},
    };
  },
  computed: {
    ...mapGetters([
      'roles',
      'userId',
    ]),
    isExaminerSecretary() {
      return this.$store.getters.roles.includes('examiner-secretary');
    },
  },
  created() {
    this.getPermitAuthority();
  },
  methods: {
    getPermitAuthority() {
      this.loading = true;
      const dataType = this.isExaminerSecretary ? 'tuk' : '';
      axios.get(`/api/dashboard/permit-authority?type=${dataType}&id_user=${this.$store.getters.userId}`)
        .then(data => {
          this.head = data.data.pusat;
          this.province = data.data.provinsi;
          this.district = data.data.kabupaten;
          this.loading = false;
        })
        .catch(error => {
          console.log(error);
          this.loading = false;
        });
    },
  },
};
</script>
<style lang="scss" scoped>
.item {
  margin: 1em 0;
  padding: 1em;
  border: 1px solid #ddd;
  border-radius: 0.8em;

  span { display: block; font-weight:bold;}
  .title {

    font-size: 90%;
  }
  .value {
    margin-top: 0.5em;
    font-size: 130%;
  }
  .square {
    position:relative;
    top: 10px;
    display: block;
    width: 30px;
    height: 30px;
  }
  .el-button {
    position: relative;
    top: 8px;
  }

  #auth-central {
    .el-button, .square {
      background: #5E727F /*#1d4f29*/; color: #fff;
    }
  }
  #auth-provincial {
    .el-button, .square {
      background: #90a2ad /*#4d9e51*/; color: #fff;
    }
  }
  #auth-municipal {
    .el-button, .square {
      background: #A27D95  /*#b9c5cc #d65900*/; color: #fff;
    }
  }
}
.item:last-child {
  margin-bottom: 0;
}

.top-right{
  position: absolute;
  top:15px;
  right: 20px;
}
</style>
