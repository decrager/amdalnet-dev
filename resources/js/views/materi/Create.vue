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
                  <el-form-item label="Judul Materi">
                    <el-input type="text" placeholder="Judul Materi" />
                  </el-form-item>
                </el-row>
              </el-form>
              <el-form>
                <el-row>
                  <el-form-item label="Deskripsi">
                    <el-input type="text" placeholder="Deskripsi" />
                  </el-form-item>
                </el-row>
              </el-form>
              <el-form>
                <el-row>
                  <el-form-item label="Tanggal Terbit">
                    <el-input type="date" placeholder="Tanggal Terbit" style="width:50%" />
                  </el-form-item>
                </el-row>
              </el-form>
              <el-form>
                <el-row>
                  <el-form-item label="Upload Dokumen">
                    <el-input type="file" placeholder="Upload Dokumen" />
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
  name: 'CreateParam',
  props: {},
  data() {
    return {
      date: '',
    };
  },
  mounted() {},
  methods: {
    handleCancel() {
      this.$router.push({
        name: 'materi',
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
