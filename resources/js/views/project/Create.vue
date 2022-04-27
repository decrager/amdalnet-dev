<template>
  <div class="form-container" style="padding: 24px">
    <workflow :is-penapisan="true" />
    <div v-if="preProject" v-loading="loading">
      <el-collapse v-model="activeName" :accordion="true">
        <el-collapse-item title="TAPAK PROYEK" name="1" disabled>
          <el-form
            ref="tapakProyek"
            :model="currentProject"
            :rules="tapakProyekRules"
            label-position="top"
            label-width="200px"
            style="max-width: 100%"
          >
            <el-row :gutter="4">
              <el-col :span="12">
                <el-form-item
                  label="Nama Rencana Usaha/Kegiatan"
                  prop="project_title"
                >
                  <keep-alive>
                    <el-input v-model="currentProject.project_title" size="medium" />
                  </keep-alive>
                </el-form-item>
              </el-col>
              <el-col :span="12">
                <el-form-item label="Upload Dokumen Kesesuaian Tata Ruang (Max 1MB, Opsional)" prop="fileKtr">
                  <classic-upload :name="fileKtrName" :fid="'ktrFile'" @handleFileUpload="handleFileKtrUpload($event)" />
                </el-form-item>
              </el-col>
            </el-row>
            <el-row :gutter="4">
              <el-col :span="12">
                <el-row>
                  <el-form-item label="Deskripsi Kegiatan" prop="description">
                    <span slot="label">
                      <span>Deskripsi Kegiatan <el-tooltip class="item" effect="dark" content="Deskripsi rencana usaha/kegiatan yang akan dilakukan mencakup kegiatan utama yang akan dilakukan dan sarana serta prasarana kegiatan pendukung yang akan dibangun. kegiatan utama yang akan dilakukan bisa saja lebih dari 1 jenis kegiatan dan begitu pula dengan kegiatan pendukungnya. Jelaskan pula keterkaitan lokasi rencana usaha dan/atau kegiatan dan kesesuaiannya dengan rencana tata ruang wilayah" placement="top">
                        <i class="el-alert__icon el-icon-warning" />
                      </el-tooltip></span>
                    </span>
                    <editor
                      v-model="currentProject.description"
                      :menubar="''"
                      :image="false"
                      :height="150"
                      :toolbar="['bold italic underline bullist numlist  preview undo redo fullscreen']"
                    />
                    <!-- <el-input v-model="currentProject.description" size="medium" type="textarea" /> -->
                  </el-form-item>
                </el-row>
                <el-row>
                  <el-form-item v-if="!isUmk" label="Deskripsi Lokasi" prop="location_desc">
                    <editor
                      v-model="currentProject.location_desc"
                      :menubar="''"
                      :image="false"
                      :height="150"
                      :toolbar="['bold italic underline bullist numlist  preview undo redo fullscreen']"
                    />
                    <!-- <el-input v-model="currentProject.location_desc" size="medium" type="textarea" /> -->
                  </el-form-item>
                </el-row>
              </el-col>
              <el-col :span="12">
                <el-row>
                  <el-form-item v-if="!isUmk" label="" prop="filePdf">
                    <div slot="label">
                      <span>* Upload Peta PDF (Max 1MB) <el-tooltip class="item" effect="dark" content="Peta yang diunggah adalah peta tapak proyek yang telah dibuat oleh pemrakarsa atau lampiran peta atas rekomendasi kesesuaian tata ruang(format pdf ukuran A3)" placement="top">
                        <i class="el-alert__icon el-icon-warning" />
                      </el-tooltip></span>
                    </div>
                    <classic-upload :name="filePdfName" :fid="'filePdf'" @handleFileUpload="handleFileTapakProyekPdfUpload" />
                  </el-form-item>
                  <el-form-item v-else label="Deskripsi Lokasi" prop="location_desc">
                    <editor
                      v-model="currentProject.location_desc"
                      :menubar="''"
                      :image="false"
                      :height="150"
                      :toolbar="['bold italic underline bullist numlist  preview undo redo fullscreen']"
                    />
                    <!-- <el-input v-model="currentProject.location_desc" size="medium" type="textarea" /> -->
                  </el-form-item>
                </el-row>
              </el-col>
              <el-col :span="12" style="margin-top: 17px;">
                <el-row>
                  <el-form-item v-if="!isUmk" label="" prop="fileMap">
                    <div slot="label">
                      <span>* Upload SHP Peta Tapak Proyek (File .zip max 1 MB)</span>
                      <a href="/sample_map/Peta_Tapak_Sample_Amdalnet.zip" class="download__sample" title="Download Sample SHP" target="_blank" rel="noopener noreferrer"><i class="ri-road-map-line" /> Download Contoh Shp</a>
                      <a href="/amdalnet-juknis-penyiapan-peta.pdf" class="download__juknis" title="Download Juknis Peta" target="_blank" rel="noopener noreferrer"><i class="ri-file-line" /> Download Juknis Peta</a>
                    </div>
                    <classic-upload :name="fileMapName" :fid="'fileMap'" @handleFileUpload="handleFileTapakProyekMapUpload" />
                  </el-form-item>
                </el-row>
              </el-col>
            </el-row>
            <div v-show="fileMap" id="mapView" style="height: 600px;" />
            <div style="margin-top: 10px">
              <span v-if="full_address !== ''" style="font-style: italic; color: red">Alamat : {{ full_address }} </span>
            </div>
            <el-row :gutter="4">
              <el-col :span="12">
                <el-form-item label="Apakah Lokasi Rencana Usaha dan/atau Kegiatan Anda Masuk dalam Kawasan Peta Indikatif Penghentian Pemberian Izin Baru(PIPPIB)?" prop="pippib">
                  <el-radio-group v-model="currentProject.pippib">
                    <el-radio :label="'Y'">Ya</el-radio>
                    <el-radio :label="'N'">Tidak</el-radio>
                  </el-radio-group>
                </el-form-item>
              </el-col>
              <el-col :span="12">
                <el-form-item v-if="currentProject.pippib === 'Y'" label="Upload Surat Pengecualian PIPPIB (Max 1MB, Opsional)" prop="filepippib">
                  <classic-upload :name="filepippibName" :fid="'ppippibFile'" @handleFileUpload="handleFilePIPPIBUpload($event)" />
                </el-form-item>
              </el-col>
            </el-row>
            <el-row :gutter="4">
              <el-col :span="12">
                <el-form-item label="Apakah Lokasi Rencana Usaha dan/atau Kegiatan Anda Masuk dalam Kawasan Lindung?" prop="kawasan_lindung">
                  <el-radio-group v-model="currentProject.kawasan_lindung">
                    <el-radio :label="'Y'">Ya</el-radio>
                    <el-radio :label="'N'">Tidak</el-radio>
                  </el-radio-group>
                </el-form-item>
              </el-col>
              <el-col :span="12">
                <el-form-item v-if="currentProject.kawasan_lindung === 'Y'" label="Unggah Surat Pengecualian Dalam Kawasan Lindung (Max 1MB, Opsional)" prop="fileKawasanLindung">
                  <classic-upload :name="fileKawasanLindungName" :fid="'kawasanLindungFile'" @handleFileUpload="handleFileKawasanLindungUpload($event)" />
                </el-form-item>
              </el-col>
            </el-row>
            <el-row type="flex" justify="end">
              <el-col :span="2">
                <el-button size="medium" type="primary" @click="goToPendekatanStudi">
                  Lanjutkan
                </el-button>
              </el-col>
            </el-row>
          </el-form>
        </el-collapse-item>
        <el-collapse-item title="PENDEKATAN STUDI" name="2" disabled>
          <div role="alert" class="el-alert el-alert--info is-light">
            <i class="el-alert__icon el-icon-warning is-big" />
            <div class="el-alert__content"><span class="el-alert__title is-bold">Pendekatan Studi</span>
              <p class="el-alert__description">
                <ul>
                  <li><b>Tunggal : </b> Apabila penanggung jawab Usaha dan/atau Kegiatan merencanakan untuk melakukan 1 (satu) jenis Usaha dan/atau Kegiatan yang kewenangan pembinaan dan/atau pengawasannya berada di bawah 1 (satu) kementerian, lembaga pemerintah nonkementerian, organisasi perangkat daerah provinsi, atau organisasi perangkat daerah kabupaten/kota.</li>
                  <li><b>Terpadu : </b> Apabila penanggung jawab Usaha dan/atau Kegiatan merencanakan untuk melakukan lebih dari 1 (satu) jenis Usaha dan/atau Kegiatan yang perencanaan dan pengelolaannya saling terkait dalam satu kesatuan hamparan ekosistem serta pembinaan dan/atau pengawasannya berada di bawah lebih dari 1 (satu) kementerian, lembaga pemerintah nonkementerian, organisasi perangkat daerah provinsi, atau organisasi perangkat daerah kabupaten/kota.</li>
                  <li><b>Kawasan : </b> pengelola kawasan selaku penanggung jawab. Usaha dan/atau Kegiatan yang merencanakan untuk melakukan lebih dari 1 (satu) Usaha dan/atau Kegiatan yang akan dilaksanakan oleh Pelaku Usaha di dalam kawasan, terletak dalam satu kesatuan zona rencana pengembangan kawasan, yang telah mendapatkan penetapan kawasan dan pengelola kawasan sesuai dengan ketentuan peraturan perundang-undangan.</li>
                </ul>
              </p>
            </div>
          </div>
          <div role="alert" class="el-alert el-alert--info is-light" style="margin-top: 10px">
            <div class="el-alert__content">
              <p class="el-alert__description">
                <ul>
                  <li><b>Daftar Kegiatan : </b>Silahkan pilih kegiatan yang berada dalam satu hamparan(Dalam satu lokasi yang berdekatan, yang terhubung satu dengan lainnya) contoh: Kegiatan Minyak dan Gas, Pipa Penyaluran, Unit Pengelola</li>
                </ul>
              </p>
            </div>
          </div>
          <el-alert
            v-if="!currentProject.isPemerintah"
            style="margin-top: 5px"
            title="Daftar Proyek dibawah ini merupakan data yang dikirim dari OSS-RBA"
            type="error"
            effect="dark"
            :closable="false"
          />
          <el-form
            ref="pendekatanStudi"
            :model="currentProject"
            :rules="pendekatanStudiRules"
            label-position="top"
            label-width="200px"
            style="max-width: 100%"
          >
            <el-row>
              <el-col :span="12">
                <el-form-item
                  label="Pendekatan Studi untuk Kegiatan"
                  prop="study_approach"
                >
                  <el-select
                    v-model="currentProject.study_approach"
                    placeholder="Pilih"
                    style="width: 100%"
                    size="medium"
                    @change="changeStudyApproach($event)"
                  >
                    <el-option
                      v-for="item in studyApproachOptions"
                      :key="item.value.id"
                      :label="item.label"
                      :value="item.value"
                    />
                  </el-select>
                </el-form-item>
              </el-col>
            </el-row>
            <el-row>
              <el-form-item prop="listSubProject">
                <keep-alive>
                  <sub-project-table :list="listSubProject" :list-kbli="getListKbli" :from-oss="fromOss" :idizin="idizin" @handlechecked="handlechecked($event)" />
                </keep-alive>
                <el-button
                  type="primary"
                  :disabled="fromOss"
                  @click="handleAddSubProjectTable"
                >+</el-button>
              </el-form-item>
            </el-row>
            <!-- Alamat -->
            <el-row type="flex" justify="end" :gutter="4">
              <el-col :span="24" :xs="24">
                <el-form-item label="Alamat" prop="address">
                  <el-table :key="refresh" :data="currentProject.address" max-height="800" :header-cell-style="{ background: '#099C4B', color: 'white' }">
                    <el-table-column align="center" width="55">
                      <template slot-scope="scope">
                        <el-checkbox v-model="scope.row.isUsed" :disabled="fromOss" />
                      </template>
                    </el-table-column>
                    <el-table-column align="center" label="No." width="55">
                      <template slot-scope="scope">
                        {{ scope.$index + 1 }}
                      </template>
                    </el-table-column>
                    <el-table-column label="Provinsi" width="200px">
                      <template slot-scope="scope">
                        <el-select
                          v-model="scope.row.prov"
                          :disabled="fromOss"
                          placeholder="Pilih"
                          style="width: 100%"
                          filterable
                          @change="changeProvince(scope.row)"
                        >
                          <el-option
                            v-for="item in getProvinceOptions"
                            :key="item.value"
                            :label="item.label"
                            :value="item.value"
                          />
                        </el-select>
                      </template>
                    </el-table-column>
                    <el-table-column label="Kota/Kabupaten" width="300px">
                      <template slot-scope="scope">
                        <el-select
                          v-model="scope.row.district"
                          :disabled="fromOss"
                          placeholder="Pilih"
                          style="width: 100%"
                          filterable
                        >
                          <el-option
                            v-for="item in scope.row.districts"
                            :key="item.value"
                            :label="item.label"
                            :value="item.value"
                          />
                        </el-select>
                      </template>
                    </el-table-column>

                    <el-table-column label="Alamat">
                      <template slot-scope="scope">
                        <el-input v-model="scope.row.address" :disabled="fromOss" />
                      </template>
                    </el-table-column>

                    <!-- <el-table-column width="100px">
                      <template slot-scope="scope">
                        <el-popconfirm
                          title="Hapus Alamat ?"
                          @confirm="currentProject.address.splice(scope.$index,1)"
                        >
                          <el-button slot="reference" type="danger" icon="el-icon-close" />
                        </el-popconfirm>
                      </template>
                    </el-table-column> -->
                  </el-table>
                  <el-button
                    type="primary"
                    :disabled="fromOss"
                    @click="handleAddAddressTable"
                  >+</el-button>
                </el-form-item>
              </el-col>
            </el-row>
            <el-row type="flex" justify="end">
              <el-col :span="5" style="padding-right: 0px">
                <el-button size="medium" @click="activeName = '1'">
                  Kembali
                </el-button>
                <el-button size="medium" type="primary" @click="handleStudyAccord">
                  Lanjutkan
                </el-button>
              </el-col>
            </el-row>
          </el-form>
        </el-collapse-item>
        <el-collapse-item v-if="currentProject.isPemerintah" title="PENENTUAN KEWENANGAN" name="7" disabled>
          <el-form
            ref="penentuanKewenangan"
            :model="currentProject"
            :rules="penentuanKewenanganRules"
            label-position="top"
            label-width="200px"
            style="max-width: 100%"
          >
            <el-row>
              <el-form-item prop="listKewenangan">
                <keep-alive>
                  <kewenangan-table :list="currentProject.listKewenangan" />
                </keep-alive>
                <el-button
                  type="primary"
                  @click="handleAddKewenangan"
                >+</el-button>
              </el-form-item>
            </el-row>
            <el-row type="flex" justify="end">
              <el-col :span="5">
                <el-button size="medium" @click="activeName = '2'">
                  Kembali
                </el-button>
                <el-button size="medium" type="primary" @click="goToStatusKegiatanPemerintah">
                  Lanjutkan
                </el-button>
              </el-col>
            </el-row>
          </el-form>
        </el-collapse-item>
        <el-collapse-item title="STATUS KEGIATAN" name="3" disabled>
          <el-form
            ref="statusKegiatan"
            :model="currentProject"
            :rules="statusKegiatanRules"
            label-position="top"
            label-width="200px"
            style="max-width: 100%"
          >
            <el-row>
              <el-col :span="12">
                <el-form-item
                  label="Rencana Kegiatan Baru atau Kegiatan Yang Sudah Berjalan"
                  prop="status"
                >
                  <el-select
                    v-model="currentProject.status"
                    placeholder="Pilih"
                    style="width: 100%"
                    size="medium"
                    @change="changeStatus($event)"
                  >
                    <el-option
                      v-for="item in statusOptions"
                      :key="item.value.id"
                      :label="item.label"
                      :value="item.value"
                    />
                  </el-select>
                </el-form-item>
              </el-col>
            </el-row>
            <el-row type="flex" justify="end">
              <el-col :span="5">
                <el-button size="medium" @click="handleBackStatusKegiatan">
                  Kembali
                </el-button>
                <el-button size="medium" type="primary" @click="goToJenisKegiatan">
                  Lanjutkan
                </el-button>
              </el-col>
            </el-row>
          </el-form></el-collapse-item>
        <el-collapse-item title="JENIS KEGIATAN" name="4" disabled>
          <el-form
            ref="jenisKegiatan"
            :model="currentProject"
            :rules="jenisKegiatanRules"
            label-position="top"
            label-width="200px"
            style="max-width: 100%"
          >
            <el-row>
              <el-col :span="12">
                <el-form-item
                  label="Kegiatan Baru atau Bagian dari Kegiatan Sebelumnya?"
                  prop="project_type"
                >
                  <el-select
                    v-model="currentProject.project_type"
                    placeholder="Pilih"
                    style="width: 100%"
                    size="medium"
                    @change="changeProjectType($event)"
                  >
                    <el-option
                      v-for="item in projectTypeOptions"
                      :key="item.value.id"
                      :label="item.label"
                      :value="item.value"
                    />
                  </el-select>
                </el-form-item>
              </el-col>
            </el-row>
            <el-row type="flex" justify="end">
              <el-col :span="5">
                <el-button size="medium" @click="activeName = '3'">
                  Kembali
                </el-button>
                <el-button size="medium" type="primary" @click="goToPersetujuanAwal">
                  Lanjutkan
                </el-button>
              </el-col>
            </el-row>
          </el-form>
        </el-collapse-item>
        <el-form
          ref="currentProject"
          :model="currentProject"
          :rules="currentProjectRules"
          label-position="top"
          label-width="200px"
          style="max-width: 100%"
        >
          <el-collapse-item title="PERSETUJUAN AWAL (Opsional)" name="5" disabled>
            <div role="alert" class="el-alert el-alert--info is-light">
              <div class="el-alert__content">
                <span class="el-alert__title">
                  <b>Persetujuan Awal :</b> Izin prinsip yang dikeluarkan oleh instansi yang berwenang sesuai dengan jenis rencana kegiatan
                </span>
              </div>
            </div>
            <el-row>
              <el-col :span="12">
                <el-form-item
                  label="Jenis Kegiatan"
                  prop="pre_agreement"
                >
                  <el-select
                    v-model="currentProject.pre_agreement"
                    clearable
                    placeholder="Pilih"
                    style="width: 100%"
                    size="medium"
                    @change="changeProjectType($event)"
                    @clear="removePreAgreement"
                  >
                    <el-option
                      v-for="item in preAgreementOptions"
                      :key="item.value.id"
                      :label="item.label"
                      :value="item.value"
                    />
                  </el-select>
                </el-form-item>

                <el-form-item
                  v-if="currentProject.pre_agreement"
                  :label="preeAgreementLabel + ' Max 1MB'"
                  prop="pre_agreement"
                >
                  <!-- <input ref="filePreAgreement" type="file" class="el-input__inner" @change="handleFilePreAgreementUpload"> -->
                  <classic-upload :name="filePreAgreementName" :fid="'filePreAgreement'" @handleFileUpload="handleFilePreAgreementUpload" />
                  <el-tag v-if="currentProject.pre_agreement === 'Lainnya'" type="info" style="width: 100%; height: 36px; margin-top: 5px; padding-top: 5px">Silahkan Mengurus Dokumen Persetujuan Investasi</el-tag>
                </el-form-item>
              </el-col>
            </el-row>
            <el-row type="flex" justify="end">
              <el-col :span="5">
                <el-button size="medium" @click="activeName = '4'">
                  Kembali
                </el-button>
                <el-button size="medium" type="primary" @click="activeName = '6'">
                  Lanjutkan
                </el-button>
              </el-col>
            </el-row>
          </el-collapse-item>
          <el-collapse-item title="PERSETUJUAN TEKNIS (Opsional)" name="6" disabled>
            <el-row>
              <el-col :span="24">
                <el-form-item
                  label="Limbah Yang Dihasilkan dari Rencana Usaha dan/atau Kegiatan :"
                >
                  <el-checkbox v-model="currentProject.wastewater" label="Air Limbah" @change="currentProject.nothing = false" />
                  <el-checkbox v-model="currentProject.emission" label="Emisi Gas Buang" @change="currentProject.nothing = false" />
                  <el-checkbox v-model="currentProject.b3" label="Limbah B3" @change="currentProject.nothing = false" />
                  <el-checkbox v-model="currentProject.traffics" label="Gangguan Lalu Lintas" @change="currentProject.nothing = false" />
                  <!-- <el-checkbox v-model="currentProject.nothing" label="Tidak Ada" @change="handleNothingTick" /> -->
                </el-form-item>
              </el-col>
            </el-row>
            <el-row style="margin-bottom: 10px">
              <el-col v-if="currentProject.wastewater" :span="18">
                <el-card>
                  <table style="width: 100%; text-align: left;">
                    <tr>
                      <th style="width: 350px">Jenis Kegiatan</th>
                      <th>Masuk Dalam Pencemaran Tinggi ?</th>
                    </tr>
                    <tr>
                      <td><div>
                            <el-checkbox v-model="currentProject.disposal_wastewater" label="Pembuangan Air Limbah" border />
                          </div>
                        <div>
                          <el-checkbox v-model="currentProject.utilization_wastewater" style="margin-top: 5px" label="Pemanfaatan Air Limbah" border />
                        </div>
                      </td>
                      <td>
                        <el-radio-group v-model="currentProject.high_pollution">
                          <el-radio :label="true">Ya</el-radio>
                          <el-radio :label="false">Tidak</el-radio>
                        </el-radio-group>
                      </td>
                    </tr>
                  </table>
                  <el-tag v-if="currentProject.high_pollution || currentProject.disposal_wastewater || currentProject.utilization_wastewater" type="danger" style="width: 100%; height: 36px; margin-top: 5px; padding-top: 5px">Anda Membutuhkan <b> Persetujuan Teknis Standar </b> Air Limbah</el-tag>
                </el-card>
              </el-col>
            </el-row>
            <el-row style="margin-bottom: 10px">
              <el-col v-if="currentProject.emission" :span="18">
                <el-card>
                  <table style="width: 100%; text-align: left;">
                    <tr>
                      <th style="width: 350px">Jenis Kegiatan</th>
                      <th>Masuk Dalam Emisi Tinggi ?</th>
                    </tr>
                    <tr>
                      <td><div>
                            <el-checkbox v-model="currentProject.chimney" label="Melalui Cerobong Asap" border />
                          </div>
                        <div>
                          <el-checkbox v-model="currentProject.genset" style="margin-top: 5px" label="Pembuangan Emisi Gas Buang Dari Genset" border />
                        </div>
                      </td>
                      <td>
                        <el-radio-group v-model="currentProject.high_emission">
                          <el-radio :label="true">Ya</el-radio>
                          <el-radio :label="false">Tidak</el-radio>
                        </el-radio-group>
                      </td>
                    </tr>
                  </table>
                  <el-tag v-if="currentProject.high_emission || currentProject.genset || currentProject.chimney" type="danger" style="width: 100%; height: 36px; margin-top: 5px; padding-top: 5px">Anda Membutuhkan <b> Persetujuan Teknis Standar </b> Gas Buang</el-tag>
                </el-card>
              </el-col>
            </el-row>
            <el-row style="margin-bottom: 10px">
              <el-col v-if="currentProject.b3" :span="18">
                <el-card>
                  <table style="width: 100%; text-align: left;">
                    <tr>
                      <th>Jenis Kegiatan</th>
                      <th />
                      <th />
                    </tr>
                    <tr>
                      <td>
                        <div>
                          <el-checkbox v-model="currentProject.collect_b3" label="Pengumpulan Limbah B3" border />
                        </div>
                        <div>
                          <el-checkbox v-model="currentProject.process_b3" style="margin-top: 5px" label="Pengolahan Limbah B3" border />
                        </div>
                        <div>
                          <el-checkbox v-model="currentProject.utilization_b3" style="margin-top: 5px" label="Pemanfaatan Limbah B3" border />
                        </div>
                      </td>
                      <td>
                        <div>
                          <el-checkbox v-model="currentProject.dumping_b3" label="Dumping Limbah B3" border />
                        </div>
                        <div>
                          <el-checkbox v-model="currentProject.hoard_b3" style="margin-top: 5px" label="Penimbunan Limbah B3" border />
                        </div>
                        <div>
                          <el-checkbox v-model="currentProject.transport_b3" style="margin-top: 5px" label="Pengangkutan Limbah B3" border />
                        </div>
                      </td>
                      <td>
                        <div>
                          <el-checkbox v-model="currentProject.tps" style="margin-top: 5px" label="Penyimpanan Limbah B3" border />
                        </div>
                      </td>
                    </tr>
                  </table>
                  <el-tag v-if="currentProject.collect_b3 || currentProject.process_b3 || currentProject.utilization_b3 || currentProject.hoard_b3 || currentProject.dumping_b3" type="danger" style="width: 100%; height: 36px; margin-top: 5px; padding-top: 5px">Anda Membutuhkan <b> Persetujuan Teknis Dengan Kajian Teknis </b> Limbah B3</el-tag>
                  <el-tag v-if="currentProject.tps" type="danger" style="width: 100%; height: 36px; margin-top: 5px; padding-top: 5px">Anda Membutuhkan <b> Rincian Teknis </b> Limbah B3</el-tag>
                </el-card>
              </el-col>
            </el-row>
            <el-row style="margin-bottom: 10px">
              <el-col v-if="currentProject.traffics" :span="18">
                <div>
                  <el-card>
                    <table style="width: 100%; text-align: left;">
                      <tr>
                        <th>Besaran Dampak Lalu Lintas Di Tahap Operasi :</th>
                      </tr>
                      <tr>
                        <td>
                          <el-radio-group v-model="currentProject.traffic">
                            <el-radio label="low">Rendah</el-radio>
                            <el-radio label="middle">Sedang</el-radio>
                            <el-radio label="high">Tinggi</el-radio>
                          </el-radio-group>
                        </td>
                      </tr>
                    </table>
                    <el-tag v-if="currentProject.traffic === 'low'" type="danger" style="width: 100%; height: 36px; margin-top: 5px; padding-top: 5px">Anda Membutuhkan <b> Persetujuan Teknis Standar </b> Gangguan Lalu Lintas</el-tag>
                    <el-tag v-else-if="currentProject.traffic === 'middle'" type="danger" style="width: 100%; height: 36px; margin-top: 5px; padding-top: 5px">Anda Membutuhkan <b> Persetujuan Teknis Rekomendasi </b> Gangguan Lalu Lintas</el-tag>
                    <el-tag v-else-if="currentProject.traffic === 'high'" type="danger" style="width: 100%; height: 36px; margin-top: 5px; padding-top: 5px">Anda Membutuhkan <b> Persetujuan Teknis Kajian Teknis </b> Gangguan Lalu Lintas</el-tag>
                  </el-card>
                </div>
              </el-col>
            </el-row>
            <el-row type="flex" justify="end">
              <el-col :span="5">
                <el-button size="medium" @click="activeName = '5'"> Kembali </el-button>
                <el-button size="medium" type="primary" @click="handleSubmit"> Lanjutkan </el-button>
              </el-col>
            </el-row>
          </el-collapse-item>
        </el-form>
      </el-collapse>
    </div>

  </div>

