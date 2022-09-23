<template>
  <section id="kebijakan" class="kebijakan section_data pb-0">
    <div class="container">
      <el-row>
        <el-col :span="24">
          <h2 class="fw white mb-1-5">List Cluster KBLI</h2>
        </el-col>
      </el-row>
      <template>
        <el-row :gutter="20" class="bb bg-custom">
          <el-col :span="1" class="text-center py1">
            <div class="d-flex align-items-center justify-align-center">
              <span class="fz12 white fw">No</span>
            </div>
          </el-col>
          <el-col :span="8" class="py1 cp">
            <div class="d-flex align-items-center" @click="handleSort(sort)">
              <span class="fz12 white fw">Cluster KBLI UKL-UPL Reskio Menengah Rendah</span>
              <i class="el-icon-d-caret white fz12 ml-0-3" />
            </div>
          </el-col>
          <el-col :span="8" class="text-center py1 cp">
            <div
              class="d-flex align-items-center justify-align-start"
              @click="handleSort(sort)"
            >
              <span class="fz12 white fw">SOP</span>
              <i class="el-icon-d-caret white fz12 ml-0-3" />
            </div>
          </el-col>
          <el-col :span="4" class="text-center py1 cp">
            <div
              class="d-flex align-items-center justify-align-start"
              @click="handleSort(sort)"
            >
              <span class="fz12 white fw">Persetujuan Teknis</span>
              <i class="el-icon-d-caret white fz12 ml-0-3" />
            </div>
          </el-col>
        </el-row>
        <el-row class="bg-custom">
          <el-col :span="1" class="text-center py">
            <span class="fz12 white fw">1</span>
          </el-col>
          <el-col :span="8" class="py">
            <span class="fz12 white">
              <b>Pembibitan dan Budi Daya Hewan Ternak</b><br>
              <u>Cluster KBLI:</u><br>
              01411, 01412, 01413, 01414, 01420, 01430, 01441,
              01442, 01443, 01444, 01494, 01450, 01461, 01462,
              01464, 01465, 01466, 01469, 01463, 01491, 01445,
              01492, 01493, 01495, 01496, 01499<br>
              <a
                target="_blank"
                class="white"
                href="https://amdal.menlhk.go.id/amdalnet/assets/uploads/cluster_kbli/01_Template%20Formulir%20UKL-UPL%20Usaha%20Pembibitan%20dan%20Budidaya%20Hewan%20Ternak_final.pdf"
              ><span class="fa fa-download" /> &nbsp;Template
                Formulir</a>
            </span>
          </el-col>
          <el-col :span="8" class="py">
            <span class="fz12 white">
              <ul>
                <li>
                  SOP B.2.2 Peningkatan TSP (debu) akibat
                  Pembersihan Lahan dan Pematangan Lahan
                </li>
                <li>
                  SOP B.1.1 Peningkatan Kesempatan Kerja Akibat
                  Penerimaan Tenaga Kerja
                </li>
                <li>
                  SOP B.2.3 Peningkatan Air Larian Akibat
                  Pembersihan Lahan dan Pematangan Lahan
                </li>
                <li>
                  SOP B.3.2 Peningkatan TSP (debu) Akibat Mobilisasi
                  Peralatan dan Material
                </li>
                <li>
                  SOP B.4.2 Peningkatan TSP (debu) akibat
                  pembangunan fasilitas/unit utama dan
                  fasilitas/unit pendukung
                </li>
                <li>
                  SOP B.4.3 Peningkatan limbah padat atau sampah
                  akibat pembangunan fasilitas/unit utama dan
                  fasilitas/unit pendukung
                </li>
                <li>
                  SOP C.1.1 Peningkatan Kesempatan Kerja Akibat
                  Penerimaan Tenaga Kerja
                </li>
                <li>
                  SOP C.1.3 Peningkatan Pendapatan Akibat Penerimaan
                  Tenaga Kerja
                </li>
                <li>
                  SOP B.4.1 Peningkatan Kebisingan Akibat
                  Pembangunan Fasilitas/Unit Utama dan
                  Fasilitas/Unit Pendukung / Fasilitas Budidaya
                </li>
                <li>
                  SOP C.1.4 Peningkatan Keresahan dan Konflik
                  Masyarakat Akibat Penerimaan Tenaga Kerja
                </li>
                <li>
                  SOP B.3.1 Peningkatan Kebisingan Akibat Mobilisasi
                  Peralatan dan Material
                </li>
                <li>
                  SOP A.1.1 Perubahan persepsi masyarakat akibat
                  sosialisasi
                </li>
                <li>
                  SOP B.4.5 Penurunan Kualitas Air Permukaan Akibat
                  Pembangunan fasilitas/unit utama dan fasilitasunit
                  pendukung
                </li>
                <li>
                  SOP A.2.1 Perubahan Persepsi Masyarakat Akibat
                  Pembebasan Lahan
                </li>
                <li>
                  SOP C.2.11 Penurunan Kualitas Air Permukaan Akibat
                  Kegiatan Operasional Unit/Fasilitas Utama dan
                  Unit/Fasilitas Pendukung
                </li>
                <li>
                  SOP A.4.1 Perubahan persepsi masyarakat akibat
                  pengambilan data kondisi lingkungan (rona
                  lingkungan hidup awal)
                </li>
                <li>
                  SOP B.2.1 Penurunan Kualitas Air Permukaan Akibat
                  Pembersihan Lahan dan Pematangan Lahan
                </li>
                <li>
                  SOP C.1.2 Peningkatan Peluang Berusaha Akibat
                  Penerimaan Tenaga Kerja
                </li>
                <li>
                  SOP C.2.2 Peningkatan Limbah Padat (Sampah) Akibat
                  Kegiatan Operasional Unit/Fasilitas Utama dan
                  Unit/Fasilitas Pendukung
                </li>
                <li>
                  SOP C.2.3 Peningkatan Kebauan Akibat Kegiatan
                  Operasional Unit/Fasilitas Utama dan
                  Unit/Fasilitas Pendukung
                </li>
                <li>
                  SOP C.2.5 Peningkatan Kebisingan Akibat Kegiatan
                  Operasional Unit/Fasilitas Utama dan
                  Unit/Fasilitas Pendukung
                </li>
                <li>
                  SOP C.2.7 Peningkatan Kesempatan Kerja Akibat
                  Kegiatan Operasional Unit/Fasilitas Utama dan
                  Unit/Fasilitas Pendukung
                </li>
                <li>
                  SOP C.2.8 Penurunan Kesehatan Masyarakat Akibat
                  Kegiatan Operasional Unit/Fasilitas Utama dan
                  Unit/Fasilitas Pendukung
                </li>
                <li>
                  SOP C.2.9 Peningkatan Keresahan dan Konflik
                  Masyarakat Akibat Kegiatan Operasional
                  Unit/Fasilitas Utama dan Unit/Fasilitas Pendukung
                </li>
                <li>
                  SOP D.1.1 Penurunan Pendapatan Masyarakat Akibat
                  Pelepasan Tenaga Kerja
                </li>
                <li>
                  SOP D.3.2 Peningkatan Kebisingan Akibat
                  Pembongkaran Fasilitas Operasional (Fasilitas
                  Utama dan/atau Pendukung)
                </li>
                <li>
                  SOP D.3.1 Penurunan Kualitas Udara Akibat
                  Pembongkaran Fasilitas Operasional (Fasilitas
                  Utama dan/atau Pendukung)
                </li>
              </ul>
            </span>
          </el-col>
          <el-col :span="4" class="py">
            <span class="fz12 white">
              <ul>
                <div>
                  <li>
                    <a
                      target="_blank"
                      class="white"
                      href="https://amdal.menlhk.go.id/amdalnet/assets/uploads/cluster_kbli/pertek1-26720210605140415.pdf"
                    ><span class="fa fa-download" /> &nbsp;
                      Pertek Pembuangan Air Limbah ke Badan Air
                      Permukaan
                    </a>
                  </li>
                </div>
                <div />
                <div />
                <div />
                <div>
                  <li>
                    <a
                      target="_blank"
                      class="white"
                      href="https://amdal.menlhk.go.id/amdalnet/assets/uploads/cluster_kbli/pertek5-32820210605141212.pdf"
                    ><span class="fa fa-download" /> &nbsp;
                      Pertek Pemenuhan Baku Mutu Emisi
                    </a>
                  </li>
                </div>
                <div />
                <div />
                <div />
                <div />
                <div />
                <div>
                  <li>
                    <a
                      target="_blank"
                      class="white"
                      href="https://amdal.menlhk.go.id/amdalnet/assets/uploads/cluster_kbli/pertek11-13620210607132932.pdf"
                    ><span class="fa fa-download" /> &nbsp;
                      Rincian Teknis Limbah B3</a>
                  </li>
                </div>
                <div />
              </ul>
            </span>
          </el-col>
        </el-row>
        <el-row class="bg-custom">
          <el-col :span="1" class="text-center py">
            <span class="fz12 white fw">2</span>
          </el-col>
          <el-col :span="8" class="py">
            <span class="fz12 white">
              <b>Perbenihan dan Budi Daya Pertanian</b><br>
              <u>Cluster KBLI:</u><br>
              01111, 01112, 01113, 01114, 01115, 01119, 01121,
              01122, 01116, 01131, 01132, 01133, 01134, 01136,
              01139, 01194, 01253, 01193, 01220, 01230, 01240,
              01251, 01259, 01210, 01301, 01302, 01191, 01192,
              01199, 01283, 01285, 01286<br>
              <a
                target="_blank"
                class="white"
                href="https://amdal.menlhk.go.id/amdalnet/assets/uploads/cluster_kbli/02_Template%20Formulir%20UKL-UPL%20Standar%20Spesifik%20Usaha%20Perbenihan%20dan%20Budi%20Daya%20Pertanian_final.pdf"
              ><span class="fa fa-download" /> &nbsp;Template
                Formulir</a>
            </span>
          </el-col>
          <el-col :span="8" class="py">
            <span class="fz12 white">
              <ul>
                <li>
                  SOP B.2.2 Peningkatan TSP (debu) akibat
                  Pembersihan Lahan dan Pematangan Lahan
                </li>
                <li>
                  SOP B.1.1 Peningkatan Kesempatan Kerja Akibat
                  Penerimaan Tenaga Kerja
                </li>
                <li>
                  SOP B.2.3 Peningkatan Air Larian Akibat
                  Pembersihan Lahan dan Pematangan Lahan
                </li>
                <li>
                  SOP B.3.2 Peningkatan TSP (debu) Akibat Mobilisasi
                  Peralatan dan Material
                </li>
                <li>
                  SOP B.4.2 Peningkatan TSP (debu) akibat
                  pembangunan fasilitas/unit utama dan
                  fasilitas/unit pendukung
                </li>
                <li>
                  SOP C.1.1 Peningkatan Kesempatan Kerja Akibat
                  Penerimaan Tenaga Kerja
                </li>
                <li>
                  SOP C.1.3 Peningkatan Pendapatan Akibat Penerimaan
                  Tenaga Kerja
                </li>
                <li>
                  SOP B.4.1 Peningkatan Kebisingan Akibat
                  Pembangunan Fasilitas/Unit Utama dan
                  Fasilitas/Unit Pendukung / Fasilitas Budidaya
                </li>
                <li>
                  SOP A.2.3 Peningkatan keresahan masyarakat akibat
                  pembebasan lahan
                </li>
                <li>
                  SOP A.1.2 Peningkatan Keresahan masyarakat akibat
                  sosialisasi
                </li>
                <li>
                  SOP C.1.4 Peningkatan Keresahan dan Konflik
                  Masyarakat Akibat Penerimaan Tenaga Kerja
                </li>
                <li>
                  SOP B.3.1 Peningkatan Kebisingan Akibat Mobilisasi
                  Peralatan dan Material
                </li>
                <li>
                  SOP A.1.1 Perubahan persepsi masyarakat akibat
                  sosialisasi
                </li>
                <li>
                  SOP A.2.2 Timbulnya konflik sosial akibat
                  pembebasan lahan
                </li>
                <li>
                  SOP B.4.5 Penurunan Kualitas Air Permukaan Akibat
                  Pembangunan fasilitas/unit utama dan fasilitasunit
                  pendukung
                </li>
                <li>
                  SOP A.2.1 Perubahan Persepsi Masyarakat Akibat
                  Pembebasan Lahan
                </li>
                <li>
                  SOP C.2.11 Penurunan Kualitas Air Permukaan Akibat
                  Kegiatan Operasional Unit/Fasilitas Utama dan
                  Unit/Fasilitas Pendukung
                </li>
                <li>
                  SOP A.4.1 Perubahan persepsi masyarakat akibat
                  pengambilan data kondisi lingkungan (rona
                  lingkungan hidup awal)
                </li>
                <li>
                  SOP B.2.1 Penurunan Kualitas Air Permukaan Akibat
                  Pembersihan Lahan dan Pematangan Lahan
                </li>
                <li>
                  SOP B.2.4 Peningkatan Kebisingan Akibat
                  Pembersihan dan Pematangan Lahan
                </li>
                <li>
                  SOP B.2.5 Peningkatan Keresahan Masyarakat Akibat
                  Pembersihan dan Pematangan Lahan
                </li>
                <li>
                  SOP B.2.6 Gangguan Flora dan Fauna yang Dilindungi
                  Akibat Pembersihan dan Pematangan Lahan
                </li>
                <li>
                  SOP B.2.7 Gangguan Estetika Lingkungan Akibat
                  Pembersihan dan Pematangan Lahan
                </li>
                <li>
                  SOP C.1.2 Peningkatan Peluang Berusaha Akibat
                  Penerimaan Tenaga Kerja
                </li>
                <li>
                  SOP C.2.5 Peningkatan Kebisingan Akibat Kegiatan
                  Operasional Unit/Fasilitas Utama dan
                  Unit/Fasilitas Pendukung
                </li>
                <li>
                  SOP C.2.12 Penurunan Kualitas Air Tanah Akibat
                  Kegiatan Operasional Unit/Fasilitas Utama dan
                  Unit/Fasilitas Pendukung
                </li>
                <li>
                  SOP C.2.13 Penurunan Kualitas Tanah Akibat
                  Kegiatan Operasional Unit/Fasilitas Utama dan
                  Unit/Fasilitas Pendukung
                </li>
                <li>
                  SOP C.2.14 Penurunan Kuantitas Air Tanah Akibat
                  Kegiatan Operasional Unit/Fasilitas Utama dan
                  Unit/Fasilitas Pendukung
                </li>
                <li>
                  SOP D.1.2 Peningkatan Keresahan Masyarakat Akibat
                  Pelepasan Tenaga Kerja
                </li>
                <li>
                  SOP D.3.2 Peningkatan Kebisingan Akibat
                  Pembongkaran Fasilitas Operasional (Fasilitas
                  Utama dan/atau Pendukung)
                </li>
                <li>
                  SOP D.3.1 Penurunan Kualitas Udara Akibat
                  Pembongkaran Fasilitas Operasional (Fasilitas
                  Utama dan/atau Pendukung)
                </li>
              </ul>
            </span>
          </el-col>
          <el-col :span="4" class="py">
            <span class="fz12 white">
              <ul>
                <div>
                  <li>
                    <a
                      target="_blank"
                      class="white"
                      href="https://amdal.menlhk.go.id/amdalnet/assets/uploads/cluster_kbli/pertek1-96620210605141422.pdf"
                    ><span class="fa fa-download" /> &nbsp;
                      Pertek Pembuangan Air Limbah ke Badan Air
                      Permukaan
                    </a>
                  </li>
                </div>
                <div />
                <div />
                <div />
                <div>
                  <li>
                    <a
                      target="_blank"
                      class="white"
                      href="https://amdal.menlhk.go.id/amdalnet/assets/uploads/cluster_kbli/pertek5-39920210605141422.pdf"
                    ><span class="fa fa-download" /> &nbsp;
                      Pertek Pemenuhan Baku Mutu Emisi
                    </a>
                  </li>
                </div>
                <div />
                <div />
                <div />
                <div />
                <div />
                <div>
                  <li>
                    <a
                      target="_blank"
                      class="white"
                      href="https://amdal.menlhk.go.id/amdalnet/assets/uploads/cluster_kbli/pertek11-43520210607133017.pdf"
                    ><span class="fa fa-download" /> &nbsp;
                      Rincian Teknis Limbah B3</a>
                  </li>
                  <li>

                    <!-- <a href="javascript:void(0);" class="btn btn-info btn-xs" onclick="if (confirm('Apa anda yakin ingin menghapus data ini?')) window.location='https://amdal.menlhk.go.id/amdalnet/Cluster_Kbli/delete/3';">Edit</a> -->
                    <!-- <a href="javascript:void(0);" class="btn btn-danger btn-
                                              Permanen</a> -->
                  </li>
                </div>
                <div />
              </ul>
            </span>
          </el-col>
        </el-row>
        <el-row class="bg-custom">
          <el-col :span="1" class="text-center py">
            <span class="fz12 white fw">3</span>
          </el-col>
          <el-col :span="8" class="py">
            <span class="fz12 white">
              <b>Budi Daya Perikanan Air Laut di Perairan</b><br>
              <u>Cluster KBLI:</u><br>
              03211, 03212, 03213, 03221, 03222, 03223, 03225,
              03226, 03227, 03229, 03214, 03215, 03216, 03217,
              03219<br>
              <a
                target="_blank"
                class="white"
                href="https://amdal.menlhk.go.id/amdalnet/assets/uploads/cluster_kbli/03_Template%20Formulir%20UKL-UPL%20Standar%20Spesifik%20Usaha%20Perikanan%20di%20Laut_final.pdf"
              ><span class="fa fa-download" /> &nbsp;Template
                Formulir</a>
            </span>
          </el-col>
          <el-col :span="8" class="py">
            <span class="fz12 white">
              <ul>
                <li>
                  SOP B.1.1 Peningkatan Kesempatan Kerja Akibat
                  Penerimaan Tenaga Kerja
                </li>
                <li>
                  SOP B.3.2 Peningkatan TSP (debu) Akibat Mobilisasi
                  Peralatan dan Material
                </li>
                <li>
                  SOP C.1.1 Peningkatan Kesempatan Kerja Akibat
                  Penerimaan Tenaga Kerja
                </li>
                <li>
                  SOP C.1.3 Peningkatan Pendapatan Akibat Penerimaan
                  Tenaga Kerja
                </li>
                <li>
                  SOP A.1.2 Peningkatan Keresahan masyarakat akibat
                  sosialisasi
                </li>
                <li>
                  SOP C.1.4 Peningkatan Keresahan dan Konflik
                  Masyarakat Akibat Penerimaan Tenaga Kerja
                </li>
                <li>
                  SOP B.3.1 Peningkatan Kebisingan Akibat Mobilisasi
                  Peralatan dan Material
                </li>
                <li>
                  SOP A.1.1 Perubahan persepsi masyarakat akibat
                  sosialisasi
                </li>
                <li>
                  SOP A.4.1 Perubahan persepsi masyarakat akibat
                  pengambilan data kondisi lingkungan (rona
                  lingkungan hidup awal)
                </li>
                <li>
                  SOP C.1.2 Peningkatan Peluang Berusaha Akibat
                  Penerimaan Tenaga Kerja
                </li>
                <li>
                  SOP C.2.17 Penurunan Kualitas Air Laut Akibat
                  Kegiatan Operasional Unit/Fasilitas Utama dan
                  Unit/Fasilitas Pendukung
                </li>
                <li>
                  SOP C.2.18 Gangguan Biota Laut Akibat Operasional
                  Unit/Fasilitas Utama Dan Unit/Fasilitas Pendukung
                </li>
                <li>
                  SOP D.1.2 Peningkatan Keresahan Masyarakat Akibat
                  Pelepasan Tenaga Kerja
                </li>
                <li>
                  SOP D.3.2 Peningkatan Kebisingan Akibat
                  Pembongkaran Fasilitas Operasional (Fasilitas
                  Utama dan/atau Pendukung)
                </li>
              </ul>
            </span>
          </el-col>
          <el-col :span="4" class="py">
            <span class="fz12 white">
              <ul>
                <div />
                <div>
                  <li>
                    <a
                      target="_blank"
                      class="white"
                      href="https://amdal.menlhk.go.id/amdalnet/assets/uploads/cluster_kbli/pertek2-27720210605141815.pdf"
                    ><span class="fa fa-download" /> &nbsp;
                      Pertek Pembuangan dan/atau Pemanfaatan Air
                      Limbah ke Formasi tertentu</a>
                  </li>
                </div>
                <div />
                <div />
                <div>
                  <li>
                    <a
                      target="_blank"
                      class="white"
                      href="https://amdal.menlhk.go.id/amdalnet/assets/uploads/cluster_kbli/pertek5-71720210605141815.pdf"
                    ><span class="fa fa-download" /> &nbsp;
                      Pertek Pemenuhan Baku Mutu Emisi
                    </a>
                  </li>
                </div>
                <div />
                <div />
                <div />
                <div />
                <div />
                <div>
                  <li>
                    <a
                      target="_blank"
                      class="white"
                      href="https://amdal.menlhk.go.id/amdalnet/assets/uploads/cluster_kbli/pertek11-87920220812131441.pdf"
                    ><span class="fa fa-download" /> &nbsp;
                      Rincian Teknis Limbah B3</a>
                  </li>
                </div>
                <div />
              </ul>
            </span>
          </el-col>
        </el-row>
        <el-row class="bg-custom">
          <el-col :span="1" class="text-center py">
            <span class="fz12 white fw">4</span>
          </el-col>
          <el-col :span="8" class="py">
            <span class="fz12 white">
              <b>Budi Daya Perikanan Air Payau di Darat</b><br>
              <u>Cluster KBLI:</u><br>
              03251, 03253, 03254, 03255, 03259, 03252<br>
              <a
                target="_blank"
                class="white"
                href="https://amdal.menlhk.go.id/amdalnet/assets/uploads/cluster_kbli/05_Template%20Formulir%20UKL-UPL%20Standar%20Spesifik%20Usaha%20Budi%20Daya%20Perikanan%20Air%20Payau%20di%20Darat_final.pdf"
              ><span class="fa fa-download" /> &nbsp;Template
                Formulir</a>
            </span>
          </el-col>
          <el-col :span="8" class="py">
            <span class="fz12 white">
              <ul>
                <li>
                  SOP B.2.2 Peningkatan TSP (debu) akibat
                  Pembersihan Lahan dan Pematangan Lahan
                </li>
                <li>
                  SOP B.1.1 Peningkatan Kesempatan Kerja Akibat
                  Penerimaan Tenaga Kerja
                </li>
                <li>
                  SOP B.2.3 Peningkatan Air Larian Akibat
                  Pembersihan Lahan dan Pematangan Lahan
                </li>
                <li>
                  SOP B.3.2 Peningkatan TSP (debu) Akibat Mobilisasi
                  Peralatan dan Material
                </li>
                <li>
                  SOP B.4.2 Peningkatan TSP (debu) akibat
                  pembangunan fasilitas/unit utama dan
                  fasilitas/unit pendukung
                </li>
                <li>
                  SOP C.1.1 Peningkatan Kesempatan Kerja Akibat
                  Penerimaan Tenaga Kerja
                </li>
                <li>
                  SOP C.1.3 Peningkatan Pendapatan Akibat Penerimaan
                  Tenaga Kerja
                </li>
                <li>
                  SOP B.4.1 Peningkatan Kebisingan Akibat
                  Pembangunan Fasilitas/Unit Utama dan
                  Fasilitas/Unit Pendukung / Fasilitas Budidaya
                </li>
                <li>
                  SOP A.2.3 Peningkatan keresahan masyarakat akibat
                  pembebasan lahan
                </li>
                <li>
                  SOP A.1.2 Peningkatan Keresahan masyarakat akibat
                  sosialisasi
                </li>
                <li>
                  SOP C.1.4 Peningkatan Keresahan dan Konflik
                  Masyarakat Akibat Penerimaan Tenaga Kerja
                </li>
                <li>
                  SOP B.3.1 Peningkatan Kebisingan Akibat Mobilisasi
                  Peralatan dan Material
                </li>
                <li>
                  SOP A.1.1 Perubahan persepsi masyarakat akibat
                  sosialisasi
                </li>
                <li>
                  SOP A.2.2 Timbulnya konflik sosial akibat
                  pembebasan lahan
                </li>
                <li>
                  SOP B.4.5 Penurunan Kualitas Air Permukaan Akibat
                  Pembangunan fasilitas/unit utama dan fasilitasunit
                  pendukung
                </li>
                <li>
                  SOP A.2.1 Perubahan Persepsi Masyarakat Akibat
                  Pembebasan Lahan
                </li>
                <li>
                  SOP C.2.11 Penurunan Kualitas Air Permukaan Akibat
                  Kegiatan Operasional Unit/Fasilitas Utama dan
                  Unit/Fasilitas Pendukung
                </li>
                <li>
                  SOP A.4.1 Perubahan persepsi masyarakat akibat
                  pengambilan data kondisi lingkungan (rona
                  lingkungan hidup awal)
                </li>
                <li>
                  SOP B.2.1 Penurunan Kualitas Air Permukaan Akibat
                  Pembersihan Lahan dan Pematangan Lahan
                </li>
                <li>
                  SOP B.2.4 Peningkatan Kebisingan Akibat
                  Pembersihan dan Pematangan Lahan
                </li>
                <li>
                  SOP B.2.7 Gangguan Estetika Lingkungan Akibat
                  Pembersihan dan Pematangan Lahan
                </li>
                <li>
                  SOP B.2.8 Gangguan terhadap Habitat Mangrove
                  Akibat Pembersihan dan Pematangan Lahan
                </li>
                <li>
                  SOP C.1.2 Peningkatan Peluang Berusaha Akibat
                  Penerimaan Tenaga Kerja
                </li>
                <li>
                  SOP C.2.5 Peningkatan Kebisingan Akibat Kegiatan
                  Operasional Unit/Fasilitas Utama dan
                  Unit/Fasilitas Pendukung
                </li>
                <li>
                  SOP C.2.19 Gangguan Terhadap Biota Perairan Payau
                  Akibat Operasional Unit/Fasilitas Utama dan
                  Unit/Fasilitas Pendukung
                </li>
                <li>
                  SOP D.1.2 Peningkatan Keresahan Masyarakat Akibat
                  Pelepasan Tenaga Kerja
                </li>
                <li>
                  SOP D.3.2 Peningkatan Kebisingan Akibat
                  Pembongkaran Fasilitas Operasional (Fasilitas
                  Utama dan/atau Pendukung)
                </li>
              </ul>
            </span>
          </el-col>
          <el-col :span="4" class="py">
            <span class="fz12 white">
              <ul>
                <div>
                  <li>
                    <a
                      target="_blank"
                      class="white"
                      href="https://amdal.menlhk.go.id/amdalnet/assets/uploads/cluster_kbli/pertek1-43920210601003408.pdf"
                    ><span class="fa fa-download" /> &nbsp;
                      Pertek Pembuangan Air Limbah ke Badan Air
                      Permukaan
                    </a>
                  </li>
                </div>
                <div>
                  <li>
                    <a
                      target="_blank"
                      class="white"
                      href="https://amdal.menlhk.go.id/amdalnet/assets/uploads/cluster_kbli/pertek2-25920210601003408.pdf"
                    ><span class="fa fa-download" /> &nbsp;
                      Pertek Pembuangan dan/atau Pemanfaatan Air
                      Limbah ke Formasi tertentu</a>
                  </li>
                </div>
                <div />
                <div />
                <div>
                  <li>
                    <a
                      target="_blank"
                      class="white"
                      href="https://amdal.menlhk.go.id/amdalnet/assets/uploads/cluster_kbli/pertek5-55120210605141250.pdf"
                    ><span class="fa fa-download" /> &nbsp;
                      Pertek Pemenuhan Baku Mutu Emisi
                    </a>
                  </li>
                </div>
                <div />
                <div />
                <div />
                <div />
                <div />
                <div>
                  <li>
                    <a
                      target="_blank"
                      class="white"
                      href="https://amdal.menlhk.go.id/amdalnet/assets/uploads/cluster_kbli/pertek11-32020210607132820.pdf"
                    ><span class="fa fa-download" /> &nbsp;
                      Rincian Teknis Limbah B3</a>
                  </li>
                  <li>

                    <!-- <a href="javascript:void(0);" class="btn btn-info btn-xs" onclick="if (confirm('Apa anda yakin ingin menghapus data ini?')) window.location='https://amdal.menlhk.go.id/amdalnet/Cluster_Kbli/delete/6';">Edit</a> -->
                    <!-- <a href="javascript:void(0);" class="btn btn-danger btn-
                                              Permanen</a> -->
                  </li>
                </div>
                <div />
              </ul>
            </span>
          </el-col>
        </el-row>
        <el-row class="bg-custom">
          <el-col :span="1" class="text-center py">
            <span class="fz12 white fw">5</span>
          </el-col>
          <el-col :span="8" class="py">
            <span class="fz12 white">
              <b>SPBU Mini dan Sejenisnya</b><br>
              <u>Cluster KBLI:</u><br>
              47301, 46610<br>
              <a
                target="_blank"
                class="white"
                href="https://amdal.menlhk.go.id/amdalnet/assets/uploads/cluster_kbli/11_Template%20Formulir%20UKL-UPL%20Standar%20Spesifik%20SPBU%20Mini_final.pdf"
              ><span class="fa fa-download" /> &nbsp;Template
                Formulir</a>
            </span>
          </el-col>
          <el-col :span="8" class="py">
            <span class="fz12 white">
              <ul>
                <li>
                  SOP B.2.2 Peningkatan TSP (debu) akibat
                  Pembersihan Lahan dan Pematangan Lahan
                </li>
                <li>
                  SOP B.1.1 Peningkatan Kesempatan Kerja Akibat
                  Penerimaan Tenaga Kerja
                </li>
                <li>
                  SOP B.2.3 Peningkatan Air Larian Akibat
                  Pembersihan Lahan dan Pematangan Lahan
                </li>
                <li>
                  SOP B.4.2 Peningkatan TSP (debu) akibat
                  pembangunan fasilitas/unit utama dan
                  fasilitas/unit pendukung
                </li>
                <li>
                  SOP B.4.3 Peningkatan limbah padat atau sampah
                  akibat pembangunan fasilitas/unit utama dan
                  fasilitas/unit pendukung
                </li>
                <li>
                  SOP C.1.1 Peningkatan Kesempatan Kerja Akibat
                  Penerimaan Tenaga Kerja
                </li>
                <li>
                  SOP C.1.3 Peningkatan Pendapatan Akibat Penerimaan
                  Tenaga Kerja
                </li>
                <li>
                  SOP B.4.1 Peningkatan Kebisingan Akibat
                  Pembangunan Fasilitas/Unit Utama dan
                  Fasilitas/Unit Pendukung / Fasilitas Budidaya
                </li>
                <li>
                  SOP A.2.3 Peningkatan keresahan masyarakat akibat
                  pembebasan lahan
                </li>
                <li>
                  SOP C.1.4 Peningkatan Keresahan dan Konflik
                  Masyarakat Akibat Penerimaan Tenaga Kerja
                </li>
                <li>
                  SOP C.2.1 Peningkatan Air Limbah Akibat Kegiatan
                  Operasional Unit/Fasilitas Utama dan
                  Unit/Fasilitas Pendukung
                </li>
                <li>
                  SOP A.4.1 Perubahan persepsi masyarakat akibat
                  pengambilan data kondisi lingkungan (rona
                  lingkungan hidup awal)
                </li>
                <li>
                  SOP B.1.2 Peningkatan Peluang Berusaha dan
                  Pendapatan akibat Penerimaan Tenaga Kerja
                </li>
                <li>
                  SOP B.2.1 Penurunan Kualitas Air Permukaan Akibat
                  Pembersihan Lahan dan Pematangan Lahan
                </li>
                <li>
                  SOP C.1.2 Peningkatan Peluang Berusaha Akibat
                  Penerimaan Tenaga Kerja
                </li>
                <li>
                  SOP C.2.2 Peningkatan Limbah Padat (Sampah) Akibat
                  Kegiatan Operasional Unit/Fasilitas Utama dan
                  Unit/Fasilitas Pendukung
                </li>
                <li>
                  SOP C.2.4 Peningkatan Timbulan Limbah B3 Akibat
                  Kegiatan Operasional Unit/Fasilitas Utama dan
                  Unit/Fasilitas Pendukung
                </li>
                <li>
                  SOP C.2.5 Peningkatan Kebisingan Akibat Kegiatan
                  Operasional Unit/Fasilitas Utama dan
                  Unit/Fasilitas Pendukung
                </li>
                <li>
                  SOP C.2.6 Peningkatan Gangguan Lalu Lintas Akibat
                  Kegiatan Operasional Unit/Fasilitas Utama dan
                  Unit/Fasilitas Pendukung
                </li>
              </ul>
            </span>
          </el-col>
          <el-col :span="4" class="py">
            <span class="fz12 white">
              <ul>
                <div />
                <div />
                <div />
                <div />
                <div>
                  <li>
                    <a
                      target="_blank"
                      class="white"
                      href="https://amdal.menlhk.go.id/amdalnet/assets/uploads/cluster_kbli/pertek5-15720210605141953.pdf"
                    ><span class="fa fa-download" /> &nbsp;
                      Pertek Pemenuhan Baku Mutu Emisi
                    </a>
                  </li>
                </div>
                <div />
                <div />
                <div />
                <div />
                <div />
                <div>
                  <li>
                    <a
                      target="_blank"
                      class="white"
                      href="https://amdal.menlhk.go.id/amdalnet/assets/uploads/cluster_kbli/pertek11-86520210607133100.pdf"
                    ><span class="fa fa-download" /> &nbsp;
                      Rincian Teknis Limbah B3</a>
                  </li>
                  <li>

                    <!-- <a href="javascript:void(0);" class="btn btn-info btn-xs" onclick="if (confirm('Apa anda yakin ingin menghapus data ini?')) window.location='https://amdal.menlhk.go.id/amdalnet/Cluster_Kbli/delete/7';">Edit</a> -->
                    <!-- <a href="javascript:void(0);" class="btn btn-danger btn-
                                              Permanen</a> -->
                  </li>
                </div>
                <div />
              </ul>
            </span>
          </el-col>
        </el-row>
        <el-row class="bg-custom">
          <el-col :span="1" class="text-center py">
            <span class="fz12 white fw">6</span>
          </el-col>
          <el-col :span="8" class="py">
            <span class="fz12 white">
              <b>Budi Daya Perikanan Air Tawar di Darat</b><br>
              <u>Cluster KBLI:</u><br>
              03221, 03222, 03223, 03227, 03229, 03225, 03226,
              03224<br>
              <a
                target="_blank"
                class="white"
                href="https://amdal.menlhk.go.id/amdalnet/assets/uploads/cluster_kbli/04_Template%20Formulir%20UKL-UPL%20Standar%20Spesifik%20Usaha%20Budi%20Daya%20Perikanan%20Air%20Tawar%20di%20darat_final.pdf"
              ><span class="fa fa-download" /> &nbsp;Template
                Formulir</a>
            </span>
          </el-col>
          <el-col :span="8" class="py">
            <span class="fz12 white">
              <ul>
                <li>
                  SOP B.2.2 Peningkatan TSP (debu) akibat
                  Pembersihan Lahan dan Pematangan Lahan
                </li>
                <li>
                  SOP B.1.1 Peningkatan Kesempatan Kerja Akibat
                  Penerimaan Tenaga Kerja
                </li>
                <li>
                  SOP B.2.3 Peningkatan Air Larian Akibat
                  Pembersihan Lahan dan Pematangan Lahan
                </li>
                <li>
                  SOP B.3.2 Peningkatan TSP (debu) Akibat Mobilisasi
                  Peralatan dan Material
                </li>
                <li>
                  SOP B.4.2 Peningkatan TSP (debu) akibat
                  pembangunan fasilitas/unit utama dan
                  fasilitas/unit pendukung
                </li>
                <li>
                  SOP C.1.1 Peningkatan Kesempatan Kerja Akibat
                  Penerimaan Tenaga Kerja
                </li>
                <li>
                  SOP C.1.3 Peningkatan Pendapatan Akibat Penerimaan
                  Tenaga Kerja
                </li>
                <li>
                  SOP B.4.1 Peningkatan Kebisingan Akibat
                  Pembangunan Fasilitas/Unit Utama dan
                  Fasilitas/Unit Pendukung / Fasilitas Budidaya
                </li>
                <li>
                  SOP A.2.3 Peningkatan keresahan masyarakat akibat
                  pembebasan lahan
                </li>
                <li>
                  SOP A.1.2 Peningkatan Keresahan masyarakat akibat
                  sosialisasi
                </li>
                <li>
                  SOP C.1.4 Peningkatan Keresahan dan Konflik
                  Masyarakat Akibat Penerimaan Tenaga Kerja
                </li>
                <li>
                  SOP B.3.1 Peningkatan Kebisingan Akibat Mobilisasi
                  Peralatan dan Material
                </li>
                <li>
                  SOP A.1.1 Perubahan persepsi masyarakat akibat
                  sosialisasi
                </li>
                <li>
                  SOP A.2.2 Timbulnya konflik sosial akibat
                  pembebasan lahan
                </li>
                <li>
                  SOP B.4.5 Penurunan Kualitas Air Permukaan Akibat
                  Pembangunan fasilitas/unit utama dan fasilitasunit
                  pendukung
                </li>
                <li>
                  SOP A.2.1 Perubahan Persepsi Masyarakat Akibat
                  Pembebasan Lahan
                </li>
                <li>
                  SOP C.2.11 Penurunan Kualitas Air Permukaan Akibat
                  Kegiatan Operasional Unit/Fasilitas Utama dan
                  Unit/Fasilitas Pendukung
                </li>
                <li>
                  SOP A.4.1 Perubahan persepsi masyarakat akibat
                  pengambilan data kondisi lingkungan (rona
                  lingkungan hidup awal)
                </li>
                <li>
                  SOP B.2.1 Penurunan Kualitas Air Permukaan Akibat
                  Pembersihan Lahan dan Pematangan Lahan
                </li>
                <li>
                  SOP B.2.4 Peningkatan Kebisingan Akibat
                  Pembersihan dan Pematangan Lahan
                </li>
                <li>
                  SOP B.2.7 Gangguan Estetika Lingkungan Akibat
                  Pembersihan dan Pematangan Lahan
                </li>
                <li>
                  SOP C.1.2 Peningkatan Peluang Berusaha Akibat
                  Penerimaan Tenaga Kerja
                </li>
                <li>
                  SOP C.2.5 Peningkatan Kebisingan Akibat Kegiatan
                  Operasional Unit/Fasilitas Utama dan
                  Unit/Fasilitas Pendukung
                </li>
                <li>
                  SOP D.1.2 Peningkatan Keresahan Masyarakat Akibat
                  Pelepasan Tenaga Kerja
                </li>
                <li>
                  SOP D.3.2 Peningkatan Kebisingan Akibat
                  Pembongkaran Fasilitas Operasional (Fasilitas
                  Utama dan/atau Pendukung)
                </li>
              </ul>
            </span>
          </el-col>
          <el-col :span="4" class="py">
            <span class="fz12 white">
              <ul>
                <div>
                  <li>
                    <a
                      target="_blank"
                      class="white"
                      href="https://amdal.menlhk.go.id/amdalnet/assets/uploads/cluster_kbli/pertek1-53620210605141737.pdf"
                    ><span class="fa fa-download" /> &nbsp;
                      Pertek Pembuangan Air Limbah ke Badan Air
                      Permukaan
                    </a>
                  </li>
                </div>
                <div>
                  <li>
                    <a
                      target="_blank"
                      class="white"
                      href="https://amdal.menlhk.go.id/amdalnet/assets/uploads/cluster_kbli/pertek2-55620210605141737.pdf"
                    ><span class="fa fa-download" /> &nbsp;
                      Pertek Pembuangan dan/atau Pemanfaatan Air
                      Limbah ke Formasi tertentu</a>
                  </li>
                </div>
                <div />
                <div />
                <div>
                  <li>
                    <a
                      target="_blank"
                      class="white"
                      href="https://amdal.menlhk.go.id/amdalnet/assets/uploads/cluster_kbli/pertek5-79820210605141737.pdf"
                    ><span class="fa fa-download" /> &nbsp;
                      Pertek Pemenuhan Baku Mutu Emisi
                    </a>
                  </li>
                </div>
                <div />
                <div />
                <div />
                <div />
                <div />
                <div>
                  <li>
                    <a
                      target="_blank"
                      class="white"
                      href="https://amdal.menlhk.go.id/amdalnet/assets/uploads/cluster_kbli/pertek11-85720220812131605.pdf"
                    ><span class="fa fa-download" /> &nbsp;
                      Rincian Teknis Limbah B3</a>
                  </li>
                  <li>

                    <!-- <a href="javascript:void(0);" class="btn btn-info btn-xs" onclick="if (confirm('Apa anda yakin ingin menghapus data ini?')) window.location='https://amdal.menlhk.go.id/amdalnet/Cluster_Kbli/delete/5';">Edit</a> -->
                    <!-- <a href="javascript:void(0);" class="btn btn-danger btn-
                                              Permanen</a> -->
                  </li>
                </div>
                <div />
              </ul>
            </span>
          </el-col>
        </el-row>
        <el-row class="bg-custom">
          <el-col :span="1" class="text-center py">
            <span class="fz12 white fw">7</span>
          </el-col>
          <el-col :span="8" class="py">
            <span class="fz12 white">
              <b>Industri Berbasis Lahan dengan Pembangunan Sarana
                Prasarana (Perakitan dan Sejenisnya)</b><br>
              <u>Cluster KBLI:</u><br>
              10611, 10612, 10613, 10621, 10631, 10632, 10211,
              10212, 10213, 10214, 10215, 10216, 10217, 10219,
              10221, 10222, 10291, 10292, 10293, 10294, 10295,
              10296, 10297, 10298, 10299, 10414, 10799, 10794,
              16102, 16104, 10130, 10213, 10311, 10391, 10392,
              10413, 10414, 10415, 10421, 10431, 10432, 10434,
              10510, 10590, 10636, 10732, 10740, 10750, 10771,
              10773, 10774, 10792, 12091, 13111, 13112, 13911,
              13912, 13913, 16105, 16211, 16212, 16213, 16214,
              16215, 16221, 16222, 16230, 16295, 16299, 17012,
              17019, 17021, 17022, 17091, 17099, 32111, 26220,
              26511, 26512, 26513, 26514, 26520, 26601, 26602,
              27111, 27112, 27113, 27120, 27202, 27402, 27403,
              27404, 27900, 28111, 28112, 28113, 28120, 28140,
              28152, 28160, 28171, 28180, 28191, 28192, 28193,
              28199, 28210, 28221, 28222, 28223, 28224, 28230,
              28240, 28250, 28262, 28263, 28264, 28265, 28291,
              28292, 28299, 29200, 29300, 30111, 30112, 30113,
              30120, 30200, 30912, 30921, 30922, 30990, 31001,
              31002, 31003, 31009, 32909, 21015, 32501, 23121,
              86903, 32201, 32202, 32300, 32401, 32402, 32501,
              32502, 32503, 32509, 32901, 32902, 32903, 32904,
              32905, 33111, 33121, 33122, 33131, 33132, 33133,
              33141, 33142, 33149, 45201, 45407, 58130, 95110,
              95120, 95210, 95220, 95240, 22193, 22194, 23124,
              23911, 23919, 23931, 23932, 23933, 23939, 23961,
              15201, 15202, 15203, 15209, 33151<br>
              <a
                target="_blank"
                class="white"
                href="https://amdal.menlhk.go.id/amdalnet/assets/uploads/cluster_kbli/06_Template%20Formulir%20UKL-UPL%20Standar%20Spesifik%20Usaha%20Industri%20Berbasis%20Lahan_final.pdf"
              ><span class="fa fa-download" /> &nbsp;Template
                Formulir</a>
            </span>
          </el-col>
          <el-col :span="8" class="py">
            <span class="fz12 white">
              <ul>
                <li>
                  SOP B.2.2 Peningkatan TSP (debu) akibat
                  Pembersihan Lahan dan Pematangan Lahan
                </li>
                <li>
                  SOP B.1.1 Peningkatan Kesempatan Kerja Akibat
                  Penerimaan Tenaga Kerja
                </li>
                <li>
                  SOP B.2.3 Peningkatan Air Larian Akibat
                  Pembersihan Lahan dan Pematangan Lahan
                </li>
                <li>
                  SOP B.3.2 Peningkatan TSP (debu) Akibat Mobilisasi
                  Peralatan dan Material
                </li>
                <li>
                  SOP B.4.2 Peningkatan TSP (debu) akibat
                  pembangunan fasilitas/unit utama dan
                  fasilitas/unit pendukung
                </li>
                <li>
                  SOP C.1.1 Peningkatan Kesempatan Kerja Akibat
                  Penerimaan Tenaga Kerja
                </li>
                <li>
                  SOP C.1.3 Peningkatan Pendapatan Akibat Penerimaan
                  Tenaga Kerja
                </li>
                <li>
                  SOP B.4.1 Peningkatan Kebisingan Akibat
                  Pembangunan Fasilitas/Unit Utama dan
                  Fasilitas/Unit Pendukung / Fasilitas Budidaya
                </li>
                <li>
                  SOP B.4.7 Gangguan Arus Lalu Lintas Akibat
                  Pembangunan Fasilitas/Unit Utama dan
                  Fasilitas/Unit Pendukung
                </li>
                <li>
                  SOP C.1.4 Peningkatan Keresahan dan Konflik
                  Masyarakat Akibat Penerimaan Tenaga Kerja
                </li>
                <li>
                  SOP A.2.4 Hilangnya mata pencaharian akibat
                  pembebasan lahan
                </li>
                <li>
                  SOP B.2.10 Perubahan Persepsi Masyarakat Akibat
                  Pembersihan dan Pematangan Lahan
                </li>
                <li>
                  SOP B.3.1 Peningkatan Kebisingan Akibat Mobilisasi
                  Peralatan dan Material
                </li>
                <li>
                  SOP A.1.1 Perubahan persepsi masyarakat akibat
                  sosialisasi
                </li>
                <li>
                  SOP A.2.2 Timbulnya konflik sosial akibat
                  pembebasan lahan
                </li>
                <li>
                  SOP B.3.4 Gangguan Arus Lalu Lintas Akibat
                  Mobilisasi Peralatan dan Material
                </li>
                <li>
                  SOP B.4.5 Penurunan Kualitas Air Permukaan Akibat
                  Pembangunan fasilitas/unit utama dan fasilitasunit
                  pendukung
                </li>
                <li>
                  SOP A.2.1 Perubahan Persepsi Masyarakat Akibat
                  Pembebasan Lahan
                </li>
                <li>
                  SOP A.2.5 Perubahan pendapatan akibat pembebasan
                  lahan
                </li>
                <li>
                  SOP B.2.11 Gangguan Kenyamanan Masyarakat Akibat
                  Pembersihan dan Pematangan Lahan
                </li>
                <li>
                  SOP B.3.3 Perubahan Persepsi Masyarakat Akibat
                  Mobilisasi Peralatan dan Material
                </li>
                <li>
                  SOP B.3.5 Kerusakan Jalan Akibat Mobilisasi
                  Peralatan dan Material
                </li>
                <li>
                  SOP C.2.11 Penurunan Kualitas Air Permukaan Akibat
                  Kegiatan Operasional Unit/Fasilitas Utama dan
                  Unit/Fasilitas Pendukung
                </li>
                <li>
                  SOP A.4.1 Perubahan persepsi masyarakat akibat
                  pengambilan data kondisi lingkungan (rona
                  lingkungan hidup awal)
                </li>
                <li>
                  SOP B.1.2 Peningkatan Peluang Berusaha dan
                  Pendapatan akibat Penerimaan Tenaga Kerja
                </li>
                <li>
                  SOP B.2.1 Penurunan Kualitas Air Permukaan Akibat
                  Pembersihan Lahan dan Pematangan Lahan
                </li>
                <li>
                  SOP B.2.4 Peningkatan Kebisingan Akibat
                  Pembersihan dan Pematangan Lahan
                </li>
                <li>
                  SOP B.2.9 Potensi Banjir/Genangan Akibat
                  Pembersihan dan Pematangan Lahan
                </li>
                <li>
                  SOP C.1.2 Peningkatan Peluang Berusaha Akibat
                  Penerimaan Tenaga Kerja
                </li>
                <li>
                  SOP C.2.2 Peningkatan Limbah Padat (Sampah) Akibat
                  Kegiatan Operasional Unit/Fasilitas Utama dan
                  Unit/Fasilitas Pendukung
                </li>
                <li>
                  SOP C.2.4 Peningkatan Timbulan Limbah B3 Akibat
                  Kegiatan Operasional Unit/Fasilitas Utama dan
                  Unit/Fasilitas Pendukung
                </li>
                <li>
                  SOP C.2.6 Peningkatan Gangguan Lalu Lintas Akibat
                  Kegiatan Operasional Unit/Fasilitas Utama dan
                  Unit/Fasilitas Pendukung
                </li>
                <li>
                  SOP C.2.8 Penurunan Kesehatan Masyarakat Akibat
                  Kegiatan Operasional Unit/Fasilitas Utama dan
                  Unit/Fasilitas Pendukung
                </li>
                <li>
                  SOP C.2.9 Peningkatan Keresahan dan Konflik
                  Masyarakat Akibat Kegiatan Operasional
                  Unit/Fasilitas Utama dan Unit/Fasilitas Pendukung
                </li>
                <li>
                  SOP C.2.12 Penurunan Kualitas Air Tanah Akibat
                  Kegiatan Operasional Unit/Fasilitas Utama dan
                  Unit/Fasilitas Pendukung
                </li>
                <li>
                  SOP C.2.20 Kerusakan Jalan Akibat Kegiatan
                  Operasional Unit/Fasilitas Utama Dan
                  Unit/Fasilitas Pendukung
                </li>
                <li>
                  SOP C.2.21 Penurunan Kualitas Udara (Emisi dari
                  Cerobong) Akibat Kegiatan Operasional
                  Unit/Fasilitas Utama dan Unit/Fasilitas Pendukung
                </li>
                <li>
                  SOP D.1.2 Peningkatan Keresahan Masyarakat Akibat
                  Pelepasan Tenaga Kerja
                </li>
                <li>
                  SOP D.3.2 Peningkatan Kebisingan Akibat
                  Pembongkaran Fasilitas Operasional (Fasilitas
                  Utama dan/atau Pendukung)
                </li>
                <li>
                  SOP D.3.1 Penurunan Kualitas Udara Akibat
                  Pembongkaran Fasilitas Operasional (Fasilitas
                  Utama dan/atau Pendukung)
                </li>
              </ul>
            </span>
          </el-col>
          <el-col :span="4" class="py">
            <span class="fz12 white">
              <ul>
                <div>
                  <li>
                    <a
                      target="_blank"
                      class="white"
                      href="https://amdal.menlhk.go.id/amdalnet/assets/uploads/cluster_kbli/pertek1-40220210605141911.pdf"
                    ><span class="fa fa-download" /> &nbsp;
                      Pertek Pembuangan Air Limbah ke Badan Air
                      Permukaan
                    </a>
                  </li>
                </div>
                <div>
                  <li>
                    <a
                      target="_blank"
                      class="white"
                      href="https://amdal.menlhk.go.id/amdalnet/assets/uploads/cluster_kbli/pertek2-28920210605141911.pdf"
                    ><span class="fa fa-download" /> &nbsp;
                      Pertek Pembuangan dan/atau Pemanfaatan Air
                      Limbah ke Formasi tertentu</a>
                  </li>
                </div>
                <div />
                <div />
                <div>
                  <li>
                    <a
                      target="_blank"
                      class="white"
                      href="https://amdal.menlhk.go.id/amdalnet/assets/uploads/cluster_kbli/pertek5-92420210605141911.pdf"
                    ><span class="fa fa-download" /> &nbsp;
                      Pertek Pemenuhan Baku Mutu Emisi
                    </a>
                  </li>
                </div>
                <div />
                <div />
                <div />
                <div />
                <div />
                <div>
                  <li>
                    <a
                      target="_blank"
                      class="white"
                      href="https://amdal.menlhk.go.id/amdalnet/assets/uploads/cluster_kbli/pertek11-27220220812131701.pdf"
                    ><span class="fa fa-download" /> &nbsp;
                      Rincian Teknis Limbah B3</a>
                  </li>
                  <li>

                    <!-- <a href="javascript:void(0);" class="btn btn-info btn-xs" onclick="if (confirm('Apa anda yakin ingin menghapus data ini?')) window.location='https://amdal.menlhk.go.id/amdalnet/Cluster_Kbli/delete/8';">Edit</a> -->
                    <!-- <a href="javascript:void(0);" class="btn btn-danger btn-
                                              Permanen</a> -->
                  </li>
                </div>
                <div />
              </ul>
            </span>
          </el-col>
        </el-row>
        <el-row class="bg-custom">
          <el-col :span="1" class="text-center py">
            <span class="fz12 white fw">8</span>
          </el-col>
          <el-col :span="8" class="py">
            <span class="fz12 white">
              <b>Industri Berbasis Produksi dengan Pembangunan
                Sarana dan Prasarana (Bahan Baku --&gt; Proses
                --&gt; Produk, Semisal Industri Sawit/CPO yang
                Menghasilkan Berbagai Produk Turunan)</b><br>
              <u>Cluster KBLI:</u><br>
              19100, 19213, 19291, 19292, 20111, 20112, 20113,
              20114, 20122, 20123, 20124, 20125, 20126, 20127,
              20129, 20131, 20211, 20212, 20213, 20214, 20221,
              20222, 20223, 20224, 20232, 20233, 20234, 20291,
              20293, 20295, 20296, 20299, 21012, 21013, 21015,
              21021, 21022, 21023, 22112, 24202, 24203, 24205,
              24310, 24320, 25120, 25130, 25910, 25931, 25994,
              18113, 13921, 13922, 13923, 13925, 13929, 13930,
              14302, 14303, 15122, 56305, 20231, 32112, 32113,
              32114, 32115, 32119, 32120, 37021, 37022, 38301,
              23957, 27401<br>
              <a
                target="_blank"
                class="white"
                href="https://amdal.menlhk.go.id/amdalnet/assets/uploads/cluster_kbli/07_Template%20Formulir%20UKL-UPL%20Standar%20Spesifik%20Usaha%20Industri%20Berbasis%20Produksi_final.pdf"
              ><span class="fa fa-download" /> &nbsp;Template
                Formulir</a>
            </span>
          </el-col>
          <el-col :span="8" class="py">
            <span class="fz12 white">
              <ul>
                <li>
                  SOP B.2.2 Peningkatan TSP (debu) akibat
                  Pembersihan Lahan dan Pematangan Lahan
                </li>
                <li>
                  SOP B.1.1 Peningkatan Kesempatan Kerja Akibat
                  Penerimaan Tenaga Kerja
                </li>
                <li>
                  SOP B.2.3 Peningkatan Air Larian Akibat
                  Pembersihan Lahan dan Pematangan Lahan
                </li>
                <li>
                  SOP B.3.2 Peningkatan TSP (debu) Akibat Mobilisasi
                  Peralatan dan Material
                </li>
                <li>
                  SOP B.4.2 Peningkatan TSP (debu) akibat
                  pembangunan fasilitas/unit utama dan
                  fasilitas/unit pendukung
                </li>
                <li>
                  SOP C.1.1 Peningkatan Kesempatan Kerja Akibat
                  Penerimaan Tenaga Kerja
                </li>
                <li>
                  SOP C.1.3 Peningkatan Pendapatan Akibat Penerimaan
                  Tenaga Kerja
                </li>
                <li>
                  SOP B.4.1 Peningkatan Kebisingan Akibat
                  Pembangunan Fasilitas/Unit Utama dan
                  Fasilitas/Unit Pendukung / Fasilitas Budidaya
                </li>
                <li>
                  SOP B.4.7 Gangguan Arus Lalu Lintas Akibat
                  Pembangunan Fasilitas/Unit Utama dan
                  Fasilitas/Unit Pendukung
                </li>
                <li>
                  SOP C.1.4 Peningkatan Keresahan dan Konflik
                  Masyarakat Akibat Penerimaan Tenaga Kerja
                </li>
                <li>
                  SOP A.2.4 Hilangnya mata pencaharian akibat
                  pembebasan lahan
                </li>
                <li>
                  SOP B.2.10 Perubahan Persepsi Masyarakat Akibat
                  Pembersihan dan Pematangan Lahan
                </li>
                <li>
                  SOP B.3.1 Peningkatan Kebisingan Akibat Mobilisasi
                  Peralatan dan Material
                </li>
                <li>
                  SOP A.1.1 Perubahan persepsi masyarakat akibat
                  sosialisasi
                </li>
                <li>
                  SOP A.2.2 Timbulnya konflik sosial akibat
                  pembebasan lahan
                </li>
                <li>
                  SOP B.3.4 Gangguan Arus Lalu Lintas Akibat
                  Mobilisasi Peralatan dan Material
                </li>
                <li>
                  SOP B.4.5 Penurunan Kualitas Air Permukaan Akibat
                  Pembangunan fasilitas/unit utama dan fasilitasunit
                  pendukung
                </li>
                <li>
                  SOP A.2.1 Perubahan Persepsi Masyarakat Akibat
                  Pembebasan Lahan
                </li>
                <li>
                  SOP A.2.5 Perubahan pendapatan akibat pembebasan
                  lahan
                </li>
                <li>
                  SOP B.2.11 Gangguan Kenyamanan Masyarakat Akibat
                  Pembersihan dan Pematangan Lahan
                </li>
                <li>
                  SOP B.3.3 Perubahan Persepsi Masyarakat Akibat
                  Mobilisasi Peralatan dan Material
                </li>
                <li>
                  SOP B.3.5 Kerusakan Jalan Akibat Mobilisasi
                  Peralatan dan Material
                </li>
                <li>
                  SOP C.2.11 Penurunan Kualitas Air Permukaan Akibat
                  Kegiatan Operasional Unit/Fasilitas Utama dan
                  Unit/Fasilitas Pendukung
                </li>
                <li>
                  SOP A.4.1 Perubahan persepsi masyarakat akibat
                  pengambilan data kondisi lingkungan (rona
                  lingkungan hidup awal)
                </li>
                <li>
                  SOP B.1.2 Peningkatan Peluang Berusaha dan
                  Pendapatan akibat Penerimaan Tenaga Kerja
                </li>
                <li>
                  SOP B.2.1 Penurunan Kualitas Air Permukaan Akibat
                  Pembersihan Lahan dan Pematangan Lahan
                </li>
                <li>
                  SOP B.2.4 Peningkatan Kebisingan Akibat
                  Pembersihan dan Pematangan Lahan
                </li>
                <li>
                  SOP B.2.9 Potensi Banjir/Genangan Akibat
                  Pembersihan dan Pematangan Lahan
                </li>
                <li>
                  SOP C.1.2 Peningkatan Peluang Berusaha Akibat
                  Penerimaan Tenaga Kerja
                </li>
                <li>
                  SOP C.2.2 Peningkatan Limbah Padat (Sampah) Akibat
                  Kegiatan Operasional Unit/Fasilitas Utama dan
                  Unit/Fasilitas Pendukung
                </li>
                <li>
                  SOP C.2.3 Peningkatan Kebauan Akibat Kegiatan
                  Operasional Unit/Fasilitas Utama dan
                  Unit/Fasilitas Pendukung
                </li>
                <li>
                  SOP C.2.4 Peningkatan Timbulan Limbah B3 Akibat
                  Kegiatan Operasional Unit/Fasilitas Utama dan
                  Unit/Fasilitas Pendukung
                </li>
                <li>
                  SOP C.2.6 Peningkatan Gangguan Lalu Lintas Akibat
                  Kegiatan Operasional Unit/Fasilitas Utama dan
                  Unit/Fasilitas Pendukung
                </li>
                <li>
                  SOP C.2.8 Penurunan Kesehatan Masyarakat Akibat
                  Kegiatan Operasional Unit/Fasilitas Utama dan
                  Unit/Fasilitas Pendukung
                </li>
                <li>
                  SOP C.2.9 Peningkatan Keresahan dan Konflik
                  Masyarakat Akibat Kegiatan Operasional
                  Unit/Fasilitas Utama dan Unit/Fasilitas Pendukung
                </li>
                <li>
                  SOP C.2.14 Penurunan Kuantitas Air Tanah Akibat
                  Kegiatan Operasional Unit/Fasilitas Utama dan
                  Unit/Fasilitas Pendukung
                </li>
                <li>
                  SOP C.2.20 Kerusakan Jalan Akibat Kegiatan
                  Operasional Unit/Fasilitas Utama Dan
                  Unit/Fasilitas Pendukung
                </li>
                <li>
                  SOP C.2.21 Penurunan Kualitas Udara (Emisi dari
                  Cerobong) Akibat Kegiatan Operasional
                  Unit/Fasilitas Utama dan Unit/Fasilitas Pendukung
                </li>
                <li>
                  SOP D.1.2 Peningkatan Keresahan Masyarakat Akibat
                  Pelepasan Tenaga Kerja
                </li>
                <li>
                  SOP D.3.2 Peningkatan Kebisingan Akibat
                  Pembongkaran Fasilitas Operasional (Fasilitas
                  Utama dan/atau Pendukung)
                </li>
                <li>
                  SOP D.3.1 Penurunan Kualitas Udara Akibat
                  Pembongkaran Fasilitas Operasional (Fasilitas
                  Utama dan/atau Pendukung)
                </li>
              </ul>
            </span>
          </el-col>
          <el-col :span="4" class="py">
            <span class="fz12 white">
              <ul>
                <div>
                  <li>
                    <a
                      target="_blank"
                      class="white"
                      href="https://amdal.menlhk.go.id/amdalnet/assets/uploads/cluster_kbli/pertek1-76720210605140710.pdf"
                    ><span class="fa fa-download" /> &nbsp;
                      Pertek Pembuangan Air Limbah ke Badan Air
                      Permukaan
                    </a>
                  </li>
                </div>
                <div>
                  <li>
                    <a
                      target="_blank"
                      class="white"
                      href="https://amdal.menlhk.go.id/amdalnet/assets/uploads/cluster_kbli/pertek2-78720210605140710.pdf"
                    ><span class="fa fa-download" /> &nbsp;
                      Pertek Pembuangan dan/atau Pemanfaatan Air
                      Limbah ke Formasi tertentu</a>
                  </li>
                </div>
                <div />
                <div />
                <div>
                  <li>
                    <a
                      target="_blank"
                      class="white"
                      href="https://amdal.menlhk.go.id/amdalnet/assets/uploads/cluster_kbli/pertek5-97920210605141230.pdf"
                    ><span class="fa fa-download" /> &nbsp;
                      Pertek Pemenuhan Baku Mutu Emisi
                    </a>
                  </li>
                </div>
                <div />
                <div />
                <div />
                <div />
                <div />
                <div>
                  <li>
                    <a
                      target="_blank"
                      class="white"
                      href="https://amdal.menlhk.go.id/amdalnet/assets/uploads/cluster_kbli/pertek11-26520220812131818.pdf"
                    ><span class="fa fa-download" /> &nbsp;
                      Rincian Teknis Limbah B3</a>
                  </li>
                  <li>

                    <!-- <a href="javascript:void(0);" class="btn btn-info btn-xs" onclick="if (confirm('Apa anda yakin ingin menghapus data ini?')) window.location='https://amdal.menlhk.go.id/amdalnet/Cluster_Kbli/delete/9';">Edit</a> -->
                    <!-- <a href="javascript:void(0);" class="btn btn-danger btn-
                                              Permanen</a> -->
                  </li>
                </div>
                <div />
              </ul>
            </span>
          </el-col>
        </el-row>
        <el-row class="bg-custom">
          <el-col :span="1" class="text-center py">
            <span class="fz12 white fw">9</span>
          </el-col>
          <el-col :span="8" class="py">
            <span class="fz12 white">
              <b>Kegiatan Pengerukan/ Dredging</b><br>
              <u>Cluster KBLI:</u><br>
              42914<br>
              <a
                target="_blank"
                class="white"
                href="https://amdal.menlhk.go.id/amdalnet/assets/uploads/cluster_kbli/08_Template%20Formulir%20UKL-UPL%20Standar%20Spesifik%20untuk%20kegiatan%20pengerukan_final.pdf"
              ><span class="fa fa-download" /> &nbsp;Template
                Formulir</a>
            </span>
          </el-col>
          <el-col :span="8" class="py">
            <span class="fz12 white">
              <ul>
                <li>
                  SOP B.1.1 Peningkatan Kesempatan Kerja Akibat
                  Penerimaan Tenaga Kerja
                </li>
                <li>
                  SOP B.3.2 Peningkatan TSP (debu) Akibat Mobilisasi
                  Peralatan dan Material
                </li>
                <li>
                  SOP C.1.1 Peningkatan Kesempatan Kerja Akibat
                  Penerimaan Tenaga Kerja
                </li>
                <li>
                  SOP C.1.3 Peningkatan Pendapatan Akibat Penerimaan
                  Tenaga Kerja
                </li>
                <li>
                  SOP C.1.4 Peningkatan Keresahan dan Konflik
                  Masyarakat Akibat Penerimaan Tenaga Kerja
                </li>
                <li>
                  SOP B.3.1 Peningkatan Kebisingan Akibat Mobilisasi
                  Peralatan dan Material
                </li>
                <li>
                  SOP A.1.1 Perubahan persepsi masyarakat akibat
                  sosialisasi
                </li>
                <li>
                  SOP B.3.4 Gangguan Arus Lalu Lintas Akibat
                  Mobilisasi Peralatan dan Material
                </li>
                <li>
                  SOP B.3.3 Perubahan Persepsi Masyarakat Akibat
                  Mobilisasi Peralatan dan Material
                </li>
                <li>
                  SOP B.3.5 Kerusakan Jalan Akibat Mobilisasi
                  Peralatan dan Material
                </li>
                <li>
                  SOP C.2.11 Penurunan Kualitas Air Permukaan Akibat
                  Kegiatan Operasional Unit/Fasilitas Utama dan
                  Unit/Fasilitas Pendukung
                </li>
                <li>
                  SOP A.4.1 Perubahan persepsi masyarakat akibat
                  pengambilan data kondisi lingkungan (rona
                  lingkungan hidup awal)
                </li>
                <li>
                  SOP B.1.2 Peningkatan Peluang Berusaha dan
                  Pendapatan akibat Penerimaan Tenaga Kerja
                </li>
                <li>
                  SOP C.1.2 Peningkatan Peluang Berusaha Akibat
                  Penerimaan Tenaga Kerja
                </li>
                <li>
                  SOP C.2.17 Penurunan Kualitas Air Laut Akibat
                  Kegiatan Operasional Unit/Fasilitas Utama dan
                  Unit/Fasilitas Pendukung
                </li>
                <li>
                  SOP C.2.18 Gangguan Biota Laut Akibat Operasional
                  Unit/Fasilitas Utama Dan Unit/Fasilitas Pendukung
                </li>
                <li>
                  SOP C.2.22 Gangguan Aktivitas Nelayan Akibat
                  Kegiatan Operasional Unit/Fasilitas Utama Dan
                  Unit/Fasilitas Pendukung
                </li>
                <li>
                  SOP C.2.23 Gangguan Transportasi Laut Akibat
                  Kegiatan Operasional Unit/Fasilitas Utama dan
                  Unit/Fasilitas Pendukung
                </li>
                <li>
                  SOP C.2.24 Gangguan Terumbu Karang Akibat Kegiatan
                  Operasional Unit/Fasilitas Utama dan
                  Unit/Fasilitas Pendukung
                </li>
              </ul>
            </span>
          </el-col>
          <el-col :span="4" class="py">
            <span class="fz12 white">
              <ul>
                <div />
                <div />
                <div />
                <div />
                <div>
                  <li>
                    <a
                      target="_blank"
                      class="white"
                      href="https://amdal.menlhk.go.id/amdalnet/assets/uploads/cluster_kbli/pertek5-38620210605141508.pdf"
                    ><span class="fa fa-download" /> &nbsp;
                      Pertek Pemenuhan Baku Mutu Emisi
                    </a>
                  </li>
                </div>
                <div />
                <div />
                <div />
                <div />
                <div />
                <div>
                  <li>
                    <a
                      target="_blank"
                      class="white"
                      href="https://amdal.menlhk.go.id/amdalnet/assets/uploads/cluster_kbli/pertek11-59420220812131846.pdf"
                    ><span class="fa fa-download" /> &nbsp;
                      Rincian Teknis Limbah B3</a>
                  </li>
                  <li>

                    <!-- <a href="javascript:void(0);" class="btn btn-info btn-xs" onclick="if (confirm('Apa anda yakin ingin menghapus data ini?')) window.location='https://amdal.menlhk.go.id/amdalnet/Cluster_Kbli/delete/10';">Edit</a> -->
                    <!-- <a href="javascript:void(0);" class="btn btn-danger btn-
                                              Permanen</a> -->
                  </li>
                </div>
                <div />
              </ul>
            </span>
          </el-col>
        </el-row>
        <el-row class="bg-custom">
          <el-col :span="1" class="text-center py">
            <span class="fz12 white fw">10</span>
          </el-col>
          <el-col :span="8" class="py">
            <span class="fz12 white">
              <b>Penyiapan Lahan untuk Kegiatan Sektor
                Transportasi</b><br>
              <u>Cluster KBLI:</u><br>
              43120<br>
              <a
                target="_blank"
                class="white"
                href="https://amdal.menlhk.go.id/amdalnet/assets/uploads/cluster_kbli/09_Template%20Formulir%20UKL-UPL%20Standar%20Spesifik%20Penyiapan%20lahan%20sektor%20transportasi_final.pdf"
              ><span class="fa fa-download" /> &nbsp;Template
                Formulir</a>
            </span>
          </el-col>
          <el-col :span="8" class="py">
            <span class="fz12 white">
              <ul>
                <li>
                  SOP B.2.2 Peningkatan TSP (debu) akibat
                  Pembersihan Lahan dan Pematangan Lahan
                </li>
                <li>
                  SOP B.1.1 Peningkatan Kesempatan Kerja Akibat
                  Penerimaan Tenaga Kerja
                </li>
                <li>
                  SOP B.2.3 Peningkatan Air Larian Akibat
                  Pembersihan Lahan dan Pematangan Lahan
                </li>
                <li>
                  SOP B.3.2 Peningkatan TSP (debu) Akibat Mobilisasi
                  Peralatan dan Material
                </li>
                <li>
                  SOP B.4.2 Peningkatan TSP (debu) akibat
                  pembangunan fasilitas/unit utama dan
                  fasilitas/unit pendukung
                </li>
                <li>
                  SOP C.1.1 Peningkatan Kesempatan Kerja Akibat
                  Penerimaan Tenaga Kerja
                </li>
                <li>
                  SOP C.1.3 Peningkatan Pendapatan Akibat Penerimaan
                  Tenaga Kerja
                </li>
                <li>
                  SOP B.4.1 Peningkatan Kebisingan Akibat
                  Pembangunan Fasilitas/Unit Utama dan
                  Fasilitas/Unit Pendukung / Fasilitas Budidaya
                </li>
                <li>
                  SOP B.4.7 Gangguan Arus Lalu Lintas Akibat
                  Pembangunan Fasilitas/Unit Utama dan
                  Fasilitas/Unit Pendukung
                </li>
                <li>
                  SOP A.2.3 Peningkatan keresahan masyarakat akibat
                  pembebasan lahan
                </li>
                <li>
                  SOP C.1.4 Peningkatan Keresahan dan Konflik
                  Masyarakat Akibat Penerimaan Tenaga Kerja
                </li>
                <li>
                  SOP C.2.1 Peningkatan Air Limbah Akibat Kegiatan
                  Operasional Unit/Fasilitas Utama dan
                  Unit/Fasilitas Pendukung
                </li>
                <li>
                  SOP A.2.4 Hilangnya mata pencaharian akibat
                  pembebasan lahan
                </li>
                <li>
                  SOP B.3.1 Peningkatan Kebisingan Akibat Mobilisasi
                  Peralatan dan Material
                </li>
                <li>
                  SOP A.1.1 Perubahan persepsi masyarakat akibat
                  sosialisasi
                </li>
                <li>
                  SOP A.2.2 Timbulnya konflik sosial akibat
                  pembebasan lahan
                </li>
                <li>
                  SOP B.3.4 Gangguan Arus Lalu Lintas Akibat
                  Mobilisasi Peralatan dan Material
                </li>
                <li>
                  SOP B.4.5 Penurunan Kualitas Air Permukaan Akibat
                  Pembangunan fasilitas/unit utama dan fasilitasunit
                  pendukung
                </li>
                <li>
                  SOP A.2.1 Perubahan Persepsi Masyarakat Akibat
                  Pembebasan Lahan
                </li>
                <li>
                  SOP A.2.5 Perubahan pendapatan akibat pembebasan
                  lahan
                </li>
                <li>
                  SOP B.2.11 Gangguan Kenyamanan Masyarakat Akibat
                  Pembersihan dan Pematangan Lahan
                </li>
                <li>
                  SOP B.3.3 Perubahan Persepsi Masyarakat Akibat
                  Mobilisasi Peralatan dan Material
                </li>
                <li>
                  SOP B.3.5 Kerusakan Jalan Akibat Mobilisasi
                  Peralatan dan Material
                </li>
                <li>
                  SOP A.4.1 Perubahan persepsi masyarakat akibat
                  pengambilan data kondisi lingkungan (rona
                  lingkungan hidup awal)
                </li>
                <li>
                  SOP B.1.2 Peningkatan Peluang Berusaha dan
                  Pendapatan akibat Penerimaan Tenaga Kerja
                </li>
                <li>
                  SOP B.2.1 Penurunan Kualitas Air Permukaan Akibat
                  Pembersihan Lahan dan Pematangan Lahan
                </li>
                <li>
                  SOP B.2.4 Peningkatan Kebisingan Akibat
                  Pembersihan dan Pematangan Lahan
                </li>
                <li>
                  SOP B.2.5 Peningkatan Keresahan Masyarakat Akibat
                  Pembersihan dan Pematangan Lahan
                </li>
                <li>
                  SOP B.2.9 Potensi Banjir/Genangan Akibat
                  Pembersihan dan Pematangan Lahan
                </li>
                <li>
                  SOP C.1.2 Peningkatan Peluang Berusaha Akibat
                  Penerimaan Tenaga Kerja
                </li>
                <li>
                  SOP C.2.5 Peningkatan Kebisingan Akibat Kegiatan
                  Operasional Unit/Fasilitas Utama dan
                  Unit/Fasilitas Pendukung
                </li>
                <li>
                  SOP C.2.6 Peningkatan Gangguan Lalu Lintas Akibat
                  Kegiatan Operasional Unit/Fasilitas Utama dan
                  Unit/Fasilitas Pendukung
                </li>
                <li>
                  SOP C.2.9 Peningkatan Keresahan dan Konflik
                  Masyarakat Akibat Kegiatan Operasional
                  Unit/Fasilitas Utama dan Unit/Fasilitas Pendukung
                </li>
                <li>
                  SOP C.2.20 Kerusakan Jalan Akibat Kegiatan
                  Operasional Unit/Fasilitas Utama Dan
                  Unit/Fasilitas Pendukung
                </li>
                <li>
                  SOP D.1.1 Penurunan Pendapatan Masyarakat Akibat
                  Pelepasan Tenaga Kerja
                </li>
                <li>
                  SOP D.3.2 Peningkatan Kebisingan Akibat
                  Pembongkaran Fasilitas Operasional (Fasilitas
                  Utama dan/atau Pendukung)
                </li>
                <li>
                  SOP D.3.1 Penurunan Kualitas Udara Akibat
                  Pembongkaran Fasilitas Operasional (Fasilitas
                  Utama dan/atau Pendukung)
                </li>
              </ul>
            </span>
          </el-col>
          <el-col :span="4" class="py">
            <span class="fz12 white">
              <ul>
                <div />
                <div />
                <div />
                <div />
                <div>
                  <li>
                    <a
                      target="_blank"
                      class="white"
                      href="https://amdal.menlhk.go.id/amdalnet/assets/uploads/cluster_kbli/pertek5-95120210605141305.pdf"
                    ><span class="fa fa-download" /> &nbsp;
                      Pertek Pemenuhan Baku Mutu Emisi
                    </a>
                  </li>
                </div>
                <div />
                <div />
                <div />
                <div />
                <div />
                <div>
                  <li>
                    <a
                      target="_blank"
                      class="white"
                      href="https://amdal.menlhk.go.id/amdalnet/assets/uploads/cluster_kbli/pertek11-91220210607132916.pdf"
                    ><span class="fa fa-download" /> &nbsp;
                      Rincian Teknis Limbah B3</a>
                  </li>
                  <li>

                    <!-- <a href="javascript:void(0);" class="btn btn-info btn-xs" onclick="if (confirm('Apa anda yakin ingin menghapus data ini?')) window.location='https://amdal.menlhk.go.id/amdalnet/Cluster_Kbli/delete/11';">Edit</a> -->
                    <!-- <a href="javascript:void(0);" class="btn btn-danger btn-
                                              Permanen</a> -->
                  </li>
                </div>
                <div />
              </ul>
            </span>
          </el-col>
        </el-row>
        <el-row class="bg-custom">
          <el-col :span="1" class="text-center py">
            <span class="fz12 white fw">11</span>
          </el-col>
          <el-col :span="8" class="py">
            <span class="fz12 white">
              <b>Kegiatan Perdagangan, Jasa dan Pariwisata yang
                Membutuhkan Sarana dan Prasarana</b><br>
              <u>Cluster KBLI:</u><br>
              93231, 91021, 91022, 93221, 93224, 93239, 49425,
              55193, 55120, 55110, 55194, 56101, 93292, 93219,
              96129, 93229, 93193, 47215, 47245, 47753, 47754,
              47727, 47728, 47843, 47723, 47844, 47724, 47725,
              35129, 03111, 03112, 03113, 03115, 03116, 03117,
              03118, 03119, 03121, 03122, 03123, 03124, 03125,
              03126, 03129, 03133, 03143, 03231, 03232, 03233,
              03241, 03242, 03243, 03261, 03262, 03263, 50133,
              01135, 01612, 01630, 01640, 01611, 01613, 01614,
              01619, 25920, 33200, 62090, 82301, 85499, 52229,
              52298, 50135, 52296, 49423, 86902, 55900, 79121,
              53202, 61994, 62014, 62015, 62024, 39000, 49433,
              50143, 58110, 58120, 58190, 74111, 74112, 74113,
              74114, 74115, 74116, 74117, 74118, 74119, 87302,
              87303, 87304, 87305, 87902, 87903, 87904, 87905,
              87906, 88102, 88992, 96111, 96112, 01715, 02305,
              03114, 03131, 03132, 03141, 03142, 01727, 42204,
              41016, 36001, 68111<br>
              <a
                target="_blank"
                class="white"
                href="https://amdal.menlhk.go.id/amdalnet/assets/uploads/cluster_kbli/10_Template%20Formulir%20UKL-UPL%20Standar%20Spesifik%20Usaha%20perdagangan%20jasa%20dan%20pariwisata_final.pdf"
              ><span class="fa fa-download" /> &nbsp;Template
                Formulir</a>
            </span>
          </el-col>
          <el-col :span="8" class="py">
            <span class="fz12 white">
              <ul>
                <li>
                  SOP B.2.2 Peningkatan TSP (debu) akibat
                  Pembersihan Lahan dan Pematangan Lahan
                </li>
                <li>
                  SOP B.1.1 Peningkatan Kesempatan Kerja Akibat
                  Penerimaan Tenaga Kerja
                </li>
                <li>
                  SOP B.2.3 Peningkatan Air Larian Akibat
                  Pembersihan Lahan dan Pematangan Lahan
                </li>
                <li>
                  SOP B.3.2 Peningkatan TSP (debu) Akibat Mobilisasi
                  Peralatan dan Material
                </li>
                <li>
                  SOP B.4.2 Peningkatan TSP (debu) akibat
                  pembangunan fasilitas/unit utama dan
                  fasilitas/unit pendukung
                </li>
                <li>
                  SOP C.1.1 Peningkatan Kesempatan Kerja Akibat
                  Penerimaan Tenaga Kerja
                </li>
                <li>
                  SOP C.1.3 Peningkatan Pendapatan Akibat Penerimaan
                  Tenaga Kerja
                </li>
                <li>
                  SOP B.4.1 Peningkatan Kebisingan Akibat
                  Pembangunan Fasilitas/Unit Utama dan
                  Fasilitas/Unit Pendukung / Fasilitas Budidaya
                </li>
                <li>
                  SOP B.4.7 Gangguan Arus Lalu Lintas Akibat
                  Pembangunan Fasilitas/Unit Utama dan
                  Fasilitas/Unit Pendukung
                </li>
                <li>
                  SOP A.2.3 Peningkatan keresahan masyarakat akibat
                  pembebasan lahan
                </li>
                <li>
                  SOP C.1.4 Peningkatan Keresahan dan Konflik
                  Masyarakat Akibat Penerimaan Tenaga Kerja
                </li>
                <li>
                  SOP C.2.1 Peningkatan Air Limbah Akibat Kegiatan
                  Operasional Unit/Fasilitas Utama dan
                  Unit/Fasilitas Pendukung
                </li>
                <li>
                  SOP B.3.1 Peningkatan Kebisingan Akibat Mobilisasi
                  Peralatan dan Material
                </li>
                <li>
                  SOP A.1.1 Perubahan persepsi masyarakat akibat
                  sosialisasi
                </li>
                <li>
                  SOP A.2.2 Timbulnya konflik sosial akibat
                  pembebasan lahan
                </li>
                <li>
                  SOP B.3.4 Gangguan Arus Lalu Lintas Akibat
                  Mobilisasi Peralatan dan Material
                </li>
                <li>
                  SOP B.4.5 Penurunan Kualitas Air Permukaan Akibat
                  Pembangunan fasilitas/unit utama dan fasilitasunit
                  pendukung
                </li>
                <li>
                  SOP A.2.1 Perubahan Persepsi Masyarakat Akibat
                  Pembebasan Lahan
                </li>
                <li>
                  SOP B.2.11 Gangguan Kenyamanan Masyarakat Akibat
                  Pembersihan dan Pematangan Lahan
                </li>
                <li>
                  SOP B.3.3 Perubahan Persepsi Masyarakat Akibat
                  Mobilisasi Peralatan dan Material
                </li>
                <li>
                  SOP B.3.5 Kerusakan Jalan Akibat Mobilisasi
                  Peralatan dan Material
                </li>
                <li>
                  SOP A.4.1 Perubahan persepsi masyarakat akibat
                  pengambilan data kondisi lingkungan (rona
                  lingkungan hidup awal)
                </li>
                <li>
                  SOP B.1.2 Peningkatan Peluang Berusaha dan
                  Pendapatan akibat Penerimaan Tenaga Kerja
                </li>
                <li>
                  SOP B.2.1 Penurunan Kualitas Air Permukaan Akibat
                  Pembersihan Lahan dan Pematangan Lahan
                </li>
                <li>
                  SOP B.2.4 Peningkatan Kebisingan Akibat
                  Pembersihan dan Pematangan Lahan
                </li>
                <li>
                  SOP B.2.5 Peningkatan Keresahan Masyarakat Akibat
                  Pembersihan dan Pematangan Lahan
                </li>
                <li>
                  SOP B.2.9 Potensi Banjir/Genangan Akibat
                  Pembersihan dan Pematangan Lahan
                </li>
                <li>
                  SOP C.1.2 Peningkatan Peluang Berusaha Akibat
                  Penerimaan Tenaga Kerja
                </li>
                <li>
                  SOP C.2.6 Peningkatan Gangguan Lalu Lintas Akibat
                  Kegiatan Operasional Unit/Fasilitas Utama dan
                  Unit/Fasilitas Pendukung
                </li>
                <li>
                  SOP C.2.9 Peningkatan Keresahan dan Konflik
                  Masyarakat Akibat Kegiatan Operasional
                  Unit/Fasilitas Utama dan Unit/Fasilitas Pendukung
                </li>
                <li>
                  SOP C.2.20 Kerusakan Jalan Akibat Kegiatan
                  Operasional Unit/Fasilitas Utama Dan
                  Unit/Fasilitas Pendukung
                </li>
                <li>
                  SOP D.1.1 Penurunan Pendapatan Masyarakat Akibat
                  Pelepasan Tenaga Kerja
                </li>
                <li>
                  SOP D.3.2 Peningkatan Kebisingan Akibat
                  Pembongkaran Fasilitas Operasional (Fasilitas
                  Utama dan/atau Pendukung)
                </li>
                <li>
                  SOP D.3.1 Penurunan Kualitas Udara Akibat
                  Pembongkaran Fasilitas Operasional (Fasilitas
                  Utama dan/atau Pendukung)
                </li>
              </ul>
            </span>
          </el-col>
          <el-col :span="4" class="py">
            <span class="fz12 white">
              <ul>
                <div>
                  <li>
                    <a
                      target="_blank"
                      class="white"
                      href="https://amdal.menlhk.go.id/amdalnet/assets/uploads/cluster_kbli/pertek1-83220210605140333.pdf"
                    ><span class="fa fa-download" /> &nbsp;
                      Pertek Pembuangan Air Limbah ke Badan Air
                      Permukaan
                    </a>
                  </li>
                </div>
                <div>
                  <li>
                    <a
                      target="_blank"
                      class="white"
                      href="https://amdal.menlhk.go.id/amdalnet/assets/uploads/cluster_kbli/pertek2-88920210605140333.pdf"
                    ><span class="fa fa-download" /> &nbsp;
                      Pertek Pembuangan dan/atau Pemanfaatan Air
                      Limbah ke Formasi tertentu</a>
                  </li>
                </div>
                <div />
                <div />
                <div>
                  <li>
                    <a
                      target="_blank"
                      class="white"
                      href="https://amdal.menlhk.go.id/amdalnet/assets/uploads/cluster_kbli/pertek5-11720210605140918.pdf"
                    ><span class="fa fa-download" /> &nbsp;
                      Pertek Pemenuhan Baku Mutu Emisi
                    </a>
                  </li>
                </div>
                <div />
                <div />
                <div />
                <div />
                <div />
                <div>
                  <li>
                    <a
                      target="_blank"
                      class="white"
                      href="https://amdal.menlhk.go.id/amdalnet/assets/uploads/cluster_kbli/pertek11-93120210607132848.pdf"
                    ><span class="fa fa-download" /> &nbsp;
                      Rincian Teknis Limbah B3</a>
                  </li>
                  <li>

                    <!-- <a href="javascript:void(0);" class="btn btn-info btn-xs" onclick="if (confirm('Apa anda yakin ingin menghapus data ini?')) window.location='https://amdal.menlhk.go.id/amdalnet/Cluster_Kbli/delete/12';">Edit</a> -->
                    <!-- <a href="javascript:void(0);" class="btn btn-danger btn-
                                              Permanen</a> -->
                  </li>
                </div>
                <div />
              </ul>
            </span>
          </el-col>
        </el-row>
        <!-- <div class="block" style="text-align: right">
          <pagination
            v-show="total > 0"
            :auto-scroll="false"
            :total="total"
            :page.sync="listQuery.page"
            :limit.sync="listQuery.limit"
            @pagination="getAll"
          />
        </div> -->
      </template>
      <!-- <template v-else>
        <el-row class="bg-custom" :gutter="20">
          <el-col :xs="24" style="padding-top: 0.5rem; padding-bottom: 0.5rem">
            <el-alert
              title="Warning"
              type="warning"
              description="No Record Found"
            />
          </el-col>
        </el-row>
      </template> -->
    </div>
  </section>
