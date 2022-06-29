<template>
  <div style="margin-bottom: 15px">
    <div style="text-align: right; margin-bottom: 15px">
      <el-button
        :loading="loadingSubmit"
        type="primary"
        @click="handleGenerate"
      >
        Generate Bagan
      </el-button>
    </div>
    <el-table
      v-loading="loading"
      :data="list"
      fit
      :header-cell-style="{ background: '#3AB06F', color: 'white' }"
    >
      <el-table-column align="center" label="No" width="50px">
        <template slot-scope="scope">
          <span>{{ scope.$index + 1 }}</span>
        </template>
      </el-table-column>

      <el-table-column align="center" label="Dampak Primer">
        <template slot-scope="scope">
          <el-card
            v-if="scope.row.primer.id"
            style="cursor: pointer"
            class="dampak-card"
            :data-id="scope.row.primer.id"
            :class="{
              'selected-card': selectedDampak.id === scope.row.primer.id,
              'relation-card': isRelation(scope.row.primer.id, 'primer'),
            }"
            @click.native="selectCard(scope.row.primer.id, 'primer')"
          >
            {{ scope.row.primer.name }}
          </el-card>
        </template>
      </el-table-column>

      <el-table-column align="center" label="Dampak Sekunder">
        <template slot-scope="scope">
          <el-card
            v-if="scope.row.sekunder.id"
            style="cursor: pointer"
            class="dampak-card"
            :data-id="scope.row.sekunder.id"
            :class="{
              'selected-card': selectedDampak.id === scope.row.sekunder.id,
              'relation-card': isRelation(scope.row.sekunder.id, 'sekunder'),
            }"
            @click.native="selectCard(scope.row.sekunder.id, 'sekunder')"
          >
            {{ scope.row.sekunder.name }}
          </el-card>
        </template>
      </el-table-column>

      <el-table-column align="center" label="Dampak Tersier">
        <template slot-scope="scope">
          <el-card
            v-if="scope.row.tersier.id"
            style="cursor: pointer"
            class="dampak-card"
            :data-id="scope.row.tersier.id"
            :class="{
              'selected-card': selectedDampak.id === scope.row.tersier.id,
              'relation-card': isRelation(scope.row.tersier.id, 'tersier'),
            }"
            @click.native="selectCard(scope.row.tersier.id, 'tersier')"
          >
            {{ scope.row.tersier.name }}
          </el-card>
        </template>
      </el-table-column>
    </el-table>
  </div>
</template>

<script>
import axios from 'axios';

