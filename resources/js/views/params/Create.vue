<template>
  <div class="app-container" style="padding: 24px">
    <el-card>
      <el-row :gutter="20">
        <el-col :span="14">
          <div class="form-container">
            <el-form ref="paramsForm">
              <el-row>
                <el-form-item label="Nama Parameter" prop="parameter">
                  <el-input type="text" placeholder="Nama Parameter" />
                </el-form-item>
              </el-row>
            </el-form>
            <el-form ref="paramsForm">
              <el-row>
                <el-form-item label="Judul" prop="judul">
                  <el-input type="text" placeholder="Judul" />
                </el-form-item>
              </el-row>
            </el-form>
            <el-form ref="paramsForm">
              <el-row>
                <el-form-item label="Nilai" prop="nilai">
                  <el-input type="text" placeholder="Nilai" />
                </el-form-item>
              </el-row>
            </el-form>
            <el-form ref="paramsForm">
              <el-row>
                <el-form-item label="Value Berupa Angka" prop="value" />
                <el-form-item label="">
                  <el-radio v-model="radio" label="1">Ya</el-radio>
                  <el-radio v-model="radio" label="2">Tidak</el-radio>
                </el-form-item>
              </el-row>
            </el-form>
          </div>
        </el-col>
      </el-row>
      <el-row>
        <el-col :span="18">
          <el-button
            class="filter-item"
            type=""
            icon="el-icon-plus"
          >
            {{ 'Tambah' }}
          </el-button>
          <el-table
            :data="tableData"
            style="width: 100%"
            :header-cell-style="{ background: '#3AB06F', color: 'white', border:'0' }"
          >
            <el-table-column
              prop="no"
              label="No"
              width="90px"
            />
            <el-table-column
              prop="name"
              label="Nama Parameter"
            />
            <el-table-column
              prop="judul"
              label="Judul"
            />
            <el-table-column
              prop="value"
              label="Value"
            />
            <el-table-column
              prop="aksi"
              label="Aksi"
            >
              <el-button
                type="text"
                href="#"
                icon="el-icon-edit"
                class="edit"
              />
              <el-button
                type="text"
                href="#"
                icon="el-icon-delete"
                class="delete"
              />
            </el-table-column>
          </el-table>
        </el-col>
        <el-col :span="18" class="parentButton">
          <el-button
            class="batal"
            type=""
            @click="handleCancle"
          >
            {{ 'Batalkan' }}
          </el-button>
        </el-col>

      </el-row>
    </el-card>
  </div>
</template>

<script>
export default {
  name: 'SopTable',
  filters: {
    statusFilter(status) {
      const statusMap = {
        Aktif: 'success',
        NonAktif: 'danger',
      };
      return statusMap[status];
    },
  },
  props: {
    list: {
      type: Array,
      default: () => [],
    },
    loading: Boolean,
  },
  data() {
    return {
      radio: '1',
      tableData: [{
        no: 1,
        nilai: 1,
        name: 'Tom',
        judul: 'No. 189, Grove St, Los Angeles',
        value: 20,
        aksi: 'ok',
      }],
    };
  },
  methods: {
    handleEditForm(id) {
      this.$emit('handleEditForm', id);
    },
    handleDelete(id, nama) {
      this.$emit('handleDelete', { id, nama });
    },
    handleCancle() {
      this.$router.push('/params');
    },
  },
};
</script>
<style scoped>
  .el-form-item{margin-bottom: 0 !important;}
  .edit{background-color: #f19e02; padding: 0.5rem; display: inline-block; color: #fff;}
  .delete{background-color: #f50103; padding: 0.5rem; display: inline-block; color: #fff;}
  .filter-item{float: right;margin-bottom: 1rem;margin-top: 2rem;background-color: #3AB06F; color: #fff; font-weight: bold;}
  .batal{background-color: gray; color:#fff;}
  .parentButton{justify-content: right;display: flex;margin-top: 1rem;}
</style>
