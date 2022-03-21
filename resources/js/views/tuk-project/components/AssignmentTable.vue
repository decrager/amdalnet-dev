<template>
  <el-table
    v-loading="loading"
    :data="list"
    fit
    :header-cell-style="{ background: '#3AB06F', color: 'white' }"
  >
    <el-table-column align="center" label="No." width="54px">
      <template slot-scope="scope">
        {{ scope.$index + 1 }}
      </template>
    </el-table-column>

    <el-table-column align="center" label="Nama">
      <template slot-scope="scope">
        <el-select
          v-model="list[scope.$index].idType"
          placeholder="Pilihan"
          filterable
          @change="handleChangeName($event, scope.$index)"
        >
          <el-option
            v-for="item in members"
            :key="item.idType"
            :label="item.name"
            :value="item.idType"
          />
        </el-select>
        <small v-if="scope.row.error.name" style="color: red">
          Anggota Wajib Dipilih
        </small>
      </template>
    </el-table-column>

    <el-table-column align="center" label="NIK">
      <template slot-scope="scope">
        <span>{{ scope.row.nik }}</span>
      </template>
    </el-table-column>

    <el-table-column align="center" label="Jabatan">
      <template slot-scope="scope">
        <span>{{ scope.row.position }}</span>
      </template>
    </el-table-column>

    <el-table-column align="center" label="Instansi">
      <template slot-scope="scope">
        <span>{{ scope.row.institution }}</span>
      </template>
    </el-table-column>

    <el-table-column align="center" label="Peran">
      <template slot-scope="scope">
        <div
          style="
            display: flex;
            align-items: center;
            justify-content: space-between;
          "
        >
          <el-select
            v-if="scope.row.type !== null"
            v-model="list[scope.$index].role"
            placeholder="Select"
          >
            <el-option
              v-for="item in scope.row.type == 'tuk' ? roleTuk : roleSecretary"
              :key="item.value"
              :label="item.label"
              :value="item.value"
            />
          </el-select>
          <el-button
            type="text"
            icon="el-icon-close"
            @click="handleDelete(scope.row.num, scope.row.id)"
          />
        </div>
        <small v-if="scope.row.error.role" style="color: red">
          Peran Wajib Dipilih
        </small>
      </template>
    </el-table-column>
  </el-table>
</template>

<script>
export default {
  name: 'TukProjectTable',
  props: {
    list: {
      type: Array,
      default: () => [],
    },
    loading: Boolean,
    members: {
      type: Array,
      default: () => [],
    },
  },
  data() {
    return {
      roleTuk: [
        {
          label: 'Validator Substansi',
          value: 'valsub',
        },
        {
          label: 'Validator Administrasi',
          value: 'valadm',
        },
      ],
      roleSecretary: [
        {
          label: 'Penanggung Jawab Materi',
          value: 'pjm',
        },
        {
          label: 'Validator Substansi',
          value: 'valsub',
        },
        {
          label: 'Validator Administrasi',
          value: 'valadm',
        },
      ],
    };
  },
  methods: {
    handleChangeName(event, idx) {
      this.$emit('changeName', { event, idx });
    },
    handleDelete(num, id) {
      this.$emit('deleteMember', { num, id });
    },
  },
};
</script>
