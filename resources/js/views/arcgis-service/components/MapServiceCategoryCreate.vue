<template>
  <el-dialog :title="'Tambah Map Service Kategori'" :visible.sync="showCategoryCreate" :close-on-click-modal="false" :show-close="false">
    <div class="form-container">
      <el-form ref="categoryForm" :model="componentCreate">
        <el-row>
          <el-form-item label="Provinsi" prop="province">
            <el-select
              v-model="componentCreate.id"
              filterable
              placeholder="Select"
              style="width: 100%"
            >
              <el-option
                v-for="item in provinces"
                :key="item.id"
                :label="item.name"
                :value="item.id"
              />
            </el-select>
          </el-form-item>
        </el-row>
        <el-row>
          <el-form-item label="Nama" prop="categoryName">
            <el-input v-model="componentCreate.category_name" type="text" />
          </el-form-item>
        </el-row>
        <el-row>
          <el-form-item label="Status" prop="active">
            <el-switch
              v-model="componentCreate.active"
              active-color="#13ce66"
              inactive-color="#ff4949"
              active-text="Aktif"
              inactive-text="Tidak Aktif"
              :active-value="1"
              :inactive-value="0"
            />
          </el-form-item>
        </el-row>
      </el-form>
      <div slot="footer" class="dialog-footer">
        <el-button @click="handleCancelComponent()"> Cancel </el-button>
        <el-button type="primary" @click="handleSubmitComponent()"> Save </el-button>
      </div>
    </div>
  </el-dialog>
</template>

<script>
export default {
  props: {
    componentCreate: {
      type: Object,
      default: () => {},
    },
    showCategoryCreate: Boolean,
    provinces: {
      type: Array,
      default: () => [],
    },
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