</template>

<script>
import { mapGetters } from 'vuex';
import Workflow from '@/components/Workflow';
import ClassicUpload from '@/components/ClassicUpload';
import Resource from '@/api/resource';
// import SupportTable from './components/SupportTable.vue';
import SubProjectTable from './components/SubProjectTable.vue';
import KewenanganTable from './components/KewenanganTable.vue';
import 'vue-simple-accordion/dist/vue-simple-accordion.css';
const SupportDocResource = new Resource('support-docs');
const provinceResource = new Resource('provinces');
const districtResource = new Resource('districts');
const authorityResource = new Resource('authorities');

import MapImageLayer from '@arcgis/core/layers/MapImageLayer';
import Map from '@arcgis/core/Map';
import MapView from '@arcgis/core/views/MapView';
import Legend from '@arcgis/core/widgets/Legend';
import LayerList from '@arcgis/core/widgets/LayerList';
import GeoJSONLayer from '@arcgis/core/layers/GeoJSONLayer';
import shp from 'shpjs';
import GroupLayer from '@arcgis/core/layers/GroupLayer';
import * as urlUtils from '@arcgis/core/core/urlUtils';
import Expand from '@arcgis/core/widgets/Expand';
import esriRequest from '@arcgis/core/request';
import popupTemplate from '../webgis/scripts/popupTemplate';
import centroid from '@turf/centroid';
import generateArcgisToken from '../webgis/scripts/arcgisGenerateToken';
import Print from '@arcgis/core/widgets/Print';
import editor from '@/components/Tinymce';

