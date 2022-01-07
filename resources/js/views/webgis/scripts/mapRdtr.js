import GroupLayer from '@arcgis/core/layers/GroupLayer';
import * as urlUtils from '@arcgis/core/core/urlUtils';
import MapImageLayer from '@arcgis/core/layers/MapImageLayer';

urlUtils.addProxyRule({
  proxyUrl: 'proxy/proxy.php',
  urlPrefix: 'https://gistaru.atrbpn.go.id/',
});

const rdtrAcehBandaAceh = new MapImageLayer({
  url: 'https://gistaru.atrbpn.go.id/arcgis/rest/services/044_RDTR_PROVINSI_ACEH/_11A1_RDTR_KOTA_BANDA_ACEH/MapServer',
  visible: false,
});

const rdtrAceh = new GroupLayer({
  title: 'RDTR Aceh',
  visible: false,
  layers: [rdtrAcehBandaAceh],
  opacity: 0.90,
});

const rdtrSumutBatangToru = new MapImageLayer({
  url: 'https://gistaru.atrbpn.go.id/arcgis/rest/services/045_RDTR_PROVINSI_SUMATERA_UTARA/_RDTR_12A2_PERKOTAAN_BATANG_TORU/MapServer',
  visible: false,
});

const rdtrSumutKualaTanjung = new MapImageLayer({
  url: 'https://gistaru.atrbpn.go.id/arcgis/rest/services/045_RDTR_PROVINSI_SUMATERA_UTARA/_RDTR_12A3_BWP_SELATAN_KUALA_TANJUNG/MapServer',
  visible: false,
});

const rdtrSumutTarutung = new MapImageLayer({
  url: 'https://gistaru.atrbpn.go.id/arcgis/rest/services/045_RDTR_PROVINSI_SUMATERA_UTARA/_RDTR_12A5_SIPOHOLON_TARUTUNG_SIATASBARITA/MapServer',
  visible: false,
});

const rdtrSumut = new GroupLayer({
  title: 'RDTR Sumatera Utara',
  visible: false,
  layers: [rdtrSumutBatangToru, rdtrSumutKualaTanjung, rdtrSumutTarutung],
  opacity: 0.90,
});

const rdtrRiauSiak = new MapImageLayer({
  url: 'https://gistaru.atrbpn.go.id/arcgis/rest/services/047_RDTR_PROVINSI_RIAU/_RDTR_14A1_PERKOTAAN_SIAK/MapServer',
  visible: false,
});

const rdtrRiauLanggam = new MapImageLayer({
  url: 'https://gistaru.atrbpn.go.id/arcgis/rest/services/047_RDTR_PROVINSI_RIAU/_RDTR_14A2_PERKOTAAN_LANGGAM/MapServer',
  visible: false,
});

const rdtrRiau = new GroupLayer({
  title: 'RDTR Riau',
  visible: false,
  layers: [rdtrRiauSiak, rdtrRiauLanggam],
  opacity: 0.90,
});

const rdtrSumselPalembang = new MapImageLayer({
  url: 'https://gistaru.atrbpn.go.id/arcgis/rest/services/049_RDTR_PROVINSI_SUMATERA_SELATAN/_RDTR_16A1_BWP_KOTA_BARU_JAKABARING_PALEMBANG/MapServer',
  visible: false,
});

const rdtrSumsel = new GroupLayer({
  title: 'RDTR Sumatera Selatan',
  visible: false,
  layers: [rdtrSumselPalembang],
  opacity: 0.90,
});

const rdtrLampungNatar = new MapImageLayer({
  url: 'https://gistaru.atrbpn.go.id/arcgis/rest/services/051_RDTR_PROVINSI_LAMPUNG/_RDTR_18A1_PERKOTAAN_NATAR/MapServer',
  visible: false,
});

const rdtrLampungGinting = new MapImageLayer({
  url: 'https://gistaru.atrbpn.go.id/arcgis/rest/services/051_RDTR_PROVINSI_LAMPUNG/_RDTR_18A2_PERKOTAAN_GISTING/MapServer',
  visible: false,
});

const rdtrLampung = new GroupLayer({
  title: 'RDTR Lampung',
  visible: false,
  layers: [rdtrLampungNatar, rdtrLampungGinting],
  opacity: 0.90,
});

