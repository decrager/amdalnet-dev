<template>
  <div v-if="isShown" class="app-container context-bar-container">
    <el-collapse v-model="contextBar" class="collapse-context-bar">
      <el-collapse-item name="1" class="item-context-bar">
        <template slot="title">
          <div class="context-bar-header">{{ data.initiator_name || '*' }} : {{ data.project_title }}</div>
        </template>
        <el-row :gutter="20">
          <el-col :span="4">
            <div class="block" style="text-align: center;">
              <el-image
                style="width: 100%; height: 150px"
                :src="data.logo"
                fit="contain"
              >
                <div slot="error" class="image-slot">
                  <i class="el-icon-picture-outline" style="font-size: 500%; line-height: 230%;" />
                </div>
              </el-image>
            </div>
          </el-col>
          <el-col :span="14">
            <div class="context-bar-item project">
              <p>{{ data.project_title }}</p>
              <p>{{ data.address }}</p>
            </div>
            <div class="context-bar-item project-initiator">
              <p>{{ data.initiator_name || '' }}</p>
              <p>{{ data.initiator_address }}</p>
            </div>
          </el-col>
          <el-col :span="6">
            <div class="context-bar-item highlight">
              <p><span :class="'doc-type '+ data.required_doc">{{ data.required_doc }}</span></p>
              <p style="margin-top: 1.5em !important;"><span style=" font-weight:normal;">Registration No:</span><br>
                <span style="font-size:1.1em;">{{ (data.registration_no || '').toUpperCase() }}</span></p>
            </div>
          </el-col>
        </el-row>
        <div class="context-bar-item"><p>{{ data.description }}</p></div>
      </el-collapse-item>
    </el-collapse>
  </div>
</template>
<script>

import Resource from '@/api/resource';
const projectResource = new Resource('projects');

export default {
  name: 'ContextBar',
  data() {
    return {
      contextBar: [],
      isShown: false,
      projectId: 0,
      data: null,
      keywords: ['amdal', 'dokumen-kegiatan', 'uklupl'],
    };
  },
  watch: {
    $route: function(){
      this.toShow();
    },
  },
  mounted() {
    this.toShow();
  },
  methods: {
    toShow() {
      this.clear();

      const regex = new RegExp(this.keywords.join('|'));
      if (!regex.test(this.$route.path)) {
        return;
      }

      const hasId = parseInt(this.$route.params && this.$route.params.id);
      if (hasId) {
        this.projectId = hasId;
        this.isShown = true;
        this.getData();
      }
    },
    clear(){
      this.isShown = false;
      this.projectId = 0;
      this.data = null;
    },
    async getData(){
      this.data = null;
      projectResource.list({ id: this.projectId }).then((res) => {
        console.log(res);
        this.data = res;
      }).finally({

      });
    },
    address(){
      const data = this.data;
      const addr = [data.lpjp_address, data.lpjp_address_district, data.lpjp_address_province].filter(e => (e !== null && e !== ''));
      if (addr.length > 0){
        return addr.join(', ');
      }

      return '';
    },

  },
};
</script>
<style lang="scss">
div.context-bar-container + section.app-main .app-container:first-child{
  padding-top:0 !important;
}
.context-bar-container {

  padding-bottom: 0;
  .el-collapse-item {
    position: relative;
  }

  .el-collapse-item__header {
    background: #216221 !important;

    div.context-bar-header{
      font-weight:normal;
     font-size:1.2em;
     line-height: 3em;
     text-transform: capitalize;
      white-space: nowrap !important;
      overflow: hidden !important;
      text-overflow: ellipsis !important;
    }
  }
  .el-collapse-item__wrap {
    position: absolute;
    top: 50px;
    right: 0;
    left: 0;
    z-index: 1111;
    box-shadow: 0 4px 4px 0 rgba(0, 0, 0, 0.1);

  }

  .el-collapse-item__content {
    /* user-select: none; */
    padding:1em 2em;
    border-radius: 0 0 1em 1em;
  }

  .context-bar-item {
    margin: 0.5em 0 1em;
         font-size:1.1em ;
    p {
    margin: 0 0 0 0 !important;
    }

    &.project {font-weight: 500;}
    &.project-initiator { font-weight: bold; }

    &.project, &.project-initiator {
       p{line-height: 1.25em !important;}
       p:first-child { margin-bottom: 0.5em !important;}
    }
    &.highlight{
      p{
        font-weight: bold;
        text-align: right;
        margin-bottom: 2em;
      }
      span.doc-type {
        display: inline-block; padding: 0.5em 1.5em; border-radius: 1em;
        color: white !important;
      }
    }
  }

}
</style>
