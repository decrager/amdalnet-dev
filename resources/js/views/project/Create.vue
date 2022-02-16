<template>
  <div class="form-container" style="padding: 24px">
    <workflow :is-penapisan="true" />
    <div v-if="preProject">
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
                <el-form-item label="Upload Dokumen Kesesuaian Tata Ruang (Max 1MB)" prop="fileKtr">
                  <classic-upload :name="fileKtrName" :fid="'ktrFile'" @handleFileUpload="handleFileKtrUpload($event)" />
                </el-form-item>
              </el-col>
            </el-row>
            <el-row :gutter="4">
              <el-col :span="12">
                <el-row>
                  <el-form-item label="Deskripsi Kegiatan" prop="description">
                    <el-input v-model="currentProject.description" size="medium" type="textarea" />
                  </el-form-item>
                </el-row>
                <el-row>
                  <el-form-item label="Deskripsi Lokasi" prop="location_desc">
                    <el-input v-model="currentProject.location_desc" size="medium" type="textarea" />
                  </el-form-item>
                </el-row>
              </el-col>
              <el-col :span="12">
                <el-row>
                  <el-form-item label="" prop="filePdf">
                    <div slot="label">
                      <span>Upload Peta PDF (Max 1MB)</span>
                    </div>
                    <classic-upload :name="filePdfName" :fid="'filePdf'" @handleFileUpload="handleFileTapakProyekPdfUpload" />
                  </el-form-item>
                </el-row>
              </el-col>
              <el-col :span="12" style="margin-top: 17px;">
                <el-row>
                  <el-form-item label="" prop="fileMap">
                    <div slot="label">
                      <span>Upload Peta Tapak (File .ZIP Max 1MB)</span>
                      <a href="/sample_map/Peta_Tapak_Sample_Amdalnet.zip" class="download__sample" target="_blank" rel="noopener noreferrer"><i class="ri-road-map-line" /> Download Contoh Shp</a>
                    </div>
                    <classic-upload :name="fileMapName" :fid="'fileMap'" @handleFileUpload="handleFileTapakProyekMapUpload" />
                  </el-form-item>
                </el-row>
              </el-col>
            </el-row>

            <div v-show="fileMap" id="mapView" style="height: 600px;" />
            <div style="margin-top: 10px">
              <span v-if="full_address !== ''" style="font-style: italic; color: red">Provinsi: {{ full_address }}</span>
            </div>

            <!-- Alamat -->
            <el-row type="flex" justify="end" :gutter="4">
              <el-col :span="24" :xs="24">
                <el-form-item label="Alamat" prop="address">
                  <el-table :key="refresh" :data="currentProject.address" :header-cell-style="{ background: '#099C4B', color: 'white' }">
                    <el-table-column label="No." width="80px">
                      <template slot-scope="scope">
                        {{ scope.$index + 1 }}
                      </template>
                    </el-table-column>
                    <el-table-column label="Provinsi" width="200px">
                      <template slot-scope="scope">
                        <el-select
                          v-model="scope.row.prov"
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
                        <el-input v-model="scope.row.address" />
                      </template>
                    </el-table-column>

                    <el-table-column width="100px">
                      <template slot-scope="scope">
                        <el-popconfirm
                          title="Hapus Alamat ?"
                          @confirm="currentProject.address.splice(scope.$index,1)"
                        >
                          <el-button slot="reference" type="danger" icon="el-icon-close" />
                        </el-popconfirm>
                      </template>
                    </el-table-column>
                  </el-table>
                  <el-button
                    type="primary"
                    @click="handleAddAddressTable"
                  >+</el-button>
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
                  <sub-project-table :list="listSubProject" :list-kbli="getListKbli" />
                </keep-alive>
                <el-button
                  type="primary"
                  @click="handleAddSubProjectTable"
                >+</el-button>
              </el-form-item>
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
                <el-button size="medium" @click="activeName = '2'">
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
          <el-collapse-item title="PERSETUJUAN AWAL" name="5" disabled>
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
                  <el-tag v-if="currentProject.pre_agreement === 'Lainnya'" type="info" style="width: 100%; height: 36px; margin-top: 5px; padding-top: 5px">Silahkan Mengurus Dokumen Persetujuan Investasi ke Kementrian Investasi</el-tag>
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
          <el-collapse-item title="PERSETUJUAN TEKNIS ( PERTEK )" name="6" disabled>
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
                  <el-tag v-if="currentProject.high_pollution" type="danger" style="width: 100%; height: 36px; margin-top: 5px; padding-top: 5px">Anda Membutuhkan <b> Persetujuan Teknis Standar </b> Air Limbah</el-tag>
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
                  <el-tag v-if="currentProject.high_emission" type="danger" style="width: 100%; height: 36px; margin-top: 5px; padding-top: 5px">Anda Membutuhkan <b> Persetujuan Teknis Standar </b> Gas Buang</el-tag>
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
                      </td>
                      <td>
                        <div>
                          <el-checkbox v-model="currentProject.utilization_b3" label="Pemanfaatan Limbah B3" border />
                        </div>
                        <div>
                          <el-checkbox v-model="currentProject.hoard_b3" style="margin-top: 5px" label="Penimbunan Limbah B3" border />
                        </div>
                      </td>
                      <td>
                        <div>
                          <el-checkbox v-model="currentProject.dumping_b3" label="Dumping Limbah B3" border />
                        </div>
                        <div>
                          <el-checkbox v-model="currentProject.tps" style="margin-top: 5px" label="TPS" border />
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
import Workflow from '@/components/Workflow';
import ClassicUpload from '@/components/ClassicUpload';
import Resource from '@/api/resource';
// import SupportTable from './components/SupportTable.vue';
import SubProjectTable from './components/SubProjectTable.vue';
import 'vue-simple-accordion/dist/vue-simple-accordion.css';
const SupportDocResource = new Resource('support-docs');

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
import qs from 'qs';
import popupTemplate from '../webgis/scripts/popupTemplate';
import centroid from '@turf/centroid';

