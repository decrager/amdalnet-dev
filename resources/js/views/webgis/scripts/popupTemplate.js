function popupTemplate(value) {
  var arrayJsonTemplate = {
    title: 'Keterangan',
    content: '<table style="border-collapse: collapse !important">' +
                '<thead>' +
                    '<tr style="margin: 5px 0;">' +
                        '<td style="width: 35%">FID</td>' +
                        '<td> : </td>' +
                        '<td>' + value.id + '</td>' +
                    '</tr>' +
                    '<tr style="margin: 5px 0; background-color: #CFEEFA">' +
                        '<td style="width: 35%">NAMA_PEMRAKARSA</td>' +
                        '<td> : </td>' +
                        '<td>' + value.pemrakarsa + '</td>' +
                    '</tr>' +
                    '<tr style="margin: 5px 0;">' +
                        '<td style="width: 35%">LAYER</td>' +
                        '<td> : </td>' +
                        '<td>' + value.layer + '</td>' +
                    '</tr>' +
                    '<tr style="margin: 5px 0; background-color: #CFEEFA">' +
                        '<td style="width: 35%">NAMA_KEGIATAN</td>' +
                        '<td> : </td>' +
                        '<td>' + value.kegiatan + '</td>' +
                    '</tr>' +
                    '<tr style="margin: 5px 0;">' +
                        '<td style="width: 35%">JENIS_DOKUMEN</td>' +
                        '<td> : </td>' +
                        '<td>' + value.dokumen + '</td>' +
                    '</tr>' +
                    '<tr style="margin: 5px 0; background-color: #CFEEFA">' +
                        '<td style="width: 35%">LOKASI</td>' +
                        '<td> : </td>' +
                        '<td>' + value.lokasi + '</td>' +
                    '</tr>' +
                    '<tr style="margin: 5px 0;">' +
                        '<td style="width: 35%">LUAS</td>' +
                        '<td> : </td>' +
                        '<td>' + value.luas + '</td>' +
                    '</tr>' +
                    '<tr style="margin: 5px 0; background-color: #CFEEFA">' +
                        '<td style="width: 35%">SKALA_DATA</td>' +
                        '<td> : </td>' +
                        '<td>' + value.skala + '</td>' +
                    '</tr>' +
                '</thead>' +
                '</table>',
  };

  return arrayJsonTemplate;
}

export default popupTemplate;
