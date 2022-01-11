function validateBatas(data) {
  const valid = [
    'OBJECTID_1',
    'PEMRAKARSA',
    'KEGIATAN',
    'TAHUN',
    'PROVINSI',
    'KETERANGAN',
    'AREA',
    'LAYER',
    'TIPE_DOKUM',
  ];

  const uploaded = Object.keys(data.features[0].properties);

  if (JSON.stringify(uploaded) !== JSON.stringify(valid)) {
    alert('Atribut .shp yang dimasukkan tidak sesuai dengan format yang benar. Download sample diatas!');
    return;
  }
}

function validateKelolaPantau(data) {
  const valid = [
    'ID',
    'KETERANGAN',
    'PEMRAKARSA',
    'KEGIATAN',
    'TAHUN',
    'LAYER',
    'TIPE_DOKUM',
    'PROVINSI',
    'KODE',
  ];

  const uploaded = Object.keys(data.features[0].properties);

  if (JSON.stringify(uploaded) !== JSON.stringify(valid)) {
    alert('Atribut .shp yang dimasukkan tidak sesuai dengan format yang benar. Download sample diatas!');
    return;
  }
}

function validateKelolaPantauType(data) {
  const uploaded = data.features[0].geometry.type;

  if (uploaded !== 'Point') {
    alert('Type Geometri yang diizinkan hanya Point!');
    return;
  }
}

export default { validateBatas, validateKelolaPantau, validateKelolaPantauType };
