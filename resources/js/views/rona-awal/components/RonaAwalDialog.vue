<template>
  <el-dialog :title="'Buat Rona Lingkungan'" :visible.sync="show" :close-on-click-modal="false" :show-close="false">
    <div class="form-container">
      <el-form ref="categoryForm" :model="rona">
        <el-row>
          <el-form-item label="Jenis Komponen" prop="jenisKomponen">
            <el-select
              v-model="rona.id_component_type"
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
          <el-form-item label="Komponen Lingkungan" prop="komponenLingkungan">
            <el-input v-model="rona.name" type="textarea" />
          </el-form-item>
        </el-row>
      </el-form>
      <div slot="footer" class="dialog-footer">
        <el-button @click="handleCancelRonaAwal()"> Cancel </el-button>
        <el-button type="primary" @click="handleSubmitRonaAwal()"> Confirm </el-button>
      </div>
    </div>
  </el-dialog>
</template>

<script>
export default {
  name: 'RonaAwalDialog',
  props: {
    rona: {
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
    handleSubmitRonaAwal() {
      this.$emit('handleSubmitRonaAwal');
    },
    handleCancelRonaAwal() {
      this.$emit('handleCancelRonaAwal');
    },
  },
};
</script>