const rdtrBabelPangBar = new MapImageLayer({
  url: 'https://gistaru.atrbpn.go.id/arcgis/rest/services/052_RDTR_PROVINSI_BANGKABELITUNG/_RDTR_19A2_KAWASAN_PERKOTAAN__PANGKALANBARU_DAN_PERKOTAAN_KOBA/MapServer',
  visible: false,
});

const rdtrBabelGantung = new MapImageLayer({
  url: 'https://gistaru.atrbpn.go.id/arcgis/rest/services/052_RDTR_PROVINSI_BANGKABELITUNG/_RDTR_19A3_PERKOTAAN_GANTUNG/MapServer',
  visible: false,
});

const rdtrBabelSungaiLiat = new MapImageLayer({
  url: 'https://gistaru.atrbpn.go.id/arcgis/rest/services/052_RDTR_PROVINSI_BANGKABELITUNG/_RDTR_SUNGAILIAT/MapServer',
  visible: false,
});

const rdtrBabel = new GroupLayer({
  title: 'RDTR Bangka Belitung',
  visible: false,
  layers: [rdtrBabelPangBar, rdtrBabelGantung, rdtrBabelSungaiLiat],
  opacity: 0.90,
});

const rdtrJabarBojongSoang = new MapImageLayer({
  url: 'https://gistaru.atrbpn.go.id/arcgis/rest/services/055_RDTR_PROVINSI_JAWA_BARAT/_32B1_RDTR_BOJONGSOANG/MapServer',
  visible: false,
});

const rdtrTegalluar = new MapImageLayer({
  url: 'https://gistaru.atrbpn.go.id/arcgis/rest/services/055_RDTR_PROVINSI_JAWA_BARAT/_32B2_RDTR_KAWASAN_TERPADU_PERMUKIMAN_TEGALLUAR/MapServer',
  visible: false,
});

const rdtrTasimalaya = new MapImageLayer({
  url: 'https://gistaru.atrbpn.go.id/arcgis/rest/services/055_RDTR_PROVINSI_JAWA_BARAT/_RDTR_32A3_KOTA_TASIKMALAYA/MapServer',
  visible: false,
});

const rdtrSingaparna = new MapImageLayer({
  url: 'https://gistaru.atrbpn.go.id/arcgis/rest/services/055_RDTR_PROVINSI_JAWA_BARAT/_RDTR_32A4_SINGAPARNA/MapServer',
  visible: false,
});

const rdtrSumedang = new MapImageLayer({
  url: 'https://gistaru.atrbpn.go.id/arcgis/rest/services/055_RDTR_PROVINSI_JAWA_BARAT/_RDTR_32A6_PERKOTAAN_SUMEDANG/MapServer',
  visible: false,
});

const rdtrParung = new MapImageLayer({
  url: 'https://gistaru.atrbpn.go.id/arcgis/rest/services/055_RDTR_PROVINSI_JAWA_BARAT/_RDTR_32A9_PERKOTAAN_PARUNG__PANJANG/MapServer',
  visible: false,
});

const rdtrBandung = new MapImageLayer({
  url: 'https://gistaru.atrbpn.go.id/arcgis/rest/services/055_RDTR_PROVINSI_JAWA_BARAT/_RDTR_KOTA_BANDUNG/MapServer',
  visible: false,
});

const rdtrBekasi = new MapImageLayer({
  url: 'https://gistaru.atrbpn.go.id/arcgis/rest/services/055_RDTR_PROVINSI_JAWA_BARAT/_RDTR_KOTA_BEKASI/MapServer',
  visible: false,
});

const rdtrJabar = new GroupLayer({
  title: 'RDTR Jawa Barat',
  visible: false,
  layers: [rdtrJabarBojongSoang, rdtrTegalluar, rdtrTasimalaya, rdtrSingaparna, rdtrSumedang, rdtrParung, rdtrBandung, rdtrBekasi],
  opacity: 0.90,
});

const rdtrJatengPwkt = new MapImageLayer({
  url: 'https://gistaru.atrbpn.go.id/arcgis/rest/services/056_RDTR_PROVINSI_JAWA_TENGAH/_RDTR_33A1_PERKOTAAN_PURWOKERTO/MapServer',
  visible: false,
});

const rdtrJatengTulis = new MapImageLayer({
  url: 'https://gistaru.atrbpn.go.id/arcgis/rest/services/056_RDTR_PROVINSI_JAWA_TENGAH/_RDTR_33A2_TULIS/MapServer',
  visible: false,
});

