<html>

<head>
    <meta http-equiv=Content-Type content="text/html; charset=windows-1252">
    <meta name=Generator content="Microsoft Word 15 (filtered)">
    <style>
        /* Font Definitions */
        @font-face {
            font-family: "Cambria Math";
            panose-1: 2 4 5 3 5 4 6 3 2 4;
        }

        @font-face {
            font-family: Calibri;
            panose-1: 2 15 5 2 2 2 4 3 2 4;
        }

        /* Style Definitions */
        p.MsoNormal,
        li.MsoNormal,
        div.MsoNormal {
            margin-top: 0cm;
            margin-right: 0cm;
            margin-bottom: 8.0pt;
            margin-left: 0cm;
            line-height: 107%;
            font-size: 11.0pt;
            font-family: "Calibri", sans-serif;
        }

        .MsoChpDefault {
            font-family: "Calibri", sans-serif;
        }

        .MsoPapDefault {
            margin-bottom: 8.0pt;
            line-height: 107%;
        }

        @page WordSection1 {
            size: 612.0pt 792.0pt;
            margin: 72.0pt 72.0pt 72.0pt 72.0pt;
        }

        div.WordSection1 {
            page: WordSection1;
        }

        /* List Definitions */
        ol {
            margin-bottom: 0cm;
        }

        ul {
            margin-bottom: 0cm;
        }
    </style>

</head>

