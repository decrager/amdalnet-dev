<template>
  <el-dialog :title="'Tambah Map Service'" :visible.sync="show" :close-on-click-modal="false" :show-close="false">
    <div class="form-container">
      <el-form ref="categoryForm" :model="component">
        <el-row>
          <el-form-item label="Kategori Peta" prop="mapCategory">
            <el-select
              v-model="component.id_category"
              filterable
              placeholder="Select"
              style="width: 100%"
            >
              <el-option
                v-for="item in category"
                :key="item.id"
                :label="item.category_name"
                :value="item.id"
              />
            </el-select>
          </el-form-item>
        </el-row>
        <el-row>
          <el-form-item label="Provinsi" prop="mapProvince">
            <el-select
              v-model="component.id_province"
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
          <el-form-item label="Nama" prop="name">
            <el-input v-model="component.name" type="text" />
          </el-form-item>
        </el-row>
        <el-row>
          <el-form-item label="URL Service" prop="urlService">
            <el-input v-model="component.url_service" type="text" />
          </el-form-item>
        </el-row>
        <el-row>
          <el-form-item label="Sumber Data" prop="sumberData">
            <el-input v-model="component.source" type="text" />
          </el-form-item>
        </el-row>
        <el-row>
          <el-form-item label="Organisasi / Instansi" prop="organization">
            <el-input v-model="component.organization" type="text" />
          </el-form-item>
        </el-row>
        <el-row>
          <el-form-item label="Status" prop="active">
            <el-switch
              v-model="component.active"
              active-color="#13ce66"
              inactive-color="#ff4949"
              active-text="Aktif"
              inactive-text="Tidak Aktif"
              :active-value="1"
              :inactive-value="0"
            />
          </el-form-item>
          <el-form-item label="Proxy" prop="isProxy">
            <el-switch
              v-model="component.is_proxy"
              active-color="#13ce66"
              inactive-color="#ff4949"
              active-text="Ya"
              inactive-text="Tidak"
              :active-value="1"
              :inactive-value="0"
            />
          </el-form-item>
        </el-row>
      </el-form>
      <div slot="footer" class="dialog-footer">
        <el-button type="warning" @click="handleCancelComponent()"> Tutup </el-button>
        <el-button type="primary" @click="handleSubmitComponent()"> Simpan </el-button>
      </div>
    </div>
  </el-dialog>
</template>

<script>
export default {
  props: {
    component: {
      type: Object,
      default: () => {},
    },
    show: Boolean,
    category: {
      type: Array,
      default: () => [],
    },
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
