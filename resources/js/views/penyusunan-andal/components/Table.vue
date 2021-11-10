<template>
  <div>
    <el-table
      v-loading="loading"
      :data="list"
      fit
      border
      highlight-current-row
      :header-cell-style="{
        background: '#cdf4b5',
        color: 'black',
        textAlign: 'center',
        border: '0px',
      }"
      style="font-size: 12px"
    >
      <el-table-column label="Dampak Penting Hipotetik">
        <template slot-scope="scope">
          <span
            :class="{ 'row-title': Boolean(scope.row.komponen == undefined) }"
          >
            {{ scope.row.hipotetik }}
          </span>
        </template>
      </el-table-column>

      <el-table-column label="Komponen Lingkungan">
        <template slot-scope="scope">
          <span>{{ scope.row.komponen ? scope.row.komponen : '' }}</span>
        </template>
      </el-table-column>

      <el-table-column label="Rona Awal Lingkungan Hidup">
        <template slot-scope="scope">
          <span>{{ scope.row.ronaAwal ? scope.row.ronaAwal : '' }}</span>
        </template>
      </el-table-column>

      <el-table-column label="Hasil Prakiraan Dampak">
        <el-table-column label="Besaran Dampak">
          <template slot-scope="scope">
            <span>{{
              scope.row.besaranDampak ? scope.row.besaranDampak : ''
            }}</span>
          </template>
        </el-table-column>

        <el-table-column label="Sifat Penting">
          <template slot-scope="scope">
            <span>{{
              scope.row.sifatPenting ? scope.row.sifatPenting : ''
            }}</span>
          </template>
        </el-table-column>
      </el-table-column>

      <el-table-column label="Hasil Evaluasi Dampak">
        <template slot-scope="scope">
          <span>{{ scope.row.hasilEvaluasi }}</span>
        </template>
      </el-table-column>

      <el-table-column label="Tanggapan" align="center">
        <template
          v-if="!Boolean(scope.row.komponen == undefined)"
          slot-scope="scope"
        >
          <el-button
            type="primary"
            size="small"
            @click.prevent="showFormTanggapan(scope.row.$index)"
          >
            Tanggapan
          </el-button>
        </template>
      </el-table-column>
    </el-table>
    <TanggapanDialog
      :tanggapan="tanggapan"
      :show="show"
      @handleSubmitTanggapan="handleSubmitTanggapan"
      @handleCancelTanggapan="handleCancelTanggapan"
    />
  </div>
</template>

<script>
import TanggapanDialog from '@/views/penyusunan-andal/components/TanggapanDialog.vue';

export default {
  name: 'DumpTable',
  components: {
    TanggapanDialog,
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
      tanggapan: '',
      show: false,
      selectedTanggapanInd: null,
    };
  },
  methods: {
    handleEditForm(id) {
      this.$emit('handleEditForm', id);
    },
    handleDelete(id, nama) {
      this.$emit('handleDelete', { id, nama });
    },
    showFormTanggapan(ind) {
      this.selectedTanggapanInd = ind;
      this.show = true;
    },
    handleSubmitTanggapan() {},
    handleCancelTanggapan() {
      this.show = false;
      this.selectedTanggapanInd = null;
    },
  },
};
</script>

<style scoped>
.row-title {
  font-weight: bold;
}
</style>
