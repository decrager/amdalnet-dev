<template>
  <div v-if="data" id="detailForm" v-loading="isSaving">
    <div>
      <!--<el-row :gutter="20" class="detail-header">
         <el-col :span="20" class="kegiatan">
          <div class="label">Kegiatan {{ data.type }}</div>
          <div>{{ data.kegiatan }}</div>
        </el-col>
        <el-col class="stage" style="text-align: right;">
         <div class="label">Tahap</div>
          <div>{{ data.stage }}</div>
        </el-col>
      </el-row>-->
      <div class="detail-header">
        <div class="stage">
          <!-- <div class="label">Kegiatan {{ data.type }}: {{ data.kegiatan }}</div> -->
          <div class="label">Tahap</div>
          <div>{{ data.stage }}</div>
        </div>
      </div>

      <div class="entry-title">
        <el-form label-position="top">
          <el-form-item label="Dampak Potensial">
            <span v-if="isFormulator">
              <el-select v-model="data.id_change_type" placeholder="Pilih" :disabled="!isFormulator" @change="onChangeType">
                <el-option
                  v-for="item in changeTypes"
                  :key="item.id"
                  :label="item.name"
                  :value="item.id"
                />
              </el-select>
              <el-input v-if="data.id_change_type === 0" v-model="data.change_type_name" type="text" style="width: 200px;" :readonly="!isFormulator" @change="hasChanges" />
            </span>
            <span v-else style="font-size: inherit; font-size: 150%; color: red;">
              {{ data.change_type_name || '*belum terdefinisi*' }}
            </span>
            <span style="margin-left: 1em; font-size: inherit; font-size: 150%;">{{ data.rona_awal }} akibat {{ data.komponen }}</span>
          </el-form-item>
        </el-form>
      </div>
      <el-row :gutter="20">
        <el-col :span="8">
          <el-form label-position="top">
            <el-form-item label="Dampak Penting Hipotetik">
              <el-select v-model="data.is_hypothetical_significant" placeholder="Select" @change="hasChanges">
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
              <!-- <el-input
                v-model="data.initial_study_plan"
                type="textarea"
                :autosize="{ minRows: 3, maxRows: 5}"
                :readonly="!isFormulator"
                @input="hasChanges"
              /> -->
              <editor
                v-if="isFormulator"
                v-model="data.initial_study_plan"
                output-format="html"
                :menubar="''"
                :image="false"
                :height="150"
                :toolbar="['bold italic underline bullist numlist  preview undo redo fullscreen']"
                @hasChanges="hasChanges"
              />
              <div v-else class="div-readonly" v-html="data.initial_study_plan || '&nbsp;<br/>&nbsp;'" />
            </el-form-item>
            <el-form-item v-if="!(data.is_hypothetical_significant === false)" label="Wilayah Studi">
              <el-input
                v-model="data.study_location"
                type="textarea"
                :autosize="{ minRows: 3, maxRows: 5}"
                :readonly="!data.is_hypothetical_significant || !isFormulator"
                @input="hasChanges"
              />
            </el-form-item>
            <el-form-item v-if="!(data.is_hypothetical_significant === false)" label="Batas Waktu Kajian">
              <div v-if="isFormulator">
                <span style="margin-right: 1em">
                  <el-input-number
                    v-model="data.study_length_year"
                    size="small"
                    :min="0"
                    :disabled="!isFormulator"
                    @change="hasChanges"
                  /> &nbsp;&nbsp;tahun</span>
                <span><el-input-number v-model="data.study_length_month" size="small" :min="0" :disabled="!isFormulator" @change="hasChanges" /> &nbsp;&nbsp;bulan</span>
              </div>
              <div v-else>
                <span style="margin-right: 1em;"><strong>{{ data.study_length_year }}</strong> tahun</span> <span><strong>{{ data.study_length_month }}</strong> bulan </span>
              </div>
            </el-form-item>
          </el-form>
        </el-col>
        <el-col :span="16">
          <pie-form
            :data="pies"
            :params="pieParams"
            :id-impact-identification="data.id"
            :loading="isLoadingPie"
            @hasChanges="hasChanges"
          />
        </el-col>
      </el-row>
      <div style="text-align: right; margin-top:2em;">
        <el-popconfirm
          :disabled="!data.hasChanges"
          confirm-button-text="Iya, refresh!"
          cancel-button-text="Tidak"
          title="Ada perubahan yang belum disimpan. Lanjutkan muat ulang data?"
          icon-color="red"
          @confirm="refresh()"
        >
          <el-button
            slot="reference"
            icon="el-icon-refresh"
            :disabled="!data.hasChanges"
          > Reset
          </el-button>
        </el-popconfirm> &nbsp;&nbsp;
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

    <Comment :impactidentification="data.id" :commenttype="commentType[mode]" :kolom="kolom" style="margin: auto;" @addComment="onAddComment" />
  </div>
