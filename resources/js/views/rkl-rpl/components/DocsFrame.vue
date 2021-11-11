<template>
  <el-input
    v-model="textarea"
    type="textarea"
    :rows="25"
    placeholder="Please input"
  />
</template>

<script>
export default {
  name: 'DocsFrame',
  data() {
    return {
      textarea: '',
    };
  },
};
</script>