export default {
  name: 'CreateProject',
  components: {
    ClassicUpload,
    Workflow,
    SubProjectTable,
    KewenanganTable,
    editor,
  },
  data() {
    var validateAddress = (rule, value, callback) => {
      if (value.length < 1) {
        callback(new Error('Mohon Masukan 1 Alamat'));
      }
      callback();
    };
    var validateListSubProject = (rule, value, callback) => {
      if (this.listSubProject.filter(e => {
        return e.isUsed && e.type && e.type === 'utama';
      }).length < 1) {
        callback(new Error('Mohon Pilih Minimal 1 Kegiatan Utama'));
      }

      callback();
    };
    var validateListKewenangan = (rule, value, callback) => {
      if (this.currentProject.listKewenangan.length < 1) {
        callback(new Error('Mohon Pilih Minimal 1 Kewenangan'));
      }
      callback();
    };

    // var validateKtr = (rule, value, callback) => {
    //   if (!this.currentProject.fileKtr){
    //     callback(new Error('File KTR Belum Diunggah'));
    //   }
    //   callback();
    // };

    // var validatePippib = (rule, value, callback) => {
    //   if (!this.currentProject.filepippib){
    //     callback(new Error('File PIPPIB Belum Diunggah'));
    //   }
    //   callback();
    // };

    // var validateKawasanLindung = (rule, value, callback) => {
    //   if (!this.currentProject.fileKawasanLindung){
    //     callback(new Error('File Kawasan Lindung Belum Diunggah'));
    //   }
    //   callback();
    // };

    var validateMap = (rule, value, callback) => {
      if (!this.fileMap){
        callback(new Error('File Peta Tapak Belum Diunggah'));
      }
      callback();
    };
    var validatePdfMap = (rule, value, callback) => {
      if (!this.filePdf){
        callback(new Error('File Peta Tapak PDF Belum Diunggah'));
      }
      callback();
    };
    return {
      loading: false,
      idizin: '',
      isUmk: false,
      isPemerintah: false,
      kewenanganPMA: null,
      kewenanganOSS: '-',
      jenisKawasanOSS: '-',
      fromOss: false,
      mapItemId: '',
      full_address: '',
      mismatchMapData: false,
      token: null,
      refresh: 0,
      preeAgreementLabel: '',
      preProject: true,
      activeName: '1',
      currentProject: {
        address: [],
        pippib: 'N',
        kawasan_lindung: 'N',
        listKewenangan: [],
      },
      listSupportTable: [],
      listSubProject: [],
      checkedSubProject: [],
      loadingSupportTable: false,
      isUpload: 'Upload',
      fileName: 'No File Selected.',
      fileMap: null,
      filePdf: null,
      isOss: true,
      fileKtrName: 'No File Selected',
      filepippibName: 'No File Selected',
      fileKawasanLindungName: 'No File Selected',
      fileMapName: 'No File Selected',
      filePdfName: 'No File Selected',
      filePreAgreementName: 'No File Selected',
      studyApproachOptions: [
        {
          value: 'Terpadu',
          label: 'Terpadu',
        },
        {
          value: 'Tunggal',
          label: 'Tunggal',
        },
        {
          value: 'Kawasan',
          label: 'Kawasan',
        },
      ],
      statusOptions: [
        {
          value: 'Rencana',
          label: 'Rencana',
        },
        {
          value: 'Berjalan',
          label: 'Berjalan',
        },
      ],
      projectTypeOptions: [
        {
          value: 'Baru',
          label: 'Baru',
        },
        {
          value: 'Pengembangan',
          label: 'Pengembangan',
        },
      ],
      preAgreementOptions: [
        {
          value: 'Tambang',
          label: 'Tambang',
        },
        {
          value: 'Pelabuhan Diluar Terminal Khusus',
          label: 'Pelabuhan Diluar Terminal Khusus',
        },
        {
          value: 'Terminal Khusus',
          label: 'Terminal Khusus',
        },
        {
          value: 'Bandara',
          label: 'Bandara',
        },
        {
          value: 'Ketenagalistrikan',
          label: 'Ketenagalistrikan',
        },
        {
          value: 'Jalan',
          label: 'Jalan',
        },
        {
          value: 'Bendungan',
          label: 'Bendungan',
        },
        {
          value: 'Pengelolaan Limbah B3',
          label: 'Pengelolaan Limbah B3',
        },
        {
          value: 'Kawasan Industri',
          label: 'Kawasan Industri',
        },
        {
          value: 'Kawasan Ekonomi Khusus',
          label: 'Kawasan Ekonomi Khusus',
        },
        {
          value: 'Kawasan Pelabuhan Bebas dan Perdagangan Bebas',
          label: 'Kawasan Pelabuhan Bebas dan Perdagangan Bebas',
        },
        {
          value: 'Migas',
          label: 'Migas',
        },
        {
          value: 'Ketenaganukliran',
          label: 'Ketenaganukliran',
        },
        {
          value: 'Lainnya',
          label: 'Lainnya',
        },
      ],
      tapakProyekRules: {
        project_title: [
          { required: true, trigger: 'change', message: 'Nama Kegiatan Belum Diisi' },
        ],
        location_desc: [
          { required: true, trigger: 'blur', message: 'Data Lokasi Kegiatan Belum Diisi' },
        ],
        description: [
          { required: true, trigger: 'blur', message: 'Data Deskripsi Kegiatan Belum Diisi' },
        ],
        // fileKtr: [
        //   { validator: validateKtr, trigger: 'change' },
        // ],
        // filepippib: [
        //   { validator: validatePippib, trigger: 'change' },
        // ],
        // fileKawasanLindung: [
        //   { validator: validateKawasanLindung, trigger: 'change' },
        // ],
        filePdf: [
          { validator: validatePdfMap, trigger: 'change' },
        ],
        fileMap: [
          { validator: validateMap, trigger: 'change' },
        ],
      },
      pendekatanStudiRules: {
        study_approach: [
          { required: true, trigger: 'change', message: 'Mohon Pilih Pendekatan Studi' },
        ],
        listSubProject: [
          { validator: validateListSubProject, trigger: 'blur' },
        ],
        address: [
          { validator: validateAddress, trigger: 'blur' },
        ],
      },
      penentuanKewenanganRules: {
        listKewenangan: [
          { validator: validateListKewenangan, trigger: 'blur' },
        ],
      },
      statusKegiatanRules: {
        status: [
          { required: true, trigger: 'change', message: 'Mohon Pilih Status Kegiatan' },
        ],
      },
      jenisKegiatanRules: {
        project_type: [
          { required: true, trigger: 'change', message: 'Mohon Pilih Status Kegiatan' },
        ],
      },
      currentProjectRules: {
        project_title: [
          { required: true, trigger: 'change', message: 'Data Belum Dipilih' },
        ],
        project_type: [
          { required: true, trigger: 'change', message: 'Data Belum Dipilih' },
        ],
        id_prov: [
          { required: true, trigger: 'change', message: 'Data Belum Dipilih' },
        ],
        id_district: [
          { required: true, trigger: 'change', message: 'Data Belum Dipilih' },
        ],
        kbli: [
          { required: true, trigger: 'blur', message: 'Data Belum Diisi' },
        ],
        risk_level: [
          { required: true, trigger: 'blur', message: 'Data Belum Diisi' },
        ],
        project_year: [
          {
            type: 'date',
            required: true,
            trigger: 'blur',
            message: 'Data Belum Diisi',
          },
        ],
        // field: [
        //   { required: true, trigger: 'change', message: 'Data Belum Dipilih' },
        // ],
        address: [
          { validator: validateAddress, trigger: 'blur' },
        ],
        sector: [
          { required: true, trigger: 'change', message: 'Data Belum Dipilih' },
        ],
        biz_type: [
          { required: true, trigger: 'change', message: 'Data Belum Dipilih' },
        ],
        scale_unit: [
          { required: true, trigger: 'change', message: 'Data Belum Dipilih' },
        ],
        scale: [
          { required: true, trigger: 'blur', message: 'Data Belum Diisi' },
        ],
        location_desc: [
          { required: true, trigger: 'blur', message: 'Data Belum Diisi' },
        ],
        description: [
          { required: true, trigger: 'blur', message: 'Data Belum Diisi' },
        ],
        fileMap: [
          { required: true, trigger: 'change', message: 'Data Belum Dipilih' },
        ],
      },
    };
  },
  computed: {
    ...mapGetters({
      'userInfo': 'user',
      'userId': 'userId',
    }),
    getProjectOption() {
      return this.$store.getters.projectOptions;
    },
    getProjectFieldOptions() {
      return this.$store.getters.projectFieldOptions;
    },
    getSectorOptions() {
      return this.$store.getters.sectorOptions;
    },
    getProvinceOptions() {
      return this.$store.getters.provinceOptions;
    },
    getCityOptions() {
      return this.$store.getters.cityOptions;
    },
    getUnitOptions() {
      return this.$store.getters.unitOptions;
    },
    getBusinessTypeOptions() {
      return this.$store.getters.businessTypeOptions;
    },
    getListKbli() {
      return this.$store.getters.kblis;
    },
  },
  async created() {
    this.loading = true;
    await this.$store.dispatch('getInitiator', this.userInfo.email);
    if (this.$store.getters.isPemerintah){
      this.currentProject.isPemerintah = 'true';
    }
    console.log(this.currentProject);
    // load info user and initiator
    // const { email } = await this.$store.dispatch('user/getInfo');

    console.log(this.userInfo);

    if (!this.$store.getters.isPemerintah && !this.$route.params.project) {
      console.log('masuk pak eko');
      await this.$store.dispatch('getOssByKbli', this.$store.getters.pemrakarsa[0].nib);

      await this.getProjectsFromOss();
    }

    // set is rencana kegiatan umk atau tidak
    this.isUmk = this.$store.getters.ossByNib.flag_umk ? this.$store.getters.ossByNib.flag_umk !== 'N' : false;

    // set flag from oss
    this.fromOss = !this.$store.getters.isPemerintah;
    // this.fromOss = !!this.$store.getters.ossByNib.data_proyek;

    generateArcgisToken(this.token);

    // for step
    this.$store.dispatch('getStep', 0);

    // for default value
    // this.currentProject.study_approach = 'Tunggal';
    // this.currentProject.status = 'Rencana';
    // this.currentProject.project_type = 'Baru';

    if (this.$route.params.project) {
      this.currentProject = this.$route.params.project;
      this.currentProject.address = this.currentProject.addressTemp;
      this.listSubProject = this.currentProject.listSubProjectNonChecked;
      this.fileMap = this.currentProject.fileMap;
      this.filePdf = this.currentProject.filePdf;
      this.filePdfName = this.filePdf?.name;
      this.fileMapName = this.fileMap?.name;
      this.fileKtr = this.currentProject.fileKtr;
      this.fileKtrName = this.fileKtr?.name;
      this.filePreAgreement = this.currentProject.filePreAgreement;
      this.filePreAgreementName = this.currentProject.filePreAgreement?.name;
      this.handleFileTapakProyekMapUpload('a');
      this.handleFileTapakProyekPdfUpload('pdf');
      // this.fileName = this.getFileName(this.currentProject.map);
      // this.fileMap = this.getFileName(this.currentProject.map);
      // this.listSupportTable = await this.getListSupporttable(
      //   this.currentProject.id
      // );
      this.getDistricts(this.currentProject.id_prov);
    }
    this.getAllData();
    this.loading = false;
  },
  methods: {
    getKewenanganOss(val){
      if (val === '00'){
        return 'Pusat';
      } else if (val === '01'){
        return 'Provinsi';
      } else {
        return 'Kabupaten';
      }
    },
    getKewenangan(list){
      let temp = this.getKewenanganOss(this.kewenanganOSS);
      list.forEach(element => {
        console.log(element.kewenangan, temp);
        if (element.kewenangan === '02'){
          temp = 'Kabupaten';
        }
        if (element.kewenangan === '01'){
          temp = 'Provinsi';
        }
        if (element.kewenangan === '00'){
          temp = 'Pusat';
        }
      });

      return temp;
    },
    async calculateKewenanganAnomali(){
      let kewenanganTemp = this.getKewenangan(this.currentProject.listSubProject);

      console.log(1, kewenanganTemp);

      // if kwenangan not pusat use authority from address project
      if (kewenanganTemp !== 'Pusat' && this.currentProject.address.filter(e => e.isUsed).length > 1){
        kewenanganTemp = this.currentProject.authority;
      }
      console.log(2, kewenanganTemp);

      const tempFile = this.currentProject.address[0].prov;
      const tempKabFile = this.currentProject.address[0].district;
      if (kewenanganTemp === 'Provinsi'){
        const authProv = await provinceResource.list({ provName: tempFile });
        this.currentProject.auth_province = authProv.id;
      } else if (kewenanganTemp === 'Kabupaten'){
        const authDistrict = await districtResource.list({ districtName: tempKabFile });
        this.currentProject.auth_district = authDistrict.id;
      }

      // if kewenangan KEK
      if (this.jenisKawasanOSS === '02'){
        kewenanganTemp = 'Pusat';
      }
      console.log(3, kewenanganTemp);

      if (this.kewenanganPMA && this.kewenanganPMA === '01'){
        kewenanganTemp = 'Pusat';
        console.log(4, kewenanganTemp);
      }

      const anomaliPBG = await authorityResource.list({ listSubProject: this.currentProject.listSubProject });

      // console.log(typeof anomaliPBG);

      // if theres is anomali pbg skip kewenagan
      if (anomaliPBG === 1){
        kewenanganTemp = this.getKewenangan(this.currentProject.listSubProject);
        console.log(5, kewenanganTemp);
      }

      if (this.jenisKawasanOSS === '03'){
        kewenanganTemp = 'Pusat';
        console.log(6, kewenanganTemp);
      }

      this.currentProject.authority = kewenanganTemp;
    },
    handlechecked(val){
      console.log(val);
      this.currentProject.address.map(e => {
        if (e.id_proyek === val.id_proyek){
          e.isUsed = val.isUsed;
        }

        return e;
      });
      this.refresh++;
    },
    checksubpro(val){
      console.log(val);
      this.checkedSubProject = val;
    },
    async addAuthoritiesBasedOnAddress(address){
      if (!address[0].prov || !address[0].district){
        this.$message({
          message: `Mohon Lengkapi Data Alamat`,
          type: 'error',
          duration: 5 * 1000,
        });
        throw new Error(`Mohon Lengkapi Data Alamat`);
      }
      let tempFile = address[0].prov;
      let tempKabFile = address[0].district;
      for (let i = 1; i < address.length; i++) {
        if (!address[i].prov || !address[i].district){
          this.$message({
            message: `Mohon Lengkapi Data Alamat`,
            type: 'error',
            duration: 5 * 1000,
          });
          throw new Error(`Mohon Lengkapi Data Alamat`);
        }
        if (address[i].prov !== tempFile) {
          this.currentProject.authority = 'Pusat';
          delete this.currentProject.auth_province;
          delete this.currentProject.auth_district;
          console.log('add1', this.currentProject.authority);
          return;
        }

        if (address[i].district !== tempKabFile) {
          this.currentProject.authority = 'Provinsi';
          const authProv = await provinceResource.list({ provName: tempFile });
          this.currentProject.auth_province = authProv.id;
          console.log('add2', this.currentProject.authority);
          delete this.currentProject.auth_district;
        }

        tempKabFile = address[i].district;
        tempFile = address[i].prov;
      }

      if (this.currentProject.authority === 'Provinsi'){
        console.log('add3', this.currentProject.authority);
        return;
      }

      this.currentProject.authority = 'Kabupaten';
      const authDistrict = await districtResource.list({ districtName: tempKabFile });
      this.currentProject.auth_district = authDistrict.id;

      console.log('kewenangan by alamat', this.currentProject);
    },
    async goToPendekatanStudi(){
      if (!this.filepippib && this.currentProject.pippib === 'Y'){
        this.$alert('Maaf kegiatan anda masuk dalam PIPPIB mohon unggah dokumen pengecualian untuk melanjutkan penapisan', {
          confirmButtonText: 'OK',
        });
        return;
      }

      this.$refs.tapakProyek.validate((valid) => {
        if (valid) {
          this.activeName = '2';
        } else {
          console.log('error submit!!');
          return false;
        }
      });
    },
    async goToStatusKegiatanPemerintah(){
      this.$refs.penentuanKewenangan.validate((valid) => {
        if (valid) {
          this.calculateAuthorities();
          this.activeName = '3';
        } else {
          console.log('error submit!!');
          return false;
        }
      });
    },
    calculateAuthorities(){
      let finalAuth = this.currentProject.listKewenangan[0].authority;

      if (finalAuth === 'Pusat'){
        this.currentProject.authority = 'Pusat';
        delete this.currentProject.auth_province;
        delete this.currentProject.auth_district;
        return;
      }

      this.currentProject.listKewenangan.forEach(element => {
        if (element.authority === 'Pusat'){
          this.currentProject.authority = 'Pusat';
          delete this.currentProject.auth_province;
          delete this.currentProject.auth_district;
          return;
        } else if (element.authority === 'Provinsi' && finalAuth !== 'Pusat'){
          finalAuth = 'Provinsi';
          delete this.currentProject.auth_district;
        } else if (finalAuth !== 'Provinsi'){
          finalAuth = 'Kabupaten';
        }
      });

      if (this.currentProject.authority === 'Pusat'){
        return;
      } else if (finalAuth === 'Provinsi' && this.currentProject.authority === 'Kabupaten'){
        this.currentProject.authority = finalAuth;
      }

      console.log('authority pemerintah', this.currentProject);
    },
    async goToStatusKegiatan(){
      console.log(this.currentProject);
      if (this.currentProject.isPemerintah){
        this.activeName = '7';
      } else {
        await this.calculateKewenanganAnomali();
        this.activeName = '3';
      }
      // this.$refs.pendekatanStudi.validate(async(valid) => {
      //   if (valid) {
      //     await this.addAuthoritiesBasedOnAddress(this.currentProject.address);
      //     this.activeName = '3';
      //   } else {
      //     console.log('error submit!!');
      //     return false;
      //   }
      // });
    },
    async handleBackStatusKegiatan(){
      delete this.currentProject.authority;
      await this.addAuthoritiesBasedOnAddress(this.currentProject.address);
      this.activeName = this.currentProject.isPemerintah ? '7' : '2';
    },
    goToJenisKegiatan(){
      this.$refs.statusKegiatan.validate((valid) => {
        if (valid) {
          this.activeName = '4';
        } else {
          console.log('error submit!!');
          return false;
        }
      });
    },
    goToPersetujuanAwal(){
      this.$refs.jenisKegiatan.validate((valid) => {
        if (valid) {
          this.activeName = '5';
        } else {
          console.log('error submit!!');
          return false;
        }
      });
    },
    arraySpanMethod({ row, column, rowIndex, columnIndex }) {
      if (row.scale) {
        if (columnIndex === 1) {
          return [1, 4];
        } else if (columnIndex === 2) {
          return [0, 0];
        }
      }
    },
    handleNothingTick(){
      this.currentProject.wastewater = false;
      this.currentProject.emission = false;
      this.currentProject.b3 = false;
      this.currentProject.traffic = false;
    },
    sumResult(listParam){
      let result = '';
      let result_risk = '';
      let scale = '';
      let scale_unit = '';

      for (let i = 0; i < listParam.length; i++) {
        if (listParam[i].result === 'AMDAL'){
          result = listParam[i].result;
          result_risk = listParam[i].result_risk;
          scale = listParam[i].scale;
          scale_unit = listParam[i].scale_unit;
          break;
        } else if (listParam[i].result === 'UKL-UPL'){
          result = listParam[i].result;
          result_risk = listParam[i].result_risk;
          scale = listParam[i].scale;
          scale_unit = listParam[i].scale_unit;
        } else if (listParam[i].result === 'SPPL' && result !== 'UKL-UPL'){
          result = listParam[i].result;
          result_risk = listParam[i].result_risk;
          scale = listParam[i].scale;
          scale_unit = listParam[i].scale_unit;
        }
      }
      return { result, result_risk, scale, scale_unit };
    },
    calculateListSubProjectResult(){
      this.currentProject.listSubProjectNonChecked = this.listSubProject;

      this.currentProject.listSubProject = this.listSubProject.filter(e => e.isUsed).map(e => {
        if (!e.listSubProjectParams || e.listSubProjectParams.filter(e => e.used) < 1){
          this.$message({
            message: `Parameter untuk project dengan kbli ${e.kbli} belum diisi`,
            type: 'error',
            duration: 5 * 1000,
          });
          throw new Error(`Parameter untuk project dengan kbli ${e.kbli} belum diisi`);
        }

        if (!e.name){
          this.$message({
            message: `Nama untuk project dengan kbli ${e.kbli} belum diisi`,
            type: 'error',
            duration: 5 * 1000,
          });
          throw new Error(`Nama untuk project dengan kbli ${e.kbli} belum diisi`);
        }
        e.listSubProjectParams = e.listSubProjectParams.filter(e => e.used);

        const { result, result_risk, scale, scale_unit } = this.sumResult(e.listSubProjectParams);
        e.result = result;
        e.result_risk = result_risk;
        e.scale = scale;
        e.scale_unit = scale_unit;

        return e;
      });
    },
    calculateChoosenProject(){
      const listMainProjectAmdal = this.currentProject.listSubProject.filter(e => {
        // if (!this.currentProject.study_approach || this.currentProject.study_approach === 'Tunggal'){
        //   return e.type === 'utama' && e.result === 'AMDAL';
        // } else if (this.currentProject.study_approach === 'Terpadu'){
        return e.result === 'AMDAL';
        // }
      });
      const listMainProjectUklUpl = this.currentProject.listSubProject.filter(e => {
        // if (!this.currentProject.study_approach || this.currentProject.study_approach === 'Tunggal'){
        //   return e.type === 'utama' && e.result === 'UKL-UPL';
        // } else if (this.currentProject.study_approach === 'Terpadu'){
        return e.result === 'UKL-UPL';
        // }
      });
      const listMainProjectSppl = this.currentProject.listSubProject.filter(e => {
        // if (!this.currentProject.study_approach || this.currentProject.study_approach === 'Tunggal'){
        //   return e.type === 'utama' && e.result === 'SPPL';
        // } else if (this.currentProject.study_approach === 'Terpadu'){
        return e.result === 'SPPL';
        // }
      });
      // console.log('project tanpa filter', this.currentProject);
      // const listMainProjectAmdal = this.currentProject.listSubProject.filter(e => e.type === 'utama' && e.result === 'AMDAL');
      // const listMainProjectUklUpl = this.currentProject.listSubProject.filter(e => e.type === 'utama' && e.result === 'UKL-UPL');
      // const listMainProjectSppl = this.currentProject.listSubProject.filter(e => e.type === 'utama' && e.result === 'SPPL');

      // console.log('listAmdal', listMainProjectAmdal);
      let choosenProject = '';

      if (listMainProjectAmdal.length !== 0){
        choosenProject = listMainProjectAmdal[0];
      } else if (listMainProjectUklUpl.length !== 0) {
        choosenProject = listMainProjectUklUpl[0];
      } else if (listMainProjectSppl.length !== 0) {
        choosenProject = listMainProjectSppl[0];
      }

      // console.log('choosenProject', choosenProject);

      // add choosen project to current project
      this.currentProject.kbli = choosenProject.kbli ? choosenProject.kbli : 'non kbli';
      this.currentProject.required_doc = choosenProject.result;
      this.currentProject.risk_level = choosenProject.result_risk;
      this.currentProject.result_risk = choosenProject.result_risk;
      this.currentProject.sector = choosenProject.sector;
      this.currentProject.plan_type = choosenProject.name;
      this.currentProject.scale = choosenProject.scale;
      this.currentProject.scale_unit = choosenProject.scale_unit;
      this.currentProject.listSubProjectParams = choosenProject.listSubProjectParams;
    },
    handleStudyAccord(){
      this.$refs.pendekatanStudi.validate(async(valid) => {
        if (valid) {
          await this.addAuthoritiesBasedOnAddress(this.currentProject.address.filter(e => e.isUsed));
          this.calculateListSubProjectResult();
          this.calculateChoosenProject();
          this.goToStatusKegiatan();
        } else {
          console.log('error submit!!');
          return false;
        }
      });
    },
    checkMapFile() {
      document.querySelector('#ktrFile').click();
    },
    showFileAlert(){
      this.$alert('File Yang Diupload Melebihi 1 MB', {
        confirmButtonText: 'OK',
      });
    },
    handleFileKtrUpload(e){
      // reset validation
      this.$refs['tapakProyek'].fields.find((f) => f.prop === 'fileKtr').resetField();

      if (e.target.files[0].size > 1048576){
        this.showFileAlert();
        return;
      }

      if (e.target.files[0].type !== 'application/pdf'){
        this.$alert('File yang diterima hanya .PDF', 'Format Salah');
        return;
      }

      this.fileKtr = e.target.files[0];
      this.currentProject.fileKtr = e.target.files[0];
      this.fileKtrName = e.target.files[0].name;
    },
    handleFilePIPPIBUpload(e){
      // reset validation
      this.$refs['tapakProyek'].fields.find((f) => f.prop === 'filepippib').resetField();

      if (e.target.files[0].size > 1048576){
        this.showFileAlert();
        return;
      }

      if (e.target.files[0].type !== 'application/pdf'){
        this.$alert('File yang diterima hanya .PDF', 'Format Salah');
        return;
      }

      this.filepippib = e.target.files[0];
      this.currentProject.filepippib = e.target.files[0];
      this.filepippibName = e.target.files[0].name;
    },
    handleFileKawasanLindungUpload(e){
      // reset validation
      this.$refs['tapakProyek'].fields.find((f) => f.prop === 'fileKawasanLindung').resetField();

      if (e.target.files[0].size > 1048576){
        this.showFileAlert();
        return;
      }

      if (e.target.files[0].type !== 'application/pdf'){
        this.$alert('File yang diterima hanya .PDF', 'Format Salah');
        return;
      }

      this.fileKawasanLindung = e.target.files[0];
      this.currentProject.fileKawasanLindung = e.target.files[0];
      this.fileKawasanLindungName = e.target.files[0].name;
    },
    handleFilePreAgreementUpload(e){
      if (e.target.files[0].size > 1048576){
        this.showFileAlert();
        return;
      }
      this.filePreAgreement = e.target.files[0];
      this.filePreAgreementName = e.target.files[0].name;
      // this.filePreAgreement = this.$refs.filePreAgreement.files[0];
    },
    handleFileTapakProyekPdfUpload(e){
      if (!e.target) {
        return;
      }

      this.$refs['tapakProyek'].fields.find((f) => f.prop === 'filePdf')?.resetField();

      if (e.target.files[0].size > 1048576){
        this.showFileAlert();
        return;
      }

      if (e.target.files[0].type !== 'application/pdf'){
        this.$alert('File yang diterima hanya .PDF', 'Format Salah');
        return;
      }

      if (e !== 'pdf'){
        this.filePdf = e.target.files[0];
        this.filePdfName = e.target.files[0].name;
      }
    },
    handleFileTapakProyekMapUpload(e){
      if (!e.target){
        return;
      }
      // reset validation
      this.$refs['tapakProyek'].fields.find((f) => f.prop === 'fileMap')?.resetField();

      if (e.target.files[0].size > 1048576){
        this.showFileAlert();
        return;
      }

      if (e.target.files[0].type !== 'application/x-zip-compressed'){
        this.$alert('File yang diterima hanya .zip', 'Format Salah');
        return;
      }

      if (e !== 'a'){
        this.fileMap = e.target.files[0];
        this.fileMapName = e.target.files[0].name;
      }
      const map = new Map({
        basemap: 'satellite',
      });

      urlUtils.addProxyRule({
        proxyUrl: 'proxy/proxy.php',
        urlPrefix: 'https://gistaru.atrbpn.go.id/',
      });

      const penutupanLahan2020 = new MapImageLayer({
        url: 'https://sigap.menlhk.go.id/server/rest/services/KLHK/A_Penutupan_Lahan_2021/MapServer',
        imageTransparency: true,
        visible: false,
        visibilityMode: '',
      });

      const kawasanHutanB = new MapImageLayer({
        url: 'https://sigap.menlhk.go.id/server/rest/services/KLHK/B_Kawasan_Hutan/MapServer',
        imageTransparency: true,
        visible: true,
        visibilityMode: '',
      });

      const pippib2021Periode2 = new MapImageLayer({
        url: 'https://sigap.menlhk.go.id/server/rest/services/KLHK/D_PIPPIB_2021_Periode_2/MapServer',
        imageTransparency: true,
        visible: true,
        visibilityMode: '',
      });

      const sigapLayer = new GroupLayer({
        title: 'Peta Tematik Status',
        visible: true,
        layers: [penutupanLahan2020, kawasanHutanB, pippib2021Periode2],
        opacity: 0.90,
      });

      map.add(sigapLayer);

      const fr = new FileReader();
      fr.onload = (event) => {
        const base = event.target.result;
        shp(base).then((datas) => {
          const mapSampleProperties = [
            'PEMRAKARSA',
            'KEGIATAN',
            'TAHUN',
            'PROVINSI',
            'KETERANGAN',
            'LAYER',
          ];

          const mapUploadProperties = Object.keys(datas.features[0].properties);
          var getPropFields = datas.features[0].properties;

          var propFields = Object.entries(getPropFields).reduce(function(a, _a) {
            var key = _a[0], value = _a[1];
            a[key.toUpperCase()] = value;
            return a;
          }, {});

          var centroids = centroid(datas.features[0]);
          var getCoordinates = centroids.geometry.coordinates;

          fetch(`https://nominatim.openstreetmap.org/reverse?lat=${getCoordinates[1]}&lon=${getCoordinates[0]}&format=json`)
            .then(response => response.json())
            .then(data => {
              this.full_address = data.display_name;
            });

          if (datas.features[0].geometry.type !== 'Polygon') {
            document.getElementById('fileMap').value = '';
            this.fileMapName = 'No File Selected';
            return this.$alert('SHP yang dimasukkan harus Polygon!', 'Format Salah', {
              confirmButtonText: 'Confirm',
            });
          }

          const checker = (arr, target) => target.every(v => arr.includes(v));
          const validDataSet = mapSampleProperties.map(element => element.toLowerCase());
          const uploadDataSet = mapUploadProperties.map(element => element.toLowerCase());
          const checkShapefile = checker(uploadDataSet, validDataSet);

          if (!checkShapefile) {
            document.getElementById('fileMap').value = '';
            this.fileMapName = 'No File Selected';
            return this.$alert('Atribut .shp yang dimasukkan tidak sesuai dengan format yang benar.', 'Format Salah', {
              confirmButtonText: 'Confirm',
              callback: action => {
                this.$notify({
                  type: 'warning',
                  title: 'Perhatian!',
                  message: 'Download Sample Peta Yang Telah Disediakan!!',
                  duration: 5000,
                });
                window.open('sample_map/Peta_Tapak_Sample_Amdalnet.zip', '_blank');
              },
            });
          }

          const blob = new Blob([JSON.stringify(datas)], {
            type: 'application/json',
          });

          const renderer = {
            type: 'simple',
            field: '*',
            symbol: {
              type: 'simple-fill',
              color: [200, 0, 0, 1],
              outline: {
                color: [200, 0, 0, 1],
              },
            },
          };
          const url = URL.createObjectURL(blob);
          const geojsonLayer = new GeoJSONLayer({
            url: url,
            visible: true,
            outFields: ['*'],
            opacity: 0.75,
            title: 'Peta Tapak Proyek',
            renderer: renderer,
            popupTemplate: popupTemplate(propFields),
          });

          map.add(geojsonLayer);
          mapView.on('layerview-create', async(event) => {
            await mapView.goTo({
              target: geojsonLayer.fullExtent,
            });
          });
        });
      };
      fr.readAsArrayBuffer(this.fileMap);

      const mapView = new MapView({
        container: 'mapView',
        map: map,
        center: [115.287, -1.588],
        zoom: 5,
      });
      this.$parent.mapView = mapView;

      const legend = new Legend({
        view: mapView,
        container: document.createElement('div'),
      });

      const legendListExpand = new Expand({
        expandIconClass: 'esri-icon-basemap', // see https://developers.arcgis.com/javascript/latest/guide/esri-icon-font/
        expandTooltip: 'Layer Legend', // optional, defaults to "Expand" for English locale
        view: mapView,
        content: legend,
      });

      const layerList = new LayerList({
        view: mapView,
        container: document.createElement('div'),
        listItemCreatedFunction: this.defineActions,
      });

      const layerListExpand = new Expand({
        expandIconClass: 'esri-icon-layers', // see https://developers.arcgis.com/javascript/latest/guide/esri-icon-font/
        expandTooltip: 'Daftar Layer', // optional, defaults to "Expand" for English locale
        view: mapView,
        content: layerList,
      });

      const print = new Print({
        view: mapView,
        printServiceUrl:
                  'https://amdalgis.menlhk.go.id/server/rest/services/Utilities/PrintingTools/GPServer/Export%20Web%20Map%20Task',
      });

      const printExpand = new Expand({
        view: mapView,
        content: print,
      });

      layerList.on('trigger-action', (event) => {
        const id = event.action.id;
        if (id === 'full-extent') {
          mapView.goTo({
            target: event.item.layer.fullExtent,
          });
        }
      });

      mapView.ui.add(layerListExpand, 'top-right');
      mapView.ui.add(legendListExpand, 'top-right');
      mapView.ui.add(printExpand, 'top-left');
    },
    async getListSupporttable(idProject) {
      const { data } = await SupportDocResource.list({ idProject });

      return data.map((e) => {
        return { fileDoc: { name: e.file }, ...e };
      });
    },
    getFileName(value) {
      const onlyName = value.split('/');

      return onlyName.at(-1);
    },
    kbliSearch(queryString, cb) {
      var links = this.$store.getters.kblis;
      var results = queryString
        ? links.filter(this.createKbliFilter(queryString))
        : links;
      // call callback function to return suggestions
      cb(results);
    },
    createKbliFilter(queryString) {
      return (link) => {
        return (
          link.value.toLowerCase().indexOf(queryString.toLowerCase()) === 0
        );
      };
    },
    checkKtrFile() {
      document.querySelector('#ktrFile').click();
    },
    checkMapFileSure(e) {
      this.fileName = e.target.files[0].name;
      this.fileMap = e.target.files[0];
      this.$refs.fileMapUpload.clearValidate();
    },
    async getAllData() {
      await this.getProjectFields();
      await this.getProvinces();
      await this.getSectors();
      await this.getKblis();
    },
    async getProjectsFromOss() {
      console.log(this.$store.getters.ossByNib);

      if (!this.$store.getters.ossByNib.data_proyek){
        return;
      }

      // this.fromOss = true;
      // this.idizin = this.$store.getters.ossByNib.id_izin;
      this.kewenanganPMA = this.$store.getters.ossByNib.status_penanaman_modal;
      this.kewenanganOSS = this.$store.getters.ossByNib.kewenangan;
      this.jenisKawasanOSS = this.$store.getters.ossByNib.jenis_kawasan;

      const ossProjects = this.$store.getters.ossByNib.data_proyek.filter(e => (e.file_izin === '-' || !e.file_izin) && !e.status_tapis);
      console.log(ossProjects);

      ossProjects.forEach(e => {
        console.log('ini', e);
        e.data_lokasi_proyek.forEach(i => {
          this.currentProject.address.push({
            prov: i.province,
            district: i.regency,
            address: i.alamat_usaha,
            id_proyek: e.id_proyek,
          });
        });

        // check kbli or not
        if (e.nonKbli === 'Y'){
          this.listSubProject.push({
            nonKbli: true,
            kbli: e.kbli,
            name: e.uraian_usaha,
            id_proyek: e.id_proyek,
            kewenangan: e.kewenangan ? e.kewenangan : this.kewenanganOSS,
            lokasi: e.data_lokasi_proyek,
            skala_resiko: e.skala_resiko,
            idizin: e.id_izin,
          });
        } else {
          this.listSubProject.push({
            kbli: e.kbli,
            name: e.uraian_usaha,
            id_proyek: e.id_proyek,
            kewenangan: e.kewenangan ? e.kewenangan : this.kewenanganOSS,
            lokasi: e.data_lokasi_proyek,
            skala_resiko: e.skala_resiko,
            idizin: e.id_izin,
          });
        }
      });

      console.log(this.listSubProject);
    },
    async getProjectFields() {
      await this.$store.dispatch('getProjectFields');
    },
    async getProvinces() {
      await this.$store.dispatch('getProvinces');
    },
    handleCancel() {
      this.$router.push('/project');
    },
    handleSubmit() {
      // filter address by is used
      this.currentProject.addressTemp = this.currentProject.address;
      this.currentProject.address = this.currentProject.addressTemp.filter(e => e.isUsed);

      if (this.fileMap){
        this.currentProject.fileMap = this.fileMap;
      }
      if (this.filePdf){
        this.currentProject.filePdf = this.filePdf;
      }
      if (this.fileKtr){
        this.currentProject.fileKtr = this.fileKtr;
      }
      if (this.filePreAgreement) {
        this.currentProject.filePreAgreement = this.filePreAgreement;
      }

      // check if project in kawasan lindung without exception
      console.log('perbandingan kawasan lindung', [this.currentProject.kawasan_lindung === 'Y', this.currentProject.fileKawasanLindung]);
      if (this.currentProject.kawasan_lindung === 'Y' && !this.currentProject.fileKawasanLindung && this.currentProject.required_doc !== 'AMDAL') {
        this.currentProject.required_doc = 'AMDAL';
        this.currentProject.result_risk = 'Tinggi';
      }

      // this.$refs.currentProject.validate((valid) => {
      //   if (valid) {
      // this.currentProject.listSupportDoc = this.listSupportTable.filter(
      //   (item) => item.name && item.file
      // );

      // send to pubishProjectRoute
      this.$router.push({
        name: 'publishProject',
        params: { project: this.currentProject, mapUpload: this.fileMap, geomFromGeojson: this.geomFromGeojson, mapUploadPdf: this.filePdf },
      });
      //   } else {
      //     console.log('error submit!!');
      //     return false;
      //   }
      // });

      urlUtils.addProxyRule({
        proxyUrl: 'proxy/proxy.php',
        urlPrefix: 'https://amdalgis.menlhk.go.id/',
      });

      var myHeaders = new Headers();
      myHeaders.append('Authorization', 'Bearer ' + this.token);

      var formdatas = new FormData();
      formdatas.append('file', this.currentProject.fileMap);
      formdatas.append('filePdf', this.currentProject.filePdf);

      var fomDataArcgis = new FormData();
      fomDataArcgis.append('type', 'Shapefile');
      fomDataArcgis.append('title', this.currentProject.project_title.replace(/ /g, '_') + '_Peta_Tapak_Proyek');
      fomDataArcgis.append('file', this.currentProject.fileMap);
      fomDataArcgis.append('tags', 'Amdalnet Web');
      fomDataArcgis.append('f', 'json');

      var requestOptions = {
        method: 'POST',
        headers: myHeaders,
        body: fomDataArcgis,
        multipart: false,
      };

      esriRequest('https://amdalgis.menlhk.go.id/portal/sharing/rest/content/users/Amdalnet/addItem', requestOptions)
        .then(response => {
          if (response.data.success === true) {
            this.mapItemId = response.data.id;

            var formDataPublish = new FormData();
            formDataPublish.append('f', 'json');
            formDataPublish.append('itemId', this.mapItemId);
            formDataPublish.append('filetype', 'shapefile');
            formDataPublish.append('publishParameters', `{"hasStaticData":true,"name":${this.currentProject.project_title.replace(/ /g, '_')},"maxRecordCount":2000,"layerInfo":{"capabilities":"Query"}}`);

            var requestOptionsPublish = {
              method: 'POST',
              headers: myHeaders,
              body: formDataPublish,
            };

            esriRequest('https://amdalgis.menlhk.go.id/portal/sharing/rest/content/users/Amdalnet/publish', requestOptionsPublish)
              .then(() => {
                this.$notify({
                  type: 'success',
                  title: 'Berhasil!',
                  message: 'Berhasil Publish Peta!!',
                  duration: 2000,
                });
              })
              .catch(error => console.log('error', error));
          }
        })
        .catch(error => console.log('error', error));
    },
    async changeProvince(row) {
      // change all district by province
      // console.log(row);
      delete row.district;
      await this.getDistricts(row.prov);
      row.districts = this.$store.getters.cityOptions;
      this.refresh++;
    },
    changeStudyApproach(value) {

    },
    changeStatus(value) {

    },
    removePreAgreement(){
      delete this.currentProject.pre_agreement;
    },
    changeProjectType(value) {
      const temp = 'Upload Dokumen ';
      if (value === 'Tambang'){
        this.preeAgreementLabel = temp + 'Studi Kelayakan Tambang (FS)';
      } else if (value === 'Pelabuhan Diluar Terminal Khusus'){
        this.preeAgreementLabel = temp + 'Rencana Induk Pelabuhan';
      } else if (value === 'Terminal Khusus'){
        this.preeAgreementLabel = temp + 'Persetujuan lokasi';
      } else if (value === 'Bandara'){
        this.preeAgreementLabel = temp + 'Rencana Induk Bandara';
      } else if (value === 'Ketenagalistrikan'){
        this.preeAgreementLabel = temp + 'Rencana Umum Penyediaan Tenaga Listrik';
      } else if (value === 'Jalan'){
        this.preeAgreementLabel = temp + 'Persetujuan Trase';
      } else if (value === 'Bendungan'){
        this.preeAgreementLabel = temp + 'Studi Kelayakan Bendungan (FS)';
      } else if (value === 'Pengelolaan Limbah B3'){
        this.preeAgreementLabel = temp + 'Persetujuan Teknis';
      } else if (value === 'Kawasan Industri'){
        this.preeAgreementLabel = temp + 'Studi Kelayakan Kawasan Industri';
      } else if (value === 'Kawasan Ekonomi Khusus'){
        this.preeAgreementLabel = temp + 'Studi Kelayakan Kawasan Ekonomi Khusus';
      } else if (value === 'Kawasan Pelabuhan Bebas dan Perdagangan Bebas'){
        this.preeAgreementLabel = temp + 'Studi Kelayakan Kawasan Pelabuhan Bebas dan Perdagangan Bebas';
      } else if (value === 'Migas'){
        this.preeAgreementLabel = temp + 'Persetujuan Investasi Migas';
      } else if (value === 'Ketenaganukliran'){
        this.preeAgreementLabel = temp + 'Studi Kelayakan Ketenaganukliran (FS)';
      } else {
        this.preeAgreementLabel = temp + 'Persetujuan Investasi';
      }
    },
    async changeProject(value) {
      this.currentProject.project_title = value.nama_kegiatan;
      this.currentProject.id_project = value.id_proyek;
      this.currentProject.location_desc = value.alamat_usaha;
      this.currentProject.description = value.deskripsi_kegiatan;
      this.currentProject.kbli = value.kbli;
      this.currentProject.risk_level = value.skala_resiko;
      this.getSectorsByKbli(this.currentProject.kbli);
      this.getBusinessByKbli(this.currentProject.kbli);
    },
    async getDistricts(prov) {
      await this.$store.dispatch('getDistricts', { provName: prov });
    },
    async getSectors() {
      await this.$store.dispatch('getSectors', { sectors: true });
    },
    async getSectorsByKbli(nameKbli) {
      await this.$store.dispatch('getSectors', {
        sectorsByKbli: nameKbli,
      });
    },
    async getUnitByKbli(nameKbli, businessType) {
      await this.$store.dispatch('getUnitByKbli', {
        kbli: nameKbli,
        businessType,
        unit: true,
      });
    },
    async getBusinessByKbli(nameKbli) {
      await this.$store.dispatch('getBusinessByKbli', {
        kbli: nameKbli,
        businessType: true,
      });
    },
    async getKblis() {
      await this.$store.dispatch('getKblis', { kblis: true });
    },
    handleAddSupportTable() {
      this.listSupportTable.push({});
    },
    handleAddKewenangan() {
      this.currentProject.listKewenangan.push({});
    },
    handleAddSubProjectTable() {
      this.listSubProject.push({
        isUsed: true,
      });
    },
    handleAddAddressTable() {
      this.currentProject.address.push({
        isUsed: true,
      });
    },
    handleKbliSelect(item) {
      this.getSectorsByKbli(item.value);
      this.getBusinessByKbli(item.value);
      // this.currentProject.biz_type = '';
      // this.currentProject.sector = '';
    },
    handleBusinessTypeSelect(item) {
      this.getUnitByKbli(this.currentProject.kbli, item);
    },
  },
};
</script>
<style>
@import url('../../../../node_modules/leaflet/dist/leaflet.css');

.el-collapse-item__header {
  /* background-color: #296d36; */
  background-color: #1E5128;
  padding-left: 10px;
  font-size: large;
  font-weight: bold;
  color: rgb(196, 196, 196);
}
.el-collapse-item__content {
  padding-top: 10px;
}

.download__sample {
  color: white;
  padding: 5px;
  background-color: #39773B;
  border-radius: 4px;
  font-weight: 500;
  font-size: 11px;
}

.download__sample:hover {
  background-color: #124e14;
  color: white;
}

.download__juknis {
  color: white;
  padding: 5px;
  background-color: #dd8705;
  border-radius: 4px;
  font-weight: 500;
  font-size: 11px;
}

.download__juknis:hover {
  background-color: #af6900;
  color: white;
}

.mismatch__map {
  color: rgb(230, 41, 41);
  font-size: 10;
  font-style: italic;
}

</style>
