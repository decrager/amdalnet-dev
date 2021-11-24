<template>
  <div>
    <div class="detailPengumuman">
      <div class="wrapInDetail">
        <el-row :gutter="20">
          <el-col :span="24">
            <h1 style="color:#fff; margin-bottom:0">Informasi Rencana Kegiatan</h1>
          </el-col>
          <el-col :span="12">
            <!-- <el-row>
              <el-col :span="10">
                <p>No. Re gistrasi</p>
              </el-col>
              <el-col :span="14">
                <p v-html="selectedAnnouncement.project.id_project" />
              </el-col>
            </el-row>
            <el-row>
              <el-col :span="10">
                <p>Jenis Dokumen</p>
              </el-col>
              <el-col :span="14">
                <p v-html="selectedAnnouncement.project.required_doc" />
              </el-col>
            </el-row>
            <el-row>
              <el-col :span="10">
                <p>Nama Kegiatan</p>
              </el-col>
              <el-col :span="14">
                <p v-html="selectedAnnouncement.project.project_title" />
              </el-col>
            </el-row>
            <el-row>
              <el-col :span="10">
                <p>Bidang Usaha/Kegiatan</p>
              </el-col>
              <el-col :span="14">
                <p v-html="selectedAnnouncement.project.sector" />
              </el-col>
            </el-row>
            <el-row>
              <el-col :span="10">
                <p>Skala/Besaran</p>
              </el-col>
              <el-col :span="14">
                <p v-html="selectedAnnouncement.project.scale_unit" />
              </el-col>
            </el-row>
            <el-row>
              <el-col :span="10">
                <p>Alamat</p>
              </el-col>
              <el-col :span="14">
                <p v-html="selectedAnnouncement.project.address" />
              </el-col>
            </el-row>
            <el-row>
              <el-col :span="10">
                <p>Kewenangan</p>
              </el-col>
              <el-col :span="14">
                <p v-html="selectedAnnouncement.project.authority" />
              </el-col>
            </el-row>
            <el-row>
              <el-col :span="10">
                <p>Pemrakarsa</p>
              </el-col>
              <el-col :span="14">
                <p v-html="selectedAnnouncement.project.pic_name" />
              </el-col>
            </el-row>
            <el-row>
              <el-col :span="10">
                <p>Penanggung Jawab</p>
              </el-col>
              <el-col :span="14">
                <p v-html="selectedAnnouncement.project.pic_name" />
              </el-col>
            </el-row>
            <el-row>
              <el-col :span="10">
                <p>Alamat Pemrakarsa</p>
              </el-col>
              <el-col :span="14">
                <p v-html="selectedAnnouncement.project.cs_address" />
              </el-col>
            </el-row>
            <el-row>
              <el-col :span="10">
                <p>No Telepon Pemrakarsa</p>
              </el-col>
              <el-col :span="14">
              </el-col>
            </el-row>
            <el-row>
              <el-col :span="10">
                <p>Email Pemrakarsa</p>
              </el-col>
              <el-col :span="14">
              </el-col>
            </el-row>
            <el-row>
              <el-col :span="10">
                <p>Provinsi/Kota</p>
              </el-col>
              <el-col :span="14">
                <p v-html="selectedAnnouncement.project.province.name" />
              </el-col>
            </el-row>
            <el-row>
              <el-col :span="24">
                <p>Deskripsi Kegiatan</p>
              </el-col>
            </el-row>
            <el-row>
              <el-col :span="24">
                <p v-html="selectedAnnouncement.project.description" />
              </el-col>
            </el-row> -->
            <table class="tableDetail tableDetail1" cellspacing="0" cellpadding="0" style="border:none">
              <tr>
                <td style="width:40%">No. Re gistrasi</td>
                <td v-html="selectedAnnouncement.project.id_project" />
              </tr>
              <tr>
                <td style="width:40%">Jenis Dokumen</td>
                <td v-html="selectedAnnouncement.project.required_doc" />
              </tr>
              <tr>
                <td style="width:40%">Nama Kegiatan</td>
                <td v-html="selectedAnnouncement.project.project_title" />
              </tr>
              <tr>
                <td style="width:40%">Bidang Usaha/Kegiatan</td>
                <td v-html="selectedAnnouncement.project.sector" />
              </tr>
              <tr>
                <td style="width:40%">Skala/Besaran</td>
                <td v-html="selectedAnnouncement.project.scale_unit" />
              </tr>
              <tr>
                <td style="width:40%">Alamat</td>
                <td v-html="selectedAnnouncement.project.address" />
              </tr>
              <tr>
                <td style="width:40%">Kewenangan</td>
                <td v-html="selectedAnnouncement.project.authority" />
              </tr>
              <tr>
                <td style="width:40%">Pemrakarsa</td>
                <td v-html="selectedAnnouncement.pic_name" />
              </tr>
              <tr>
                <td style="width:40%">Penanggung Jawab</td>
                <td v-html="selectedAnnouncement.pic_name" />
              </tr>
              <tr>
                <td style="width:40%">Alamat Pemrakarsa</td>
                <td v-html="selectedAnnouncement.cs_address" />
              </tr>
              <tr>
                <td style="width:40%">No Telepon Pemrakarsa</td>
                <td />
              </tr>
              <tr>
                <td style="width:40%">Email Pemrakarsa</td>
                <td />
              </tr>
              <tr>
                <td style="width:40%">Provinsi/Kota</td>
                <td v-html="selectedAnnouncement.project.province.name" />
              </tr>
              <tr>
                <td colspan="2">Deskripsi Kegiatan</td>
              </tr>
              <tr>
                <td colspan="2" v-html="selectedAnnouncement.project.description" />
              </tr>
            </table>
          </el-col>
          <!-- <el-col :span="2"></el-col> -->
          <el-col :span="12">
            <!-- <div class="detailLokasi">
              <p style="color:rgb(60, 63, 60)">Lokasi</p>
              <div class="mapsDetail"></div>
            </div> -->
            <table class="tableDetail" cellspacing="0" cellpadding="0" style="border:none">
              <tr>
                <td colspan="2" class="bg-white-custom">Lokasi</td>
              </tr>
              <tr class="bg-white-custom">
                <td colspan="2">
                  <div class="detailLokasi">
                    <div id="mapView" />
                  </div>
                </td>
              </tr>
              <tr class="bg-blue-custom">
                <td colspan="2">Deskripsi Kegiatan</td>
              </tr>
              <tr class="bg-white-custom">
                <td colspan="2" v-html="selectedAnnouncement.project.description" />
              </tr>
            </table>
          </el-col>
        </el-row>
        <!-- <el-row :gutter="20">
          <el-col :span="16">
            <div class="wrapDetail">
              <img src="https://placeimg.com/150/150/arch/grayscale" alt="">
              <div class="wrapDetailRight">
                <p style="margin-bottom: 1rem">{{ selectedAnnouncement.project_type }}</p>
                <h4 class="fw-bold">{{ selectedAnnouncement.pic_name }}</h4>
                <h4 class="fw-bold">{{ selectedAnnouncement.project ? selectedAnnouncement.project.address : '' }}, {{ selectedAnnouncement.project ? selectedAnnouncement.project.province.name : '' }}</h4>
              </div>
            </div>
          </el-col>
          <el-col :span="8">
            <div class="wrapDetailRightRow">
              <el-button type="success" plain>{{ selectedAnnouncement.project ? selectedAnnouncement.project.required_doc : '' }}</el-button>
              <h4 class="fw-bold">Reg No. {{ selectedAnnouncement.project ? selectedAnnouncement.project.id_project : '' }}</h4>
              <h4 class="fw-bold">[{{ selectedAnnouncement.project ? selectedAnnouncement.project.project_type : '' }}]</h4>
            </div>
          </el-col>
        </el-row>
        <el-row :gutter="20">
          <el-col :span="12">
            <div style="padding-left: 2rem">
              <h4 class="fw-bold">Pengumuman</h4>
              <p>{{ formatDateStr(selectedAnnouncement.start_date) }} - {{ formatDateStr(selectedAnnouncement.end_date) }}</p>
            </div>
          </el-col>
          <el-col :span="12">
            <div style="padding-right: 2rem">
              <el-button type="warning"><a :href="basePath+selectedAnnouncement.proof">Unduh bukti pengumuman</a></el-button>
            </div>
          </el-col>
        </el-row>
        <el-row :gutter="20">
          <el-col :span="24">
            <div style="padding-left: 2rem; margin-top: 2rem">
              <p style="margin-bottom: 1rem" v-html="selectedAnnouncement.project ? selectedAnnouncement.project.description : ''" />
              <h4 class="fw-bold">Dampak Potensi</h4>
              <p style="margin-bottom: 1.5rem" v-html="selectedAnnouncement.project ? selectedAnnouncement.project.potential_impact : ''" />
            </div>
          </el-col>
        </el-row>
        <el-row :gutter="20">
          <el-col :span="14">
            <div style="padding-left: 2rem">
              <div style="display: flex; margin-bottom: 1rem">
                <h4 class="fw-bold" style="">Sifat Kegiatan:</h4>
                <h4>&nbsp;{{ selectedAnnouncement.project ? selectedAnnouncement.project.project_type : '' }}</h4>
              </div>
              <h4 class="fw-bold">Project Location</h4>
              <p style="margin-bottom: 1.5rem">{{ selectedAnnouncement.project_location }}</p>
            </div>
          </el-col>
          <el-col :span="10">
            <div style="padding-right: 2rem">
              <div style="height: 400px; width: 100%; z-index: -1">
                <div>
                  <div id="mapView" />
                </div>
                <l-map :zoom="zoom" :center="center">
                  <l-marker :lat-lng="center" />
                  <l-tile-layer :url="urlMap" :attribution="attribution" />
                </l-map>
              </div>
            </div>
          </el-col>
        </el-row> -->
      </div>
    </div>
    <div class="detailPengumuman">
      <div class="wrapInDetail wrapInDetailBottom">
        <el-form
          ref="form"
          enctype="multipart/form-data"
          @submit.prevent="saveFeedback"
        >
          <input v-model="selectedAnnouncement.id" type="hidden">
          <el-row :gutter="20">
            <el-col :span="12">
              <h3 style="text-align:center; color:#fff;">Saran, Pendapat, dan Tanggapan untuk Kegiatan</h3>
              <el-row :gutter="20">
                <el-col :span="12">
                  <el-form-item>
                    <div class="text-white fw-bold">Nama</div>
                    <el-input v-model="form.name" placeholder="Nama" />
                  </el-form-item>
                </el-col>
                <el-col :span="12">
                  <el-form-item>
                    <div class="text-white fw-bold">Peran</div>
                    <el-form-item label="">
                      <el-select v-model="form.peran" placeholder="Pilih Peran" @change="handleChangeModal">
                        <el-option label="Masyarakat Terkena Dampak Langsung" value="1" />
                        <el-option label="Pemerhati Lingkungan Hidup" value="2" />
                        <el-option label="LSM" value="3" />
                        <el-option label="Masyarakat Berkepentingan Lainya" value="4" />
                      </el-select>
                    </el-form-item>
                    <!-- <div style="padding-left:2rem">
                      <div>
                        <el-radio
                          v-model="form.responder_type_id"
                          label="1"
                        >
                          <span style="color:white">Terkena Dampak Langsung</span></el-radio>
                      </div>
                      <div>
                        <el-radio
                          v-model="form.responder_type_id"
                          label="2"
                        ><span style="color:white">Pemerhati Lingkungan Hidup</span></el-radio>
                      </div>
                      <div>
                        <el-radio
                          v-model="form.responder_type_id"
                          label="3"
                        ><span style="color:white">LSM</span></el-radio>
                      </div>
                      <div>
                        <el-radio
                          v-model="form.responder_type_id"
                          label="4"
                        ><span style="color:white">Masyarakat Berkepentingan Lainya</span></el-radio>
                      </div>
                    </div> -->
                  </el-form-item>
                </el-col>
              </el-row>
              <el-row :gutter="20">
                <el-col :span="12">
                  <el-form-item>
                    <div class="text-white fw-bold">Nik</div>
                    <el-input v-model="form.id_card_number" placeholder="Nik" />
                  </el-form-item>
                </el-col>
                <el-col :span="12">
                  <el-form-item>
                    <div class="text-white fw-bold">No. Telepon/Handphone</div>
                    <el-input
                      v-model="form.phone"
                      placeholder="No. Telepon/Handphone"
                    />
                  </el-form-item>
                </el-col>
              </el-row>
              <el-row :gutter="20">
                <el-col :span="24">
                  <el-form-item>
                    <div class="text-white fw-bold">Email</div>
                    <el-input
                      v-model="form.email"
                      type="email"
                      placeholder="Email"
                    />
                  </el-form-item>
                </el-col>
              </el-row>
            </el-col>
            <el-col :span="12">
              <el-form-item>
                <div style="margin-top:2rem; display:block;">
                  <div class="text-white fw-bold" style="text-align:center">Unggah Foto Selfie</div>
                  <div style="width:200px; height:200px; background:#d0d0d0 none repeat scroll 0% 0%; display:block; margin:auto; border-radius:50%;line-height: 307px;text-align: center;">
                    <img v-if="url" :src="url" style="width:60%;height: 60%;object-fit: cover;">
                  </div>
                  <div style="text-align:center;">
                    <input
                      ref="file"
                      style="margin-left:6rem"
                      type="file"
                      class=""
                      @change="handleFileUpload()"
                    >
                  </div>
                </div>
              </el-form-item>
            </el-col>
          </el-row>
          <el-row :gutter="20">
            <el-col :span="12">
              <el-row>
                <el-col :span="24">
                  <el-form-item>
                    <div class="text-white fw-bold">Kondisi Lingkungan di Dalam dan Sekitar Lokasi Tapak Proyek</div>
                    <el-input
                      v-model="form.envyCondition"
                      type="textarea"
                      placeholder="Kondisi Lingkungan di Dalam dan Sekitar Lokasi Tapak Proyek"
                    />
                  </el-form-item>
                  <el-form-item>
                    <div class="text-white fw-bold">Nilai Lokal  yang Berpotensi akan Terkena Dampak</div>
                    <el-input
                      v-model="form.localImpact"
                      type="textarea"
                      placeholder="Nilai Lokal  yang Berpotensi akan Terkena Dampak"
                    />
                  </el-form-item>
                  <h3 class="fw-bold text-white">Rating</h3>
                  <div class="text-white fw-bold" style="margin-bottom:1rem">
                    Tingkat kesetujuan Anda terhadap Kegiatan/Proyek Ini
                  </div>
                  <div style="display:flex">
                    <div>
                      <ul>
                        <li class="fz8">1 Bintang : Sangat tidak setuju</li>
                        <li class="fz8">2 Bintang : Tidak setuju</li>
                        <li class="fz8">3 Bintang : Netral</li>
                        <li class="fz8">4 Bintang : Setuju</li>
                        <li class="fz8">5 Bintang : Sangat Setuju</li>
                      </ul>
                    </div>
                    <div style="padding-left:1rem">
                      <el-rate v-model="ratings" style="font-size:3rem" @change="handleChange(ratings)" />
                    </div>
                  </div>
                </el-col>
              </el-row>
            </el-col>
            <el-col :span="12">
              <el-form-item>
                <div class="text-white fw-bold">Kekhawatiran</div>
                <el-input
                  v-model="form.concern"
                  type="textarea"
                  placeholder="Kekhawatiran"
                />
              </el-form-item>
              <el-form-item>
                <div class="text-white fw-bold">Harapan</div>
                <el-input
                  v-model="form.expectation"
                  type="textarea"
                  placeholder="Harapan"
                />
              </el-form-item>
            </el-col>
          </el-row>
          <el-dialog
            title="Masyarakat Terkena Dampak Langsung"
            :visible.sync="centerDialogVisible"
            width="30%"
            center
          >
            <el-checkbox-group v-model="form.comunityType">
              <el-checkbox label="Kelompok Masyarakat Rentan" />
              <el-checkbox label="Kelompok Masyarakat Adat" />
              <el-checkbox label="Kelompok Ke s etaraan Gender" />
            </el-checkbox-group>
            <template>
              <el-radio v-model="form.comunityGender" label="1">Laki - laki</el-radio>
              <el-radio v-model="form.comunityGender" label="2">Perempuan</el-radio>
            </template>
            <span slot="footer" class="dialog-footer">
              <el-button type="primary" @click="centerDialogVisible = false">Simpan</el-button>
            </span>
          </el-dialog>
        </el-form>
        <el-row :gutter="20">
          <el-col :span="18">
            <div class="detailFoot">
              <el-button
                id="batal"
                type="danger"
                @click="handleCancelComponent()"
              >Batal</el-button>
              <el-button
                id="kirim"
                type="primary"
                @click="saveFeedback()"
              >Kirim</el-button>
            </div>
          </el-col>
        </el-row>
      </div>
    </div>
  </div>
