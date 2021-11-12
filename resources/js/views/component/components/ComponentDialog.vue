<template>
  <el-dialog :title="'Buat Komponen'" :visible.sync="show" :close-on-click-modal="false" :show-close="false">
    <div class="form-container">
      <el-form ref="categoryForm" :model="component">
        <el-row>
          <el-form-item label="Tahap Kegiatan" prop="tahapKegiatan">
            <el-select
              v-model="component.id_project_stage"
              placeholder="Select"
              style="width: 100%"
            >
              <el-option
                v-for="item in options"
                :key="item.value"
                :label="item.label"
                :value="item.value"
              />
            </el-select>
          </el-form-item>
        </el-row>
        <el-row>
          <el-form-item label="Komponen Kegiatan" prop="komponenKegiatan">
            <el-input v-model="component.name" type="textarea" />
          </el-form-item>
        </el-row>
      </el-form>
      <div slot="footer" class="dialog-footer">
        <el-button @click="handleCancelComponent()"> Cancel </el-button>
        <el-button type="primary" @click="handleSubmitComponent()"> Confirm </el-button>
      </div>
    </div>
  </el-dialog>
</template>

<script>
export default {
  name: 'ComponentDialog',
  props: {
    component: {
      type: Object,
      default: () => {},
    },
    show: Boolean,
    options: {
      type: Array,
      default: () => [],
    },
  },
  data(){
    return {
      fileName: 'File Not Found',
    };
  },
  methods: {
    handleSubmitComponent() {
      this.$emit('handleSubmitComponent');
    },
    handleCancelComponent() {
      this.$emit('handleCancelComponent');
    },
  },
};
</script>
