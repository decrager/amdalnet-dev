<template>
  <section id="about" class="about section_data">
    <div class="about__container container">
      <div class="about__data">
        <h2 class="section__title about__title">Tahapan Pengajuan Persetujuan Lingkungan</h2>
        <div class="progressbar-wrapper">
          <ul class="progressbar">
            <li class="active ">Pengajuan<br> Persetujuan Lingkungan</li>
            <li class="active">Penapisan<br>Otomatis</li>
            <li class="active">Pelingkupan<br>dan Rona Awal</li>
            <li class="active">Prakiraan<br>dan Evaluasi Dampak</li>
            <li class="active">Uji<br>Kelayakan</li>
            <li class="active">Keputusan<br>Kelayakan Lingkungan</li>
          </ul>
        </div>
      </div>
    </div>
  </section>
</template>

<script>
export default {
  name: 'StepsHome',
};
</script>

<style scoped>
.progressbar-wrapper {
      width: 100%;
      display: grid;
      margin-bottom: 30px;
      /*background-color: white;*/
      padding: 20px;
      border-radius: 5px;
}

.progressbar li {
      list-style-type: none;
      width: 16%;
      float: left;
      font-size: 12px;
      position: relative;
      text-align: center;
      color: #7d7d7d;
}

.progressbar li:before {
    width: 70px;
    height: 70px;
    content: "";
    line-height: 60px;
    border: 3px solid #7d7d7d;
    display: block;
    text-align: center;
    margin: 0 auto 3px auto;
    border-radius: 50%;
    position: relative;
    z-index: 2;
    background-color: #fff;
}
.progressbar li:after {
     width: 100%;
     height: 2px;
     content: '';
     position: absolute;
     background-color: #7d7d7d;
     top: 30px;
     left: -50%;
     z-index: 0;
}
.progressbar li:first-child:after {
     content: none;
}

.progressbar li.active {
    line-height: 20px;
    color: #ffffff;
    font-weight: 500;
    font-size: 13px;
}
.progressbar li.active:before {
    border-color: #bacdba;
    background: #2E7932;
 }
.progressbar li.active + li:after {
    background-color: #bacdba;
}

.progressbar li.active:before {
    background: #20492e  url('/images/checkwhite.png') no-repeat center center;
    background-size: 40%;
}
.progressbar li::before {
    /*background: #fff url('/images/check.png') no-repeat center center;*/
    background-size: 40%;
}

.progressbar li. {

}

@media screen and (max-width: 450px) {
  .progressbar li.active {
    line-height: 12px;
    color: #2E7932;
    font-weight: 400;
    font-size: 7px;
  }
  .progressbar li:before {
    width: 25px;
    height: 25px;
        border: none

}

.progressbar li:after {
     top: 15px;
}
}
</style>
