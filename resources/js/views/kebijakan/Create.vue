<template>
  <div class="app-container" style="padding: 24px">
    <el-form
      ref="categoryForm"
      :model="currentParam"
      label-position="top"
      label-width="200px"
      style="max-width: 100%"
    >
      <el-card>
        <el-row :gutter="20">
          <el-col :span="14">
            <div class="form-container">
              <el-form>
                <el-row>
                  <el-form-item label="Jenis Peraturan">
                    <el-select
                      v-model="value"
                      placeholder="Jenis Peraturan"
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
              </el-form>
              <el-form>
                <el-row>
                  <el-form-item label="Kebijakan PPU">
                    <el-input type="text" placeholder="Kebijakan PPU" />
                  </el-form-item>
                </el-row>
              </el-form>
              <el-form>
                <el-row>
                  <el-form-item label="Jenis PPU">
                    <el-input type="text" placeholder="Jenis PPU" />
                  </el-form-item>
                </el-row>
              </el-form>
              <el-form>
                <el-row>
                  <el-form-item label="Bidang Kegiatan">
                    <el-input type="text" placeholder="Bidang Kegiatan" />
                  </el-form-item>
                </el-row>
              </el-form>
              <el-form>
                <el-row>
                  <el-form-item label="Tentang">
                    <el-input type="textarea" placeholder="Tentang" />
                  </el-form-item>
                </el-row>
              </el-form>
              <!-- <el-form>
                <el-form-item label="Taggal Terbit">
                  <el-date-picker
                    v-model="date"
                    type="datetime"
                    placeholder="Tanggal"
                  />
                </el-form-item>
              </el-form> -->
              <el-form>
                <el-row>
                  <el-form-item label="Taggal Terbit">
                    <el-input type="date" placeholder="Taggal Terbit" />
                  </el-form-item>
                </el-row>
              </el-form>
              <el-form>
                <el-row>
                  <el-form-item label="Upload Document">
                    <el-input type="file" placeholder="Upload Document" />
                  </el-form-item>
                </el-row>
              </el-form>
              <div class="" style="margin-top: 0.5rem; text-align: right">
                <el-button type="danger" @click="handleCancel">Kembali</el-button>
                <el-button type="success">Simpan</el-button>
              </div>
            </div>
          </el-col>
        </el-row>
      </el-card>
    </el-form>
  </div>
</template>

<script>
// import Resource from '@/api/resource';
// const appParamResource = new Resource('app-params');

export default {
  name: 'AddKebijakan',
  props: {},
  data() {
    return {
      options: [
        {
          value: 'Option1',
          label: 'Option1',
        },
      ],
    };
  },
  mounted() {},
  methods: {
    handleCancel() {
      this.$router.push({
        name: 'kebijakan',
      });
    },
  },
};
</script>
<style scoped>
.el-form-item {
  margin-bottom: 0 !important;
}
.edit {
  background-color: #f19e02;
  padding: 0.5rem;
  display: inline-block;
  color: #fff;
}
.delete {
  background-color: #f50103;
  padding: 0.5rem;
  display: inline-block;
  color: #fff;
}
.filter-item {
  float: right;
  margin-bottom: 1rem;
  margin-top: 2rem;
  background-color: #3ab06f;
  color: #fff;
  font-weight: bold;
}
.batal {
  background-color: gray;
  color: #fff;
}
.parentButton {
  justify-content: right;
  display: flex;
  margin-top: 1rem;
}
</style>
