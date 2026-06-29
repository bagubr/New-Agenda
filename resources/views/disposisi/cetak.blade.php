<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cetak Disposisi - {{ $dispo_masuk->id }}</title>
</head>

<body>
    <p style="margin-top:0.0000pt;margin-right:0.0000pt;text-align:left;"><br></p>
    <table style="border-collapse: collapse;border: none;width: 100%;">
        <tbody>
            <tr>
                <td style="vertical-align: top; width: 470px; text-align: center;" colspan="2">
                    <img src="data:image/png;base64,{{$logo}}" style="width: 400px; height: auto; margin-bottom: 20px;" alt="Logo" >
                </td>
            </tr>
            <tr>
                <td colspan="2" style="width: 100px;border-width: initial;border-style: none;border-color: initial;background: rgb(0, 0, 0);vertical-align: top;">
                    <p style="text-align:center;"><strong><span style="font-family:Arial;font-weight:bold;font-size:21px;color:rgb(255,255,255);">LEMBAR DISPOSISI</span></strong></p>
                </td>
            </tr>
            <tr>
                <td colspan="2">
                    <strong>
                        <ol>
                            <li>
                                <span style="font-family:Arial;font-size:15px;">SURAT DARI</span>
                                <span style="font-family:Arial;font-size:15px; margin-left: 150px;">: {{ $dispo_masuk->suratMasuk->asal }}</span>
                            </li>
                            <li>
                                <span style="font-family:Arial;font-size:15px;">NOMOR SURAT</span>
                                <span style="font-family:Arial;font-size:15px; margin-left: 129px;">: {{ $dispo_masuk->suratMasuk->no_surat }}  ({{ date('d-m-Y', strtotime($dispo_masuk->suratMasuk->tanggal)) }})</span>
                            </li>
                            <li>
                                <span style="font-family:Arial;font-size:15px;">TANGGAL SURAT</span>
                                <span style="font-family:Arial;font-size:15px; margin-left: 113px;">: {{ date('d-m-Y', strtotime($dispo_masuk->suratMasuk->tanggal)) }}</span>
                            </li>
                            <li>
                                <span style="font-family:Arial;font-size:15px;">DITERIMA TANGGAL</span>
                                <span style="font-family:Arial;font-size:15px; margin-left: 86px;">: {{ $dispo_masuk->suratMasuk->tgl_agenda }}</span>
                            </li>
                            <li>
                                <span style="font-family:Arial;font-size:15px;">NOMOR AGENDA</span>
                                <span style="font-family:Arial;font-size:15px; margin-left: 116px;">: {{ $dispo_masuk->suratMasuk->no_agenda }}</span>
                            </li>
                            <li>
                                <span style="font-family:Arial;font-size:15px;">PERIHAL</span>
                                <span style="font-family:Arial;font-size:15px; margin-left: 175px;">: {!! $dispo_masuk->suratMasuk->perihal !!}</span>
                            </li>
                        </ol>
                    </strong>
                </td>
            </tr>
        </tbody>
    </table>
    <p style="margin-top:6pt;"><span style="font-family:Arial;font-size:15px;">&nbsp;</span></p>
    <table style="border-collapse: collapse;border: none;width: 100%;">
        <tbody>
            
            <tr>
                <td style="width: 250pt;padding: 0pt 5.4pt;border-width: initial;background: rgb(0, 0, 0);vertical-align: top;">
                    <p style="margin-top:6;text-align:center;"><strong><span style="font-family:Arial;font-weight:bold;font-size:21px;color:rgb(255,255,255);">DITERUSKAN KEPADA</span></strong></p>
                </td>
                <td style="width: 250pt;padding: 0pt 5.4pt;border-width: initial;border-style: none;border-color: initial;background: rgb(0, 0, 0);vertical-align: top;">
                    <p style="margin-top:6.2000pt;text-align:center;"><strong><span style="font-family:Arial;font-weight:bold;font-size:21px;color:rgb(255,255,255);">DISPOSISI&nbsp;</span></strong></p>
                </td>
            </tr>
            @foreach ( $dispo_masuk->suratMasuk->dispomasuk as $diteruskan )
                <tr>
                    <td style="border-width: initial;border-style: none;border-color: initial;vertical-align: top;">
                        <p style="margin-top:10pt;text-align:justify;"><strong><span style="font-family:Arial;font-weight:bold;font-size:15px;">{{ $loop->iteration }}. {{$diteruskan->disposisi_name}}</span></strong></p>
                    </td>
                    <td style="border-width: initial;border-style: none;border-color: initial;vertical-align: top;">
                        <p style="margin-top:10pt;text-align:justify;"><strong><span style="font-family:Arial;font-weight:bold;font-size:15px;">{{$diteruskan->tindak}} <br> {{$diteruskan->ket}}</span></strong></p>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>

    <script>
        // Auto print when page loads
        window.onload = function() {
            window.download();
        }
    </script>

</body>

</html>