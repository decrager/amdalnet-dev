<template>
  <div
    v-if="components.length > 0"
    :class="className"
  >
    <p v-for="comp in components" :key="comp.id+'_'+id">
      <el-button v-if="showDelete" type="danger" icon="el-icon-close" size="mini" plain square style="float:left; width:20px; padding: 3px 0;" @click="removeComponent(comp.id)" />
      <i v-if="showEdit" class="el-icon-edit" size="mini" style="float:right; padding:5px; cursor:pointer;" @click="editComponent(comp.id)" />
      {{ comp.name }}  <i v-if="comp.is_master" class="el-icon-success" style="color:#2e6b2e;" />
    </p>
  </div>
</template>
<script>
export default {
  name: 'ComponentsList',
  props: {
    components: {
      type: Array,
      default: function(){
        return [];
      },
    },
    id: { type: String, default: 'Ssa' },
    showDelete: { type: Boolean, default: true },
    showEdit: { type: Boolean, default: true },
    className: { type: String, default: 'components-container' },

  },
  methods: {
    removeComponent(id){
      // const e = this.components.find( c => c.id === id);
      this.$emit('delete', id);
    },
    editComponent(id){
      // const e = this.components.find( c => c.id === id);
      this.$emit('edit', id);
    },
  },
};
</script>
