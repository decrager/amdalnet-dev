<template>
  <div v-if="data" id="detailForm" v-loading="isSaving">
    <div>
      <el-row class="detail-header">
        <el-col :span="18" class="stage">{{ data.stage }}</el-col>
        <el-col :span="6" style="font-size:150%; font-weight:900;text-align:right;">
        <!-- <el-button icon="el-icon-back" type="info" circle />
        <span style="font-weight:900; margin: auto 1em">2</span>

        <el-button icon="el-icon-right" type="info" circle />
        -->
        </el-col>
      </el-row>

      <div class="entry-title">
        <el-form label-position="top">
          <el-form-item label="Dampak Potensial">
            <el-select v-model="data.id_change_type" placeholder="Pilih" :disabled="!isFormulator" @change="onChangeType">
              <el-option
                v-for="item in changeTypes"
                :key="item.id"
                :label="item.name"
                :value="item.id"
              />
            </el-select>
            <el-input v-if="data.id_change_type === 0" v-model="data.change_type_name" type="text" style="width: 200px;" :readonly="!isFormulator" @change="hasChanges" />
            <span style="margin-left: 1em; font-size: inherit; font-size: 150%;">{{ data.rona_awal }} akibat {{ data.komponen }}</span>
          </el-form-item>
        </el-form>
      </div>
      <el-row :gutter="20">
        <el-col :span="8">
          <el-form label-position="top">
            <el-form-item label="Dampak Penting Hipotetik">
              <el-select v-model="data.is_hypothetical_significant" placeholder="Select" @change="onDPHChange">
                <el-option
                  v-for="item in dphdtph"
                  :key="item.value"
                  :label="item.label"
                  :value="item.value"
                />
              </el-select>
          &nbsp;&nbsp;<el-switch
            v-if="data.is_hypothetical_significant === false"
            v-model="data.is_managed"
            active-text="Dikelola"
            :readonly="!isFormulator"
            @change="hasChanges"
            />
            </el-form-item>
            <el-form-item label="Pengelolaan yang sudah direncanakan">
              <el-input
                v-model="data.initial_study_plan"
                type="textarea"
                :autosize="{ minRows: 3, maxRows: 5}"
                :readonly="!isFormulator"
                @input="hasChanges"
              />
            </el-form-item>
            <el-form-item v-if="data.is_" label="Wilayah Studi">
              <el-input
                v-model="data.study_location"
                type="textarea"
                :autosize="{ minRows: 3, maxRows: 5}"
                :readonly="!data.is_hypothetical_significant || !isFormulator"
                @input="hasChanges"
              />
            </el-form-item>
            <el-form-item label="Batas Waktu Kajian">
              <span style="margin-right: 1em">
                <el-input-number
                  v-model="data.study_length_year"
                  size="small"
                  :disabled="!data.is_hypothetical_significant || !isFormulator"
                  @change="hasChanges"
                /> &nbsp;&nbsp;tahun</span>
              <span><el-input-number v-model="data.study_length_month" size="small" :disabled="!data.is_hypothetical_significant || !isFormulator" @change="hasChanges" /> &nbsp;&nbsp;bulan</span>
            </el-form-item>
          </el-form>
        </el-col>
        <el-col :span="16">
          <pie-form
            v-if="data.is_hypothetical_significant === true"
            :data="pies"
            :params="pieParams"
            :id-impact-identification="data.id"
            :loading="isLoadingPie"
            @hasChanges="hasChanges"
          />
        </el-col>
      </el-row>
      <div style="text-align: right; margin-top:2em;">
        <el-button
          type="success"
          icon="el-icon-check"
          style="margin-bottom: 10px;"
          :disabled="!data.hasChanges"
          @click="saveChanges()"
        >
          Simpan Perubahan
        </el-button>
      </div>
    </div>

    <Comment :impactidentification="data.id" commenttype="dpdphdm" :kolom="kolom" style="margin: auto;" />
  </div>
