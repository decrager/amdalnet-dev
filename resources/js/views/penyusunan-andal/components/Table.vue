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
            :class="{ 'row-title': Boolean(scope.row.component == undefined) }"
          >
            {{ scope.row.name }}
          </span>
        </template>
      </el-table-column>

      <el-table-column label="Komponen Lingkungan">
        <template slot-scope="scope">
          <span>{{ scope.row.component ? scope.row.component : '' }}</span>
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
            <el-input
              v-if="scope.row.component != undefined"
              v-model="scope.row.impact_size"
            />
            <span v-else>{{ '' }}</span>
          </template>
        </el-table-column>

        <el-table-column label="Sifat Penting">
          <template slot-scope="scope">
            <el-input
              v-if="scope.row.component != undefined"
              v-model="scope.row.important_trait"
            />
            <span v-else>{{ '' }}</span>
          </template>
        </el-table-column>
      </el-table-column>

      <el-table-column label="Hasil Evaluasi Dampak">
        <template slot-scope="scope">
          <el-input
            v-if="scope.row.component != undefined"
            v-model="scope.row.impact_eval_result"
          />
          <span v-else>{{ '' }}</span>
        </template>
      </el-table-column>

      <el-table-column label="Tanggapan" align="center">
        <template
          v-if="!Boolean(scope.row.component == undefined)"
          slot-scope="scope"
        >
          <el-button
            type="primary"
            size="small"
            @click.prevent="showFormTanggapan(scope.row.id)"
          >
            Tanggapan
          </el-button>
        </template>
      </el-table-column>
    </el-table>
    <TanggapanDialog
      :tanggapan="tanggapan"
      :show="show"
      @handleSubmitTanggapan="handleSubmitTanggapan($event)"
      @handleCancelTanggapan="handleCancelTanggapan"
    />
  </div>
</template>

<script>
import TanggapanDialog from '@/views/penyusunan-andal/components/TanggapanDialog.vue';

export default {
  name: 'PenyusunanAndalTable',
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
      selectedTanggapanid: null,
    };
  },
  methods: {
    handleEditForm(id) {
      this.$emit('handleEditForm', id);
    },
    handleDelete(id, nama) {
      this.$emit('handleDelete', { id, nama });
    },
    showFormTanggapan(id) {
      this.selectedTanggapanid = id;
      this.tanggapan = this.list.find((li) => li.id === id).response;
      this.show = true;
    },
    handleSubmitTanggapan({ tanggapan }) {
      const indexList = this.list.findIndex(
        (li) => li.id === this.selectedTanggapanid
      );
      this.list[indexList].response = tanggapan;
      this.$emit('handleSubmitTanggapan');
      this.show = false;
      this.selectedTanggapanid = null;
      this.tanggapan = '';
    },
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