<body lang=EN-ID style='word-wrap:break-word'>

    <div class=WordSection1>

        <p class=MsoNormal align=center style='text-align:center'><span lang=EN-US
                style='font-size:20.0pt;line-height:107%'>Hasil Konsultasi Publik</span></p>

        <p class=MsoNormal align=center style='text-align:center'><span lang=EN-US
                style='font-size:10.0pt;line-height:107%'>&nbsp;</span></p>

        <table class=MsoTableGrid border=0 cellspacing=0 cellpadding=0 style='border-collapse:collapse;border:none'>
            <tr>
                <td width=246 style='width:184.25pt;padding:0cm 5.4pt 0cm 5.4pt'>
                    <p class=MsoNormal style='margin-bottom:0cm;line-height:normal'><span lang=EN-US
                            style='font-size:12.0pt'>Nama Pemrakarsa</span></p>
                </td>
                <td width=30 style='width:22.5pt;padding:0cm 5.4pt 0cm 5.4pt'>
                    <p class=MsoNormal align=center style='margin-bottom:0cm;text-align:center;
  line-height:normal'><span lang=EN-US style='font-size:12.0pt'>:</span></p>
                </td>
                <td width=348 style='width:260.75pt;padding:0cm 5.4pt 0cm 5.4pt'>
                    <p class=MsoNormal style='margin-bottom:0cm;line-height:normal'><span lang=EN-US
                            style='font-size:12.0pt'>{{ $project->initiator->name }}</span></p>
                </td>
            </tr>
            <tr>
                <td width=246 style='width:184.25pt;padding:0cm 5.4pt 0cm 5.4pt'>
                    <p class=MsoNormal style='margin-bottom:0cm;line-height:normal'><span lang=EN-US
                            style='font-size:12.0pt'>Nama Rencana Usaha/Kegiatan</span></p>
                </td>
                <td width=30 style='width:22.5pt;padding:0cm 5.4pt 0cm 5.4pt'>
                    <p class=MsoNormal align=center style='margin-bottom:0cm;text-align:center;
  line-height:normal'><span lang=EN-US style='font-size:12.0pt'>:</span></p>
                </td>
                <td width=348 style='width:260.75pt;padding:0cm 5.4pt 0cm 5.4pt'>
                    <p class=MsoNormal style='margin-bottom:0cm;line-height:normal'><span lang=EN-US
                            style='font-size:12.0pt'>{{ $project->project_title }}</span></p>
                </td>
            </tr>
            <tr>
                <td width=246 style='width:184.25pt;padding:0cm 5.4pt 0cm 5.4pt'>
                    <p class=MsoNormal style='margin-bottom:0cm;line-height:normal'><span lang=EN-US
                            style='font-size:12.0pt'>Tanggal</span></p>
                </td>
                <td width=30 style='width:22.5pt;padding:0cm 5.4pt 0cm 5.4pt'>
                    <p class=MsoNormal align=center style='margin-bottom:0cm;text-align:center;
  line-height:normal'><span lang=EN-US style='font-size:12.0pt'>:</span></p>
                </td>
                <td width=348 style='width:260.75pt;padding:0cm 5.4pt 0cm 5.4pt'>
                    <p class=MsoNormal style='margin-bottom:0cm;line-height:normal'><span lang=EN-US
                            style='font-size:12.0pt'>{{ date('d-m-Y', strtotime($public_consultation->event_date))
                            }}</span></p>
                </td>
            </tr>
            <tr>
                <td width=246 style='width:184.25pt;padding:0cm 5.4pt 0cm 5.4pt'>
                    <p class=MsoNormal style='margin-bottom:0cm;line-height:normal'><span lang=EN-US
                            style='font-size:12.0pt'>Lokasi Tempat Konsultasi Publik</span></p>
                </td>
                <td width=30 style='width:22.5pt;padding:0cm 5.4pt 0cm 5.4pt'>
                    <p class=MsoNormal align=center style='margin-bottom:0cm;text-align:center;
  line-height:normal'><span lang=EN-US style='font-size:12.0pt'>:</span></p>
                </td>
                <td width=348 style='width:260.75pt;padding:0cm 5.4pt 0cm 5.4pt'>
                    <p class=MsoNormal style='margin-bottom:0cm;line-height:normal'><span lang=EN-US
                            style='font-size:12.0pt'>{{ $public_consultation->location }}</span></p>
                </td>
            </tr>
            <tr>
                <td width=246 style='width:184.25pt;padding:0cm 5.4pt 0cm 5.4pt'>
                    <p class=MsoNormal style='margin-bottom:0cm;line-height:normal'><span lang=EN-US
                            style='font-size:12.0pt'>Alamat Tempat Pelaksanaan Konsultasi
                            Publik</span></p>
                </td>
                <td width=30 style='width:22.5pt;padding:0cm 5.4pt 0cm 5.4pt'>
                    <p class=MsoNormal align=center style='margin-bottom:0cm;text-align:center;
  line-height:normal'><span lang=EN-US style='font-size:12.0pt'>:</span></p>
                </td>
                <td width=348 style='width:260.75pt;padding:0cm 5.4pt 0cm 5.4pt'>
                    <p class=MsoNormal style='margin-bottom:0cm;line-height:normal'><span lang=EN-US
                            style='font-size:12.0pt'>{{ $public_consultation->address }}</span></p>
                </td>
            </tr>
        </table>

        <p class=MsoNormal><span lang=EN-US style='font-size:12.0pt;line-height:107%'>&nbsp;</span></p>

        <table class=MsoTableGrid border=0 cellspacing=0 cellpadding=0 style='border-collapse:collapse;border:none'>
            <tr>
                <td width=252 valign=top style='width:188.75pt;padding:0cm 5.4pt 0cm 5.4pt'>
                    <p class=MsoNormal style='margin-bottom:0cm;line-height:normal'><span lang=EN-US
                            style='font-size:12.0pt'>Rangkuman Deskriptif atas Harapan
                            Masyarakat</span></p>
                </td>
                <td width=24 valign=top style='width:18.0pt;padding:0cm 5.4pt 0cm 5.4pt'>
                    <p class=MsoNormal align=center style='margin-bottom:0cm;text-align:center;
  line-height:normal'><span lang=EN-US style='font-size:12.0pt'>:</span></p>
                </td>
                <td width=348 valign=top style='width:260.75pt;padding:0cm 5.4pt 0cm 5.4pt'>
                    <p class=MsoNormal style='margin-bottom:0cm;line-height:normal'><span lang=EN-US
                            style='font-size:12.0pt'>{!! $public_consultation->positive_feedback_summary !!}</span></p>
                </td>
            </tr>
            <tr>
                <td width=252 valign=top style='width:188.75pt;padding:0cm 5.4pt 0cm 5.4pt'>
                    <p class=MsoNormal style='margin-bottom:0cm;line-height:normal'><span lang=EN-US
                            style='font-size:12.0pt'>Rangkuman Deskriptif atas Kekhawatiran
                            Masyarakat</span></p>
                </td>
                <td width=24 valign=top style='width:18.0pt;padding:0cm 5.4pt 0cm 5.4pt'>
                    <p class=MsoNormal align=center style='margin-bottom:0cm;text-align:center;
  line-height:normal'><span lang=EN-US style='font-size:12.0pt'>:</span></p>
                </td>
                <td width=348 valign=top style='width:260.75pt;padding:0cm 5.4pt 0cm 5.4pt'>
                    <p class=MsoNormal style='margin-bottom:0cm;line-height:normal'><span lang=EN-US
                            style='font-size:12.0pt'>{!! $public_consultation->negative_feedback_summary !!}</span></p>
                </td>
            </tr>
        </table>


        <div style="page-break-before: always;"></div>
        <p class=MsoNormal><span lang=EN-US style='font-size:12.0pt;line-height:107%'>&nbsp;</span></p>

        <p class=MsoNormal align=center style='text-align:center'><span lang=EN-US
                style='font-size:20.0pt;line-height:107%;''>Dokumentasi Foto Konsultasi Publik</span>
        </p>

        <div style="margin-bottom: 30px;"></div>

        @foreach($public_consultation->docs as $doc)
        @php
        $doc_data = json_decode($doc->doc_json, true);
        @endphp
        @if($doc_data['doc_type'] === 'Foto Dokumentasi')
        <div style="width: 100%; text-align:center; margin-bottom: 40px;">
            <img src="{{ Storage::disk('public')->path(str_replace('/storage/', '', $doc_data['filepath'])) }}" alt=""
                style="width: 70%;">
        </div>
        @endif
        @endforeach

        <p class=MsoNormal align=center style='text-align:center'><span lang=EN-US
                style='font-size:12.0pt;line-height:107%'>&nbsp;</span></p>

        <p class=MsoNormal align=center style='text-align:center'><span lang=EN-US
                style='font-size:12.0pt;line-height:107%'>&nbsp;</span></p>

        <p class=MsoNormal align=center style='text-align:center'><span lang=EN-US
                style='font-size:12.0pt;line-height:107%'>&nbsp;</span></p>

        <p class=MsoNormal align=center style='text-align:center'><span lang=EN-US
                style='font-size:12.0pt;line-height:107%'>&nbsp;</span></p>

        <p class=MsoNormal align=center style='text-align:center'><span lang=EN-US
                style='font-size:12.0pt;line-height:107%'>&nbsp;</span></p>

    </div>

</body>

</html>