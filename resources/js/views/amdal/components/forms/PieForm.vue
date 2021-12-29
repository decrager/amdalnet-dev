<template>
  <div v-loading="loading">
    <fieldset>
      <legend>Evaluasi Dampak Potensial</legend>
      <el-form v-if="data" ref="form" label-position="top" label-width="120px">
        <el-row
          :gutter="20"
          class="pies-input"
        >
          <template
            v-for="param in params"
          >
            <el-col :key="'pie_param_' + param.id" :span="spanValue" class="pie-item">
              <el-form-item :label="param.name" prop="desc">
                <el-input
                  v-model="data[data.findIndex(e => e.id_pie_param === param.id)].text"
                  type="textarea"
                  :autosize="{ minRows: 3, maxRows: 5}"
                  :disabled="!isFormulator"
                  @change="markChange"
                />
              </el-form-item>
            </el-col>
          </template>
        </el-row>
      </el-form>
    </fieldset>
  </div>
</template>
<script>
export default {
  name: 'PieForm',
  props: {
    data: {
      type: Array,
      default: function() {
        return [];
      },
    },
    params: {
      type: Array,
      default: function() {
        return [];
      },
    },
    idImpactIdentification: {
      type: Number,
      default: 0,
    },
    loading: {
      type: Boolean,
      default: false,
    },
  },
  data() {
    return {
      hasChanges: false,
      spanValue: 12,
    };
  },
  computed: {
    isFormulator() {
      return this.$store.getters.roles.includes('formulator');
    },
  },
  watch: {
    data: function(val) {
      console.log('pie data', val);
    },
    params: function(val) {
      console.log('pie data', val);
    },
  },
  mounted(){
  },
  methods: {
    markChange(e){
      this.hasChanges = true;
      // console.log('pieFom hasChanges', this.hasChanges);
    },
  },
};
</script>
<style lang="scss" scoped>
fieldset {
  min-height: 200px;
}
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
