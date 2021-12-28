<template>
  <fieldset>
    <legend>Evaluasi Dampak Potensial</legend>
    <el-form ref="form" :model="pies" label-position="top" label-width="120px" @change="markChange">
      <el-row :gutter="20" class="pies-input">
        <el-col :span="spanValue" class="pie-item">
          <el-form-item label="A." prop="desc">
            <el-input
              v-model="pies.A"
              type="textarea"
              :autosize="{ minRows: 3, maxRows: 5}"
            />
          </el-form-item>
        </el-col>
        <el-col :span="spanValue" class="pie-item">
          <el-form-item label="B." prop="desc">
            <el-input
              v-model="pies.B"
              type="textarea"
              :autosize="{ minRows: 3, maxRows: 5}"
            />
          </el-form-item>
        </el-col>
        <el-col :span="spanValue" class="pie-item">
          <el-form-item label="C." prop="desc">
            <el-input
              v-model="pies.A"
              type="textarea"
              :autosize="{ minRows: 3, maxRows: 5}"
            />
          </el-form-item>
        </el-col>
        <el-col :span="spanValue" class="pie-item">
          <el-form-item label="D." prop="desc">
            <el-input
              v-model="pies.A"
              type="textarea"
              :autosize="{ minRows: 3, maxRows: 5}"
            />
          </el-form-item>
        </el-col>
        <el-col :span="spanValue" class="pie-item">
          <el-form-item label="E." prop="desc">
            <el-input
              v-model="pies.A"
              type="textarea"
              :autosize="{ minRows: 3, maxRows: 5}"
            />
          </el-form-item>
        </el-col>
      </el-row>
    </el-form>
  </fieldset>
</template>
<script>
export default {
  name: 'PieForm',
  data() {
    return {
      hasChanges: false,
      spanValue: 12,
      piesParam: [],
      pies: { A: '', B: '', C: '', D: '', E: '' },
    };
  },
  mounted(){
  },
  methods: {
    markChange(e){
      this.hasChanges = true;
      console.log('hasChanges', this.hasChanges);
    },
  },
};
</script>
<style lang="scss" scoped>
.pies-input{

  -ms-box-orient: horizontal;
  display: -webkit-box;
  display: -moz-box;
  display: -ms-flexbox;
  display: -moz-flex;
  display: -webkit-flex;
  display: flex;

  -webkit-flex-wrap: wrap;
  flex-wrap: wrap;
}

fieldset {
  background: #f5f5f5; /*#f3f7f3;*/
  border: 1px solid #e0e9e0;
  border-radius: 1em;
  padding: 1em 2em;

  legend {
    font-weight: 600;
  }
}

</style>