</template>
<script>
import Resource from '@/api/resource';
import PieForm from './PieForm.vue';
import Comment from '@/views/amdal/components/Comment.vue';
const pieResource = new Resource('pie-entries');
const impactsResource = new Resource('impact-id');
export default {
  name: 'DampakHipotetikDetailForm',
  components: {
    PieForm,
    Comment,

  },
  props: {
    data: {
      type: Object,
      default: null,
    },
    pieParams: {
      type: Array,
      default: function() {
        return [];
      },
    },
    changeTypes: {
      type: Array,
      default: function() {
        return [];
      },
    },
  },
  data() {
    return {
      options: [],
      is_managed: false,
      editedItem: { },
      initial_study: '',
      change_type: '',
      pies: null,
      dphdtph: [
        { label: 'DPH', value: true },
        { label: 'DTPH', value: false },
      ],
      isSaving: false,
      isLoadingPie: false,
      dataChanged: false,
      kolom: [
        { label: 'Dampak Potensial', value: 'Dampak Potensial' },
        { label: 'Dampak Penting Hipotetik', value: 'Dampak Penting Hipotetik' },
        { label: 'Pengelolaan yang sudah direncanakan', value: 'Pengelolaan yang sudah direncanakan' },
        { label: 'Wilayah Studi', value: 'Wilayah Studi' },
        { label: 'Batas Waktu Studi', value: 'Batas Waktu Studi' },
        { label: 'Evaluasi Dampak Potensial', value: 'Evaluasi Dampak Potensial' },
      ],
    };
  },
  computed: {
    isFormulator() {
      return this.$store.getters.roles.includes('formulator');
    },
  },
  watch: {
    data: function(val) {
      if (val.id_change_type){
        const ct = this.changeTypes.find(e => e.id === val.id_change_type);
        if (!ct){
          val.id_change_type = 0;
        }
      }

      // console.log('watch', val);
      this.dataChanged = false;

      if (val.is_hypothetical_significant === true) {
        this.getPies();
      }
    },
    changeTypes: function(val){
      // console.log(val);
    },
  },
  methods: {
    async getPies(){
      this.pies = null;
      this.isLoadingPie = true;

      // console.log('hasPie?', this.data.pie);

      if ((typeof this.data.pie !== 'undefined') && (this.data.pie !== null) && (this.data.pie.length > 0)){
        this.pies = this.data.pie;
        this.isLoadingPie = false;
      } else {
        pieResource.list({
          id_impact_identification: [this.data.id],
        }).then((res) => {
          // console.log('calling ', res.length);
          if (res && (res.length > 0)) {
            this.data.pie = res;
            this.pies = res;
          } else {
            const pies = [];
            this.pieParams.forEach((e) => {
              pies.push({
                id: 0,
                id_impact_identification: this.data.id,
                id_pie_param: e.id,
                text: null,
              });
            });
            this.pies = pies;
            this.data.pie = pies;
            // console.log('empty pies', this.pies);
          }
          this.isLoadingPie = false;

          this.$emit('pieData', this.pies);
        });
      }
    },
    hasChanges(e) {
      console.log('has changes:', e);
      this.dataChanged = true;
      this.data.hasChanges = true;
      this.$emit('hasChanges', this.data);
    },
    onChangeType(val){
      const ct = this.changeTypes.find(e => e.id === val);
      if (val === 0){
        this.data.change_type_name = '';
      } else if (ct) {
        this.data.change_type_name = ct.name;
      }
      this.hasChanges(val);
    },
    onDPHChange(isDPH){
      this.data.pie = null;
      this.pies = null;
      console.log('isDPH', isDPH);
      if (isDPH){
        this.getPies();
      }
      this.hasChanges(true);
    },
    onPieFormChanges(val) {
      console.log('onPieFormChanges: ', val);
      this.hasChanges(val);
    },
    saveChanges(){
      console.log('You\'re initiating save!');
      this.isSaving = true;

      impactsResource.store(this.data).then((res) => {
        var message = 'Dampak Penting Hipotetik berhasil disimpan';
        var message_type = 'success';
        this.$message({
          message: message,
          type: message_type,
          duration: 5 * 1000,
        });
        this.isSaving = false;
        this.data.hasChanges = false;
        console.log(this.data);
        this.$emit('hasChanges', this.data);
      });
    },
  },
};
</script>
<style lang="scss">
#detailForm {
  padding: 2em;
  border: 1px solid #e0e0e0;
  border-radius: 1em;
  .detail-header {
    .stage {
      margin-bottom: 2em;;
      font-size:180%;
      text-transform: uppercase;
      font-weight:900;
      line-height: 1em;
    }
    .stage:before{
      content: 'tahap';
      font-size: 60%;
      display:table;
      clear:both;
      font-weight: normal;
      text-transform: unset;
    }
  }
  .entry-title{
    font-size: 150%;
    margin: 1em 0 2em;
    font-weight: bold;
  }
  label.el-form-item__label {
      font-weight: 500;
      line-height: 1.3em ;
    }
}
</style>