export default {
  name: 'CreateProject',
  components: {
    ClassicUpload,
    Workflow,
    SubProjectTable,
  },
  data() {
    var validateAddress = (rule, value, callback) => {
      if (value.length < 1) {
        callback(new Error('Mohon Masukan 1 Alamat'));
      }
      callback();
    };
    var validateListSubProject = (rule, value, callback) => {
      if (this.listSubProject.length < 1) {
        callback(new Error('Mohon Masukan 1 Kegiatan'));
      }
      callback();
    };

    var validateKtr = (rule, value, callback) => {
      if (!this.currentProject.fileKtr){
        callback(new Error('File KTR Belum Diunggah'));
      }
      callback();
    };

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
      full_address: '',
      mismatchMapData: false,
      token: '',
      refresh: 0,
      preeAgreementLabel: '',
      preProject: true,
      activeName: '1',
      currentProject: {
        address: [],
      },
      listSupportTable: [],
      listSubProject: [],
      loadingSupportTable: false,
      isUpload: 'Upload',
      fileName: 'No File Selected.',
      fileMap: null,
      filePdf: null,
      isOss: true,
      fileKtrName: 'No File Selected',
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
        address: [
          { validator: validateAddress, trigger: 'blur' },
        ],
        location_desc: [
          { required: true, trigger: 'blur', message: 'Data Lokasi Kegiatan Belum Diisi' },
        ],
        description: [
          { required: true, trigger: 'blur', message: 'Data Deskripsi Kegiatan Belum Diisi' },
        ],
        fileKtr: [
          { validator: validateKtr, trigger: 'change' },
        ],
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
    isPemerintah() {
      return this.$store.getters.isPemerintah;
    },
  },
  async created() {
    urlUtils.addProxyRule({
      proxyUrl: 'proxy/proxy.php',
      urlPrefix: 'https://amdalgis.menlhk.go.id/',
    });

    var data = qs.stringify({
      'username': 'Amdalnet',
      'password': 'Amdalnet123',
      'client': 'requestip',
      'expiration': 20160,
      'f': 'json',
    });

    var config = {
      method: 'post',
      headers: {
        'Content-Type': 'application/x-www-form-urlencoded',
        'Connection': 'keep-alive',
        'Content-Encoding': 'gzip',
      },
      body: data,
      responseType: 'json',
    };

    esriRequest('https://amdalgis.menlhk.go.id/portal/sharing/rest/generateToken', config)
      .then(response => {
        this.token = response.data.token;
      });

    // for step
    this.$store.dispatch('getStep', 0);

    // get initiator data
    const { email } = await this.$store.dispatch('user/getInfo');
    this.$store.dispatch('getInitiator', email);

    // for default value
    // this.currentProject.study_approach = 'Tunggal';
    // this.currentProject.status = 'Rencana';
    // this.currentProject.project_type = 'Baru';

    if (this.$route.params.project) {
      this.currentProject = this.$route.params.project;
      this.listSubProject = this.currentProject.listSubProject;
      this.fileMap = this.currentProject.fileMap;
      this.filePdf = this.currentProject.filePdf;
      this.filePdfName = this.filePdf.name;
      this.fileMapName = this.fileMap.name;
      this.fileKtr = this.currentProject.fileKtr;
      this.fileKtrName = this.fileKtr.name;
      this.filePreAgreement = this.currentProject.filePreAgreement;
      this.filePreAgreementName = this.currentProject.filePreAgreement.name;
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
  },
  methods: {
    goToPendekatanStudi(){
      this.$refs.tapakProyek.validate((valid) => {
        if (valid) {
          this.activeName = '2';
        } else {
          console.log('error submit!!');
          return false;
        }
      });
    },
    goToStatusKegiatan(){
      this.$refs.pendekatanStudi.validate((valid) => {
        if (valid) {
          this.activeName = '3';
        } else {
          console.log('error submit!!');
          return false;
        }
      });
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
      this.currentProject.listSubProject = this.listSubProject.map(e => {
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
        if (!this.currentProject.study_approach || this.currentProject.study_approach === 'Tunggal'){
          return e.type === 'utama' && e.result === 'AMDAL';
        } else if (this.currentProject.study_approach === 'Terpadu'){
          return e.result === 'AMDAL';
        }
      });
      const listMainProjectUklUpl = this.currentProject.listSubProject.filter(e => {
        if (!this.currentProject.study_approach || this.currentProject.study_approach === 'Tunggal'){
          return e.type === 'utama' && e.result === 'UKL-UPL';
        } else if (this.currentProject.study_approach === 'Terpadu'){
          return e.result === 'UKL-UPL';
        }
      });
      const listMainProjectSppl = this.currentProject.listSubProject.filter(e => {
        if (!this.currentProject.study_approach || this.currentProject.study_approach === 'Tunggal'){
          return e.type === 'utama' && e.result === 'SPPL';
        } else if (this.currentProject.study_approach === 'Terpadu'){
          return e.result === 'SPPL';
        }
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
      this.currentProject.authority = 'Pusat';
      this.currentProject.plan_type = choosenProject.name;
      this.currentProject.scale = choosenProject.scale;
      this.currentProject.scale_unit = choosenProject.scale_unit;
      this.currentProject.listSubProjectParams = choosenProject.listSubProjectParams;
    },
    handleStudyAccord(){
      this.calculateListSubProjectResult();
      this.calculateChoosenProject();
      this.goToStatusKegiatan();
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
      this.$refs['tapakProyek'].fields.find((f) => f.prop === 'filePdf').resetField();

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
      // reset validation
      this.$refs['tapakProyek'].fields.find((f) => f.prop === 'fileMap').resetField();

      if (e.target.files[0].size > 1048576){
        this.showFileAlert();
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
        url: 'https://sigap.menlhk.go.id/server/rest/services/A_Sumber_Daya_Hutan/Penutupan_Lahan_2020/MapServer',
        imageTransparency: true,
        visible: false,
        visibilityMode: '',
      });

      const kawasanHutanB = new MapImageLayer({
        url: 'https://sigap.menlhk.go.id/server/rest/services/B_Kawasan_Hutan/Kawasan_Hutan/MapServer',
        imageTransparency: true,
        visible: false,
        visibilityMode: '',
      });

      const pippib2021Periode2 = new MapImageLayer({
        url: 'https://sigap.menlhk.go.id/server/rest/services/K_Rencana_Kehutanan/PIPPIB_2021_Periode_2/MapServer',
        imageTransparency: true,
        visible: false,
        visibilityMode: '',
      });

      const sigapLayer = new GroupLayer({
        title: 'Peta Tematik Status',
        visible: false,
        layers: [penutupanLahan2020, kawasanHutanB, pippib2021Periode2],
        opacity: 0.90,
      });

      map.add(sigapLayer);

      const fr = new FileReader();
      fr.onload = (event) => {
        const base = event.target.result;
        shp(base).then((datas) => {
          const mapSampleProperties = [
            'OBJECTID_1',
            'PEMRAKARSA',
            'KEGIATAN',
            'TAHUN',
            'PROVINSI',
            'KETERANGAN',
            'AREA',
            'LAYER',
          ];

          const mapUploadProperties = Object.keys(datas.features[0].properties);
          const propFields = datas.features[0].properties;

          var centroids = centroid(datas.features[0]);
          var getCoordinates = centroids.geometry.coordinates;

          fetch(`https://nominatim.openstreetmap.org/reverse?lat=${getCoordinates[1]}&lon=${getCoordinates[0]}&format=json`)
            .then(response => response.json())
            .then(data => {
              if (data.osm_type === 'way') {
                fetch(`https://www.openstreetmap.org/api/0.6/way/${data.osm_id}.json`)
                  .then(response => response.json())
                  .then(state => {
                    this.full_address = state.elements[0].tags.addr;
                  });
              } else if (data.osm_type === 'relation') {
                fetch(`https://www.openstreetmap.org/api/0.6/relation/${data.osm_id}.json`)
                  .then(response => response.json())
                  .then(state => {
                    this.full_address = state.elements[0].tags.name;
                  });
              }
            });

          if (datas.features[0].geometry.type !== 'Polygon') {
            document.getElementById('fileMap').value = '';
            this.fileMapName = 'No File Selected';
            return this.$alert('SHP yang dimasukkan harus Polygon!', 'Format Salah', {
              confirmButtonText: 'Confirm',
            });
          }

          if (JSON.stringify(mapUploadProperties) !== JSON.stringify(mapSampleProperties)) {
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
      await this.getProjectsFromOss();
    },
    async getProjectsFromOss() {
      await this.$store.dispatch('getProjectOss');
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
      formdatas.append('url', 'https://amdalgis.menlhk.go.id/server/rest/services/Hosted/Test/FeatureServer');
      formdatas.append('type', 'Feature Service');
      formdatas.append('title', this.currentProject.project_title + '_Peta Tapak Proyek');
      formdatas.append('file', this.currentProject.fileMap);
      formdatas.append('filePdf', this.currentProject.filePdf);
      formdatas.append('fileName', this.currentProject.project_title + '_Peta Tapak Proyek');

      var requestOptions = {
        method: 'POST',
        headers: myHeaders,
        body: formdatas,
        responseType: 'json',
        multipart: true,
      };

      esriRequest('https://amdalgis.menlhk.go.id/portal/sharing/rest/content/users/Amdalnet/addItem', requestOptions)
        .then(response => response.data)
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
    handleAddSubProjectTable() {
      this.listSubProject.push({});
    },
    handleAddAddressTable() {
      this.currentProject.address.push({});
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

.mismatch__map {
  color: rgb(230, 41, 41);
  font-size: 10;
  font-style: italic;
}

</style>