const rdtrJatengSuko = new MapImageLayer({
  url: 'https://gistaru.atrbpn.go.id/arcgis/rest/services/056_RDTR_PROVINSI_JAWA_TENGAH/_RDTR_33A3_SUKOHARJO/MapServer',
  visible: false,
});

const rdtrJatengKarto = new MapImageLayer({
  url: 'https://gistaru.atrbpn.go.id/arcgis/rest/services/056_RDTR_PROVINSI_JAWA_TENGAH/_RDTR_33A4_KARTASURA/MapServer',
  visible: false,
});

const rdtrJatengGrogol = new MapImageLayer({
  url: 'https://gistaru.atrbpn.go.id/arcgis/rest/services/056_RDTR_PROVINSI_JAWA_TENGAH/_RDTR_33A5_PERKOTAAN_GROGOL/MapServer',
  visible: false,
});

const rdtrJatengCilacap = new MapImageLayer({
  url: 'https://gistaru.atrbpn.go.id/arcgis/rest/services/056_RDTR_PROVINSI_JAWA_TENGAH/_RDTR_33A6_PERKOTAAN_CILACAP/MapServer',
  visible: false,
});

const rdtrJateng = new GroupLayer({
  title: 'RDTR Jawa Barat',
  visible: false,
  layers: [rdtrJatengPwkt, rdtrJatengTulis, rdtrJatengSuko, rdtrJatengKarto, rdtrJatengGrogol, rdtrJatengCilacap],
  opacity: 0.90,
});

const rdtrDIYSleman = new MapImageLayer({
  url: 'https://gistaru.atrbpn.go.id/arcgis/rest/services/057_RDTR_PROVINSI_YOGYAKARTA/_RDTR_34A4_KAWASANSLEMANTIMUR/MapServer',
  visible: false,
});

const rdtrDIYKasian = new MapImageLayer({
  url: 'https://gistaru.atrbpn.go.id/arcgis/rest/services/057_RDTR_PROVINSI_YOGYAKARTA/_RDTR_BWP_KASIHAN/MapServer',
  visible: false,
});

const rdtrDIYSewon = new MapImageLayer({
  url: 'https://gistaru.atrbpn.go.id/arcgis/rest/services/057_RDTR_PROVINSI_YOGYAKARTA/_RDTR_BWP_SEWON/MapServer',
  visible: false,
});

const rdtrDIYYogya = new MapImageLayer({
  url: 'https://gistaru.atrbpn.go.id/arcgis/rest/services/057_RDTR_PROVINSI_YOGYAKARTA/_RDTR_KOTA_YOGYAKARTA/MapServer',
  visible: false,
});

const rdtrDIY = new GroupLayer({
  title: 'RDTR Jawa Barat',
  visible: false,
  layers: [rdtrDIYSleman, rdtrDIYKasian, rdtrDIYSewon, rdtrDIYYogya],
  opacity: 0.90,
});

const rdtrJatimSby = new MapImageLayer({
  url: 'https://gistaru.atrbpn.go.id/arcgis/rest/services/058_RDTR_PROVINSI_JAWA_TIMUR/_RDTR_35B8_KOTA_SURABAYA/MapServer',
  visible: false,
});

const rdtrJatimSidoarjo = new MapImageLayer({
  url: 'https://gistaru.atrbpn.go.id/arcgis/rest/services/058_RDTR_PROVINSI_JAWA_TIMUR/_RDTR_35B9_PERKOTAAN_SIDOARJO/MapServer',
  visible: false,
});

const rdtrJatimCandi = new MapImageLayer({
  url: 'https://gistaru.atrbpn.go.id/arcgis/rest/services/058_RDTR_PROVINSI_JAWA_TIMUR/_RDTR_35C1_PERKOTAAN_CANDI/MapServer',
  visible: false,
});

const rdtrJatimBuduran = new MapImageLayer({
  url: 'https://gistaru.atrbpn.go.id/arcgis/rest/services/058_RDTR_PROVINSI_JAWA_TIMUR/_RDTR_35C2_PERKOTAAN_BUDURAN/MapServer',
  visible: false,
});

const rdtrJatimPrambon = new MapImageLayer({
  url: 'https://gistaru.atrbpn.go.id/arcgis/rest/services/058_RDTR_PROVINSI_JAWA_TIMUR/_RDTR_35C3_PERKOTAAN_PRAMBON/MapServer',
  visible: false,
});

