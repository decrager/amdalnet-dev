<template>
  <div v-loading="loading" class="pies-form">
    <fieldset>
      <legend>Evaluasi Dampak Potensial <span v-if="!isFormulator" style="font-size:80%; font-style: italic; color: blue;">&nbsp;&nbsp;read-only</span></legend>
      <el-form v-if="data" ref="form" label-position="top" label-width="120px">
        <el-row
          :gutter="20"
          class="pies-input"
        >
          <template
            v-for="param in params"
          >
            <el-col :key="'pie_param_' + param.id" :span="spanValue" class="pie-item">
              <el-form-item prop="desc">
                <el-popover
                  v-if="param.description !== ''"
                  placement="top-start"
                  width="350"
                  popper-class="pie-desc-popper"
                  trigger="hover"
                >
                  <p style="word-break: break-word !important; text-align:left !important;">{{ param.description }}</p>
                  <label slot="reference" :key="'po_'+ param.id + '_'+ data[data.findIndex(e => e.id_pie_param === param.id)].id" class="el-form-item__label" style="cursor: pointer;">{{ param.name }}</label>
                </el-popover>
                <label v-else class="el-form-item__label">{{ param.name }}</label>
                <el-input
                  v-model="data[data.findIndex(e => e.id_pie_param === param.id)].text"
                  type="textarea"
                  :autosize="{ minRows: 3, maxRows: 5}"
                  :readonly="!isFormulator"
                  @input="markChange"
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
      // console.log('pie data', val);
    },
    params: function(val) {
      // console.log('pie data', val);
    },
    loading: function(val){
      // console.log('pieForm loading...', val);
    },
  },
  mounted(){
  },
  methods: {
    markChange(e){
      this.hasChanges = true;
      this.$emit('hasChanges', this.hasChanges);
      // console.log('pieFom hasChanges', this.hasChanges);
    },
  },
};
</script>
<style lang="scss">
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

div.pie-desc-popper {
  background: #333 !important;
  color: white !important;
  border-radius:0.6em;
  padding: 1em !important;
  border:none;

  p{ margin:0; padding:0; font-size:90%; line-height: 1.45em; letter-spacing: 0.05em;}

  &[x-placement^="bottom"] .popper__arrow::after { border-bottom-color: #333; }
  &[x-placement^="top"] .popper__arrow::after { border-top-color: #333; }
}
</style>