</template>
<script>
import axios from 'axios';
import _ from 'lodash';
import L from 'leaflet';
import MapImageLayer from '@arcgis/core/layers/MapImageLayer';
import Map from '@arcgis/core/Map';
import MapView from '@arcgis/core/views/MapView';
import Home from '@arcgis/core/widgets/Home';
import Compass from '@arcgis/core/widgets/Compass';
import BasemapToggle from '@arcgis/core/widgets/BasemapToggle';
import Attribution from '@arcgis/core/widgets/Attribution';
import Expand from '@arcgis/core/widgets/Expand';
import Legend from '@arcgis/core/widgets/Legend';
import LayerList from '@arcgis/core/widgets/LayerList';
import GroupLayer from '@arcgis/core/layers/GroupLayer';
import GeoJSONLayer from '@arcgis/core/layers/GeoJSONLayer';
import shp from 'shpjs';
// import { LMap, LTileLayer, LMarker } from 'vue2-leaflet';

export default {
  name: 'Details',
  components: {
    // LMap,
    // LTileLayer,
    // LMarker,
  },
  props: {
    // showDetails: Boolean,
    selectedAnnouncement: {
      type: Object,
      default: () => {},
    },
    selectedProject: {
      type: Object,
      default: () => {},
    },
    announcementId: {
      type: Number,
      default: 0,
    },
  },
  data() {
    return {
      data: {},
      form: {
        name: null,
        id_card_number: null,
        phone: null,
        email: null,
        responder_type_id: null,
        concern: null,
        expectation: null,
        announcement_id: 0,
        rating: null,
        peran: '',
        envyCondition: null,
        localImpact: null,
        comunityType: ['Kelompok Masyarakat Rentan', 'Kelompok Masyarakat Adat', 'Kelompok Ke setaraan Gender'],
        comunityGender: null,
      },
      responders: [],
      errorMessage: null,
      photo_filepath: null,
      ratings: null,
      url: '/images/avatar.png',
      urlMap: 'http://{s}.tile.osm.org/{z}/{x}/{y}.png',
      attribution: '© <a href="http://osm.org/copyright">OpenStreetMap</a> contributors',
      zoom: 12,
      center: [-6.93, 107.60],
      bounds: null,
      basePath: window.location.origin,
      icon: L.icon({
        iconUrl: require('leaflet/dist/images/marker-icon.png').default,
        iconSize: [38, 95],
        iconAnchor: [22, 94],
      }),
      centerDialogVisible: false,
      checkList: [],
      radio: '',
    };
  },
  async created() {
    await this.getResponderType();
    this.loadMap();
  },
  methods: {
    loadMap() {
      const map = new Map({
        basemap: 'topo',
      });

      const batasProv = new MapImageLayer({
        url: 'https://regionalinvestment.bkpm.go.id/gis/rest/services/Administrasi/batas_wilayah_provinsi/MapServer',
        sublayers: [{
          id: 0,
          title: 'Batas Provinsi',
        }],
      });

      const graticuleGrid = new MapImageLayer({
        url: 'https://gis.ngdc.noaa.gov/arcgis/rest/services/graticule/MapServer',
      });

      map.addMany([batasProv, graticuleGrid]);

      const baseGroupLayer = new GroupLayer({
        title: 'Base Layer',
        visible: true,
        layers: [batasProv, graticuleGrid],
        opacity: 0.90,
      });

      map.add(baseGroupLayer);

      const mapGeojsonArray = [];
      const splitMap = this.selectedProject.map.split('|');
      console.log(splitMap);
      shp(window.location.origin + this.selectedProject.map).then(data => {
        if (data.length > 1) {
          for (let i = 0; i < data.length; i++) {
            const blob = new Blob([JSON.stringify(data[i])], {
              type: 'application/json',
            });
            const url = URL.createObjectURL(blob);
            const geojsonLayerArray = new GeoJSONLayer({
              url: url,
              outFields: ['*'],
              title: 'Layers',
            });
            mapView.on('layerview-create', (event) => {
              mapView.goTo({
                target: geojsonLayerArray.fullExtent,
              });
            });
            mapGeojsonArray.push(geojsonLayerArray);
          }
          const kegiatanGroupLayer = new GroupLayer({
            title: this.selectedProject.project_title,
            visible: true,
            visibilityMode: 'exclusive',
            layers: mapGeojsonArray,
            opacity: 0.75,
          });

          map.add(kegiatanGroupLayer);
        } else {
          const blob = new Blob([JSON.stringify(data)], {
            type: 'application/json',
          });
          const url = URL.createObjectURL(blob);
          const geojsonLayer = new GeoJSONLayer({
            url: url,
            visible: true,
            outFields: ['*'],
            opacity: 0.75,
            title: this.selectedProject.project_title,
          });

          map.add(geojsonLayer);
          mapView.on('layerview-create', (event) => {
            mapView.goTo({
              target: geojsonLayer.fullExtent,
            });
          });
        }
      });

      const mapView = new MapView({
        container: 'mapView',
        map: map,
        center: [115.287, -1.588],
        zoom: 6,
      });
      this.$parent.mapView = mapView;

      const home = new Home({
        view: mapView,
      });

      mapView.popup.on('trigger-action', (event) => {
        if (event.action.id === 'get-details') {
          console.log('tbd');
        }
      });

      mapView.ui.add(home, 'top-left');
      const compass = new Compass({
        view: mapView,
      });
      mapView.ui.add(compass, 'top-left');
      const basemapToggle = new BasemapToggle({
        view: mapView,
        container: document.createElement('div'),
        secondBasemap: 'satellite',
      });
      const expandBasemapToggler = new Expand({
        view: mapView,
        name: 'basemap',
        content: basemapToggle.domNode,
        expandIconClass: 'esri-icon-basemap',
      });
      mapView.ui.add(expandBasemapToggler, 'top-left');
      const attribution = new Attribution({
        view: mapView,
      });
      mapView.ui.add(attribution, 'manual');

      const legend = new Legend({
        view: mapView,
        container: document.createElement('div'),
      });
      const layerList = new LayerList({
        view: mapView,
        container: document.createElement('div'),
        listItemCreatedFunction: this.defineActions,
      });

      layerList.on('trigger-action', (event) => {
        const id = event.action.id;
        if (id === 'full-extent') {
          mapView.goTo({
            target: event.item.layer.fullExtent,
          });
        }
      });

      const legendExpand = new Expand({
        view: mapView,
        content: legend.domNode,
        expandIconClass: 'esri-icon-collection',
        expandTooltip: 'Legend',
      });

      const layersExpand = new Expand({
        view: mapView,
        content: layerList.domNode,
        expandIconClass: 'esri-icon-layer-list',
        expandTooltip: 'Layers',
      });
      mapView.ui.add(legendExpand, 'bottom-left');
      mapView.ui.add(layersExpand, 'top-right');
    },
    formatDateStr(date) {
      const today = new Date(date);
      var bulan = ['Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni', 'Juli', 'Agustus', 'September', 'Oktober', 'November', 'Desember'];

      // Getting required values
      const year = today.getFullYear();
      const month = today.getMonth();
      const day = today.getDate();

      const monthID = bulan[month];

      // Creating a new Date (with the delta)
      const finalDate = `${day} ${monthID} ${year}`;

      return finalDate;
    },
    handleChange(i){
      this.form.rating = i;
    },
    getAnnouncement() {
      axios.get('/api/announcements').then((response) => {
        return response.data.data;
      });
    },
    handleFileUpload(e) {
      this.photo_filepath = this.$refs.file.files[0];
      this.url = URL.createObjectURL(this.$refs.file.files[0]);
    },
    async saveFeedback() {
      const formData = new FormData();
      formData.append('photo_filepath', this.photo_filepath);
      formData.append('name', this.form.name);
      formData.append('id_card_number', this.form.id_card_number);
      formData.append('phone', this.form.phone);
      formData.append('email', this.form.email);
      formData.append('responder_type_id', this.form.peran);
      formData.append('concern', this.form.concern);
      formData.append('expectation', this.form.expectation);
      formData.append('rating', this.form.rating);
      formData.append('announcement_id', this.selectedAnnouncement.id);
      formData.append('environment_condition', this.form.envyCondition);
      formData.append('local_impact', this.form.localImpact);
      formData.append('community_type', this.form.comunityType);
      formData.append('community_gender', this.form.comunityGender);

      _.each(this.formData, (value, key) => {
        formData.append(key, value);
      });

      const headers = { 'Content-Type': 'multipart/form-data' };
      await axios
        .post('api/feedbacks', formData, { headers })
        .then((data) => {
          console.log(data);
          this.$message({
            type: 'success',
            message: 'Successfully create a feedback',
            duration: 5 * 1000,
          });
          this.$emit('handleSetTabs');
          this.getAnnouncement();
        })
        .catch((error) => {
          this.errorMessage = error.message;
          this.$message({
            type: 'error',
            message: error.message,
            duration: 5 * 1000,
          });
        });
    },
    async getResponderType() {
      await axios.get('api/responder-types').then((response) => {
        this.responders = response.data.data;
      });
    },
    handleCancelComponent() {
      this.$emit('handleCancelComponent', 'TABS');
    },
    handleChangeModal(){
      if (this.form.peran === '1'){
        this.centerDialogVisible = true;
      }
    },
  },
};
</script>
<style scoped>
.titleDetail h1 {
  font-size: 19px;
  margin-top: 15px !important;
}
.detailPengumuman {
  background: #365337;
  padding: 1.5rem;
  border-radius: 10px;
  margin-top: 2rem;
}
h1 {
  color: #fff !important;
  font-weight: bold !important;
  margin-bottom: 1.5rem !important;
  margin-top: 0 !important;
}
table.table__striped {
  border-collapse: collapse;
  width: 100%;
}

