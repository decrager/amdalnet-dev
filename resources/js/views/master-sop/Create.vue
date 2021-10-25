<template>
  <div class="form-container" style="padding: 24px">
    <el-form
      ref="categoryForm"
      :model="currentLpjp"
      label-position="top"
      label-width="200px"
      style="max-width: 100%"
    >
      <el-row :gutter="32">
        <el-col :span="12">
          <el-form-item label="Kode" prop="code">
            <el-input v-model="currentSop.code" placeholder="Kode" />
          </el-form-item>
        </el-col>
        <el-col :span="12">
          <el-form-item label="Tanggal Berlaku" prop="effectiveDate">
            <el-date-picker
                  v-model="currentSop.effective_date"
                  type="date"
                  placeholder="yyyy-MM-dd"
                  value-format="yyyy-MM-dd"
                  style="width: 100%"
                />
          </el-form-item>
        </el-col>
      </el-row>
      <el-row :gutter="32">
        <el-col :span="12">
          <el-form-item label="Nama SOP" prop="name">
            <el-input v-model="currentSop.name" placeholder="Nama SOP" />
          </el-form-item>
        </el-col>
      </el-row>
      <el-row :gutter="32">
        <el-col :span="12">
          <el-form-item label="Tahap Kegiatan" prop="projectStage">
            <el-select
              v-model="projectStage"
              placeholder="Tahap Kegiatan"
              clearable
              class="filter-item"
              style="width: 100%"
              @change="changeProjectStage($event)"
            >
              <el-option
                v-for="item in projectStageOptions"
                :key="item.value"
                :label="item.label"
                :value="item.value"
              />
            </el-select>
          </el-form-item>
        </el-col>
        <el-col :span="12">
          <el-form-item label="Komponen Kegiatan" prop="component">
            <el-select
              v-model="currentSop.id_component"
              placeholder="Komponent Kegiatan"
              clearable
              class="filter-item"
              style="width: 100%"
            >
              <el-option
                v-for="item in componentOptions"
                :key="item.value"
                :label="item.label"
                :value="item.value"
              />
            </el-select>
          </el-form-item>
        </el-col>
      </el-row>
      <el-row :gutter="32">
        <el-col :span="12">
          <el-form-item label="Jenis Komponen" prop="componentType">
            <el-select
              v-model="component"
              placeholder="Jenis Komponen"
              clearable
              class="filter-item"
              style="width: 100%"
              @change="changeComponentType($event)"
            >
              <el-option
                v-for="item in componentTypeOptions"
                :key="item.value"
                :label="item.label"
                :value="item.value"
              />
            </el-select>
          </el-form-item>
        </el-col>
        <el-col :span="12">
          <el-form-item label="Komponen Lingkungan" prop="ronaAwal">
            <el-select
              v-model="currentSop.id_rona_awal"
              placeholder="Komponen Lingkungan"
              clearable
              class="filter-item"
              style="width: 100%"
            >
              <el-option
                v-for="item in ronaAwalOptions"
                :key="item.value"
                :label="item.label"
                :value="item.value"
              />
            </el-select>
          </el-form-item>
        </el-col>
        <el-col :span="12">
          <el-form-item label="Komponen Dampak" prop="impactComponent">
            <el-select
              v-model="currentSop.impact"
              placeholder="Komponen Dampak"
              clearable
              class="filter-item"
              style="width: 100%"
            >
              <el-option
                v-for="item in impactOptions"
                :key="item.value"
                :label="item.label"
                :value="item.value"
              />
            </el-select>
          </el-form-item>
          <el-form-item label="Dampak Lainnya" prop="otherImpact">
            <el-input
              v-model="currentSop.other_impact"
              placeholder="Dampak Lainnya"
              :disabled="true"
            />
          </el-form-item>
        </el-col>
      </el-row>
      <el-row :gutter="32">
        <el-col :span="12">
          <el-form-item label="Besaran Dampak" prop="impactQuantity">
            <el-input
              v-model="currentSop.impact_quantity"
              placeholder="Besaran Dampak"
            />
          </el-form-item>
        </el-col>
      </el-row>
      <el-row :gutter="32">
        <el-col :span="12" :xs="24">
          <el-form-item
            prop="mgmtForm"
            style="margin-bottom: 30px"
            label="Bentuk Pengelolaan"
          >
            <tinymce
              ref="editor"
              v-model="currentSop.mgmt_form"
              :height="200"
            />
          </el-form-item>
        </el-col>
      </el-row>
      <el-row :gutter="32">
        <el-col :span="12">
          <el-form-item label="Periode Pengelolaan" prop="mgmtPeriod">
            <el-input
              v-model="currentSop.mgmt_period"
              placeholder="Periode Pengelolaan"
            />
          </el-form-item>
        </el-col>
      </el-row>
      <el-row :gutter="32">
        <el-col :span="12" :xs="24">
          <el-form-item
            prop="monitoringForm"
            style="margin-bottom: 30px"
            label="Bentuk Pengawasan"
          >
            <tinymce
              ref="editor"
              v-model="currentSop.monitoring_form"
              :height="200"
            />
          </el-form-item>
        </el-col>
      </el-row>
      <el-row :gutter="32">
        <el-col :span="12">
          <div>Periode Pemantauan</div>
          <div>
            <el-radio v-model="monitoringPeriod" label="Default">Default</el-radio>
          </div>
          <el-form-item label="Waktu Pengawasan" prop="monitoringTime">
            <el-input
              v-model="currentSop.monitoring_time"
              placeholder="Waktu Pengawasan"
            />
          </el-form-item>
          <el-form-item label="Frekwensi Pengawasan" prop="monitoringFreq">
            <el-input
              v-model="currentSop.monitoring_freq"
              placeholder="Frekwensi Pengawasan"
            />
          </el-form-item>
          <el-form-item label="Tanggal Pengawasan" prop="monitoringDateField">
            <el-input
              v-model="currentSop.monitoring_date_field"
              placeholder="Tanggal Pengawasan"
            />
          </el-form-item>
          <div>
            <el-radio v-model="monitoringPeriod" label="Lainnya">Lainnya</el-radio>
          </div>
        </el-col>
      </el-row>
      <el-row :gutter="32">
        <el-col :span="12">
          <el-form-item label="Periode Pengawasan" prop="monitoringPeriod">
            <el-input
              v-model="currentSop.monitoring_period"
              placeholder="Periode Pengawasan"
            />
          </el-form-item>
        </el-col>
      </el-row>
    </el-form>
    <div slot="footer" class="dialog-footer">
      <el-button @click="handleCancel()"> Cancel </el-button>
      <el-button type="primary" @click="handleSubmit()"> Confirm </el-button>
    </div>
  </div>