</template>

<script>
import axios from 'axios';
// import Pagination from '@/components/Pagination';

export default {
  name: 'Kebijakan',
  components: {
    // Pagination,
  },
  data() {
    return {
      value: '',
      search: '',
      allData: [],
      regulations: [],
      total: 0,
      listQuery: {
        page: 1,
        limit: 10,
      },
      keyword: '',
      optionValue: null,
      sort: 'ASC',
      tableData: [
        {
          no: '1000',
          kebijakan: 'PP Nomor 22 Tahun 2021',
          jenis: 'Peraturan Pemerintah',
          bidang: 'Lingkungan Hidup',
          tentang:
            'Penyelenggaraan Perlindungan dan Pegelolaan Lingkungan Hidup',
          tanggal: '01 Mar 2021',
          link: 'https://amdal.menlhk.go.id/amdal_site/uploads/kebijakan/PP Nomor 22 Tahun 2021.pdf',
        },
      ],
    };
  },
  created() {
    this.getAll();
    this.getRegulations();
  },
  methods: {
    formatDateStr(date) {
      const today = new Date(date);
      var bulan = [
        'Januari',
        'Februari',
        'Maret',
        'April',
        'Mei',
        'Juni',
        'Juli',
        'Agustus',
        'September',
        'Oktober',
        'November',
        'Desember',
      ];
      const year = today.getFullYear();
      const month = today.getMonth();
      const day = today.getDate();
      const monthID = bulan[month];
      const finalDate = `${day} ${monthID} ${year}`;
      return finalDate;
    },
    getAll(search, sort) {
      axios
        .get(
          `/api/policys?keyword=${this.keyword}&page=${this.listQuery.page}&sort=${this.sort}`
        )
        .then((response) => {
          this.allData = response.data.data;
          this.total = response.data.total;
        });
    },
    getRegulations() {
      axios.get(`/api/regulations`).then((response) => {
        this.regulations = response.data;
      });
    },
    handleSearch() {
      this.getAll(this.keyword);
    },
    handleFilter() {
      axios
        .get(
          `/api/policys?type=${this.optionValue}&page=${this.listQuery.page}`
        )
        .then((response) => {
          this.allData = response.data.data;
          this.total = response.data.total;
        });
    },
    handleSort() {
      if (this.sort === 'ASC') {
        this.sort = 'DESC';
      } else {
        this.sort = 'ASC';
      }
      this.getAll(null, this.sort);
    },
    GetFilename(url) {
      if (url) {
        var m = url.toString().match(/.*\/(.+?)\./);
        if (m && m.length > 1) {
          return m[1];
        }
      }
      return '';
    },
  },
};
</script>

