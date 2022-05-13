<template>
  <div class="ph-spt">
    <div class="boxed">

      <el-table
        v-if="data"
        :ref="tablePHSPT"
        :data="data"
        highlight-current-row
        max-height="250"
        style="width: 100%"
        @current-change="onSelectRow"
      >
        <el-table-column
          type="index"
          width="50"
        />
        <el-table-column
          prop="name"
          label="Nama"
        />
        <el-table-column
          align="center"
          prop="community_type"
          label="Kelompok"
        />
        <el-table-column
          align="center"
          label="Relevan"
        >
          <template slot-scope="scope">
            <i v-if="scope.row.is_relevant === true" class="el-icon-check" />
            <i v-else class="el-icon-close" />
          </template>
        </el-table-column>
      </el-table>
    </div>
    <div v-if="selected !== null" class="boxed">
      <el-row :gutter="20">
        <el-col :span="6" style="text-align:center;">
          <div style="width: 150px; height: 150px; margin: 1em auto;">
            <el-image
              style="width: 150px; height: 150px; margin: 1em auto;"
              :src="selected.photo_filepath"
              fit="contain"
            >
              <div slot="error" class="image-slot">
                <i class="el-icon-user-solid" style="font-size: 500%; line-height: 160%;" />
              </div>
            </el-image>
          </div>
        </el-col>
        <el-col :span="18">
          <p class="header">Nama</p>
          <p class="data">{{ selected.name }}</p>
          <p class="header">No. Telepon</p>
          <p class="data">{{ selected.phone }}</p>
          <p class="header">Alamat email</p>
          <p class="data">{{ selected.email }}</p>
          <div class="entry">
            <div style="margin-bottom:3.5em;">
              <el-rate
                v-model="selected.rating"
                disabled
                :colors="['#216221', '#216221', '#216221']"
              />
            </div>

            <p class="header">Kekhawatiran</p>
            <p class="data">{{ selected.concern }}</p>
            <p class="header">Harapan</p>
            <p class="data">{{ selected.expectation }}</p>

          </div>

        </el-col>
      </el-row>
    </div>
  </div>
</template>
<script>

import Resource from '@/api/resource';
const feedbackResource = new Resource('feedbacks');

export default {
  name: 'ProjectPublicFeedback',
  props: {
    id: {
      type: Number,
      default: 0,
    },
  },
  data(){
    return {
      data: [],
      selected: null,
    };
  },
  mounted(){
    this.getData();
  },
  methods: {
    async getData(){
      this.data = [];
      await feedbackResource.list({ announcement_id: this.id, deleted: false })
        .then((res) => {
          this.data = res.data;
        })
        .finally({

        });
    },
    onSelectRow(obj){
      // console.log('spt:', obj);
      this.selected = obj;
    },
  },
};
</script>
<style lang="scss">
.ph-spt {
  padding: 2em;
  .boxed {
    margin: 1em 0 2em;
    padding: 2em;
    border: 1px solid #e0e0e0;
    border-radius: 1em;

    &:last-child{
      margin-bottom: 0;
    }

    .entry {
      margin: 2em 0 0;
      .el-rate__item .el-rate__icon {
        font-size: 50px;
      }
    }
  }
}
</style>