</template>

<script>
import Tinymce from '@/components/Tinymce';
import Resource from '@/api/resource';
const sopResource = new Resource('sops');
const projectStageResource = new Resource('project-stages');
const componentResource = new Resource('components');
const componentTypeResource = new Resource('component-types');
const ronaAwalResource = new Resource('rona-awals');

export default {
  name: 'CreateSop',
  components: { Tinymce },
  props: {
    sop: {
      type: Object,
      default: null,
    },
  },
  data() {
    return {
      currentSop: {},
      projectStageOptions: [],
      componentOptions: [],
      componentTypeOptions: [],
      ronaAwalOptions: [],
      impactOptions: [
        { value: 'Penurunan', label: 'Penurunan' },
        { value: 'Peningkatan', label: 'Peningkatan' },
        { value: 'Perubahan', label: 'Perubahan' },
        { value: 'Lainnya', label: 'Lainnya' },
      ],
    };
  },
  mounted() {
    if (this.$route.params.sop) {
      this.currentSop = this.$route.params.sop;
    }
    this.getProjectStages();
    this.getComponentTypes();
  },
  methods: {
    async getProjectStages() {
      const { data } = await projectStageResource.list({});
      this.projectStageOptions = data.map((i) => {
        return { value: i.id, label: i.name };
      });
    },
    async getComponentTypes() {
      const { data } = await componentTypeResource.list({});
      this.componentTypeOptions = data.map((i) => {
        return { value: i.id, label: i.name };
      });
    },
    async changeProjectStage(value) {
      this.getComponents(value);
    },
    async changeComponentType(value) {
      this.getRonaAwal(value);
    },
    async getComponents(idProjectStage) {
      const { data } = await componentResource.list({ idProjectStage });
      this.componentOptions = data.map((i) => {
        return { value: i.id, label: i.name };
      });
    },
    async getRonaAwal(idComponentType) {
      const { data } = await ronaAwalResource.list({ idComponentType });
      this.ronaAwalOptions = data.map((i) => {
        return { value: i.id, label: i.name };
      });
    },
    handleCancel() {
      this.$router.push('/master/sop');
    },
    handleSubmit() {
      console.log('Masuk');
      // make form data because we got file
      const formData = new FormData();
      // eslint-disable-next-line no-undef
      _.each(this.currentSop, (value, key) => {
        formData.append(key, value);
      });
      
      if (this.currentSop.id !== undefined) {
        sopResource
          .updateMultipart(this.currentSop.id, formData)
          .then((response) => {
            this.$message({
              type: 'success',
              message: 'SOP Details has been updated successfully',
              duration: 5 * 1000,
            });
            this.$router.push('/master/sop');
          })
          .catch((error) => {
            console.log(error);
          });
      } else {
        sopResource
          .store(formData)
          .then((response) => {
            this.$message({
              message:
                'New SOP ' +
                this.currentSop.name +
                ' has been created successfully.',
              type: 'success',
              duration: 5 * 1000,
            });
            this.currentSop = {};
            this.$router.push('/master/sop');
          })
          .catch((error) => {
            console.log(error);
          });
      }
    },
  },
};
</script>