const rdtrJatimBalong = new MapImageLayer({
  url: 'https://gistaru.atrbpn.go.id/arcgis/rest/services/058_RDTR_PROVINSI_JAWA_TIMUR/_RDTR_35C4_PERKOTAAN_BALONGBENDO/MapServer',
  visible: false,
});

const rdtrJatimWonoayu = new MapImageLayer({
  url: 'https://gistaru.atrbpn.go.id/arcgis/rest/services/058_RDTR_PROVINSI_JAWA_TIMUR/_RDTR_35C5_PERKOTAAN_WONOAYU/MapServer',
  visible: false,
});

const rdtrJatimBangil = new MapImageLayer({
  url: 'https://gistaru.atrbpn.go.id/arcgis/rest/services/058_RDTR_PROVINSI_JAWA_TIMUR/_RDTR_35C6_BANGIL/MapServer',
  visible: false,
});

const rdtrJatimBeji = new MapImageLayer({
  url: 'https://gistaru.atrbpn.go.id/arcgis/rest/services/058_RDTR_PROVINSI_JAWA_TIMUR/_RDTR_35C7_PERKOTAAN_BEJI/MapServer',
  visible: false,
});

const rdtrJatimKraton = new MapImageLayer({
  url: 'https://gistaru.atrbpn.go.id/arcgis/rest/services/058_RDTR_PROVINSI_JAWA_TIMUR/_RDTR_35C8_KRATON/MapServer',
  visible: false,
});

const rdtrJatimKediri = new MapImageLayer({
  url: 'https://gistaru.atrbpn.go.id/arcgis/rest/services/058_RDTR_PROVINSI_JAWA_TIMUR/_RDTR_35D2_KOTA_KEDIRI/MapServer',
  visible: false,
});

const rdtrJatim = new GroupLayer({
  title: 'RDTR Jawa Timur',
  visible: false,
  layers: [rdtrJatimSby, rdtrJatimSidoarjo, rdtrJatimCandi, rdtrJatimBuduran, rdtrJatimPrambon, rdtrJatimBalong, rdtrJatimWonoayu, rdtrJatimBangil, rdtrJatimBeji, rdtrJatimKraton, rdtrJatimKediri],
  opacity: 0.90,
});

const rdtrBaliSingaraja = new MapImageLayer({
  url: 'https://gistaru.atrbpn.go.id/arcgis/rest/services/060_RDTR_PROVINSI_BALI/_RDTR_51A2_PERKOTAAN_SINGARAJA/MapServer',
  visible: false,
});

const rdtrBaliKuta = new MapImageLayer({
  url: 'https://gistaru.atrbpn.go.id/arcgis/rest/services/060_RDTR_PROVINSI_BALI/_RDTR_KUTA_SELATAN/MapServer',
  visible: false,
});

const rdtrBali = new GroupLayer({
  title: 'RDTR Bali',
  visible: false,
  layers: [rdtrBaliSingaraja, rdtrBaliKuta],
  opacity: 0.90,
});

const rdtrNTBTanjung = new MapImageLayer({
  url: 'https://gistaru.atrbpn.go.id/arcgis/rest/services/061_RDTR_PROVINSI_NUSA_TENGGARA_BARAT/_RDTR_52A2_PERKOTAAN_TANJUNG/MapServer',
  visible: false,
});

const rdtrNTB = new GroupLayer({
  title: 'RDTR NTB',
  visible: false,
  layers: [rdtrNTBTanjung],
  opacity: 0.90,
});

const rdtrKalbarSanggau = new MapImageLayer({
  url: 'https://gistaru.atrbpn.go.id/arcgis/rest/services/063_RDTR_PROVINSI_KALIMANTAN_BARAT/_RDTR_6103_BAONGLAWANGPERKOTAANSANGGAU/MapServer',
  visible: false,
});

const rdtrKalbarSambas = new MapImageLayer({
  url: 'https://gistaru.atrbpn.go.id/arcgis/rest/services/063_RDTR_PROVINSI_KALIMANTAN_BARAT/_RDTR_61A3_PERKOTAAN_SAMBAS/MapServer',
  visible: false,
});

const rdtrKalbarSatong = new MapImageLayer({
  url: 'https://gistaru.atrbpn.go.id/arcgis/rest/services/063_RDTR_PROVINSI_KALIMANTAN_BARAT/_RDTR_61A4_KPCT_KUALA_TOLAK_KUALA_SATONG/MapServer',
  visible: false,
});

