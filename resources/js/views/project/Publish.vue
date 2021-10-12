<template>
  <div class="form-container" style="padding: 24px">
    <el-row>
      <el-col :span="12">Informasi rencana Kegiatan</el-col>
      <el-col :span="12">
        <h1>Peta Will Be Here Soon</h1>
      </el-col>
    </el-row>
    <el-row> deskripsi kegiatan / deskripsi lokasi </el-row>
    <el-row>hasil penapisan</el-row>
  </div>
</template>

<script>
import Resource from '@/api/resource';
const kbliEnvParamResource = new Resource('kbli-env-params');

export default {
  name: 'Publish',
  props: {
    project: {
      type: Object,
      default: null,
    },
  },
  data() {
    return {
      kbliEnvParams: null,
      doc_req: 'SPPL',
      risk_level: 'Rendah',
    };
  },
  mounted() {
    console.log(this.project);
    this.getKbliEnvParams();
  },
  methods: {
    calculatedProjectDoc() {
      this.kbliEnvParams.forEach((item) => {
        let tempStatus = true;
        item.conditions.forEach((element) => {
          if (this.checkIfTrue(element)) {
            tempStatus = tempStatus && true;
          } else {
            tempStatus = tempStatus && false;
          }
        });
        if (tempStatus) {
          this.doc_req = item.doc_req;
          this.risk_level = item.risk_level;
        }
      });
    },
    checkIfTrue(item) {
      const [data1, operator, data2] = item.split(/\s+/);

      switch (operator) {
        case '<=':
          return parseFloat(data1) <= parseFloat(data2);
        case '>=':
          return parseFloat(data1) >= parseFloat(data2);
        case '<':
          return parseFloat(data1) < parseFloat(data2);
        case '>':
          return parseFloat(data1) > parseFloat(data2);

        default:
          break;
      }
    },
    async getKbliEnvParams() {
      const { data } = await kbliEnvParamResource.list({
        kbli: this.project.kbli,
        businessType: this.project.business_type,
        unit: this.project.scale_unit,
      });
      this.kbliEnvParams = data.map((item) => {
        const items = item.condition.replace(/[\[\"\]]/g, '').split(',');
        item.conditions = items.map((e) => this.project.scale + ' ' + e);
        return item;
      });

      this.calculatedProjectDoc();
    },
  },
};
</script>
