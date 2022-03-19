<template>
  <div v-if="components.length > 0" class="components-container">
    <p v-for="comp in components" :key="comp.id+'_'+id" style="text-align:center; background: #fff; border: 1px solid #e0e0e0; border-radius:0.3em; padding: 0.5em;">
      <el-button type="danger" icon="el-icon-close" size="mini" plain square style="float:left; width:20px; padding: 3px 0;" @click="removeComponent(comp.id)" />
      <i class="el-icon-edit" size="mini" style="float:right; padding:5px; cursor:pointer;" @click="editComponent(comp.id)" />
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