const rdtrKalbar = new GroupLayer({
  title: 'RDTR Kalimantan Barat',
  visible: false,
  layers: [rdtrKalbarSanggau, rdtrKalbarSambas, rdtrKalbarSatong],
  opacity: 0.90,
});

const rdtrKalselTabalong = new MapImageLayer({
  url: 'https://gistaru.atrbpn.go.id/arcgis/rest/services/065_RDTR_PROVINSI_KALIMANTAN_SELATAN/_RDTR_63A2_INDUSTRI_SERADANG_TABALONG/MapServer',
  visible: false,
});

const rdtrKalsel = new GroupLayer({
  title: 'RDTR Kalimantan Selatan',
  visible: false,
  layers: [rdtrKalselTabalong],
  opacity: 0.90,
});

const rdtrKaltaraMalinau = new MapImageLayer({
  url: 'https://gistaru.atrbpn.go.id/arcgis/rest/services/067_RDTR_PROVINSI_KALIMANTAN_UTARA/_RDTR_64A3_PERKOTAAN_MALINAU/MapServer',
  visible: false,
});

const rdtrKaltara = new GroupLayer({
  title: 'RDTR Kalimantan Utara',
  visible: false,
  layers: [rdtrKaltaraMalinau],
  opacity: 0.90,
});

const rdtrSultengBanggai = new MapImageLayer({
  url: 'https://gistaru.atrbpn.go.id/arcgis/rest/services/069_RDTR_PROVINSI_SULAWESI_TENGAH/_RDTR_72A4_PERKOTAAN_BANGGAI_UTARA_TENGAH_SELATAN/MapServer',
  visible: false,
});

const rdtrSulteng = new GroupLayer({
  title: 'RDTR Sulawesi Tengah',
  visible: false,
  layers: [rdtrSultengBanggai],
  opacity: 0.90,
});

const rdtrSulselWatansoppeng = new MapImageLayer({
  url: 'https://gistaru.atrbpn.go.id/arcgis/rest/services/070_RDTR_PROVINSI_SULAWESI_SELATAN/_RDTR_73A7_PERKOTAAN_WATANSOPPENG/MapServer',
  visible: false,
});

const rdtrSulselPinrang = new MapImageLayer({
  url: 'https://gistaru.atrbpn.go.id/arcgis/rest/services/070_RDTR_PROVINSI_SULAWESI_SELATAN/_RDTR_73A8_PERKOTAAN_PINRANG/MapServer',
  visible: false,
});

const rdtrSulselMaminasata = new MapImageLayer({
  url: 'https://gistaru.atrbpn.go.id/arcgis/rest/services/070_RDTR_PROVINSI_SULAWESI_SELATAN/_RDTR_73A9_KOTA_BARU_MAMMINASATA/MapServer',
  visible: false,
});

const rdtrSulsel = new GroupLayer({
  title: 'RDTR Sulawesi Selatan',
  visible: false,
  layers: [rdtrSulselWatansoppeng, rdtrSulselPinrang, rdtrSulselMaminasata],
  opacity: 0.90,
});

const rdtrMalukuAmbon = new MapImageLayer({
  url: 'https://gistaru.atrbpn.go.id/arcgis/rest/services/074_RDTR_PROVINSI_MALUKU/_RDTR_81A1_PUSAT_KOTA_AMBON/MapServer',
  visible: false,
});

const rdtrMaluku = new GroupLayer({
  title: 'RDTR Maluku',
  visible: false,
  layers: [rdtrMalukuAmbon],
  opacity: 0.90,
});

const rdtrPapuaSentani = new MapImageLayer({
  url: 'https://gistaru.atrbpn.go.id/arcgis/rest/services/076_RDTR_PROVINSI_PAPUA/_RDTR_91A2_PERKOTAAN_SENTANI/MapServer',
  visible: false,
});

const rdtrPapua = new GroupLayer({
  title: 'RDTR Papua',
  visible: false,
  layers: [rdtrPapuaSentani],
  opacity: 0.90,
});

const rdtrMap = [
  rdtrPapua,
  rdtrMaluku,
  rdtrSulteng,
  rdtrSulsel,
  rdtrKaltara,
  rdtrKalsel,
  rdtrKalbar,
  rdtrNTB,
  rdtrBali,
  rdtrJatim,
  rdtrJateng,
  rdtrDIY,
  rdtrJabar,
  rdtrBabel,
  rdtrLampung,
  rdtrSumsel,
  rdtrRiau,
  rdtrSumut,
  rdtrAceh,
];

export default rdtrMap;
