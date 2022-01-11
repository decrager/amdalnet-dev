function popupTemplateKelolaPantau(value) {
  var arrayJsonTemplate = {
    title: 'Keterangan',
    content: '<table style="border-collapse: collapse !important">' +
                '<thead>' +
                    '<tr style="margin: 5px 0;">' +
                        '<td style="width: 35%">ID</td>' +
                        '<td> : </td>' +
                        '<td>' + value.ID + '</td>' +
                    '</tr>' +
                    '<tr style="margin: 5px 0; background-color: #CFEEFA">' +
                        '<td style="width: 35%">KETERANGAN</td>' +
                        '<td> : </td>' +
                        '<td>' + value.KETERANGAN + '</td>' +
                    '</tr>' +
                    '<tr style="margin: 5px 0;">' +
                        '<td style="width: 35%">PEMRAKARSA</td>' +
                        '<td> : </td>' +
                        '<td>' + value.PEMRAKARSA + '</td>' +
                    '</tr>' +
                    '<tr style="margin: 5px 0; background-color: #CFEEFA">' +
                        '<td style="width: 35%">KEGIATAN</td>' +
                        '<td> : </td>' +
                        '<td>' + value.KEGIATAN + '</td>' +
                    '</tr>' +
                    '<tr style="margin: 5px 0;">' +
                        '<td style="width: 35%">TAHUN</td>' +
                        '<td> : </td>' +
                        '<td>' + value.TAHUN + '</td>' +
                    '</tr>' +
                    '<tr style="margin: 5px 0; background-color: #CFEEFA">' +
                        '<td style="width: 35%">LAYER</td>' +
                        '<td> : </td>' +
                        '<td>' + value.LAYER + '</td>' +
                    '</tr>' +
                    '<tr style="margin: 5px 0;">' +
                        '<td style="width: 35%">TIPE_DOKUMEN</td>' +
                        '<td> : </td>' +
                        '<td>' + value.TIPE_DOKUM + '</td>' +
                    '</tr>' +
                    '<tr style="margin: 5px 0; background-color: #CFEEFA">' +
                        '<td style="width: 35%">PROVINSI</td>' +
                        '<td> : </td>' +
                        '<td>' + value.PROVINSI + '</td>' +
                    '</tr>' +
                    '<tr style="margin: 5px 0; background-color: #CFEEFA">' +
                        '<td style="width: 35%">KODE</td>' +
                        '<td> : </td>' +
                        '<td>' + value.KODE + '</td>' +
                    '</tr>' +
                '</thead>' +
                '</table>',
  };

  return arrayJsonTemplate;
}

export default popupTemplateKelolaPantau;