</template>
<script>
import editor from '@/components/Tinymce';
import Resource from '@/api/resource';
import PieForm from './PieForm.vue';
import Comment from '@/views/amdal/components/Comment.vue';
const pieResource = new Resource('pie-entries');
const impactsResource = new Resource('impacts');
export default {
  name: 'DampakHipotetikDetailForm',
  components: {
    editor,
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
    mode: {
      type: Number,
      default: 0,
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
      commentType: ['dpdph-ka', 'dpdph-andal'],
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
      this.getPies();
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
          mode: this.mode,
        }).then((res) => {
          if (res && (res.length > 0)) {
            // this.data.pie = res;
            // this.pies = res;
            const pies = [];
            res.forEach((e) => {
              pies.push({
                id: e.id,
                id_impact_identification: e.id_impact_identification,
                id_pie_param: e.id_pie_param,
                text: e.text,
              });
            });

            this.pies = pies;
            this.data.pie = pies;
            console.log('getPies', this.pies[0].text);
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
            console.log('empty pies', this.pies);
          }

          this.$emit('pieData', this.pies);
        }).finally(() => {
          this.isLoadingPie = false;
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
      // this.data.pie = null;
      // this.pies = null;
      this.hasChanges(true);
    },
    onPieFormChanges(val) {
      // console.log('onPieFormChanges: ', val);
      this.hasChanges(val);
    },
    saveChanges(){
      // console.log('You\'re initiating save!');
      this.isSaving = true;

      impactsResource.store({ mode: this.mode, data: [this.data] }).then((res) => {
        var message = 'Dampak Penting Hipotetik berhasil disimpan';
        var message_type = 'success';
        this.$message({
          message: message,
          type: message_type,
          duration: 5 * 1000,
        });

        /* this.data.id_change_type = res.id_change_type;
        // this.data.change_type_name = res.change_type_name;
        this.data.is_hypothetical_significant = res.is_hypothetical_significant;
        this.data.is_managed = res.is_managed;
        this.data.initial_study_plan = res.initial_study_plan;
        this.data.study_length_month = res.study_length_month;
        this.data.study_length_year = res.study_length_year;
        this.data.study_location = res.study_location;*/
        this.data.hasChanges = false;
        this.$emit('hasChanges', this.data);
      }).finally(() => {
        this.isSaving = false;
      });
    },
    refresh() {
      this.isSaving = true;
      console.log('refreshing:', this.mode);
      impactsResource.list(
        { id: this.data.id, mode: this.mode }
      ).then((e) => {
        const ct = this.changeTypes.find(c => c.id === e.id_change_type);
        if (ct) {
          this.data.id_change_type = e.id_change_type;
        } else {
          this.data.id_change_type = 0;
        }
        this.data.change_type_name = e.change_type_name;
        this.data.is_hypothetical_significant = e.is_hypothetical_significant;
        this.data.is_managed = e.is_managed;
        this.data.initial_study_plan = e.initial_study_plan;
        this.data.study_length_month = e.study_length_month;
        this.data.study_length_year = e.study_length_year;
        this.data.study_location = e.study_location;
        this.data.hasChanges = false;
        this.data.pie = null;
        this.$emit('hasChanges', this.data);
        // if (this.data.is_hypothetical_significant === true) {
        this.getPies();
        // }
      }).finally(() => {
        this.isSaving = false;
      });
    },
    onAddComment(val){
      this.data.comment++;
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
    font-size:180%;
     margin-bottom: 2em;
    .stage {
      margin-bottom: 1em;
      text-transform: uppercase;
      font-weight:900;
      line-height: 1em;
    }
    .label{
      font-size: 60%;
      font-weight: normal;
      text-transform: uppercase;
    }
    .kegiatan {
      text-transform: capitalize;
      font-weight: 500;
       line-height: 1em;
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
  div.div-readonly {
    border:1px solid #e0e0e0;
    background: white;
    padding: 0.5em 1em;
    border-radius: 0.3em;

    p{
      margin: 0.5em 0 0.8em;
      line-height: 1.25em;
    }
  }
}
</style>
