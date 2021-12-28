<template>
  <div id="detailForm">
    <el-row class="detail-header">
      <el-col :span="18" class="stage">Pra Produksi</el-col>
      <el-col :span="6" style="font-size:150%; font-weight:900;text-align:right;">
        <el-button icon="el-icon-back" type="info" circle />
        <span style="font-weight:900; margin: auto 1em">2</span>

        <el-button icon="el-icon-right" type="info" circle />

      </el-col>
    </el-row>
    <div class="entry-title">
      <span>
        <el-select v-model="change_type" placeholder="Pilih Perubahan">
          <el-option
            v-for="item in options"
            :key="item.value"
            :label="item.label"
            :value="item.value"
          />
        </el-select></span>
      <el-input type="text" style="width: 200px;" />
      <span style="margin-left: 1em;">Penguasaan Lahan akibat Pengadaan Lahan</span>
    </div>
    <el-row :gutter="20">
      <el-col :span="8">
        <el-form label-position="top">
          <el-form-item label="Dampak Hipotetik">
            <el-select v-model="value" placeholder="Select">
              <el-option
                v-for="item in options"
                :key="item.value"
                :label="item.label"
                :value="item.value"
              />
            </el-select>
          &nbsp;&nbsp;<el-switch
            v-model="is_managed"
            active-text="Dikelola"
            />
          </el-form-item>
          <el-form-item label="Pengelolaan yang sudah direncanakan">
            <el-input
              v-model="initial_study"
              type="textarea"
              :autosize="{ minRows: 3, maxRows: 5}"
            />
          </el-form-item>
          <el-form-item label="Wilayah Studi">
            <el-input
              v-model="initial_study"
              type="textarea"
              :autosize="{ minRows: 3, maxRows: 5}"
            />
          </el-form-item>
          <el-form-item label="Batas Waktu Kajian">
            <span style="margin-right: 1em"><el-input-number v-model="num3" size="small" /> &nbsp;&nbsp;tahun</span>
            <span><el-input-number v-model="num3" size="small" /> &nbsp;&nbsp;bulan</span>
          </el-form-item>
        </el-form>
      </el-col>
      <el-col :span="16">
        <pie-form />
      </el-col>
    </el-row>
    <div style="text-align: right; margin-top:2em;">
      <el-button

        type="success"
        icon="el-icon-check"
        style="margin-bottom: 10px;"
      >
        Simpan Perubahan
      </el-button>
    </div>
  </div>
</template>
<script>
import PieForm from './PieForm.vue';
export default {
  name: 'DampakHipotetikDetailForm',
  components: {
    PieForm,

  },
  data() {
    return {
      options: [],
      is_managed: false,
      editedItem: { },
      initial_study: '',
      change_type: '',
    };
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
