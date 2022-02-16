<template>
  <el-dialog :key="refreshDialog" :title="'Masukkan Parameter'" :visible.sync="show" :close-on-click-modal="false" :show-close="false">
    <el-table
      v-loading="loading"
      :header-cell-style="{ background: '#099C4B', color: 'white' }"
      :data="list"
      fit
      highlight-current-row
    >
      <el-table-column label="No." width="60px">
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
            class="edit-input"
            size="mini"
            type="number"
            step=".01"
            min="0"
            :disabled="!scope.row.used"
            @blur="handleBlur(scope.row)"
          />
        </template>
      </el-table-column>

      <el-table-column label="Satuan">
        <template slot-scope="scope">
          {{ scope.row.scale_unit }}
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
  },
  data(){
    return {
      loading: false,
    };
  },
  methods: {
    handleUsedChange(value) {
      delete value.scale;
      delete value.result;
      delete value.amdal_type;
      // this.handleRefreshDialog();
    },
    handleCancelParam() {
      this.$emit('handleCancelParam');
    },
    async handleBlur(value){
      console.log(value.scale);
      if (value.scale && value.scale > 0) {
        console.log(value);
        console.log(this.kbli);

        // calculate result
        const { data } = await kbliEnvParamResource.list({
          fieldId: this.kbli,
          businessType: value.id_param,
          unit: value.id_unit,
        });
        console.log('1', data);
        const kbliEnvParams = data.map((item) => {
          const items = item.condition.replace(/[\[\"\]]/g, '').split(',');
          item.conditions = items.map((e) => value.scale + ' ' + e);
          return item;
        });

        console.log(kbliEnvParams);

        // this.calculatedProjectDoc();
        kbliEnvParams.forEach((item) => {
          let tempStatus = true;
          item.conditions.forEach((element) => {
            if (this.checkIfTrue(element)) {
              tempStatus = tempStatus && true;
            } else {
              tempStatus = tempStatus && false;
            }
          });
          if (tempStatus) {
            value.result = item.doc_req;
            value.result_risk = item.risk_level;
            value.amdal_type = item.amdal_type;
          }
        });

        // check for any condition that not in kbli param
        if (!value.result) {
          value.result = 'AMDAL';
          value.result_risk = 'Tinggi';
        }

        this.handleRefreshDialog();
      }
    },
    checkIfTrue(item) {
      console.log('masuk pak eko');
      const [ar1, operator, ar2] = item.split(/\s+/);

      // replace . to ''
      const data1 = ar1.replace('.', '');
      const data2 = ar2.replace('.', '');

      console.log('awal', [ar1, ar2]);
      console.log('test', [data1, data2]);

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
