<template>
  <div class="ph-formulator">

    <p v-if="tim !== null" class="header">{{ tim.name }}</p>

    <section v-if="(ketua !== null) && (ketua.length > 0) ">
      <p class="header">Ketua</p>
      <formulator-persons :data="ketua" />
    </section>
    <section v-if="(anggota !== null) && (anggota.length > 0)">
      <p class="header">Anggota</p>
      <formulator-persons :data="anggota" />
    </section>
    <section v-if="(ahli !== null) && ((ahli.length > 0)) ">
      <p class="header">Tim Ahli</p>
      <formulator-persons :data="ahli" :mode="2" />
    </section>
    <section v-if="(ketua.length < 1) && (anggota.length < 1)">
      <p class="header">Data Penyusun</p>
      <formulator-persons :data="data" />
    </section>

  </div>
</template>
<script>
import FormulatorPersons from './sections/Formulators.vue';
import Resource from '@/api/resource';
const formulatorResource = new Resource('formulator-teams');
export default {
  name: 'ProjectFormulatorTeam',
  components: { FormulatorPersons },
  prop: {
    id: {
      type: Number,
      default: 0,
    },
  },
  data(){
    return {
      data: [],
      ketua: [],
      anggota: [],
      ahli: [],
      tim: null,
    };
  },
  mounted(){
    this.getFormulatorTeam();
    this.getFormulator();
    this.getExpert();
  },
  methods: {
    async getFormulator(){
      this.data = [];
      const projectId = this.$route.params && this.$route.params.id;
      const response = await formulatorResource.list({ type: 'tim-penyusun', idProject: projectId });
      this.data = response;
      this.ketua = this.data.filter((e) => e.position === 'Ketua');
      this.anggota = this.data.filter((e) => e.position === 'Anggota');
    },
    async getExpert(){
      this.ahli = [];
      const projectId = this.$route.params && this.$route.params.id;
      await formulatorResource.list({ type: 'tim-ahli', idProject: projectId })
        .then((res) => {
          this.ahli = res;
          // console.log('ahli: ', this.ahli);
        })
        .finally();
    },
    async getFormulatorTeam(){
      this.tim = null;
      const projectId = this.$route.params && this.$route.params.id;
      await formulatorResource.list({ type: 'project', idProject: projectId })
        .then((res) => {
          this.tim = res;
          // console.log('tim: ', this.tim);
        })
        .finally();
    },
  },
};
</script>
<style lang="scss" scoped>
.ph-formulator{
  padding: 2em;
}
</style>
