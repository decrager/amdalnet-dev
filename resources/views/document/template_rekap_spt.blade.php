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
            size: 792.0pt 612.0pt;
            margin: 72.0pt 72.0pt 72.0pt 72.0pt;
        }

        div.WordSection1 {
            page: WordSection1;
        }

        table {
            font-size: 11px !important;
        }
    </style>

</head>

<body lang=EN-ID style='word-wrap:break-word'>

    <div class=WordSection1>

        <p class=MsoNormal align=center style='text-align:center'><span lang=EN-US
                style='font-size:18.0pt;line-height:107%'>Daftar Rekap SPT dari Masyarakat</span></p>

        <p class=MsoNormal align=center style='text-align:center'><span lang=EN-US>&nbsp;</span></p>

        <table class=MsoTableGrid border=1 cellspacing=0 cellpadding=0 width=930 style='width:100%;margin-left: 0pt;border-collapse:collapse;border:
 none' width="100%" style="width: 100%;">
            <tr>
                <td width=22 style='width:11.5pt;border:solid windowtext 1.0pt;padding:0cm 5.4pt 0cm 5.4pt'>
                    <p class=MsoNormal align=center style='margin-bottom:0cm;text-align:center;
  line-height:normal'><span lang=EN-US>No</span></p>
                </td>
                <td width=72 style='width:39.0pt;border:solid windowtext 1.0pt;border-left:
  none;padding:0cm 5.4pt 0cm 5.4pt'>
                    <p class=MsoNormal align=center style='margin-bottom:0cm;text-align:center;
  line-height:normal'><span lang=EN-US>Tanggal</span></p>
                </td>
                <td width=94 style='width:58.0pt;border:solid windowtext 1.0pt;border-left:
  none;padding:0cm 5.4pt 0cm 5.4pt'>
                    <p class=MsoNormal align=center style='margin-bottom:0cm;text-align:center;
  line-height:normal'><span lang=EN-US>Nama</span></p>
                </td>
                <td width=84 style='width:50.5pt;border:solid windowtext 1.0pt;border-left:
  none;padding:0cm 5.4pt 0cm 5.4pt'>
                    <p class=MsoNormal align=center style='margin-bottom:0cm;text-align:center;
  line-height:normal'><span lang=EN-US>Peran</span></p>
                </td>
                <td width=30 style='width:7.5pt;border:solid windowtext 1.0pt;border-left:
  none;padding:0cm 5.4pt 0cm 5.4pt'>
                    <p class=MsoNormal align=center style='margin-bottom:0cm;text-align:center;
  line-height:normal'><span lang=EN-US>Rating</span></p>
                </td>
                <td width=98 style='width:56.0pt;border:solid windowtext 1.0pt;border-left:
  none;padding:0cm 5.4pt 0cm 5.4pt'>
                    <p class=MsoNormal align=center style='margin-bottom:0cm;text-align:center;
  line-height:normal'><span lang=EN-US>Kekhawatiran</span></p>
                </td>
                <td width=98 style='width:56.0pt;border:solid windowtext 1.0pt;border-left:
  none;padding:0cm 5.4pt 0cm 5.4pt'>
                    <p class=MsoNormal align=center style='margin-bottom:0cm;text-align:center;
  line-height:normal'><span lang=EN-US>Harapan</span></p>
                </td>
            </tr>
            @if($feedbacks)
            @foreach($feedbacks as $feedback)
            <tr>
                <td width=22 style='width:11.5pt;border:solid windowtext 1.0pt;border-top:
  none;padding:0cm 5.4pt 0cm 5.4pt'>
                    <p class=MsoNormal align=center style='margin-bottom:0cm;text-align:center;
  line-height:normal'><span lang=EN-US>{{ $loop->index + 1 }}</span></p>
                </td>
                <td width=72 style='width:39.0pt;border-top:none;border-left:none;
  border-bottom:solid windowtext 1.0pt;border-right:solid windowtext 1.0pt;
  padding:0cm 5.4pt 0cm 5.4pt'>
                    <p class=MsoNormal style='margin-bottom:0cm;line-height:normal'><span lang=EN-US
                            style='color:black'>{{ date('d/m/Y H:i:s', strtotime($feedback->created_at)) }}</span></p>
                </td>
                <td width=94 style='width:58.0pt;border-top:none;border-left:none;
  border-bottom:solid windowtext 1.0pt;border-right:solid windowtext 1.0pt;
  padding:0cm 5.4pt 0cm 5.4pt'>
                    <p class=MsoNormal style='margin-bottom:0cm;line-height:normal'><span lang=EN-US
                            style='color:black'>{{ $feedback->name }} </span></p>
                </td>
                <td width=84 style='width:50.5pt;border-top:none;border-left:none;
  border-bottom:solid windowtext 1.0pt;border-right:solid windowtext 1.0pt;
  padding:0cm 5.4pt 0cm 5.4pt'>
                    <p class=MsoNormal style='margin-bottom:0cm;line-height:normal'><span lang=EN-US
                            style='color:black'>{{ $feedback->responderType->name }}</span></p>
                </td>
                <td width=30 style='width:7.5pt;border-top:none;border-left:none;border-bottom:
  solid windowtext 1.0pt;border-right:solid windowtext 1.0pt;padding:0cm 5.4pt 0cm 5.4pt'>
                    <p class=MsoNormal align=center style='margin-bottom:0cm;text-align:center;
  line-height:normal'><span lang=EN-US style='color:black'>{{ $feedback->rating }}/5</span></p>
                </td>
                <td width=98 style='width:56.0pt;border-top:none;border-left:none;
  border-bottom:solid windowtext 1.0pt;border-right:solid windowtext 1.0pt;
  padding:0cm 5.4pt 0cm 5.4pt'>
                    <p class=MsoNormal style='margin-bottom:0cm;line-height:normal'><span lang=EN-US
                            style='color:black'>{{ $feedback->concern }}</span></p>
                </td>
                <td width=98 style='width:56.0pt;border-top:none;border-left:none;
  border-bottom:solid windowtext 1.0pt;border-right:solid windowtext 1.0pt;
  padding:0cm 5.4pt 0cm 5.4pt'>
                    <p class=MsoNormal style='margin-bottom:0cm;line-height:normal'><span lang=EN-US
                            style='color:black'>{{ $feedback->expectation }}</span></p>
                </td>
            </tr>
            @endforeach
            @endif
        </table>

        <p class=MsoNormal align=center style='text-align:center'><span lang=EN-US>&nbsp;</span></p>

    </div>

</body>

</html>