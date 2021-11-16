<template>
  <div class="app-container">
    <h1>Diagram Alir Pelingkupan</h1>
    <img :src="bap" alt="" style="width: 100%; height: 100%;">
  </div>
</template>

<script>
const logo = require('@/assets/bap.png').default;

export default {
  name: 'FlowChart',
  data() {
    return {
      bap: logo,
    };
  },
};
</script>