<style scoped>
#kebijakan {
  background-color: #133715;
  padding-top: 11rem;
  padding-bottom: 11rem;
}
.fz12 {
  font-size: 12px;
}
.fw {
  font-weight: bold;
}
.white {
  color: #fff;
}
.text-center {
  text-align: center;
}

.py {
  padding-top: 0.5rem;
  padding-bottom: 0.5rem;
}
.py1 {
  padding-top: 1rem;
  padding-bottom: 1rem;
}
.bb {
  border-bottom: 1px solid #2b3d31;
}
.br {
  border-right: 1px solid #2b3d31;
}
.bg-custom {
  background-color: #041608;
}
.cl-blue {
  color: #204f67;
}
.tb-hover:hover {
  background-color: #133715;
}
.tb-hover:hover a {
  color: #fff;
}
.mb-1-5 {
  margin-bottom: 1.5rem;
}
.mb-1 {
  margin-bottom: 1rem;
}
.d-flex {
  display: flex;
}
.align-items-center {
  align-items: center;
}
.justify-align-center {
  justify-content: center;
}
.ml-0-3 {
  margin-left: 0.3rem;
}
.cp {
  cursor: pointer;
}
.pb-0 {
  padding-bottom: 0;
}
.buttonDownload {
  display: inline-block;
  background: #099c4b;
  padding: 0.4rem;
  color: #fff;
  border-radius: 10px;
}
.el-table__header-wrapper {
  background-color: red;
}
.fz9{font-size: 9px;}
</style>
