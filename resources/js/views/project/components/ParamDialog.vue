<template>
  <el-dialog :key="refreshDialog" :title="'Masukkan Parameter'" :visible.sync="show" :close-on-click-modal="false" :show-close="false">
    <el-row>
      <el-col><div><el-tag style="margin-bottom: 5px;">Silahkan centang dan isi parameter yang sesuai dengan rencana usaha dan/atau kegiatan anda</el-tag></div></el-col>
    </el-row>
    <el-table
      v-loading="loading"
      :header-cell-style="{ background: '#099C4B', color: 'white' }"
      :data="list"
      fit
      highlight-current-row
    >
      <el-table-column label="No. " width="60px">
        <template slot-scope="scope">
          {{ scope.$index + 1 }}
        </template>
      </el-table-column>

      <el-table-column width="60px">
        <template slot-scope="scope">
          <el-checkbox v-model="scope.row.used" @change="handleUsedChange(scope.row)" />
        </template>
      </el-table-column>

      <el-table-column label="Parameter">
        <template slot-scope="scope">
          {{ scope.row.param }}
        </template>
      </el-table-column>

      <el-table-column label="Besaran">
        <template slot-scope="scope">
          <el-input
            v-model="scope.row.scale"
            v-mask="currencyMask"
            masked="true"
            class="edit-input"
            size="mini"
            type="text"
            step=".01"
            min="0"
            :disabled="!scope.row.used"
            @blur="handleBlur(scope.row)"
          />
        </template>
      </el-table-column>

      <el-table-column label="Satuan">
        <template slot-scope="scope">
          <div v-if="scope.row.param === 'semua besaran'">
            <el-input
              v-model="scope.row.scale_unit"
              size="mini"
              type="text"
              class="edit-input"
              :disabled="!scope.row.used"
              @blur="handleBlur(scope.row)"
            />
          </div>
          <div v-else>
            {{ scope.row.scale_unit }}
          </div>
        </template>
      </el-table-column>

      <el-table-column label="Hasil">
        <template slot-scope="scope">
          {{ scope.row.result }}
        </template>
      </el-table-column>

      <el-table-column label="Tipe Amdal">
        <template slot-scope="scope">
          {{ scope.row.amdal_type }}
        </template>
      </el-table-column>
    </el-table>
    <div slot="footer" class="dialog-footer">
      <el-button @click="handleCancelParam"> Tutup </el-button>
    </div>
  </el-dialog>
</template>

<script>
import Resource from '@/api/resource';
import createNumberMask from 'text-mask-addons/dist/createNumberMask';

const kbliEnvParamResource = new Resource('kbli-env-params');

