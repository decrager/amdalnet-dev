<template>
  <el-row v-if="isAndalNotEmpty" :gutter="24">
    <el-col :md="12" :sm="24">
      <div class="form-group">
        <label>Tahap</label>
        <el-input v-model="andal.stage" :readonly="true" />
      </div>
      <div class="form-group">
        <label>Dampak Penting Hipotetik</label>
        <el-input v-model="andal.dph" :readonly="true" />
      </div>
      <div class="form-group">
        <label>Komponen Rona Awal Lingkungan</label>
        <el-input v-model="andal.ronaAwal" :readonly="true" />
      </div>
      <!-- <div class="form-group">
        <label>Besaran Dampak</label>
        <el-input v-model="andal.impact_size" :readonly="!isFormulator" />
      </div> -->
      <div class="form-group">
        <label>Perubahan Kondisi Dengan dan Tanpa Rencana Kegiatan</label>
        <div class="wrapper-form">
          <div class="form-group">
            <label>1. Kondisi Saat Studi Dilakukan</label>
            <el-input
              v-model="andal.studies_condition"
              type="textarea"
              :rows="2"
              :readonly="!isFormulator"
            />
          </div>
          <div class="form-group">
            <label>
              2.A. Perkembangan Kondisi TANPA Adanya Rencana Kegiatan
            </label>
            <el-input
              v-model="andal.condition_dev_no_plan"
              type="textarea"
              :rows="2"
              :readonly="!isFormulator"
            />
          </div>
          <div class="form-group">
            <label>
              B. Perkembangan Kondisi DENGAN Adanya Rencana Kegiatan
            </label>
            <el-input
              v-model="andal.condition_dev_with_plan"
              type="textarea"
              :rows="2"
              :readonly="!isFormulator"
            />
          </div>
          <div class="form-group">
            <label> 3. Selisih Besaran Dampak</label>
            <el-input
              v-model="andal.impact_size_difference"
              type="textarea"
              :rows="2"
              :readonly="!isFormulator"
            />
          </div>
        </div>
      </div>
      <el-row :gutter="24">
        <el-col :md="12" :sm="24">
          <label>Kategori Dampak</label>
          <el-select
            v-model="andal.impact_type"
            placeholder="Pilih Jenis Dampak"
            :disabled="!isFormulator"
          >
            <el-option
              v-for="item in jenisDampak"
              :key="item.value"
              :label="item.label"
              :value="item.value"
            />
          </el-select>
        </el-col>
        <el-col :md="12" :sm="24">
          <label>Hasil Evaluasi Dampak</label>
          <el-select
            v-model="andal.impact_eval_result"
            placeholder="Pilih Hasil Evaluasi Dampak"
            :disabled="!isFormulator"
          >
            <el-option
              v-for="item in hasilEvaluasiDampak"
              :key="item.value"
              :label="item.label"
              :value="item.value"
            />
          </el-select>
        </el-col>
      </el-row>
    </el-col>
    <el-col :md="12" :sm="24">
      <label>Sifat Penting</label>
      <div class="wrapper-form">
        <div
          v-for="(it, index) in andal.important_trait"
          :key="it.id"
          class="form-group"
        >
          <el-row :gutter="24">
            <el-col :md="16" :sm="24">
              <label> {{ index + 1 }}. {{ it.description }} </label>
            </el-col>
            <el-col :md="8" :sm="24">
              <label>{{ index === 0 ? 'Pilih Sifat Penting' : ' ' }}</label>
            </el-col>
          </el-row>
          <el-row :gutter="24" style="display: flex; align-items: center">
            <el-col :md="16" :sm="24">
              <el-input
                v-model="andal.important_trait[index].desc"
                type="textarea"
                :rows="2"
                :readonly="!isFormulator"
              />
            </el-col>
            <el-col :md="8" :sm="24">
              <el-radio-group
                v-model="andal.important_trait[index].important_trait"
                style="display: grid; grid-template-columns: 0.5fr 0.5fr"
              >
                <el-radio label="+P" :disabled="!isFormulator">+P</el-radio>
                <el-radio label="-P" :disabled="!isFormulator">-P</el-radio>
                <el-radio label="+TP" :disabled="!isFormulator">+TP</el-radio>
                <el-radio label="-TP" :disabled="!isFormulator">-TP</el-radio>
              </el-radio-group>
            </el-col>
          </el-row>
        </div>
      </div>
    </el-col>
    <el-col
      v-if="isFormulator"
      :md="24"
      :sm="24"
      style="text-align: right; margin-top: 10px"
    >
      <el-button @click="reset">Reset</el-button>
      <el-button type="primary" :loading="loadingsubmit" @click="handleSubmit">
        Simpan
      </el-button>
    </el-col>
  </el-row>
</template>

<script>
export default {
  name: 'FormDetail',
  props: {
    andal: {
      type: Object,
      default: () => {},
    },
    loadingsubmit: Boolean,
  },
  data() {
    return {
      hasilEvaluasiDampak: [
        {
          label: 'Positif',
          value: 'Positif',
        },
        {
          label: 'Negatif',
          value: 'Negatif',
        },
      ],
      jenisDampak: [
        {
          label: 'Primer',
          value: 'Primer',
        },
        {
          label: 'Sekunder',
          value: 'Sekunder',
        },
        {
          label: 'Tersier',
          value: 'Tersier',
        },
      ],
    };
  },
  computed: {
    isAndalNotEmpty() {
      if (this.andal) {
        return true;
      }

      return false;
    },
    isFormulator() {
      return this.$store.getters.roles.includes('formulator');
    },
  },
  methods: {
    reset() {
      this.andal.impact_size = null;
      this.andal.studies_condition = null;
      this.andal.condition_dev_no_plan = null;
      this.andal.condition_dev_with_plan = null;
      this.andal.impact_size_difference = null;
      this.andal.impact_type = null;
      this.andal.impact_eval_result = null;

      for (let i = 0; i < this.andal.important_trait.length; i++) {
        this.andal.important_trait[i].desc = null;
        this.andal.important_trait[i].important_trait = null;
      }
    },
    handleSubmit() {
      this.$emit('handleSubmit');
    },
  },
};
</script>

<style scoped>
.form-group {
  margin-bottom: 10px;
}
.wrapper-form {
  border: 1px solid black;
  padding: 8px;
}
</style>
