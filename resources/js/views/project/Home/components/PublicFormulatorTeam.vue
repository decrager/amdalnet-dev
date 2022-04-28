<template>
  <div class="ph-formulator ph">
    {{ id }}

    <p v-if="tim !== null" class="ph-header">{{ tim.name }}</p>

    <section v-if="(ketua !== null) && (ketua.length > 0) ">
      <p class="ph-header">Ketua</p>
      <formulator-persons :data="ketua" />
    </section>
    <section v-if="(anggota !== null) && (anggota.length > 0)">
      <p class="ph-header">Anggota</p>
      <formulator-persons :data="anggota" />
    </section>

    <section v-if="(ahli !== null) && ((ahli.length > 0)) ">
      <p class="ph-header">Tim Ahli</p>
      <formulator-persons :data="ahli" :mode="2" />
    </section>

  </div>
</template>
<script>
import FormulatorPersons from './sections/Formulators.vue';
import Resource from '@/api/resource';
const formulatorResource = new Resource('formulator-teams');
export default {
  name: 'PublicProjectFormulatorTeam',
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
  watch: {
    id: function(val){
      console.log('watching Ids');
      if (val > 0){
        this.getFormulatorTeam();
        this.getFormulator();
        this.getExpert();
      }
    },
  },

  methods: {
    async getFormulator(){
      this.data = [];

      await formulatorResource.list({ type: 'tim-penyusun', idProject: this.id })
        .then((res) => {
          this.data = res;
          this.ketua = this.data.filter((e) => e.position === 'Ketua');
          this.anggota = this.data.filter((e) => e.position === 'Anggota');
        })
        .finally();
    },
    async getExpert(){
      this.ahli = [];

      await formulatorResource.list({ type: 'tim-ahli', idProject: this.id })
        .then((res) => {
          this.ahli = res;
          // console.log('ahli: ', this.ahli);
        })
        .finally();
    },
    async getFormulatorTeam(){
      this.tim = null;
      await formulatorResource.list({ type: 'project', idProject: this.id })
        .then((res) => {
          this.tim = res;
          // console.log('tim: ', this.tim);
        })
        .finally();
    },
  },
};
</script>