export default {
  name: 'BaganTable',
  data() {
    return {
      list: [],
      realData: [],
      loading: false,
      loadingSubmit: false,
      selectedDampak: {
        id: null,
        type: null,
      },
      deletedRelation: [],
      addedRelation: [],
    };
  },
  created() {
    this.getData();
  },
  methods: {
    async getData() {
      this.loading = true;
      this.list = [];
      const data = await axios.get(
        `/api/bagan-alir-dampak-penting/table/${this.$route.params.id}`
      );
      this.realData = data.data;

      let idx = 0;
      // eslint-disable-next-line no-constant-condition
      while (true) {
        let emptyIdx = 0;
        const primer = {};
        const sekunder = {};
        const tersier = {};

        if (typeof this.realData.primer[idx] !== 'undefined') {
          primer.id = this.realData.primer[idx].id;
          primer.name = this.realData.primer[idx].dampak;
        } else {
          emptyIdx++;
        }

        if (typeof this.realData.sekunder[idx] !== 'undefined') {
          sekunder.id = this.realData.sekunder[idx].id;
          sekunder.name = this.realData.sekunder[idx].dampak;
        } else {
          emptyIdx++;
        }

        if (typeof this.realData.tersier[idx] !== 'undefined') {
          tersier.id = this.realData.tersier[idx].id;
          tersier.name = this.realData.tersier[idx].dampak;
        } else {
          emptyIdx++;
        }

        if (emptyIdx === 3) {
          break;
        }

        this.list.push({
          primer,
          sekunder,
          tersier,
        });

        idx++;
      }

      this.loading = false;
    },
    selectCard(id, type) {
      if (id === this.selectedDampak.id) {
        this.selectedDampak = {
          id: null,
          type: null,
        };
      } else if (
        this.selectedDampak.id === null ||
        this.selectedDampak.type === type
      ) {
        this.selectedDampak = {
          id,
          type,
        };
      } else {
        // CASE 1: SELECT SEKUNDER TYPE AS A CHILD FOR PRIMER TYPE
        if (this.selectedDampak.type === 'primer' && type === 'sekunder') {
          this.addOrRemove(id, type, 'child');

          // CASE 2: SELECT PRIMER TYPE AS A PARENT FOR SEKUNDER TYPE
        } else if (
          this.selectedDampak.type === 'sekunder' &&
          type === 'primer'
        ) {
          this.addOrRemove(id, type, 'parent');

          // CASE 3: SELECT TERSIER TYPE AS A CHILD FOR SEKUNDER TYPE
        } else if (
          this.selectedDampak.type === 'sekunder' &&
          type === 'tersier'
        ) {
          this.addOrRemove(id, type, 'child');

          //   CASE 4: SELECT SEKUNDER TYPE AS A PARENT FOR TERSIER TYPE
        } else if (
          this.selectedDampak.type === 'tersier' &&
          type === 'sekunder'
        ) {
          this.addOrRemove(id, type, 'parent');
        } else {
          this.selectedDampak = {
            id,
            type,
          };
        }
      }
    },
    isRelation(id, type) {
      if (this.selectedDampak.id === null) {
        return false;
      }

      if (id !== this.selectedDampak.id) {
        if (type === 'primer') {
          const relation = this.realData.sekunder
            .filter((x) => {
              return Boolean(x.parents.find((y) => y.id === id));
            })
            .find((x) => x.id === this.selectedDampak.id);
          if (relation) {
            return true;
          }
        } else if (type === 'sekunder') {
          const childRelation = this.realData.sekunder
            .filter((x) => {
              return Boolean(
                x.parents.find((y) => y.id === this.selectedDampak.id)
              );
            })
            .find((x) => x.id === id);
          const parentRelation = this.realData.tersier
            .filter((x) => {
              return Boolean(x.parents.find((y) => y.id === id));
            })
            .find((x) => x.id === this.selectedDampak.id);

          return childRelation || parentRelation;
        } else if (type === 'tersier') {
          const relation = this.realData.tersier
            .filter((x) => {
              return Boolean(
                x.parents.find((y) => y.id === this.selectedDampak.id)
              );
            })
            .find((x) => x.id === id);

          if (relation) {
            return true;
          }
        }
      }

      return false;
    },
    addOrRemove(id, type, relationType) {
      const child = {
        id: relationType === 'child' ? id : this.selectedDampak.id,
        type: relationType === 'child' ? type : this.selectedDampak.type,
      };

      const parent = {
        id: relationType === 'parent' ? id : this.selectedDampak.id,
        type: relationType === 'parent' ? type : this.selectedDampak.type,
      };

      const relation = this.realData[child.type]
        .filter((x) => {
          return Boolean(x.parents.find((y) => y.id === parent.id));
        })
        .find((x) => x.id === child.id);
      if (relation) {
        // CHECK EXISTING ADDED DATA
        const isAddedBefore = this.addedRelation.find((x) => {
          return x.parentId === parent.id && x.childId === child.id;
        });
        if (isAddedBefore) {
          // REMOVE DATA FROM ADDED RELATION
          this.addedRelation = this.addedRelation.filter((x) => {
            return !(x.parentId === parent.id && x.childId === child.id);
          });
        }
        // CHECK IF DATA EXIST IN DATABASE
        const isInDatabase = relation.parents.find(
          (x) => x.real_id === parent.id
        );
        const idx = this.realData[child.type].findIndex(
          (x) => x.id === child.id
        );
        if (isInDatabase) {
          // ADD DATA TO DELETED RELATION
          this.deletedRelation.push({
            parentId: parent.id,
            childId: child.id,
          });
          // SET PARENTS TO NULL IN REAL DATA
          const parentIdx = this.realData[child.type][idx].parents.findIndex(
            (x) => x.id === parent.id
          );
          this.realData[child.type][idx].parents[parentIdx].id = null;
        } else {
          this.realData[child.type][idx].parents = this.realData[child.type][
            idx
          ].parents.filter((x) => x.id !== parent.id);
        }
      } else {
        //  CHECK EXISTING DELETED DATA
        const isDeletedBefore = this.deletedRelation.find((x) => {
          return x.parentId === parent.id && x.childId === child.id;
        });
        if (isDeletedBefore) {
          // REMOVE DATA FROM DELETED RELATION
          this.deletedRelation = this.deletedRelation.filter((x) => {
            return !(x.parentId === parent.id && x.childId === child.id);
          });
        }
        // CHECK IF DATA EXIST IN DATABASE
        const isInDatabase = this.realData[child.type]
          .filter((x) => {
            return Boolean(x.parents.find((y) => y.real_id === parent.id));
          })
          .find((x) => x.id === child.id);
        if (!isInDatabase) {
          // ADD DATA TO ADDED RELATION
          this.addedRelation.push({
            parentId: parent.id,
            childId: child.id,
          });
          // ADD PARENTS TO REAL DATA
          const idx = this.realData[child.type].findIndex(
            (x) => x.id === child.id
          );
          this.realData[child.type][idx].parents.push({
            id: parent.id,
            real_id: null,
          });
        } else {
          const idx = this.realData[child.type].findIndex(
            (x) => x.id === child.id
          );
          const parentIdx = this.realData[child.type][idx].parents.findIndex(
            (x) => x.real_id === parent.id
          );
          this.realData[child.type][idx].parents[parentIdx].id = parent.id;
        }
      }
    },
    async handleGenerate() {
      this.loadingSubmit = true;
      await axios.post(
        `/api/bagan-alir-dampak-penting/table/${this.$route.params.id}`,
        {
          addedRelation: this.addedRelation,
          deletedRelation: this.deletedRelation,
        }
      );
      this.addedRelation = [];
      this.deletedRelation = [];
      this.loadingSubmit = false;
      this.getData();
      this.$emit('refreshBagan');
    },
  },
};
</script>

<style>
.selected-card {
  background-color: #47c36a;
  color: white;
}
.relation-card {
  background-color: #ffba00;
}
.dampak-card .el-card__body {
  padding: 8px;
}
</style>
