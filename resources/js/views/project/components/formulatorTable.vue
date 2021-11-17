<template>
  <el-table
    :key="reload"
    v-loading="loading"
    :header-cell-style="{ background: '#099C4B', color: 'white' }"
    :data="list"
    border
    fit
    highlight-current-row
  >
    <el-table-column align="center" label="No." width="80">
      <template slot-scope="scope">
        <span>{{ scope.$index + 1 }}</span>
      </template>
    </el-table-column>

    <el-table-column align="center" label="Nama">
      <template slot-scope="scope">
        <el-select v-model="scope.row.name" filterable placeholder="Pilih" size="mini" @change="handleNameChange($event, scope.row)">
          <el-option
            v-for="item in getFormulators"
            :key="item.value"
            :label="item.label"
            :value="item.value"
          />
        </el-select>
      </template>
    </el-table-column>

    <el-table-column align="left" label="Nomor Registrasi">
      <template slot-scope="scope">
        {{ scope.row.reg_no }}
      </template>
    </el-table-column>

    <el-table-column align="left" label="Jabatan">
      <template slot-scope="scope">
        <el-select
          v-model="scope.row.position"
          placeholder="Pilih"
          style="width: 100%"
          size="mini"
        >
          <el-option
            v-for="item in getMembershipOption"
            :key="item.value.id"
            :label="item.label"
            :value="item.value"
          />
        </el-select>
      </template>
    </el-table-column>

    <el-table-column align="left" label="Nomor Sertifikat">
      <template slot-scope="scope">
        {{ scope.row.cert_no }}
      </template>
    </el-table-column>

    <el-table-column align="left" label="Status Keanggotaan">
      <template slot-scope="scope">
        {{ scope.row.membership_status }}
      </template>
    </el-table-column>
  </el-table>
</template>

<script>
import Resource from '@/api/resource';
const formulatorResource = new Resource('formulators');

export default {
  name: 'FormulatorTable',
  props: {
    list: {
      type: Array,
      default: () => [],
    },
    loading: Boolean,
  },
  data() {
    return {
      reload: 0,
    };
  },
  computed: {
    getMembershipOption(){
      return this.$store.getters.membershipOptions;
    },
    getFormulators(){
      return this.$store.getters.formulators;
    },
  },
  methods: {
    async handleNameChange(value, item){
      const formulator = await formulatorResource.get(value);
      item.reg_no = formulator.reg_no;
      item.cert_no = formulator.cert_no;
      item.membership_status = formulator.membership_status;

      this.reload++;
    },
    checkDocFile(idx) {
      const id = '#' + idx;
      document.querySelector(id).click();
    },
    checkDocFileSure(id, x) {
      this.list[x].file = document.querySelector('#' + id).files[0].name;
      this.list[x].fileDoc = document.querySelector('#' + id).files[0];

      console.log(this.list[x].fileDoc);
    },
  },
};
</script>