export default {
  name: 'ParamDialog',
  props: {
    list: {
      type: Array,
      default: () => [],
    },
    show: Boolean,
    refreshDialog: {
      type: Number,
      default: () => 0,
    },
    kbli: {
      type: String,
      default: () => '',
    },
    sector: {
      type: String,
      default: () => '',
    },
    pemerintah: {
      type: Boolean,
      default: () => false,
    },
  },
  data(){
    return {
      loading: false,
      currencyMask: createNumberMask({
        prefix: '',
        includeThousandsSeparator: true,
        allowNegative: false,
        thousandsSeparatorSymbol: '.',
        allowDecimal: true,
        decimalSymbol: ',',
      }),
      minScale: 0,
      maxScale: 0,
    };
  },
  methods: {
    // scaleFilter(row) {
    //   const { scale } = row;
    //   console.log('lama', scale);
    //   scale.replace(',', '.');
    //   row.scale = new Intl.NumberFormat('id-ID', { style: 'decimal' }).format(row.scale);
    //   console.log('baru', row);
    // },
    handleUsedChange(value) {
      delete value.scale;
      // delete value.scale_unit;
      delete value.result;
      delete value.amdal_type;
      // this.handleRefreshDialog();
    },
    handleCancelParam() {
      if (this.list[0].scale_unit === '' || !this.list[0].scale_unit) {
        this.$message({
          message: `Satuan belum diisi, silahkan isi terlebih dahulu.`,
          type: 'error',
          duration: 5 * 1000,
        });
      } else {
        this.$emit('handleCancelParam');
      }
    },
    async handleBlur(value){
      // reset min and max scale
      this.minScale = 0;
      this.maxScale = 0;
      // reset all value first
      delete value.result;
      delete value.result_risk;
      delete value.amdal_type;
      // const a = value.scale;
      // console.log(0, value.scale);
      // remove . change , to .
      value.scale = value.scale.replace(/\./g, '');
      // console.log(1, value.scale);
      value.scale = value.scale.replace(',', '.');
      // console.log(2, value.scale);

      // const b = value.scale;
      // console.log(b);
      value.scaleNumber = parseFloat(value.scale);
      // console.log(3, value.scaleNumber);
      // console.log(value.scaleNumber);
      if (value.scaleNumber && value.scaleNumber > 0) {
        // console.log(0, value);
        // console.log(this.kbli);

        // calculate result
        const { data } = this.pemerintah
          ? await kbliEnvParamResource.list({
            fieldId: this.kbli,
            isPemerintah: 'true',
            sector: this.sector,
            businessType: value.id_param,
            unit: value.id_unit,
          }) : await kbliEnvParamResource.list({
            fieldId: this.kbli,
            sector: this.sector,
            businessType: value.id_param,
            unit: value.id_unit,
          });
        // console.log('1', data);
        const kbliEnvParams = data.map((item) => {
          const items = item.condition.replace(/[\[\"\]]/g, '').split(',');
          item.conditions = items.map((e) => value.scaleNumber + ' ' + e);
          return item;
        });

        // console.log(2, kbliEnvParams);

        // this.calculatedProjectDoc();
        let minimumResult = 'AMDAL';
        let minimumResultRisk = 'Tinggi';
        let maximumResult = 'SPPL';
        let maximumResultRisk = 'Rendah';
        kbliEnvParams.forEach((item) => {
          // for set minimum result
          if (item.doc_req === 'UKL-UPL' && minimumResult !== 'SPPL'){
            minimumResult = 'UKL-UPL';
            minimumResultRisk = item.risk_level;
          } else if (item.doc_req === 'SPPL'){
            minimumResult = 'SPPL';
            minimumResultRisk = item.risk_level;
          }

          // for set maximum result
          if (item.doc_req === 'UKL-UPL' && maximumResult !== 'AMDAL'){
            maximumResult = 'UKL-UPL';
            maximumResultRisk = item.risk_level;
          } else if (item.doc_req === 'AMDAL'){
            maximumResult = 'AMDAL';
            maximumResultRisk = item.risk_level;
          }

          let tempStatus = true;
          item.conditions.forEach((element) => {
            if (this.checkIfTrue(element)) {
              tempStatus = tempStatus && true;
            } else {
              tempStatus = tempStatus && false;
            }
            // console.log(2.5, tempStatus);
          });
          if (tempStatus) {
            value.result = item.doc_req;
            value.result_risk = item.risk_level;
            value.amdal_type = item.amdal_type;
            // console.log(value);
          }
        });

        // console.log(3, { minimumResult, minimumResultRisk });

        // check for any condition that not in kbli param
        if (!value.result) {
          // console.log('tege', {
          //   scale: value.scaleNumber,
          //   minimumResult,
          //   minimumResultRisk,
          //   maximumResult,
          //   maximumResultRisk,
          //   minScale: this.minScale,
          //   maxScale: this.maxScale,
          // });
          // check if value.scaleNumber more or less than min or max
          if (value.scaleNumber < this.minScale){
            value.result = minimumResult;
            value.result_risk = minimumResultRisk;
          } else if (value.scaleNumber > this.maxScale){
            value.result = maximumResult;
            value.result_risk = maximumResultRisk;
          }
        }

        // console.log(4, value);

        this.handleRefreshDialog();
      }
    },
    checkIfTrue(item) {
      // console.log('masuk pak eko');
      const [data1, operator, data2] = item.split(/\s+/);

      // update minimum and maximum scale
      if (this.minScale === 0 || this.minScale > parseFloat(data2)){
        this.minScale = parseFloat(data2);
      }

      if (this.maxScale < parseFloat(data2)){
        this.maxScale = parseFloat(data2);
      }

      // replace . to ''
      // const data1 = ar1.replace('.', '');
      // const data2 = ar2.replace('.', '');

      // console.log('awal', [ar1, ar2]);
      // console.log('test', [parseFloat(data1), operator, parseFloat(data2)]);

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
    handleRefreshDialog(){
      this.$emit('handleRefreshDialog');
    },
  },
};
</script>