table.table__striped th,
table.table__striped td {
  text-align: left;
  padding: 8px;
  font-size: 0.8rem !important;
  font-weight: bold !important;
}

table.table__striped tr:nth-child(even) {
  background-color: #aec8af;
  color: #383732;
}
table.table__striped tr:nth-child(odd) {
  background-color: #fff;
  color: #383732;
}
.detailFoot {
  display: flex;
  justify-content: flex-end;
  margin-top: 2rem;
}
#batal {
  background-color: #da5b4a !important;
}
#kirim {
  background-color: #1bbf66 !important;
}
.text-white {
  color: #fff;
}
/* .el-form-item {
  margin-bottom: 0;
} */
.fw-bold {
  font-weight: bold;
}
.el-select {
  width: 100%;
}
.rating {
  display: flex;
}
.rating p {
  font-size: 0.8rem;
}
.wrapDetail {
  display: flex;
  padding: 2rem 1.5rem;
  border-radius: 10px;
  align-items: center;
}
.wrapInDetail {
  background-color: #365337;
  padding-bottom: 2rem;
}
.wrapDetailRight {
  padding-left: 1rem;
  margin-top: -2rem;
}
.fw-bold {
  font-weight: bold;
}
.wrapDetailRightRow {
  margin-top: 3rem;
  text-align: right;
  padding-right: 2rem;
}
.el-button--success.is-plain {
  color: #fff;
  background: #13ce66;
  border-color: #13ce66;
  font-weight: bold;
  border-radius: 1rem;
  margin-bottom: 1rem;
}
.el-button--warning {
  float: right;
}
.maps {
  width: 100%;
  height: 300px;
  background-color: red;
}
.wrapInDetailBottom {
  padding:2rem;
}
.fz8{font-size: 0.8rem;}
.el-rate__icon{font-size: 2.5rem !important;}
.el-rate__icon.el-icon-star-off {font-size: 3rem;}
#header {
    z-index: 99999;
}
#mapView {
  width: 100%;
  height: 23.7rem;
  max-height: 100vh;
  /* padding: 0;
  margin: 0 10px;
  position: absolute; */
}
.tableDetail1 tr:nth-child(even) {background: #aec7af}
.tableDetail1 tr:nth-child(odd) {background: #FFF}
.bg-blue-custom {background: #aec7af}
.bg-white-custom {background: #FFF}
.tableDetail td{color: #3c3f3c; padding:0.5rem; width: 50%; border:0}
.detailLokasi{background-color: #fff;padding: 0.5rem;}
.mapsDetail{width: 100%; height: 20rem;}
</style>
